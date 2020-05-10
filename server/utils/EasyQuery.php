<?php
namespace joaompfe\EasyQuery;

use mysqli;
use Exception;

const DEFAULT_SI = '?';

if (!function_exists('array_key_first')) {
    function array_key_first(array $arr) {
        foreach($arr as $key => $unused) {
            return $key;
        }
        return NULL;
    }
}

class EasyQuery {
    private $si;            // Special identifier, main query statement identifier. Default value is: "?".
    private $stmts = [];    // Associative array of SQL statements and their identifiers
    private $mysqli;        // Connection to MySQL used in queries

    function __construct(string $specialIdentifier = NULL) {    
        if (!$specialIdentifier) {
            $this->setSpecialIdentifier(DEFAULT_SI);
        }
    }

    /**
     * Set field si variable if passed value starts with '?'.
     * 
     * @param string $specialIdentifier
     * 
     * @throws Exception
     */
    function setSpecialIdentifier($specialIdentifier) {
        if ($specialIdentifier[0] == DEFAULT_SI) {  // test if $specialIdentifier starts with '?'
            $this->si = $specialIdentifier;
        }
        else {
            throw new Exception("Special identifier must start with '?'. Identifier '" . $specialIdentifier . "' don't start with '?'.");
        }
    }

    /**
     * Set field stmts. If an identifier is associated with main statement, special
     * identifier is set to that value, otherwise key of main statement is set to
     * the default special identifier
     * 
     * @param array $stmts
     * 
     * @throws Exception
     */
    private function setStatements($stmts) {
        $mainStmtKey = array_key_first($stmts);
        // If no identifier was defined in main statement, then it default value is 0. 
        // Otherwise the provided identifier is assumed as the new special identifier.
        if ($mainStmtKey != 0) {
            $this->setSpecialIdentifier($mainStmtKey);
        } else {
            $stmts[$this->si] = $stmts['0'];
            unset($stmts['0']);
        }

        foreach (array_slice($stmts, 1) as $i=>$stmt) {
            if (strtok($i, '.') != $this->si) {
                throw new Exception("Statement identifiers must start with special identifier. Identifier '" . "$i' don't start with the special identifier '" . $this->si . "'.");
            }
        }

        $this->stmts = $stmts;
    }

    /**
     * Translate the statement, replacing lexical tokens with their respective context
     * value
     * 
     * @param string $stmt The query statement to be analysed
     * @param array The source of the data used in the replacement
     */
    private function translateStatement($stmt, $obj) {
        $si = $this->si;
        
        $regex = "/(\\$si\.|\G(?!^))[a-zA-Z]+\K\./";    // replace dots (not the first) in $si.something.something with "']['", resulting in $si.something']['something
        $stmt = preg_replace($regex, '\'][\'', $stmt);
        $regex = "/\\$si\.[a-zA-Z]+\K(?![a-zA-Z])/";                      
        $stmt = preg_replace($regex, '\']}', $stmt);    // add "']}" after $si.something']['something, resulting in $si.something']['something']}
        $regex = "/\\$si\K\./";
        $stmt = preg_replace($regex, '[\'', $stmt);     // replace first dot in $si.something']['something']} with "['", resulting in $si['something']['something']}
        
        $stmt = preg_replace("/\\$si/", '{$obj', $stmt);    // replace $si with "{obj", resulting in {$obj['something']['something']}

        $stmt = eval('return "' . $stmt . '";');  // evaluate the string to replace "{$obj['something']['something']}" with it real value
        
        return $stmt;
    }

    /**
     * Assigns $value to some element (determined by $columnName) of $obj
     * 
     * @param array $obj Assoc array for which to add/set the element
     * @param string $columnName Defines the element to be added/setted
     * @param $value The value to be assigned
     */
    private function setValue(&$obj, $columnName, $value) {
        $code = '$obj';
        foreach (explode(".", $columnName) as $subProperty) {
            $code .= "['$subProperty']";
        }
        $code .= '=$value;';    // Se, por acaso, ele estiver a fazer override, expirementar '[]=$value;'
        eval($code);
    }

    /**
     * Executes queries recursivly, assigning the result to $model assoc array.
     * The statement is first translated before query database. After that, $model
     * skeleton is defined accordingly fields/columns in the query. And then rows
     * are fetched and data assgined to $model, which in turn is pushed into $array.
     * 
     * This last process can start recursivity of individualQuery function if there is
     * a query to be done for each $model in the $array.
     * 
     * @param string $i The identifier of the $stmt
     * @param string $stmt The statement to be queried in database
     * @param array $mainObj The root/main object to which data is recursively being 
     * append
     * 
     * @return array $array|$model|[] The query resulted assoc array
     */
    private function individualQuery($i, $stmt, $mainObj) {
        if ($i != $this->si) {
            $stmt = $this->translateStatement($stmt, $mainObj);
        }

        // If identifier ($i) contains '[]' then this query should represent an array
        $isArray = preg_match('/\[\]$/', $i) === 1;
        if ($isArray) {
            $i = preg_replace('/\[\]$/', '', $i);   // remove "[]" part to facilitate some regex manipulations later
        }
        
        $result = $this->mysqli->query($stmt);

        if (!$result) {
            error_log("Failed to connect to MySQL: (" . $stmt . ") " . $this->mysqli->error);
        }

        $fields = $result->fetch_fields();
        $num_rows = $result->num_rows;

        // Define the elements and elements of $model: the skeleton of $model
        $model = array();
        foreach ($fields as $field) {
            $obj = &$model;

            $model_properties = explode(".", $field->name);
            $max_index = count($model_properties) - 1;

            foreach ($model_properties as $index=>$property) {
                if (!isset($obj["$property"])) {
                    $obj["$property"] = ($index < $max_index) ? array() : NULL;
                }
                $obj = &$obj["$property"];
            }
        }
        unset($obj);

        $regex = ($isArray) ? "/^\\$i\[\]\.[a-zA-Z]+(?:\[\])?$/" : "/^\\$i\.[a-zA-Z]+(?:\[\])?$/";
        $nextStmts = [];
        foreach ($this->stmts as $ni=>$nextStmt) {
            error_log("$regex");
            if (preg_match($regex, $ni)) {
                $nextStmts[$ni] = $nextStmt;
            }
        }

        if ($num_rows > 0) {
            $array = array();

            while ($row = $result->fetch_assoc()) {
                foreach ($row as $columnName=>$value) {
                    $this->setValue($model, $columnName, $value);
                }

                foreach ($nextStmts as $ni=>$nextStmt) {
                    $model_i = preg_replace('/\[\]/', '', $ni);        // remove "[]" part
                    $model_i = preg_replace("/^\\$i\./", '', $model_i); // remove $i part
                    $this->setValue($model, $model_i, $this->individualQuery($ni, $nextStmt, $model));
                }
                
                $array[] = $model;
            }
            return ($isArray || $i == $this->si) ? $array : $array[0];
        }
        else {
            return ($isArray) ? [] : NULL;
        }
    }

    /**
     * Query the passed statements and construct an assoc array containing all queries
     * results data which is usable in json_enconde function.
     * 
     * @param mysqli $mysqli The connection to MySQL database to be used
     * @param array $stmts The statements to be executed
     * 
     * @return array The resulting assoc array, ready for json_encode function
     */
    function query(mysqli $mysqli, array $stmts) {
        $this->setStatements($stmts);
        $this->mysqli = $mysqli;
        return $this->individualQuery($this->si, $this->stmts[$this->si], NULL);
    }
};

// NOTES
/**
 * só variáveis com [a-zA-Z] caracteres são suportadas para já
 * 
 * todos os campos que requeiram uma query que retorne 0 rows são igualados a []
 * 
 * adicionar tipos de variáveis aos argumentos das funções públicas
 */
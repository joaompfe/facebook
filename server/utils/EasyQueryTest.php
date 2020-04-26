<?php
namespace joaompfe\EasyQueryTest;

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
    private $si;            // Special identifier, main query statement identifier
    private $stmts = [];    // Associative array of SQL statements and their identifiers
    private $mainStmt;
    private $mysqli;

    function __construct(string $specialIdentifier = NULL) {
        if (!$specialIdentifier) {
            $this->setSpecialIdentifier(DEFAULT_SI);
        }
    }

    function setSpecialIdentifier($specialIdentifier) {
        if ($specialIdentifier[0] === DEFAULT_SI) {
            $this->si = $specialIdentifier;
        }
        else {
            throw new Exception("Special identifier must start with '?'. Identifier '" . $specialIdentifier . "' don't start with '?'.");
        }
    }

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

    private function parseStatement($stmt, $obj) {
        error_log('parseStatement $stmt argument: ');
        error_log($stmt);
        $si = $this->si;
        
        $regex = "/(\\$si\.|\G(?!^))[a-zA-Z]+\K\./";    // replace dots (not the first) in $si.something.something with "']['", resulting in $si.something']['something
        $stmt = preg_replace($regex, '\'][\'', $stmt);
        $regex = "/\\$si\.[a-zA-Z]+\K(?![a-zA-Z])/";                      
        $stmt = preg_replace($regex, '\']}', $stmt);    // add "']}" after $si.something']['something, resulting in $si.something']['something']}
        $regex = "/\\$si\K\./";
        $stmt = preg_replace($regex, '[\'', $stmt);     // replace first dot in $si.something']['something']} with "['", resulting in $si['something']['something']}
        
        $stmt = preg_replace("/\\$si/", '{$obj', $stmt);    // replace $si with "{obj", resulting in {$obj['something']['something']}

        error_log('parseStatement $stmt after regex operations: ');
        error_log($stmt);
        $stmt = eval('return "' . $stmt . '";');  // evaluate the string to replace "{$obj['something']['something']}" with it real value
        
        error_log('parseStatement $stmt return: ');
        error_log($stmt);
        return $stmt;
    }

    /**
     * Assigns #value to some #obj property.
     */
    private function setValue(&$obj, $columnName, $value) {
        $code = '$obj';
        foreach (explode(".", $columnName) as $subProperty) {
            $code .= "['$subProperty']";
        }
        $code .= '=$value;';    // Se, por acaso, ele estiver a fazer override, expirementar '[]=$value;'
        eval($code);
    }

    private function individualQuery($i, $stmt, $mainObj) {
        if ($i != $this->si) {
            $stmt = $this->parseStatement($stmt, $mainObj);
        }

        $isArray = preg_match('/\[\]$/', $i) === 1;
        if ($isArray) {
            $i = preg_replace('/\[\]$/', '', $i);      // remove "[]" par
            error_log("new \$i: $i");
        }
        
        $result = $this->mysqli->query($stmt);

        // TODO return error
        if (!$result) {
            error_log("Query error: " . $this->mysqli->error . ". On statement: " . $stmt);
        }

        $fields = $result->fetch_fields();
        $num_rows = $result->num_rows;

        // Define the keys and subkeys of $model
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
        error_log("individualQuery $i model template: ");
        error_log(json_encode($model, JSON_PRETTY_PRINT));

        $regex = ($isArray) ? "/^\\$i\[\]\.[a-zA-Z]+(?:\[\])?$/" : "/^\\$i\.[a-zA-Z]+(?:\[\])?$/";
        $nextStmts = [];
        foreach ($this->stmts as $ni=>$nextStmt) {
            error_log("$regex");
            if (preg_match($regex, $ni)) {
                error_log("MATCH actual: $i next: $ni");
                $nextStmts[$ni] = $nextStmt;
            }
        }
        if (isset($nextStmts)) {
            error_log("Actual identifier: $i. Next identifier: $nextStmts");
        }

        if ($num_rows > 0) {
            $array = array();
            while ($row = $result->fetch_assoc()) {
                foreach ($row as $columnName=>$value) {
                    $this->setValue($model, $columnName, $value);
                }
                foreach ($nextStmts as $ni=>$nextStmt) {
                    $model_i = preg_replace('/\[\]/', '', $ni);    // remove "[]" part
                    $model_i = preg_replace("/^\\$i\./", '', $model_i);  // remove $i part
                    $this->setValue($model, $model_i, $this->individualQuery($ni, $nextStmt, $model));
                }
                
                error_log("Multi-row loop $i model: ");
                error_log(json_encode($model, JSON_PRETTY_PRINT));
                $array[] = $model;
            }
            return ($isArray || $i == $this->si) ? $array : $array[0];
        }
        else {
            return ($isArray) ? [] : NULL;
        }
    }

    function query(mysqli $mysqli, array $stmts) {
        $this->setStatements($stmts);
        error_log('$this->stmts ');
        error_log(json_encode($this->stmts, JSON_PRETTY_PRINT));
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
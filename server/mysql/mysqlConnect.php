<?php
    
require_once(__DIR__ . '/config.php'); 

$connection = mysqli_connect($GLOBALS["db.host"],$GLOBALS["db.user"],$GLOBALS["db.pass"], $GLOBALS["db.schema"],$GLOBALS["db.port"]);

//Comentar isto caso dê problemas com caracteres
mysqli_set_charset($connection,"utf8");
//supõe-se que o Netbeans trabalhe em UTF8

if ($connection->connect_errno) {
    error_log("Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
$GLOBALS["db.connection"] = $connection;
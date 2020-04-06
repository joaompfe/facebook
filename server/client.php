<?php
session_start();

require_once 'Client.php';

if (!isset($_SESSION["client"])) {
    echo "{}";
    error_log("PHP CLIENT NO SESSION");
}
else if (isset($_SESSION["client"])) {
    echo json_encode($_SESSION["client"]);
    error_log("PHP CLIENT SESSION AND CLIENT");
}
else {
    error_log("PHP CLIENT SESSION AND NO CLIENT");
}
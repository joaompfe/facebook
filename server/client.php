<?php
session_start();

require_once 'Client.php';

if (!isset($_SESSION["client"])) {
    echo "{}";
}
else if (isset($_SESSION["client"])) {
    echo json_encode($_SESSION["client"]);
}
else {
    error_log("PHP CLIENT SESSION AND NO CLIENT");
}
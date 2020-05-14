<?php

session_start();

// Get JSON data in POST
// For some reason this needs to be done to be able to receive POST HTTP requests
// from angularjs
if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}

$email = $_POST["email"];
$password = $_POST["password"];

include 'mysql/mysqlConnect.php';

$result = $GLOBALS["db.connection"]->query(
    "SELECT * FROM persons WHERE email = '$email' AND password = '$password';");

include './mysql/mysqlClose.php';

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION["client"] = $row;
    $response = array("loggedSucessfully"=>true, "client"=>$row);
    echo json_encode($response);
}
else {
    $response = array("loggedSucessfully"=>false);
    echo json_encode($response);
}
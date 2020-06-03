<?php
// TODO criar pasta de person
session_start();

// Get JSON data in POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}

$firstName = $_POST["firstName"];
$lastName = $_POST["lastName"];
$email = $_POST["email"];
$password = $_POST["password"];
$birthday = $_POST["birthday"];
$gender = $_POST["gender"];

$sql = "INSERT INTO persons (firstName, lastName, email, password, birthday, gender) 
    VALUES ('$firstName', '$lastName', '$email', '$password', '$birthday', '$gender');";

include 'mysql/mysqlConnect.php';

// query returns TRUE if executes sucessfully, ie, if sign up occour successfully
$response["signUpSucessfully"] = $GLOBALS["db.connection"]->query($sql);

if (!$response["signUpSucessfully"]) {
   error_log("SQL: " . $sql . " ERROR: " . $GLOBALS["db.connection"]->error); 
}

include './mysql/mysqlClose.php';

echo json_encode($response);
<?php
// This script writes a post in the database

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}

if (isset($_POST["content"]) && isset($_SESSION["client"]["id"])) {
    $content = $_POST["content"];
    $authorId = $_SESSION["client"]["id"];
}
else {
    $response["success"] = false;
    echo json_encode($response);
    return;
}

$sql = "INSERT INTO posts (author, text) VALUES ($authorId, '$content');";

include 'mysql/mysqlConnect.php';

// query returns TRUE if executes sucessfully, ie, if sign up occour successfully
$response["success"] = $GLOBALS["db.connection"]->query($sql);

include './mysql/mysqlClose.php';

if (!$response["success"]) {
    error_log("SQL: " . $sql . " ERROR: " . $GLOBALS["db.connection"]->error); 
}

echo json_encode($response);
<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}

$id = $_SESSION["client"]["id"];


if (!empty($_FILES)) {


    $filename = $_FILES['file']['name'];
    $location = '../upload/';
    move_uploaded_file($_FILES['file']['tmp_name'],$location.$filename);

    //$alterProfilePic = "UPDATE persons SET profilePicID = "

    $sql = "INSERT INTO images (filename, author) VALUES ('" . $_FILES['file']['name'] . "', $id)";

    $imageInsertedquery = "SELECT id FROM images WHERE id = (SELECT MAX(id) FROM images)";

    
    
    include 'mysql/mysqlConnect.php';

    
    $GLOBALS["db.connection"]->query($sql);

    $result = $GLOBALS["db.connection"]->query($imageInsertedquery);
    
    $imageInsertedArray = $result->fetch_assoc();
    $imageInserted = $imageInsertedArray["id"];
    error_log(print_r($imageInserted, true));

    $alterProfilePic = "UPDATE persons SET profilePicID = $imageInserted WHERE id = $id";

    $GLOBALS["db.connection"]->query($alterProfilePic);

    include './mysql/mysqlClose.php';

    
} else {
    error_log( 'Server Error');
}
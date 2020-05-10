<?php

session_start();

if (!isset($_SESSION["client"])) {
    //TODO
    //http_response_code(401); ?
    $response["success"] = FALSE;
    echo json_encode($response);
    error_log("AWWAWWAWAAWAW");
    return;
}

$id = $_GET["id"];


$sql = "SELECT fullName, birthday, gender FROM persons where id = '$id'";


include '../mysql/mysqlConnect.php';

$result = $GLOBALS["db.connection"]->query($sql);
$user = $result->fetch_assoc();

$response['success'] = TRUE;
$response['profile']  = $user;

include '../mysql/mysqlClose.php';

echo json_encode($response);
?>
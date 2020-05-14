<?php

session_start();

if (!isset($_SESSION["client"])) {
    //TODO
    //http_response_code(401); ?
    $response["success"] = false;
    echo json_encode($response);
    return;
}

$id = $_GET["id"];

$sql = "SELECT id, fullName, birthday, gender FROM persons where id = '$id'";

include $_SERVER["DOCUMENT_ROOT"] . '/server/mysql/mysqlConnect.php';

$result = $GLOBALS["db.connection"]->query($sql);
$response['success'] = $result && ($user = $result->fetch_assoc());
if ($response['success']) {
    $response['profile'] = $user;
}

include $_SERVER["DOCUMENT_ROOT"] . '/server/mysql/mysqlClose.php';

echo json_encode($response);
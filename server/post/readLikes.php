<?php
session_start();

if (isset($_GET["postId"]) && isset($_SESSION["client"])) {
    $postId = $_GET["postId"];
    $personId = $_SESSION["client"]["id"];
}
else {
    $response["success"] = FALSE;
    echo json_encode($response);
    return;
}

if (isset($_GET["quantity"])) {
    error_log("quantity value: {$_GET["quantity"]}");
}

$limitClause = (isset($_GET["quantity"])) ? " LIMIT {$_GET["quantity"]} " : "";

$stmt = ["SELECT person FROM postLikes WHERE post = $postId" . $limitClause . ";"];

include $_SERVER['DOCUMENT_ROOT'] . '/server/mysql/mysqlConnect.php';

require_once 'utils/EasyQuery.php';
use joaompfe\EasyQuery\EasyQuery;
$likes = (new EasyQuery())->query($GLOBALS["connection"], $stmt);

$response["sucess"] = TRUE;
$response["likes"] = $likes;
$response["postId"] = $postId;  //???

echo json_encode($response);

include $_SERVER['DOCUMENT_ROOT'] . '/server/mysql/mysqlClose.php';

?>
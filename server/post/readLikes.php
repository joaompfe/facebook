<?php
session_start();

if (isset($_GET["postId"]) && isset($_SESSION["client"])) {
    $postId = $_GET["postId"];
    $personId = $_SESSION["client"]["id"];
}
else {
    $response["success"] = false;
    echo json_encode($response);
    return;
}

// TODO proteger contra sql injection
$sinceIdClause = isset($_GET["sinceId"]) ? " id > {$_GET["sinceId"]} " : "";
$limitClause = isset($_GET["quantity"]) ? " LIMIT {$_GET["quantity"]} " : "";

$sql = "SELECT id, person FROM postLikes WHERE post = $postId" . " AND " . $sinceIdClause . $limitClause . ";";

include $_SERVER['DOCUMENT_ROOT'] . '/server/mysql/mysqlConnect.php';

$result = $GLOBALS["db.connection"]->query($sql);

if ($result !== false) {
    $response["success"] = true;
    $response["likes"] = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $response["success"] = false;
}

echo json_encode($response);

include $_SERVER['DOCUMENT_ROOT'] . '/server/mysql/mysqlClose.php';

?>
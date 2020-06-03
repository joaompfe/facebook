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

$sql = "INSERT INTO postLikes (post, person) VALUES ($postId, $personId);";

include $_SERVER['DOCUMENT_ROOT'] . '/server/mysql/mysqlConnect.php';

$response["success"] = $GLOBALS["db.connection"]->query($sql);
$response["like"] = ["author"=>["id"=>$personId, "fullName"=>$_SESSION["client"]["fullName"]],
                    "id"=>mysqli_insert_id($GLOBALS["db.connection"])];

include $_SERVER['DOCUMENT_ROOT'] . '/server/mysql/mysqlClose.php';

echo json_encode($response);

?>
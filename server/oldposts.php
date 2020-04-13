<?php

session_start();

$quantity = $_GET["quantity"];

if (!isset($_SESSION["client"])) {
    http_response_code(401);
    //echo?
    return;
}
else if (!isset($_SESSION["olderSentPostId"])) {
    $sql = "SELECT * FROM posts ORDER BY creationTime DESC LIMIT $quantity;";
}
else {
    $olderSentPostId = $_SESSION["olderSentPostId"];
    $sql = "SELECT * FROM posts ORDER BY creationTime WHERE id < $olderSentPostId DESC LIMIT $quantity;";
}

include 'mysql/mysqlConnect.php';

$posts = array();
$results = $GLOBALS["db.connection"]->query($sql);
if ($row = $result->fetch_assoc()) {
    $_SESSION["olderSentPostId"] =  $row["id"];
    $posts[] = $row;
}
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}
echo json_encode($posts);

include './mysql/mysqlClose.php';
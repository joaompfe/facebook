<?php

session_start();

$quantity = $_GET["quantity"];

if (!isset($_SESSION["client"])) {
    http_response_code(401);
    //echo?
    return;
}
else if (!isset($_SESSION["newerSentPostId"])) {
    $sql = "SELECT * FROM posts ORDER BY creationTime DESC LIMIT $quantity;";
}
else {
    $newerSentPostId = $_SESSION["newerSentPostId"];
    $sql = "SELECT * FROM posts ORDER BY creationTime WHERE id > $newerSentPostId DESC LIMIT $quantity;";
}

include 'mysql/mysqlConnect.php';

$posts = array();
$result = $GLOBALS["db.connection"]->query($sql);
if ($result === FALSE) {
    echo "{}";
    return;
}
if ($row = $result->fetch_assoc()) {
    $_SESSION["newerSentPostId"] =  $row["id"];
    $posts[] = $row;
}
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}
echo json_encode($posts);

include './mysql/mysqlClose.php';
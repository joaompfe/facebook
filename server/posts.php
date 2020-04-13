<?php

session_start();

if (!isset($_SESSION["client"])) {
    //TODO
    //http_response_code(401); ?
    echo "{}";
    return;
}

$quantity = $_GET["quantity"];  // How much posts, at maximum, to return
$type = $_GET["type"];          // Type can be "new" or "old". "new" is for postsId's > "newerSentPostId". "old" is for postsIds < "olderSentPostId"

if (isset($_GET["newerSentPostId"]) && isset($_GET["olderSentPostId"])) {
    $comparisonPostId = $_GET["postId"];    // The id of the post with the highest id (the newest) sent to client
    if ($type == "new") {
        $sql = "SELECT * FROM posts 
        WHERE id > $comparisonPostId 
        ORDER BY creationTime DESC LIMIT $quantity;";
    }
    else {
        $sql = "SELECT * FROM posts 
        WHERE id < $comparisonPostId 
        ORDER BY creationTime DESC LIMIT $quantity;";
    }
}
else {
    $sql = "SELECT * FROM posts ORDER BY creationTime DESC LIMIT $quantity";
}

include 'mysql/mysqlConnect.php';

$posts = array();

$result = $GLOBALS["db.connection"]->query($sql);
if ($result === FALSE) {
    echo "{}";
    return;
}
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}

echo json_encode($posts);

include './mysql/mysqlClose.php';
<?php

session_start();

if (!isset($_SESSION["client"])) {
    //TODO
    //http_response_code(401); ?
    echo "{}";
    return;
}

$postId = $_GET["postId"];      // 
$quantity = $_GET["quantity"];  // How much comments, at maximum, to return

if (isset($_GET["commentId"])) {
    $comparisonCommentId = $_GET["commentId"];    // The id of the commentw post with the highest id (the newest) sent to client
    $whereClause = " WHERE pc.post = $postId AND pc.id < $comparisonCommentId ";
}
else {
    $whereClause = " WHERE pc.post = $postId "; 
}


$sql = "SELECT pc.*, p.fullName, (SELECT COUNT(id) FROM commentComments WHERE comment = pc.id) 'amountOfComments' 
        FROM postComments pc
        JOIN persons p ON p.id = pc.author
        $whereClause
        ORDER BY pc.id DESC LIMIT $quantity;";

$comments = array();

include 'mysql/mysqlConnect.php';

$result = $GLOBALS["db.connection"]->query($sql);
if ($result === FALSE) {
    error_log($GLOBALS["db.connection"]->error);
    echo "{}";
    return;
}
while ($r = $result->fetch_assoc()) {
    $author = array("id"=>$r["author"], "fullName"=>$r["fullName"]);

    $comment = array("id"=>$r["id"], "creationTime"=>$r["creationTime"], "text"=>$r["text"],
    "author"=>$author, "amountOfCommentsInDatabase"=>$r["amountOfComments"]);

    $comments[] = $comment;
}

echo json_encode($comments);
error_log(json_encode($comments, JSON_PRETTY_PRINT));

include './mysql/mysqlClose.php';
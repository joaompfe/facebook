<?php

session_start();

if (!isset($_SESSION["client"])) {
    //TODO
    //http_response_code(401); ?
    $response["success"] = false;
    echo json_encode($response);
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


$sql = "SELECT pc.*, p.fullName, p.gender, (SELECT COUNT(*) FROM commentReplies WHERE comment = pc.id) 'amountOfReplies' 
        FROM postComments pc
        JOIN persons p ON p.id = pc.author
        $whereClause
        ORDER BY pc.id DESC LIMIT $quantity;";

$comments = array();

include $_SERVER['DOCUMENT_ROOT'] . '/server/mysql/mysqlConnect.php';

$result = $GLOBALS["db.connection"]->query($sql);
if (!$result) {
    error_log($GLOBALS["db.connection"]->error);
    $response["success"] = false;
}
while ($r = $result->fetch_assoc()) {
    $author = array("id"=>$r["author"], "fullName"=>$r["fullName"], "gender"=>$r["gender"]);

    $comment = array("id"=>$r["id"], "creationTime"=>$r["creationTime"], "text"=>$r["text"],
    "author"=>$author, "amountOfRepliesInDatabase"=>$r["amountOfReplies"], "replies"=>[]);

    $comments[] = $comment;
}

$response["success"] = true;
$response["comments"] = $comments;

echo json_encode($response);

include $_SERVER['DOCUMENT_ROOT'] . '/server/mysql/mysqlClose.php';
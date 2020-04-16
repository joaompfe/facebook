<?php

session_start();

if (!isset($_SESSION["client"])) {
    //TODO
    //http_response_code(401); ?
    echo "{}";
    return;
}

$commentId = $_GET["commentId"];       // 
$quantity = $_GET["quantity"];      // How much comments, at maximum, to return

if (isset($_GET["commentCommentId"])) {
    $comparisonCommentId = $_GET["commentCommentId"];    // The id of the commentw post with the highest id (the newest) sent to client
    $whereClause = " WHERE cc.comment = $commentId AND cc.id < $comparisonCommentId ";
}
else {
    $whereClause = " WHERE cc.comment = $commentId "; 
}


$sql = "SELECT cc.*, p.fullName, (SELECT COUNT(id) FROM commentComments WHERE comment = cc.id) 'amountOfComments' 
        FROM commentComments cc
        JOIN persons p ON p.id = cc.author
        $whereClause
        ORDER BY cc.id DESC LIMIT $quantity;";

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
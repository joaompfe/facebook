<?php

session_start();

if (!isset($_SESSION["client"])) {
    //TODO
    //http_response_code(401); ?
    $response["success"] = FALSE;
    echo json_encode($response);
    return;
}

$commentId = $_GET["commentId"]; 
$quantity = $_GET["quantity"];  // How much comments, at maximum, to return

if (isset($_GET["commentReplyId"])) {
    $comparisonReplyId = $_GET["commentReplyId"];    // The id of the commentw post with the highest id (the newest) sent to client
    $whereClause = " WHERE replies.comment = $commentId AND replies.id < $comparisonReplyId ";
}
else {
    $whereClause = " WHERE replies.comment = $commentId "; 
}


$sql = "SELECT replies.*, p.fullName
        FROM commentReplies replies
        JOIN persons p ON p.id = replies.author
        $whereClause
        ORDER BY replies.id DESC LIMIT $quantity;";

$replies = array();

include $_SERVER['DOCUMENT_ROOT'] . '/server/mysql/mysqlConnect.php';

$result = $GLOBALS["db.connection"]->query($sql);
if (!$result) {
    error_log($GLOBALS["db.connection"]->error);
    $response["success"] = FALSE;
    echo json_encode($response);
    return;
}
while ($r = $result->fetch_assoc()) {
    $author = array("id"=>$r["author"], "fullName"=>$r["fullName"]);

    $reply = array("id"=>$r["id"], "creationTime"=>$r["creationTime"], "text"=>$r["text"],
    "author"=>$author);

    $replies[] = $reply;
}

$response["success"] = TRUE;
$response["replies"] = $replies;

echo json_encode($response);

include $_SERVER['DOCUMENT_ROOT'] . '/server/mysql/mysqlClose.php';
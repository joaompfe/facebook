<?php
/**
 * This script returns a quantity os posts, where each post contains information
 * about its autor and information about its most liked comments. The most liked 
 * comment also contains an empty array of replies.
 */
session_start();

if (!isset($_SESSION["client"])) {
    //TODO
    //http_response_code(401); ?
    $response["success"] = FALSE;
    echo json_encode($response);
    return;
}

$quantity = $_GET["quantity"];  // How much posts, at maximum, to return
$type = $_GET["type"];          // Type can be "new" or "old". "new" is for postsId's > "newerSentPostId". "old" is for postsIds < "olderSentPostId"

if (isset($_GET["postId"])) {
    $comparisonPostId = $_GET["postId"];    // TODO comentar
    if ($type == "new") {
        $whereClause = " WHERE pos.id > $comparisonPostId ";
    }
    else {
        $whereClause = " WHERE pos.id < $comparisonPostId ";
    }
}
else {
    $whereClause = " ";
}
/*
$stmt = array(
    "SELECT pos.id, pos.creationTime, pos.text, pos.author 'author.id', 
        author.fullName 'author.fullName', pc.id 'comments[].id', pc.author 'comments[].author.id', 
        pc.creationTime 'comments[].creationTime', pc.text 'comments[].text', 
        commentAuthor.fullName 'comments[].author.fullName',
        (SELECT COUNT(*) 'count' FROM postComments WHERE post = pos.id) 'amountOfCommentsInDatabase',
        (SELECT COUNT(*) 'count' FROM commentComments WHERE comment = pc.id) 'comment.amountOfCommentsInDatabase'
        FROM posts pos
        JOIN persons author ON author.id = pos.author
        LEFT JOIN postComments pc ON pc.id = (SELECT id FROM postComments pc WHERE post = pos.id ORDER BY likes DESC LIMIT 1)
        LEFT JOIN persons commentAuthor ON commentAuthor.id = pc.author
        $whereClause
        ORDER BY creationTime DESC LIMIT $quantity;",
    "?.likes[]"=>"SELECT person 'author.id', fullName 'author.fullName' FROM postLikes pl
        JOIN persons p ON p.id = pl.person WHERE post = ?.id;"
);*/

/*  */
$stmt = array(
    "SELECT pos.id, pos.creationTime, pos.text, pos.author 'author.id', 
        author.fullName 'author.fullName',
        (SELECT COUNT(*) FROM postComments WHERE post = pos.id) 'amountOfCommentsInDatabase'
        FROM posts pos
        JOIN persons author ON author.id = pos.author
        $whereClause
        ORDER BY creationTime DESC LIMIT $quantity;",
    "?.likes[]"=>"SELECT person 'author.id', fullName 'author.fullName' FROM postLikes pl
        JOIN persons p ON p.id = pl.person WHERE post = ?.id;",
    "?.comments[]"=>"SELECT postComments.id, author 'author.id', creationTime, text, 
        fullName 'author.fullName', 
        (SELECT COUNT(*) FROM commentReplies WHERE comment = postComments.id) 'amountOfRepliesInDatabase' 
        FROM postComments 
        JOIN persons on postComments.author = persons.id
        WHERE post = ?.id ORDER BY (
            SELECT IFNULL(COUNT(*), 0) FROM commentLikes WHERE comment = postComments.id) DESC LIMIT 1;",
    "?.comments[].replies[]"=>"SELECT * FROM commentReplies WHERE id = -1;"
);
include 'mysql/mysqlConnect.php';

require_once 'utils/EasyQuery.php';
use joaompfe\EasyQuery\EasyQuery;
$easyQuery = new EasyQuery();

$posts = $easyQuery->query($GLOBALS["db.connection"], $stmt);

$response["success"] = TRUE;
$response["posts"] = $posts;
echo json_encode($response);

error_log(json_encode($response["posts"], JSON_PRETTY_PRINT));
include './mysql/mysqlClose.php';
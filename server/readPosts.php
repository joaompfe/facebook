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
    $response["success"] = false;
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

$stmt = array(
    "SELECT pos.id, pos.creationTime, pos.text, pos.author 'author.id', 
        author.fullName 'author.fullName', author.gender 'author.gender',
        (SELECT COUNT(*) FROM postComments WHERE post = pos.id) 'amountOfCommentsInDatabase'
        FROM posts pos
        JOIN persons author ON author.id = pos.author
        $whereClause
        ORDER BY creationTime DESC LIMIT $quantity;",
    "?.likes[]"=>"SELECT person 'author.id', fullName 'author.fullName' FROM postLikes pl
        JOIN persons p ON p.id = pl.person WHERE post = ?.id;",
    "?.comments[]"=>"SELECT postComments.id, author 'author.id', creationTime, text, 
        fullName 'author.fullName', gender 'author.gender',
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

include './mysql/mysqlClose.php';
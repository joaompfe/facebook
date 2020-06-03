<?php
/**
 * This script returns a quantity os posts, where each post contains information
 * about its autor and information about its most liked comments. The most liked 
 * comment also contains an empty array of replies.
 */
require_once 'utils/EasyQuery.php';
use joaompfe\EasyQuery\EasyQuery;

session_start();

if (!isset($_SESSION["client"])) {
    //TODO
    //http_response_code(401); ?
    $response["success"] = false;
    echo json_encode($response);
    return;
}

include 'mysql/mysqlConnect.php';
$conn = $GLOBALS["db.connection"];

// How much posts, at maximum, to return
$quantity = mysqli_real_escape_string($conn, $_GET["quantity"]);
// Type can be "new" or "old". "new" is for postsId's > "newerSentPostId". "old" is for postsIds < "olderSentPostId"
$type = $_GET["type"];
$id = $_SESSION["client"]["id"];

$whereClause = "requestor = $id OR acceptor = $id OR author = $id OR public = 1 ";
$havingClause = "";

if (isset($_GET["postId"])) {
    $comparisonPostId = $_GET["postId"];
    $havingClause = $type == "new" ? " pos.id > $comparisonPostId " : " pos.id < $comparisonPostId ";

    error_log($whereClause . "\n" . $whereClause . "\n" . $whereClause . "\n" . $whereClause . "\n");
}
else {
    error_log("A\nA\nA\nA\nA\nA\n");
}

$stmt = array(
    "SELECT pos.id, pos.creationTime, pos.text, pos.author 'author.id', 
        author.fullName 'author.fullName', author.gender 'author.gender',
        (SELECT COUNT(*) FROM postComments WHERE post = pos.id) 'amountOfCommentsInDatabase'
        FROM posts pos
        JOIN persons author ON author.id = pos.author
        LEFT JOIN friendships f ON f.requestor = pos.author OR f.acceptor = pos.author
        WHERE $whereClause
        HAVING $havingClause
        ORDER BY creationTime DESC LIMIT $quantity;",
    "?.likes[]"=>"SELECT person'author.id', pl.id 'id', fullName 'author.fullName' FROM postLikes pl
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

$easyQuery = new EasyQuery();

$posts = $easyQuery->query($conn, $stmt);
if ($posts == null) { $posts = []; };

$response["success"] = TRUE;
$response["posts"] = $posts;
echo json_encode($response);

include './mysql/mysqlClose.php';
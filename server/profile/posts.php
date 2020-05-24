<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/server/utils/EasyQuery.php';
use joaompfe\EasyQuery\EasyQuery;

session_start();

if (!isset($_SESSION["client"])) {
    //TODO
    //http_response_code(401); ?
    $response["success"] = false;
    echo json_encode($response);
    return;
}

include $_SERVER['DOCUMENT_ROOT'] .  '/server/mysql/mysqlConnect.php';
$conn = $GLOBALS["db.connection"];

$personId = mysqli_real_escape_string($conn, $_GET["id"]);
// How much posts, at maximum, to return
$quantity = mysqli_real_escape_string($conn, $_GET["quantity"]);
// Type can be "new" or "old". "new" is for postsId's > "newerSentPostId". "old" is for postsIds < "olderSentPostId"
$type = $_GET["type"];
$sessionId = $_SESSION["client"]["id"];

// Test if they are friends or is public profile
if ($personId !== $sessionId) {
    $sql = "SELECT * FROM friendships f
    RIGHT JOIN persons ON f.requestor = persons.id OR f.acceptor = persons.id
    WHERE 
    ((requestor = $personId AND acceptor = $sessionId) OR 
    (requestor = $sessionId AND acceptor = $personId)) OR
    (id = $personId AND public = '1')";

    $result = $GLOBALS["db.connection"]->query($sql);
    if (!$result) {
        error_log($GLOBALS["db.connection"]->error);
        http_response_code(400);
        include $_SERVER['DOCUMENT_ROOT'] . '/server/mysql/mysqlClose.php';
        exit();
    }

    $areFriends = $result->num_rows > 0;
    if (!$areFriends) {
        $response["success"] = true;
        $response["areFriends"] = false;
        $response["posts"] = null;
        echo json_encode($response);
        include $_SERVER['DOCUMENT_ROOT'] . '/server/mysql/mysqlClose.php';
        exit();
    }
}

$whereClause = " author = $personId ";

if (isset($_GET["postId"]) && is_int($_GET["postId"])) {
    $comparisonPostId = $_GET["postId"];
    $whereClause .= $type == "new" ? " pos.id > $comparisonPostId " : " pos.id < $comparisonPostId ";
}

$stmt = array(
    "SELECT pos.id, pos.creationTime, pos.text, pos.author 'author.id', 
        author.fullName 'author.fullName', author.gender 'author.gender',
        (SELECT COUNT(*) FROM postComments WHERE post = pos.id) 'amountOfCommentsInDatabase'
        FROM posts pos
        JOIN persons author ON author.id = pos.author
        LEFT JOIN friendships f ON f.requestor = pos.author OR f.acceptor = pos.author
        WHERE $whereClause
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

$easyQuery = new EasyQuery();

$posts = $easyQuery->query($conn, $stmt);

$response["success"] = true;
$response["areFriends"] = true;
$response["posts"] = $posts;
echo json_encode($response);

include $_SERVER['DOCUMENT_ROOT'] . '/server/mysql/mysqlClose.php';
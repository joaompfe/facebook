<?php
// This script returns a quantity os posts, where each post contains information
// about its autor and information about its most liked comments. The most liked
// comment also contains an empty array of subcomments. All this is done with
// associative arrray and then encoded to json to send to client.
session_start();

if (!isset($_SESSION["client"])) {
    //TODO
    //http_response_code(401); ?
    echo "{}";
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
// Selects the newest $quantity comments and, for each, information about the author and the 
// most liked comment
$sql = "SELECT pos.*, author.fullName 'fullName', pc.id 'commentId', pc.author 'commentAuthorId', 
        pc.creationTime 'commentCreationTime', pc.text 'commentText', pc.likes 'commentLikes', 
        commentAuthor.fullName 'commentAuthorFullName'
        FROM posts pos
        JOIN persons author ON author.id = pos.author
        JOIN postComments pc ON pc.id = (SELECT id FROM postComments pc WHERE post = pos.id ORDER BY likes DESC LIMIT 1)
        JOIN persons commentAuthor ON commentAuthor.id = pc.author
        $whereClause
        ORDER BY creationTime DESC LIMIT $quantity;";

include 'mysql/mysqlConnect.php';

$posts = array();

$result = $GLOBALS["db.connection"]->query($sql);
if ($result === FALSE) {
    error_log($GLOBALS["db.connection"]->error);
    echo "{}";
    return;
}
while ($r = $result->fetch_assoc()) {
    $postId = $r["id"];
    $mostLikedCommentId = $r["commentId"];

    // Author of the post
    $author = array("id"=>$r["author"], "fullName"=>$r["fullName"]);
    
    $subcomments = array();
    $comments = array();

    $post = array("id"=>$r["id"], "creationTime"=>$r["creationTime"], "text"=>$r["text"],
        "likes"=>$r["likes"], "author"=>$author);
    
    $mostLikedComment = array("id"=>$r["commentId"], "creationTime"=>$r["commentCreationTime"],
    "text"=>$r["commentText"], "author"=>array("id"=>$r["commentAuthorId"], 
    "fullName"=>$r["commentAuthorFullName"]), "comments"=>$subcomments, "likes"=>$r["commentLikes"]);
    
    // Add amountOfCommentsInDatabase property to $post
    $sql = "SELECT COUNT(*) 'count' FROM postComments WHERE post = $postId";
    $countResult = $GLOBALS["db.connection"]->query($sql);
    if ($countResult->num_rows > 0) {
        $count = $countResult->fetch_assoc()["count"];
        $post["amountOfCommentsInDatabase"] = $count;
    }
    // Add amountOfCommentsInDatabase property to $mostLikedComment
    $sql = "SELECT COUNT(*) 'count' FROM commentComments WHERE comment = $mostLikedCommentId";
    $countResult = $GLOBALS["db.connection"]->query($sql);
    if ($countResult->num_rows > 0) {
        $count = $countResult->fetch_assoc()["count"];
        $mostLikedComment["amountOfCommentsInDatabase"] = $count;
    }

    // Most liked comment of the post
    $comments[] = $mostLikedComment;
    
    $post["comments"] = $comments;

    $posts[] = $post;
}

echo json_encode($posts);
//error_log(json_encode($posts, JSON_PRETTY_PRINT));

include './mysql/mysqlClose.php';

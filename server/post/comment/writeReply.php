<?php
// This script writes a comment post in the database

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}

if (isset($_POST["content"]) && isset($_POST["commentId"]) && isset($_SESSION["client"]["id"])) {
    $content = $_POST["content"];
    $commentId = $_POST["commentId"];
    $authorId = $_SESSION["client"]["id"];
}
else {
    error_log("Missing POST data or no client sessioned");
    $response["success"] = false;
    echo json_encode($response);
    return;
}


$sql = "INSERT INTO commentReplies (author, text, comment) VALUES ($authorId, '$content', $commentId);";

include $_SERVER['DOCUMENT_ROOT'] . '/server/mysql/mysqlConnect.php';

// query returns TRUE if executes sucessfully
$response["success"] = $GLOBALS["db.connection"]->query($sql);

if ($response["success"]) {
    // Get inserted comment to return it back to client side
    $insertedCommentId = mysqli_insert_id($GLOBALS["db.connection"]);
    $sql = "SELECT replies.*, author.fullName FROM facebook.commentReplies replies
            JOIN persons author ON author.id = replies.author
            WHERe replies.id = $insertedCommentId;";
    $result = $GLOBALS["db.connection"]->query($sql);
    if ($result->num_rows > 0) {
        $r = $result->fetch_assoc();
        $response["reply"] = array("id"=>$r["id"], "creationTime"=>$r["creationTime"],
            "text"=>$r["text"], "likes"=>$r["likes"], "author"=>array("id"=>$r["author"],
            "fullName"=>$r["fullName"]));
    }
}
else {
    error_log("SQL: " . $sql . " ERROR: " . $GLOBALS["db.connection"]->error); 
}

include $_SERVER['DOCUMENT_ROOT'] . '/server/mysql/mysqlClose.php';

echo json_encode($response);
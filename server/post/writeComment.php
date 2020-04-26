<?php
// This script writes a comment post in the database

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}

if (isset($_POST["content"]) && isset($_POST["postId"]) && isset($_SESSION["client"]["id"])) {
    $content = $_POST["content"];
    $postId = $_POST["postId"];
    $authorId = $_SESSION["client"]["id"];
}
else {
    $response["success"] = FALSE;
    echo json_encode($response);
    return;
}


$sql = "INSERT INTO postComments (author, text, post) VALUES ($authorId, '$content', $postId);";

include $_SERVER['DOCUMENT_ROOT'] . '/server/mysql/mysqlConnect.php';

// query returns TRUE if executes sucessfully
$response["success"] = $GLOBALS["db.connection"]->query($sql);

if ($response["success"]) {
    // Get newly inserted comment to send it back to client side
    $insertedCommentId = mysqli_insert_id($GLOBALS["db.connection"]);
    $sql = "SELECT pc.*, author.fullName FROM facebook.postComments pc
            JOIN persons author ON author.id = pc.author
            WHERe pc.id = $insertedCommentId;";
    $result = $GLOBALS["db.connection"]->query($sql);
    if ($result->num_rows > 0) {
        $r = $result->fetch_assoc();
        $response["comment"] = array("id"=>$r["id"], "creationTime"=>$r["creationTime"],
            "text"=>$r["text"], "likes"=>$r["likes"], "author"=>array("id"=>$r["author"],
            "fullName"=>$r["fullName"]), "amountOfRepliesInDatabase"=>0, "replies"=>[]);
    }
}
else {
    error_log("SQL: " . $sql . " ERROR: " . $GLOBALS["db.connection"]->error); 
}

include $_SERVER['DOCUMENT_ROOT'] . '/server/mysql/mysqlClose.php';

echo json_encode($response);
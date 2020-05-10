<?php

session_start();

if (!isset($_SESSION["client"])) {
    //TODO
    //http_response_code(401); ?
    $response["success"] = FALSE;
    echo json_encode($response);
    error_log("AWWAWWAWAAWAW");
    return;
}

$id = $_GET["id"];

$sql = "SELECT filename FROM images i
		JOIN persons p ON p.profilePicID = i.id
		where p.id = '$id'";

include '../mysql/mysqlConnect.php';

$result = $GLOBALS["db.connection"]->query($sql);

$picture = $result->fetch_assoc();

$response['success'] = TRUE;
$response['profilePic']  = $picture;

include '../mysql/mysqlClose.php';

echo json_encode($response);

?>
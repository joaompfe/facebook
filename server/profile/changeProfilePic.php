<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && empty($_POST)) {
    $_POST = json_decode(file_get_contents('php://input'), true);
}

if (empty($_FILES) ||                                   // There's no uploaded file
$_FILES['file']['error'] !== UPLOAD_ERR_OK ||           // Error uploading file
getimagesize($_FILES['file']['tmp_name']) === false ||  // Uploaded file is not image
!isset($_SESSION['client']))                            // There's no client in session
{
    $response['success'] = false;
    echo json_encode($response);
    return;
}

$id = $_SESSION["client"]["id"];

// Delete existing profile picture
array_map('unlink', glob($_SERVER["DOCUMENT_ROOT"] . "/persons/$id/profile_pic.*"));

$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
$destination = $_SERVER["DOCUMENT_ROOT"] . "/persons/$id/profile_pic.$extension";

// The new image will be saved in /persons/$id/profile_pic.$extension
$response['success'] = move_uploaded_file($_FILES['file']['tmp_name'], $destination);
echo json_encode($response);
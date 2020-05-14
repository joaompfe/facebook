<?php

session_start();

if (!isset($_SESSION["client"]) || 
!isset($_GET["id"]) ||
!isset($_GET["gender"]) ||
!isset($_GET["size"])) {
    //TODO
    http_response_code(401);
    exit();
    $response["success"] = false;
    echo json_encode($response);
    return;
}

$id = $_GET["id"];
// Find profile picture
$files_path = glob($_SERVER["DOCUMENT_ROOT"] . "/persons/$id/profile_pic.*");

// If don't exist uses default picture
if (isset($files_path[0])) {
    $path = $files_path[0];
}
else {
    $gender = ($_GET["gender"] == "F") ? 'f' : 'm';
    switch ($_GET["size"]) {
        case 'XS': $size = '40'; break;
        case 'S': $size = '50'; break;
        case 'M': $size = '100'; break;
        case 'L': $size = '960'; break;
        default: $size = '100';
    }
    $path = $_SERVER["DOCUMENT_ROOT"] 
    . '/content/default_profile_photos/' . $gender . '_profile_photo_' . $size . '.jpg';
}

// Open picture in binary mode and read only.
$fp = fopen($path, 'rb');

// Send the right headers
header("Content-Type: image/png");
header("Content-Length: " . filesize($path));

// Dump the picture and stop the script
fpassthru($fp);
exit();
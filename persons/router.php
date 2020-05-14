<?php
// INUTILIZADO
// TODO testar se o utilizador pode ver a foto

// Se o utilizador não tiver foto retornar a default
// open the file in a binary mode
$id = $_SESSION['client']['id'];
$path = "./$id/profile_pic.*";
$fp = fopen($path, 'rb');
if (!$fp) {
    $path = '../content/default_profile_photos/f_profile_photo_100.jpg';
    $fp = fopen($path, 'rb');
}

// send the right headers
header("Content-Type: image/png");
header("Content-Length: " . filesize($path));

// dump the picture and stop the script
fpassthru($fp);
exit();

http_response_code(403);
exit();
return false;
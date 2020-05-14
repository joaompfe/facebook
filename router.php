<?php

$ruri = $_SERVER["PHP_SELF"];

if (preg_match('/^\/(server|app|content|index.html)/', $ruri)) {
    return false;
}

if (preg_match('/persons/', $ruri)) {
    http_response_code(403);
    exit();
}

http_response_code(400);
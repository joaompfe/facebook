<?php

session_start();

if (isset($_GET["personID"]) && isset($_SESSION["client"])) {
	$friend = $_GET["personID"];
	$user = $_SESSION["client"]["id"];
} else {
	$response["success"] = false;
	echo json_encode($response);
	return;
}

include '../mysql/mysqlConnect.php';


$sql = "INSERT INTO friendrequest (requestor, target) values  ('$user', $friend)";

$response["success"] = $GLOBALS["db.connection"]->query($sql);

echo json_encode($response);

include '../mysql/mysqlClose.php';


?>

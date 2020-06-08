<?php

session_start();

if (isset($_GET["personID"]) && isset($_SESSION["client"]) && isset($_GET["requestResponse"])) {
	$requestResponse = $_GET["requestResponse"];
	$requestor = $_GET["personID"];
	$user = $_SESSION["client"]["id"];
} else {
	$response["success"] = false;
	echo json_encode($response);
	return;
}


include '../mysql/mysqlConnect.php';

$sqlu ="UPDATE friendrequest SET state='$requestResponse' WHERE requestor = $requestor and target = $user";
$sqlin ="INSERT INTO friendships (requestor, acceptor) VALUES ($requestor, $user)";

$response["success"] = $GLOBALS["db.connection"]->query($sqlu);

if(strcmp('$requestResponse', 'Accepted')){

	$response["success"] = $GLOBALS["db.connection"]->query($sqlin);

}



echo json_encode($response);

include '../mysql/mysqlClose.php';

?>
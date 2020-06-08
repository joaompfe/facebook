<?php

session_start();

if (!isset($_SESSION["client"])) {
	//TODO
	//http_response_code(401); ?
	$response["success"] = false;
	echo json_encode($response);
	return;
}

include '../mysql/mysqlConnect.php';

$user = $_SESSION["client"]["id"];

$sql = "SELECT * FROM friendrequest fr 
			JOIN persons p on fr.requestor = p.id
			WHERE target = '$user' and state = 'Waiting'";

$personRequests = array();

$result = $GLOBALS["db.connection"]->query($sql);

if (!$result) {
	error_log($GLOBALS["db.connection"]->error);
	$response["success"] = false;
}
while ($row = $result->fetch_assoc()) {
	$personRequests[] = array("id" => $row["requestor"], "fullName" => $row["fullName"], "gender" => $row["gender"]);
}


$response["success"] = true;
$response["personRequests"] = $personRequests;

echo json_encode($response);


include '../mysql/mysqlClose.php';

?>
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

$id = $_SESSION["client"]["id"];

// $sql = "SELECT * FROM persons";

$sql = "SELECT * from persons
		where id not in(
			select id from friendships f
			LEFT join persons p on f.requestor = p.id or f.acceptor = p.id where requestor = $id or acceptor = $id) and id <> $id";

$suggestions = array();

$result = $GLOBALS["db.connection"]->query($sql);

if (!$result) {
	error_log($GLOBALS["db.connection"]->error);
	$response["success"] = false;
}
while($row = $result->fetch_assoc())
{
	$suggestions[] = array("id"=>$row["id"], "name"=> $row["firstName"] . $row["lastName"], "gender"=>$row["gender"]);
}

$response["success"] = true;
$response["personSuggestions"] = $suggestions;


echo json_encode($response);

include '../mysql/mysqlClose.php';
?>
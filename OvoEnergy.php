<?php

error_reporting(E_ALL);
ini_set('display_errors', true);

header('Content-Type: application/json');


$dbhost = "*****";
$dbuser = "*****";
$dbpass = "*****";
$dbname = "*****";



$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sqlE = "SELECT dateandtime, consumption FROM Electricity ORDER BY dateandtime";
$sqlG = "SELECT dateandtime, consumption FROM Gas ORDER BY dateandtime";
$resultE = $conn->query($sqlE);


$dataE = array();
foreach ($resultE as $row) {
	if(time() - strtotime($row["dateandtime"]) > 60*60*24*7) {} else {
		$row["dateandtime"] = date("d-M H:i", strtotime($row["dateandtime"]));
		$dataE[] = $row;
	}
}

$resultG = $conn->query($sqlG);
$dataG = array();
foreach ($resultG as $row) {
	if(time() - strtotime($row["dateandtime"]) > 60*60*24*7) {} else {
		$row["dateandtime"] = date("d-M H:i", strtotime($row["dateandtime"]));
		$dataG[] = $row;
	}
}

$join = array($dataE,$dataG);

echo json_encode($join);



$conn->close();
?>
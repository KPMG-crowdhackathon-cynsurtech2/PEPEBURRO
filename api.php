<?php
require_once("settings.php");
require_once("mysql.php");

$op = isset($_GET['op']) ? $_GET['op'] : "";
$id = isset($_GET['id']) ? $_GET['id'] : "";
$type_id = isset($_GET['type_id']) ? $_GET['type_id'] : "";

if(!$op)
	die("Choose operation");
elseif($op == "get_by_id"){
	if($id){
		$res = get_by_id($id);
		echo json_encode($res);
	}
	else
		die("Select id");
}
elseif($op == "get_by_type_id"){
	if($type_id){
		$res = get_by_type_id($type_id);
		echo json_encode($res);
	}
	else
		die("Select type_id");
}



?>
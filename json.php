<?php 

require('config.php');
header('Content-Type: application/json');

$result = $db->query("SELECT * FROM posts WHERE is_published = 1");

if(!$result){
	die($db->error);
}
if($result->num_rows >= 1){
	
	$rows = array();
	while($row = $result->fetch_assoc()) {
		$rows[] = $row;
	}
	
	print json_encode($rows); 
	
}

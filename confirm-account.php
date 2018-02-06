<?php 
require('config.php');
//If the user follows a confirmation link here, it will set their account to active
//URL will look like ?user_id=X&key=dfkjg

$user_id = $_GET['user_id'];

$secret_key = $_GET['key'];
$secret_key = $secret_key;

//look this combo up in the DB, if it matches 1 row, activate their account, send them to login page with feedback 

echo $query = "SELECT user_id, secret_key
			FROM users 
			WHERE user_id = $user_id
			AND secret_key = '$secret_key'
			LIMIT 1";
$result = $db->query($query);
if( !$result ){
	die('Invalid Confirmation Link');
}

if( $result->num_rows != 1 ){
	die('Invalid Confirmation Link (no rows)');
}else{
	//success. update the db
	$query = "UPDATE users
			SET did_confirm = 1
			WHERE user_id = $user_id";
	$result = $db->query($query);
	//check it
	if( $db->affected_rows == 1 ){
		//redirect
		header('Location:login.php?feedback=Your%20account%20is%20now%20active.%20Please%20log%20in.');
	}else{
		die('Update failed');
	}	
}
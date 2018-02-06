<?php 
//parse the comment if it was submitted
if( $_POST['did_comment'] ){
	//extract and sanitize
	$body = filter_var( $_POST['body'], FILTER_SANITIZE_STRING );
	$body = mysqli_real_escape_string($db, $body);

	//validate
	$valid = true;

	//body cannot be blank
	if( $body == '' ){
		$valid = false;
		$errors['body'] = 'The body of the comment cannot be blank';
	}

	//if valid, add to database
	if($valid){
		//Make this work with the logged in user. User 1 is temporary fix
		$user_id = $logged_in_user['user_id'];
		$query = "INSERT INTO comments
				( user_id, body, date, post_id, is_approved )
				VALUES 
				( $user_id, '$body', NOW(), $post_id, 1 )";
		//run it
		$result = $db->query( $query );
		//check it
		if( $db->affected_rows >= 1 ){
			$feedback = 'Thank you for your comment';
		}else{
			$feedback = 'Comment not added to Database';
		}
	}else{
		//feedback
		$feedback = 'Sorry, Your comment cannot be added.';
	}
	
}//end if did comment
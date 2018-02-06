<?php  
//which post are we editing?
$post_id = $_GET['post_id'];

if( ! $post_id ){
	die('invalid post.');
}

//Update the post info if the user submitted the form
if( $_POST['did_edit'] ){
	//sanitize everything
	$title 			= clean_string( $_POST['title'] );
	$body			= clean_string( $_POST['body'] );
	$category_id 	= clean_int( $_POST['category_id'] );
	$allow_comments = clean_boolean( $_POST['allow_comments'] );

	//validate 
	$valid = true;

	//if title is blank or too long (75)
	if( $title == '' OR strlen($title) > 75 ){
		$valid = false;
		$errors['title'] = 'Please provide a title that is shorter than 75 characters';
	}
	
	//if body is too long
	if( strlen($body) > 600 ){
		$valid = false;
		$errors['body'] = 'The description must contain fewer than 600 characters';
	}
	//if category is blank
	if( $category_id == '' ){
		$valid = false;
		$errors['category_id'] = 'Please choose a category for your post';
	}
	
	//if valid, update
	if($valid){
		$query = "UPDATE posts
				SET 
				title 			= '$title',
				body 			= '$body',
				category_id 	= $category_id,
				is_published 	= 1,
				allow_comments 	= $allow_comments
				WHERE post_id = $post_id
				LIMIT 1";
		$result  = $db->query( $query );
		if(! $result){
			die($db->error);
		}

		if( $db->affected_rows == 1 ){
			$feedback = 'Success! Your post has been saved.';
		}else{
			$feedback = 'No changes were made to this post';
		}
	}//end if valid
	else{
		$feedback = 'Fix the following Problems:';
	}	
	
}//end form parser


//get all the info about this post and make sure it belongs to the logged in user
	$user_id = $logged_in_user['user_id'];
	$query = "SELECT *
			FROM posts
			WHERE post_id = $post_id
			AND user_id = $user_id
			LIMIT 1";
	$result = $db->query($query);

	if(!$result){
		die( $db->error );
	}
	//if no rows found, this post doesn't belong to the user
	if($result->num_rows == 0){
		die('Not your image');
	}
	//neccessary to make the form show the image and "stick"
	$row = $result->fetch_assoc();

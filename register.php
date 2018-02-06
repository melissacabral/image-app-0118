<?php 
/*
stand-alone register page
The normal site header is not included on this page
*/
require('config.php');
include_once('includes/functions.php');

//parse the register form
if($_POST['did_register']){
	//sanitize everything for the DB
	$username = clean_string( $_POST['username'] );
	$email = clean_string( $_POST['email'] );
	$password = clean_string( $_POST['password'] );

	if($_POST['policy'] == 1){
		$policy = 1;
	}else{
		$policy = 0;
	}

	//validate
	$valid = true;
		// username blank or outside of limits (5-30 chars)
	if( strlen($username) < 5 OR strlen($username) > 30 ){
		$valid = false;
		$errors['username'] = 'Please choose a username between 5 - 30 characters';
	}else{
			// username already taken
		$query = "SELECT username 
		FROM users 
		WHERE username = '$username'
		LIMIT 1";
		$result = $db->query($query);
		if( $result->num_rows == 1 ){
			$valid = false;
			$errors['username'] = 'Sorry, your username is already taken';
		}
		}//end of username checks
		
		// email wrong format
		if( ! filter_var($email, FILTER_VALIDATE_EMAIL) ){
			$valid = false;
			$errors['email'] = 'Please provide a valid email address';
		}else{
			// email already taken
			$query = "SELECT email 
			FROM users
			WHERE email = '$email'
			LIMIT 1";
			$result = $db->query( $query );
			if( $result->num_rows == 1 ){
				$valid = false;
				$errors['email'] = 'That email account is already in use. Do you want to login?';
			}
		}//end email checks
		
		// password too short (less than 8 chars)
		if( strlen($password) < 8 ){
			$valid = false;
			$errors['password'] = 'Your password must be at least 8 characters long';
		}
		// policy not checked
		if( $policy != 1 ){
			$valid = false;
			$errors['policy'] = 'You must agree to the terms of service';
		}
	//if valid, add a new user to DB
		if( $valid ){
		//add salt to the password before hashing
			$salted_pass = $password . SALT;

			$secret_key = sha1( microtime() . SALT );
			$query = "INSERT INTO users
			( username, email, password, join_date, is_admin, secret_key, did_confirm )
			VALUES 
			( '$username', '$email', SHA1('$salted_pass'), NOW(), 0, '$secret_key', 0 )";
			$result = $db->query( $query );
		//if that works, send email confirmation, log them in, etc
			if( $db->affected_rows == 1 ){
			//DB success! 
				$user_id = $db->insert_id;
			//Generate confirmation link
				$confirmation_url = SITE_URL . '/confirm-account.php?user_id=' . $user_id . 
				'&amp;key=' . $secret_key;

				$feedback = "Welcome! You are now a user. A confirmation link was sent to 
				$email";
			//TODO. Send this in an email. Echo it on the page for now
				$feedback .= ' <a href="' . $confirmation_url . '">Confirm Link</a>';		
			}else{
				$feedback = 'User not added to DB';
			}
		}else{
			$feedback = 'Your account could not be created for the following reasons:';
	}//end if valid
}//end parser
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Sign up for an Account</title>
	<link rel="stylesheet" type="text/css" href="css/login-style.css">

</head>
<body>

	<h1>Create an Account</h1>

	<?php //show user feedback
	if( isset($feedback) ){
		echo '<div class="feedback">' . $feedback ;
		if( ! empty($errors) ){
			array_to_list($errors);
		}	
		echo  '</div>';
	}
	?>
	
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" novalidate>
		<label>Username</label>
		<input type="text" name="username" required>

		<label>Email</label>
		<input type="email" name="email" required>

		<label>Password</label>
		<input type="password" name="password" required>

		<label>
			<input type="checkbox" name="policy" value="1">
			I agree to the <a href="#">terms of service</a>
		</label>

		<input type="submit" value="Sign Up">
		<input type="hidden" name="did_register" value="1">

	</form>
	
	<footer>
		<a href="login.php">Log In</a> |
		<a href="index.php">Back to home page</a>
	</footer>

</body>
</html>
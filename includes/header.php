<?php 
session_start();
//DB connection
require('config.php');
include_once( 'includes/functions.php' );

//get all the info about the possibly logged in user
$logged_in_user = check_login();
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta charset="utf-8">
	<title>Image App!</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<header class="header">
		<h1><a href="index.php">Image App Name</a></h1>

	</header>
	<nav class="main-navigation wrapper">
		<section class="search-bar">
			<form action="search.php" method="get">
				<label class="screen-reader-text" for="the_phrase">Search:</label>
				<input type="search" name="phrase" id="the_phrase">

				<input type="submit" value="search">
			</form>
		</section>

		<ul class="menu">
			<?php if( ! $logged_in_user ){ ?>
			<li><a href="login.php">Log In</a></li>
			<li><a href="register.php">Sign Up</a></li>

			<?php }else{ ?>
			<li><a href="add-post.php">Add Post</a></li>
			<li>
				<a href="profile.php?user_id=<?php echo $logged_in_user['user_id']; ?>">
					Your Profile
				</a>
			</li>
			<li><a href="#">Edit Profile</a></li>
			<?php } ?>
		</ul>
	</nav>


	<div class="wrapper">
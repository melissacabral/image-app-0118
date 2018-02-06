<?php 
require('includes/header.php');
//redirect to login if not logged in
if( ! $logged_in_user ){
	header('Location:login.php');
}
require('includes/add-post-parse.php'); ?>

<main class="content">
	<h2>Add a Post</h2>

	<?php 
	if(isset($feedback)){
		echo '<div class="feedback">';
			echo $feedback;
			array_to_list($errors);
		echo '</div>';
	} 
	?>
	<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" 
		enctype="multipart/form-data">
		
		<label>Image:</label>
		<input type="file" name="uploadedfile">

		<input type="hidden" name="did_add_post" value="1">

		<input type="submit" value="Next Step: Add Details &rarr;">
	</form>
</main>

<?php require('includes/sidebar.php'); ?>
<?php require('includes/footer.php'); ?>

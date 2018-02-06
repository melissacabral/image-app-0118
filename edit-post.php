<?php 	
/*
Step 2 of Adding a new post
This form can also be used to edit any of the user's existing posts
*/

require('includes/header.php');
require('includes/edit-post-parse.php');
?>

<main class="content">
	<h2>Image Details</h2>

	<img src="<?php echo post_image_url( $row['image'], 'medium' ); ?>" alt="Uploaded Image">


	<?php 
	if(isset($feedback)){
		echo '<div class="feedback">';
			echo $feedback;
			array_to_list($errors);
		echo '</div>';
	} 
	?>

	<form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?' . 
										$_SERVER['QUERY_STRING']; ?>">
		<label>Title</label>
		<input type="text" name="title" value="<?php echo $row['title']; ?>">

		<label>Description</label>
		<textarea name="body"><?php echo $row['body']; ?></textarea>

		<label>Category</label>
		<?php category_dropdown( $row['category_id'] ); ?>

		<label>
			<input type="checkbox" name="allow_comments" value="1" <?php 
			checked( $row['allow_comments'], 1 ); ?> >
			Allow Comments on this post
		</label>	

		<input type="hidden" name="did_edit" value="1">
		<input type="submit" value="Save Post">

	</form>

</main>

<?php require('includes/sidebar.php'); ?>
<?php require('includes/footer.php'); ?>


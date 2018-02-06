<?php include('includes/header.php'); 
//Which user profile? 
$user_id = $_GET['user_id'];
?>

<main class="content">

<?php //get all the info about this user
$query = 		"SELECT users.*, posts.*
				FROM users
					LEFT JOIN posts
				    ON ( users.user_id = posts.user_id )
				WHERE did_confirm = 1
				AND posts.is_published = 1
				AND users.user_id = $user_id
				ORDER BY username ASC
				LIMIT 10";
//run it
$result = $db->query($query);
//check it - are there rows of data to show?
if( $result->num_rows >= 1 ){
	$count = 1;
	//loop it
	while( $row = $result->fetch_assoc() ){

		//only show user info if first iteration
		if($count == 1){
		?>	
		<section class="profile-header full-column">
			<h2 class="user-card">
				<?php show_avatar($row['user_id']); ?>
				<?php echo $row['username']; ?>		
			</h2>
			<p><?php echo $row['bio']; ?></p>
		</section>
		<?php }//end if first iteration ?>

		<a href="single.php?post_id=<?php echo $row['post_id']; ?>">
			<img src="<?php echo post_image_url($row['image'], 'thumbnail') ?>">
		</a>
		<?php 
		$count ++;
	} //end while
	//free it
	$result->free();
}else{
	//no rows found
	echo 'Sorry, no posts to show here';
} ?>

</main>

<?php include('includes/sidebar.php'); ?>
<?php include('includes/footer.php'); ?>

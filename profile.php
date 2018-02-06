<?php include('includes/header.php'); 
//Which user profile? 
$user_id = $_GET['user_id'];
?>

<main class="content">

<?php //get all the info about this user
$query = 		"SELECT * FROM users WHERE user_id = $user_id LIMIT 1";
//run it
$result = $db->query($query);
//check it - are there rows of data to show?
if( $result->num_rows >= 1 ){
	//loop it
	while( $row = $result->fetch_assoc() ){
		?>	
		<section class="profile-header full-column">
			<h2 class="user-card">
				<?php show_avatar($row['user_id']); ?>
				<?php echo $row['username']; ?>		
			</h2>
			<p><?php echo $row['bio']; ?></p>
		</section>


		<!-- <article>
			<h2>
				<?php show_avatar( $row['user_id'], 50 ); ?>
				<?php echo $row['username']; ?>				
			</h2>
			<a href="single.php?post_id=<?php echo $row['post_id']; ?>">
				<img src="http://<?php echo $row['image']; ?>" alt="<?php echo $row['title'] ?>">
			</a>

			<div class="post-info">
				<h3><?php echo $row['title']; ?></h3>
				<h4><?php echo $row['name']; ?></h4>
				<p><?php echo $row['body'] ?></p>
				<span class="date"><?php echo convert_date($row['date']); ?></span>
				<span class="comment-count"><?php count_comments( $row['post_id'] ); ?></span>
			</div>
		</article> -->
		<?php 
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

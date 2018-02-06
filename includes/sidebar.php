<aside class="sidebar">

	<?php if( $logged_in_user ){ ?>
	<section class="logged-in-user">
		<h2>You are logged in</h2>

		<?php show_avatar($logged_in_user['user_id']); ?>

		<h3><?php echo $logged_in_user['username'] ?></h3>

		<a href="login.php?action=logout">Log Out</a>
	</section>
	<?php } ?>

	<?php //get up to 5 most recently joined users
	$query = 	"SELECT username, user_id
				FROM users
				WHERE did_confirm = 1
				ORDER BY join_date DESC
				LIMIT 5";
	//run it
	$result = $db->query( $query );
	//check it
	if( $result->num_rows >= 1 ){ 
	?>
	<section class="widget">
		<h2>Newest Users</h2>

		<?php while( $row = $result->fetch_assoc() ){ ?>
		
			<?php show_avatar($row['user_id']); ?>
		
		<?php } //end while
		//free it
		$result->free(); ?>

	</section>
	<?php }//end if there are users to show ?>


	<?php //categories
	$query = 	"SELECT categories.*, COUNT(*) AS total
				FROM categories, posts
				WHERE categories.category_id = posts.category_id
				GROUP BY categories.category_id
				ORDER BY RAND()
				LIMIT 20";
	//run it
	$result = $db->query( $query );
	//check it
	if( $result->num_rows >= 1 ){ 
	?>
	<section class="widget">
		<h2>Categories</h2>

		<ul>
			<?php while( $row = $result->fetch_assoc() ){ ?>
			<li><?php echo $row['name']; ?>  (<?php echo $row['total']; ?>)</li>
			<?php 
			} 
			$result->free();
			?>
		</ul>
	</section>
	<?php } //end if categories ?>





	<?php //categories
	$query = 	"SELECT * 
				FROM tags
				ORDER BY name ASC
				LIMIT 20";
	//run it
	$result = $db->query( $query );
	//check it
	if( $result->num_rows >= 1 ){ 
	?>
	<section class="widget">
		<h2>Tags</h2>

		<ul>
			<?php while( $row = $result->fetch_assoc() ){ ?>
			<li><?php echo $row['name']; ?></li>
			<?php 
			} //end while
			$result->free();
			?>
		</ul>
	</section>
	<?php } //end if there are tags ?>

</aside>
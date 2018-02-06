<?php 
include('includes/header.php'); 
//which post are we trying to view? (URL is single.php?post_id=X)
$post_id = $_GET['post_id'];

//make sure the post_id is a number
if( ! is_numeric($post_id) ){
	die('Invalid post');
}

include('includes/comment-parse.php');
?>

<main class="content">

<?php //get the one published post
$query = 		"SELECT posts.title, posts.body, posts.image, posts.date, posts.post_id, 
						posts.allow_comments, users.username, users.user_id, categories.name
				FROM posts, users, categories
				WHERE posts.is_published = 1
				AND posts.user_id = users.user_id
				AND posts.category_id = categories.category_id
				AND posts.post_id = $post_id
				LIMIT 1";
//run it
$result = $db->query($query);
//check it - are there rows of data to show?
if( $result->num_rows >= 1 ){
	//loop it
	while( $row = $result->fetch_assoc() ){
		$allow_comments = $row['allow_comments'];
		?>	
		<article>
			<h2>
				<?php show_avatar( $row['user_id'], 50 ); ?>
				<?php echo $row['username']; ?>				
			</h2>
			<a href="single.php?post_id=<?php echo $row['post_id']; ?>">
				<img src="<?php echo post_image_url( $row['image'], 'large' ); ?>" alt="<?php echo $row['title'] ?>">
			</a>

			<div class="post-info">
				<h3><?php echo $row['title']; ?></h3>
				<h4><?php echo $row['name']; ?></h4>
				<p><?php echo $row['body'] ?></p>
				<span class="date"><?php echo convert_date($row['date']); ?></span>
				<span class="comment-count"><?php count_comments( $row['post_id'] ); ?></span>
			</div>
		</article>
		<?php 
	} //end while
	?>

	<section class="comments">
		<?php //get all the approved comments about this post, Oldest first
		$query = 	"SELECT users.username, users.user_id, comments.body, comments.date
					FROM comments, users
					WHERE comments.user_id = users.user_id
					AND comments.is_approved = 1
					AND comments.post_id = $post_id
					ORDER BY date ASC
					LIMIT 30";
		//run it
		$result = $db->query( $query );
		//check it
		if( $result->num_rows >= 1 ){ ?>
		<h3>Comments</h3>

		<ol class="comments-list">
			<?php while( $row = $result->fetch_assoc() ){ ?>
			<li>
				<h4>
					<?php show_avatar( $row['user_id'], 50 ); ?>
					<?php echo $row['username']; ?>
				</h4>
				<p><?php echo $row['body']; ?></p>
				<span class="date"><?php echo convert_date( $row['date'] ); ?></span>
			</li>
			<?php } //endwhile ?>

		</ol>
		<?php 
		}else{
			echo 'There are no comments yet on this post.';
		} ?>
	</section>

	<?php 
	if($allow_comments){
		include('includes/comment-form.php');
	} 
	?>

	<?php
	//free it
	$result->free();
}else{
	//no rows found
	echo 'Sorry, no posts to show here';
} ?>

</main>

<?php include('includes/sidebar.php'); ?>
<?php include('includes/footer.php'); ?>

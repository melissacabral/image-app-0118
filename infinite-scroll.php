<?php include('includes/header.php'); ?>

<main class="content">

<?php //get all the published posts, newest first
$query = 		"SELECT posts.title, posts.body, posts.image, posts.date, posts.post_id, 
users.username, users.user_id, categories.name
FROM posts, users, categories
WHERE posts.is_published = 1
AND posts.user_id = users.user_id
AND posts.category_id = categories.category_id
AND users.did_confirm = 1
ORDER BY posts.date DESC
LIMIT 2";
//run it
$result = $db->query($query);
//check it - are there rows of data to show?

if( $result->num_rows >= 1 ){
	//loop it

	while( $row = $result->fetch_assoc() ){
		?>	
		<article>
			<h2>
				<?php show_avatar( $row['user_id'], 50 ); ?>
				<?php echo $row['username']; ?>				
			</h2>
			<a href="single.php?post_id=<?php echo $row['post_id']; ?>">
				<img src="<?php echo post_image_url( $row['image'], 'large' ); ?>" alt="<?php echo $row['title'] ?>">
			</a>

			<?php //if this post was written by the logged in user, show the edit button
			if( $row['user_id'] == $logged_in_user['user_id'] ){
				$post_id = $row['post_id'];
				echo "<a href='edit-post.php?post_id=$post_id'>Edit</a>";
			} 
			?>

			<div class="post-info">
				<h3><?php echo $row['title']; ?></h3>
				
				<div class="likes">
					<?php like_interface( $row['post_id'], $logged_in_user['user_id'] ); ?>
				</div>	

				<h4><?php echo $row['name']; ?></h4>
				<p><?php echo $row['body'] ?></p>
				<span class="date"><?php echo convert_date($row['date']); ?></span>
				<span class="comment-count"><?php count_comments( $row['post_id'] ); ?></span>
			</div>
		</article>
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
<script
src="http://code.jquery.com/jquery-3.3.1.min.js"  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="  crossorigin="anonymous"></script>

<script type="text/javascript">
	var nextPage = 2;
//todo... make it stop when we run out of posts...  get the total of all posts in the original query?

	$(window).scroll(function(){
		if  ($(window).scrollTop() == $(document).height() - $(window).height()){
			loadArticle(nextPage);
			nextPage++;
		}
	}); 
	function loadArticle(pageNumber) {
		console.log(pageNumber);
		$.ajax({
			url: "ajax-handlers/infinite.php",
			type:'POST',
			data: { 
				page_no: pageNumber
			},

			success: function(html){
            $("main.content").append(html);    // This will be the element where our content will be loaded
        }
    });
		return false;
	}
</script>

<?php include('includes/footer.php'); ?>

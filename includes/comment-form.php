<section class="comment-form" id="leave-comment">
	<h3>Leave a Comment</h3>

	<?php 
	if( isset($feedback) ){
		echo $feedback;
	} ?>

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?<?php echo $_SERVER['QUERY_STRING']; ?>#leave-comment" method="post">

		<label for="the_body">Comment:</label>
		<textarea name="body" id="the_body"></textarea>

		<input type="submit" value="Comment">
		<input type="hidden" name="did_comment" value="1">
		
	</form>
	
</section>
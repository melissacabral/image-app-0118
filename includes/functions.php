<?php 
/*
	Change a timestamp into a human-friendly date
*/
function convert_date( $ugly_date ){
	$date = new DateTime( $ugly_date );
	return $date->format( 'D, F j, Y' );
}

/*
	Count the number of comments on any post
*/
function count_comments( $post_id ){
	global $db;
	$query = "SELECT COUNT(*) AS total
				FROM comments
				WHERE post_id = $post_id
				AND is_approved = 1";
	//run it
	$result = $db->query( $query );
	//check it
	if( $result->num_rows >= 1 ){
		//display the count
		$row = $result->fetch_assoc();
		//show the count, plus comment/comments		
		//ternary operator
		echo $row['total'] == 1 ? '1 comment' : $row['total'] . ' comments';
		
		//free it
		$result->free();
	}	
}


/*
	Show any user's avatar, at any size. If it doesn't exist, show a default pic
*/
function show_avatar( $user_id, $size = 80 ){
	global $db;

	//get the avatar of the user specified
	$query = "SELECT  avatar 
			FROM users
			WHERE user_id = $user_id
			LIMIT 1";
	$result = $db->query( $query );

	if( $result->num_rows >= 1 ){
		while( $row = $result->fetch_assoc() ){
			//show their avatar OR a default pic
			if( $row['avatar'] == '' ){
				//default
				$url = 'images/default-user.png';
			}else{
				$url = 'http://' . $row['avatar'];
			}//end if avatar is blank
		}//end while
	}//end if

	//show the image
	?>
	<a href="profile.php?user_id=<?php echo $user_id; ?>">
	<img class="profile-pic" src="<?php echo $url; ?>" alt="Userpic" width="<?php echo $size; ?>" 
		height="<?php echo $size; ?>"></a>
	<?php 
} //end of avatar function

/*
	clean String input for the DB
 */
function clean_string( $input ){
	global $db;
	$output = filter_var( $input, FILTER_SANITIZE_STRING );
	$output = mysqli_real_escape_string( $db, $output );

	return $output;
}

/*
	clean integer input for the DB
 */
function clean_int( $input ){
	global $db;
	$output = filter_var( $input, FILTER_SANITIZE_NUMBER_INT );
	$output = mysqli_real_escape_string( $db, $output );

	return $output;
}

/*
Clean any boolean so it can only be 1 or 0
 */
function clean_boolean( $input ){
	if( $input == 1 ){
		$output = 1;
	}else{
		$output = 0;
	}
	return $output;
}

/*
Check to see if the viewer is a logged in user
*/
function check_login(){
	global $db;

	//check to see if the user_id and secret_key session vars exist
	if( isset($_SESSION['user_id']) AND isset($_SESSION['secret_key'])  ){

		$user_id 	= $_SESSION['user_id'];
		$secret_key = $_SESSION['secret_key'];
		//check for match in db
		$query = "SELECT * FROM users
				WHERE user_id = $user_id
				AND secret_key = '$secret_key'
				LIMIT 1";
		$result = $db->query($query);

		if( !$result ){
			//query failed. not logged in
			return false;
		}
		if($result->num_rows == 1){
			//SUCCESS. they're logged in. Return the info about the logged in user
			return $result->fetch_assoc();
		}else{
			//no rows found
			return false;
		}

	}else{
		return false;
	}	
}

/*
Output any array as an unordered list
 */

function array_to_list($array = array()){
	if (!empty($array)) {
		echo '<ul>';
		foreach ($array as $key => $value) {
			echo '<li>' . $value . '</li>';
		}
		echo '</ul>';
	}
}

/*
Count likes on any post
 */
function count_likes( $post_id ){
	global $db;
	$query = "SELECT COUNT(*) AS total_likes
				FROM likes
				WHERE post_id = $post_id";
	$result = $db->query($query);

	if( ! $result ){
		echo $db->error;
	}

	$row = $result->fetch_assoc();
	
	$total = $row['total_likes'];
	return $total == 1 ? '1 Like' : "$total Likes"; 
}

/*
User interface for the like button
@param post_id int - The post that we are liking/un-liking
@param user_id int - The logged in user's ID
 */

function like_interface( $post_id, $user_id = 0 ){
	global $db;

	//did this user like this post?
	if($user_id){
		$query = "SELECT COUNT(*) AS you_like
				FROM likes
				WHERE user_id = $user_id
				AND post_id = $post_id";
		$result = $db->query( $query );
		if(! $result){
			echo $db->error;
		}

		$row = $result->fetch_assoc();
		$class = $row['you_like'] ? 'you-like' : 'not-liked';

	}//end if user_id exists
	?>
	<span class="like-interface">
		<div class="<?php echo $class; ?>">
			<?php if( $user_id ){ ?>
			<span class="heart-button" data-postid="<?php echo $post_id; ?>">❤</span>
			<?php } //end if logged in ?>

			<?php echo count_likes( $post_id ); ?>
		</div>
	</span>
	<?php
}

/*
Display an image from the DB at any known size
 */
function post_image_url( $image, $size = 'medium' ){
	return 'uploads/' . $image . '_' . $size . '.jpg';
}


/*
Output all categories as a Form <select> element
 */
function category_dropdown( $current = 0 ){
	global $db;
	$query = "SELECT * FROM categories";

	$result = $db->query($query);

	if(!$result){
		return false;
	}

	if( $result->num_rows >= 1 ){
		?>
		<select name="category_id">
			<?php while( $row = $result->fetch_assoc() ){ ?>
			<option value="<?php echo $row['category_id']; ?>" <?php 
				if( $row['category_id'] == $current ){
					echo 'selected';
				} ?>>
				<?php echo $row['name']; ?>
			</option>
			<?php } //end while ?>
		</select>

		<?php
	}
}

/*
Checkbox helper
 */
function checked( $thing1, $thing2 ){
	if( $thing1 == $thing2 ){
		echo 'checked';
	}
}

/*
Output a comma separated list of all the tags on any post
 */
function list_tags( $post_id = 0 ){
	global $db;
	$query = "SELECT tags.name
				FROM tags, post_tags
				WHERE tags.tag_id = post_tags.tag_id
				AND post_tags.post_id = $post_id";
	$result = $db->query($query);
	if($result->num_rows >= 1){
		$tags = array();
		while($row = $result->fetch_assoc()){
			$tags[] = $row['name'];
		}
		//comma separate the array
		echo implode(', ', $tags);
	}
}


//no close php
<?php 
include('includes/header.php'); 

//get all the categories for the buttons
$query = "SELECT * FROM categories";

$result = $db->query( $query );

//check to see if there is a result
if( ! $result ){
	die( $db->error );
}

?>
<main class="content">
	<h2>Pick a Category</h2>

	<?php while( $row = $result->fetch_assoc() ){ ?>
	<button class="category-button" data-catid="<?php echo $row['category_id'] ?>">
		<?php echo $row['name']; ?>
	</button>
	<?php } ?>

	<div id="display-area">
		Pick a category to see all the posts in it
	</div>

</main>
<?php include('includes/sidebar.php'); ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
	
	//when the user clicks a category, grab the catid data
	$('.category-button').click( function(e){
		var catId = $(this).data('catid');
		console.log(catId);

		$.ajax({
			method 	: 'GET',
			url		: 'ajax-handlers/get-posts-by-category.php',
			dataType: 'html',
			data 	: { 'catId' : catId },
			success : function( response ){
				//display the response on this page
				$('#display-area').html( response );
			},
			error 	: function(){
				console.log('ajax error');
			}
		});
	} );


	//listen for ajax requests to start/stop. Give visual feedback
	$(document).on({
		ajaxStart 	: function(){
			$('#display-area').addClass('loading');
		},
		ajaxStop 	: function(){
			$('#display-area').removeClass('loading');
		}
	});

</script>

<?php include('includes/footer.php'); ?>
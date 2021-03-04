
<?php
if(isset($_POST['search_catg'])){ 
	$table_id = $_POST['table_id']; ?>
	<div class="col-md-12">
		<a href="index.php?page=gallery" class="btn btn-warning"><i class="fa fa-reply"></i> Go Back</a>
	</div>
	<div class="col-md-12">&nbsp;</div>
	<?php
	$sqli = "SELECT * FROM gallery WHERE catg_id = '$table_id'";
	$query = query_execute_sqli($sqli);	
	while($row = mysqli_fetch_array($query)){  
		$id = $row['id'];
		$title = $row['title'];
		$image = $row['image'];
		$date = date('d/m/Y' , strtotime($row['date'])); ?>
		
		<a class="fancybox" href="images/mlm_gallery/<?=$image?>" title="<?=$title?>">
			<img alt="<?=$title?>"  src="images/mlm_gallery/<?=$image?>" />
		</a> <?php
	}
}
else{ ?>
	<div class="col-md-12">
		<div class="col-md-12"><h4>Select Category</h4></div>
		<div class="col-md-12">&nbsp;</div>
		<?php
		$sql = "SELECT * FROM gallery_category";
		$query = query_execute_sqli($sql);
		$num = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query)){
			$id = $row['id'];
			$category = $row['category']; ?>
			<div class="col-md-2">
				<form action="" method="post">
					<input type="hidden" name="table_id" value="<?=$id?>" />
					<input type="submit" name="search_catg" value="<?=$category?>" class="btn btn-success btn-lg" />
				</form>
			</div> <?php
		} ?>
	</div> <?php
} ?>
<link href="assets/js/plugins/fancybox/jquery.fancybox.css" rel="stylesheet">
<script src="assets/js/plugins/fancybox/jquery.fancybox.js"></script>
<script>
$(document).ready(function() {
	$('.fancybox').fancybox({
		openEffect	: 'none',
		closeEffect	: 'none'
	});
});
</script>

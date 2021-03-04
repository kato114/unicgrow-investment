<style>
.row > .column {
  padding: 0 8px;
}

.row:after {
  content: "";
  display: table;
  clear: both;
}

.column {
  float: left;
  width: 25%;
}

/* The Modal (background) */
.modal {
  display: none;
  position: fixed;
  z-index: 1;
  padding-top: 100px;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: black;
}

/* Modal Content */
.modal-content {
  position: relative;
  background-color: #fefefe;
  margin: auto;
  padding: 0;
  width: 50%;
  max-width: 1200px;
}

/* The Close Button */
.close {
  color: white;
  position: absolute;
  top: 10px;
  right: 25px;
  font-size: 35px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #999;
  text-decoration: none;
  cursor: pointer;
}

.mySlides {
  display: none;
}

.cursor {
  cursor: pointer
}

/* Next & previous buttons */
.prev,
.next {
  cursor: pointer;
  position: absolute;
  top: 50%;
  width: auto;
  padding: 16px;
  margin-top: -50px;
  color: white;
  font-weight: bold;
  font-size: 20px;
  transition: 0.6s ease;
  border-radius: 0 3px 3px 0;
  user-select: none;
  -webkit-user-select: none;
}

/* Position the "next button" to the right */
.next {
  right: 0;
  border-radius: 3px 0 0 3px;
}

/* On hover, add a black background color with a little bit see-through */
.prev:hover,
.next:hover {
  background-color: rgba(0, 0, 0, 0.8);
}

/* Number text (1/3 etc) */
.numbertext {
  color: #f2f2f2;
  font-size: 12px;
  padding: 8px 12px;
  position: absolute;
  top: 0;
}

img {
  margin-bottom: -4px;
}

.caption-container {
  text-align: center;
  background-color: black;
  padding: 2px 16px;
  color: white;
}

.demo {
  opacity: 0.6;
}

.active,
.demo:hover {
  opacity: 1;
}

img.hover-shadow {
  transition: 0.3s
}

.hover-shadow:hover {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19)
}
</style>

<?php
if(isset($_POST['search_catg'])){ 
	$table_id = $_POST['table_id']; ?>
	<div class="row">
		<div class="col-md-12">
			<a href="index.php?page=gallery" class="btn btn-warning btn-lg"> <i class="fa fa-reply"></i>Go Back</a>
		</div>
		<div class="col-md-12">&nbsp;</div>
		<?php
		$sqli = "SELECT * FROM gallery WHERE catg_id = '$table_id'";
		$query = query_execute_sqli($sqli);	
		while($row = mysqli_fetch_array($query))
		{  
			$id = $row['id'];
			$title = $row['title'];
			$image = $row['image'];
			$date = date('d/m/Y' , strtotime($row['date'])); ?>
			<div class="col-md-3">
			<img src="images/mlm_gallery/<?=$image?>" style="width:100%" onclick="openModal();currentSlide(<?=$id?>)" class="hover-shadow cursor">
			</div> <?php
		}
		?>
	</div>
	<div id="myModal" class="modal">
		<span class="close cursor" onclick="closeModal()">&times;</span>
		<div class="modal-content">
		<?php
		$sqli = "SELECT * FROM gallery";
		$query = query_execute_sqli($sqli);	
		while($row = mysqli_fetch_array($query))
		{  
			$id = $row['id'];
			$title = $row['title'];
			$image = $row['image'];
			$date = date('d/m/Y' , strtotime($row['date'])); ?>
			<div class="mySlides">
				<div class="numbertext"><?=$title?></div>
				<img src="images/mlm_gallery/<?=$image?>" style="width:100%">
			</div>
		
			<a class="prev" onclick="plusSlides(-1)">&#10094;</a>
			<a class="next" onclick="plusSlides(1)">&#10095;</a>
			
			<!--<div class="caption-container">
				<p id="caption"></p>
			</div>
	
	
			<div class="column">
			<img class="demo cursor" src="images/mlm_gallery/<?=$image?>" style="width:100%" onclick="currentSlide(<?=$id?>)">
			</div>-->
		 <?php
		}
		?>
		</div>
	</div>  <?php
}
else{ ?>
	<div class="col-md-12">
		<div class="col-md-12"><h4>Select Category</h4></div>
		<div class="col-md-12">&nbsp;</div>
		<?php
		$sql = "SELECT * FROM gallery_category";
		$query = query_execute_sqli($sql);
		$num = mysqli_num_rows($query);
		while($row = mysqli_fetch_array($query))
		{
			$id = $row['id'];
			$category = $row['category'];
			?>
				<div class="col-md-2">
				<form action="" method="post">
					<input type="hidden" name="table_id" value="<?=$id?>" />
					<input type="submit" name="search_catg" value="<?=$category?>" class="btn btn-success btn-lg" />
				</form>
				</div> <?php
		} ?>
	</div> <?php
} ?>


<script>
function openModal() {
  document.getElementById('myModal').style.display = "block";
}

function closeModal() {
  document.getElementById('myModal').style.display = "none";
}

var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("demo");
  var captionText = document.getElementById("caption");
  if (n > slides.length) {slideIndex = 1}
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";
  dots[slideIndex-1].className += " active";
  captionText.innerHTML = dots[slideIndex-1].alt;
}
</script>

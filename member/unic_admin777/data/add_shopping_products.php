<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
include("../function/functions.php");
include("../function/pair_point_income.php");
include("../function/daily_income.php");

$allowedfiletypes = array("jpeg","jpg");
$uploadfolder = $shopping_img_full_path;
$thumbnailheight = 100; //in pixels
$thumbnailfolder = $uploadfolder."thumbs/" ;
	

if(isset($_POST['submit']))
{
	$product_name = $_REQUEST['product_name'];
	$product_cost = $_REQUEST['product_cost'];
	$product_title = $_REQUEST['product_title'];
	$product_discription = $_REQUEST['product_discription'];
	$product_images = $_REQUEST['product_images'];
	
    if(empty($_FILES['product_images']['name']))
	{
        echo "<strong>Error: File not uploaded!</strong></p>\n\n" ;
    } 
	else 
	{
		$r = date('Ymd');
		$unique_pin = $r.$unique_time = time();

		
		query_execute_sqli("insert into shopping (product_id , product_name , product_cost , title , discription) values ('$unique_pin' , '$product_name' , '$product_cost' , '$product_title' , '$product_discription') ");
		
		
		$qq = query_execute_sqli("select * from shopping where product_id = '$unique_pin' ");
		while($row = mysqli_fetch_array($qq))
		{
			print $p_id = $row['product_id'];
		}
		
        $uploadfilename = $_FILES['product_images']['name'];
        $fileext = strtolower(substr($uploadfilename,strrpos($uploadfilename,".")+1));
        if (!in_array($fileext,$allowedfiletypes)) { echo "<strong>Error: Invalid file extension!</strong></p>\n\n" ; }
        else 
		{
            $fulluploadfilename = $uploadfolder.$p_id.".".$fileext ;
            if (copy($_FILES['product_images']['tmp_name'], $fulluploadfilename)) 
			{
                echo "$product_name image has been uploaded succesfully.</p>\n\n";
            } 
			else { echo "<strong>Error: Couldn't save Image ($fulluploadfilename)!</strong></p>\n\n"; }
        }
		print "<br>Product Successfully Added !!";
	}	
	
}
else
{?>
	<form name="pay_form" action="index.php?page=add_shopping_products" method="post" enctype="multipart/form-data">
	<table class="table table-bordered">
		<thead><tr><th colspan="2">Add Shopping Products</th></tr></thead>
		<tr>
			<th>Product Name</th>
			<td><input type="text" name="product_name" /></td>
		</tr>
		<tr>
			<th>Product Cost</th>
			<td><input type="text" name="product_cost" />RC</td>
		</tr>
		<tr>
			<th>Product Title</th>
			<td><input type="text" name="product_title" /></td>
		</tr>
		<tr>
			<th>Product Discription</th>
			<td><textarea name="product_discription" style="height:40px;"></textarea></td>
		</tr>
		<tr>
			<th>Product Image</th>
			<td><input type="file" name="product_images" /></td>
		</tr>
		<tr>
			<td class="text-center" colspan="2">
				<input type="submit" name="submit" value="Add Product" class="btn btn-info" />
			</td>
		</tr>
	</table>
	</form>

<?php }?>

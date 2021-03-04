<?php
include('../../security_web_validation.php');
?>
<?php
include("condition.php");
include("../function/setting.php");
include("../function/functions.php");
include("../function/direct_income.php");
include("../function/check_income_condition.php");
include("../function/pair_point_calculation.php");
require_once("../function/send_mail.php");

$allowedfiletypes = array("jpeg","jpg");
$uploadfolder = $shopping_img_full_path;
$thumbnailheight = 100; //in pixels
$thumbnailfolder = $uploadfolder."thumbs/" ;

$id = $_SESSION['mlmproject_user_id'];
if(isset($_POST['Submit']))
{
	$product_id = $_REQUEST['product_id'];
	$p_id = $_REQUEST['p_id'];
	if($_POST['Submit'] == 'Edit')
	{
		$q = query_execute_sqli("select * from shopping ");
		$num = mysqli_num_rows($q);
		if($num > 0)
		{ 
			$q = query_execute_sqli("select * from shopping where id = '$p_id' and product_id = '$product_id' ");
			while($r = mysqli_fetch_array($q))
			{
				$product_name = $r['product_name'];
				$product_cost = $r['product_cost'];
				$title = $r['title'];
				$discription = $r['discription'];
				$product_id = $r['product_id'];
			}
		
		?>
			<table width="400" border="0">
				<form name="pay_form2" action="index.php?page=shopping_products" method="post" enctype="multipart/form-data">
				<input type="hidden" name="p_id" value="<?php print $p_id; ?>"  />
				<input type="hidden" name="o_pro_id" value="<?php print $product_id; ?>"  />
			  <tr>
				<td colspan="2" style="font-size:16px; color:#666666;"><b>Add Shopping Products</b></td>
			  </tr>
			  <tr>
				<td colspan="2">&nbsp;</td>
			  </tr>
			  <tr>
				<td>Product Name</td>
				 <td><input type="text" name="product_name" value="<?php print $product_name; ?>" /></td>
			  </tr>
			  <tr>
				<td colspan="2">&nbsp;</td>
			  </tr>
			  <tr>
				<td>Product Cost</td>
				 <td><input type="text" name="product_cost" value="<?php print $product_cost; ?>" /> RC</td>
			  </tr>
			  <tr>
				<td colspan="2">&nbsp;</td>
			  </tr>
			  <tr>
				<td>Product Title</td>
				<td><input type="text" name="product_title" value="<?php print $title; ?>" /></td>
			  </tr>
			  <tr>
				<td colspan="2">&nbsp;</td>
			  </tr>
			  <tr>
				<td>Product Discription</td>
				 <td><textarea name="product_discription" style="height:40px;"><?php print $discription; ?></textarea></td>
			  </tr>
			  <tr>
				<td colspan="2">&nbsp;</td>
			  </tr>
			  <tr>
				<td>Product Image</td>
				 <td><input type="file" name="product_images" /></td>
			  </tr>
			  <tr>
				<td colspan="2">&nbsp;</td>
			  </tr>
			  <tr>
				<td align="center" colspan="2"><input type="submit" name="Submit" value="Edit Product" class="btn btn-info" /></td>
			  </tr>
			  </form>
			</table>	
		
		<?php
		}		
	}
	elseif($_POST['Submit'] == 'Delete')
	{
		query_execute_sqli("delete from shopping where id = '$p_id' and product_id = '$product_id' ");
	}
	elseif($_POST['Submit'] == 'Edit Product')
	{
		$p_id = $_REQUEST['p_id'];
		$product_name = $_REQUEST['product_name'];
		$product_cost = $_REQUEST['product_cost'];
		$product_title = $_REQUEST['product_title'];
		$product_discription = $_REQUEST['product_discription'];
		$product_images = $_REQUEST['product_images'];
		
		if(empty($_FILES['product_images']['name']))
		{
			query_execute_sqli("update shopping set product_name = '$product_name' , product_cost = '$product_cost' , title = '$product_title' , discription = '$product_discription' where id = '$p_id' ");
		}
		else 
		{
			print $o_pro_id = $_REQUEST['o_pro_id'];
			$r = date('Ymd');
			$unique_pin = $r.$unique_time = time();
			
			query_execute_sqli("update shopping set product_id = '$unique_pin' , product_name = '$product_name' , product_cost = '$product_cost' , title = '$product_title' , discription = '$product_discription' where id = '$p_id' ");
			
			
			$qq = query_execute_sqli("select * from shopping where product_id = '$unique_pin' ");
			while($row = mysqli_fetch_array($qq))
			{
				$p_id = $row['product_id'];
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
			unlink($uploadfolder.$o_pro_id.'.jpg');	
		}
		print "<br>Edit Shopping Product Completed Successfully !";
	}
}
else
{
	
	
?>
	<table width="700" border="0">
	 <tr>
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><img src="product_images/shop-ad.jpg" width="700" /></td>
  </tr>
	
  <tr>
    <td colspan="5">&nbsp;</td>
  </tr>
  <tr>
    <td width="650px" height=30px class="text-center"><strong>Product Name</strong></td>
	<td height=30px width="650px" class="text-center" align="center"><strong>Product Cost</strong></td>
	<td height=30px width="650px" class="text-center" align="center"><strong>Product Title</strong></td>
	<td height=30px width="650px" class="text-center" align="center"><strong>Product Discription</strong></td>
	<td colspan="2" height=30px width="650px" class="text-center" align="center"><strong>Product Operation</strong></td>
  </tr>
 
  <?php 
  	$q = query_execute_sqli("select * from shopping ");
	$num = mysqli_num_rows($q);
	if($num > 0)
	{
		while($r = mysqli_fetch_array($q))
		{
			$product_name = $r['product_name'];
			$product_cost = $r['product_cost'];
			$title = $r['title'];
			$discription = $r['discription'];
			$product_id = $r['product_id'];
			$p_id = $r['id'];
			
			
		  ?>
		  
		   <tr>
		   
			<form name="invest" method="post" action="index.php?page=shopping_products">
			<td height="20px" class="input-small" align="center"><img src="../product_images/<?php print $product_id; ?>.jpg" width="100px" /><br /><?php print $product_name; ?></td>
			<td height="20px" class="input-small" align="center">$<?php print $product_cost; ?></td>
			<td height="20px" class="input-small" align="center"><?php print $title; ?></td>
			<td height="20px" class="input-small" align="center"><?php print $discription; ?></td>
			<td height="20px" class="input-small" align="center">
			<input type="hidden" name="product_id" value="<?php print $product_id; ?>"  />
			<input type="hidden" name="p_id" value="<?php print $p_id; ?>"  />
			<input type="submit" name="Submit" value="Edit" class="btn btn-info"  />
			<input type="submit" name="Submit" value="Delete" class="btn btn-info"  /></form></td>
			</tr>
	<?php }
	}?>
	
  </table>
	
	
	
	
<?php } ?>


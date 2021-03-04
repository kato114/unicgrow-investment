<?php
include('../security_web_validation.php');
?>
<?php 
include("condition.php");
$sql = "SELECT * FROM advertise order by ad_date desc";
$res=query_execute_sqli($sql);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" 
        type="text/javascript">
</script> 
<body>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=APP_ID";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="fb-like" data-href="http://www.alexapay.com" data-send="true" data-width="450" data-show-faces="true" data-font="verdana" data-colorscheme="dark">
<table width="100%" border="0">     
  <tr>
  <?php while($row=mysqli_fetch_array($res)){
if($c%2==0){ ?>
                                      </tr><tr>
                                      <?php } ?>
	 <td align="center">
	 	<div class="fb-shared-activity" data-width="300" data-height="300"><a target="_blank" href="http://www.facebook.com/sharer.php?u= http://www.crorepati.net/business/admin/images/advertisement/<?php echo $row['ad_image'];?>" title="Share this webpage on Facebook">
		<span style="font-size:16pt;"><?php echo $row['ad_date'];?></span><br />
	<img src="../business_2015/images/advertisement/<?php echo $row['ad_image'];?>" style="border:1px solid #000000;"  /><br /><span style="font-size:16pt;">Share on Facebook</span></a>
	</div>
	 </td>
	 <?php
$c++;
} ?>
  </tr>
  
</table>
</div>
</body>
</html>
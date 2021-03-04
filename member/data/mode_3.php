<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
$parent_username = get_user_name($_SESSION['mlmproject_user_id']);

?>

<table width="400" border="0">
<form method="post" action="https://www.libertyreserve.com" >

  <td colspan="2" align="center">Your Information</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>Name</td>
    <td><?php print $_SESSION['dccan_new_user_name']; ?></td>
  </tr>
  <tr>
    <td>Username</td>
    <td><?php print $_SESSION['dccan_new_username']; ?></td>
  </tr>
  <tr>
    <td>Parent's Username</td>
    <td><?php echo $parent_username; ?></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><input type="submit" value="submit" name="submit" class="normal-button" /></td>
  </tr>
  </form>
</table>

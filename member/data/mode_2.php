<?php
include('../security_web_validation.php');
?>
<?php
session_start();
include("condition.php");
$parent_username = get_user_name($_SESSION['mlmproject_user_id']);

?>

<table width="400" border="0">
<form method="post" action="https://www.alertpay.com/PayProcess.aspx" >
   <input type="hidden" name="ap_merchant" value="apdevforum@gmail.com"/>
   <input type="hidden" name="ap_purchasetype" value="item"/>
   <input type="hidden" name="ap_itemname" value="User Investment"/>
   <input type="hidden" name="ap_amount" value="<?php print $_SESSION['alert_pay_user_invest']; ?>"/>
   <input type="hidden" name="ap_currency" value="USD"/>
  <tr>
  <td colspan="2" align="center"><strong>Your Mining Space</strong></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td><strong>Your New Mining Space</strong></td>
    <td><strong><?php print $_SESSION['alert_pay_user_invest']; ?></strong></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><input type="image"
src="https://www.alertpay.com//PayNow/4F59239578EA46C1AD168BA6E9BD2067g.gif"/></td>
  </tr>
  </form>
</table>

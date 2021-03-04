<?php
include('../security_web_validation.php');
?>
<?php 
session_start();
include("condition.php");
//

define("IPN_V2_HANDLER", "https://sandbox.alertpay.com/sandbox/ipn2.ashx");
    define("TOKEN_IDENTIFIER", "token=");
   
    // get the token from Alertpay
    print $token = urlencode($_GET['token']);
     
    //preappend the identifier string "token="
    $token = TOKEN_IDENTIFIER.$token;
   
    /**
     *
     * Sends the URL encoded TOKEN string to the Alertpay's IPN handler
     * using cURL and retrieves the response.
     *
     * variable $response holds the response string from the Alertpay's IPN V2.
     */
   
    $response = '';
   
   print  $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, IPN_V2_HANDLER);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $token);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    print $response = curl_exec($ch);

    curl_close($ch);
    echo urldecode($response); die;
    if(strlen($response) > 0)
    {
        if(urldecode($response) == "INVALID TOKEN")
        {
            //the token is not valid
        }
        else
        {
           
            //urldecode the received response from Alertpay's IPN V2
            $response = urldecode($response);
           
            //split the response string by the delimeter "&"
            $aps = explode("&", $response);
               
            //define an array to put the IPN information
            $info = array();
           
            foreach ($aps as $ap)
            {
                //put the IPN information into an associative array $info
                $ele = explode("=", $ap);
                $info[$ele[0]] = $ele[1];
            }
           
            //setting information about the transaction from the IPN information array
            $receivedMerchantEmailAddress = $info['ap_merchant'];
            $transactionStatus = $info['ap_status'];
            $testModeStatus = $info['ap_test'];
            $purchaseType = $info['ap_purchasetype'];
            $totalAmountReceived = $info['ap_totalamount'];
            $feeAmount = $info['ap_feeamount'];
            $netAmount = $info['ap_netamount'];
            $transactionReferenceNumber = $info['ap_referencenumber'];
            $currency = $info['ap_currency'];
            $transactionDate = $info['ap_transactiondate'];
            $transactionType = $info['ap_transactiontype'];
           
            //setting the customer's information from the IPN information array
            $customerFirstName = $info['ap_custfirstname'];
            $customerLastName = $info['ap_custlastname'];
            $customerAddress = $info['ap_custaddress'];
            $customerCity = $info['ap_custcity'];
            $customerState = $info['ap_custstate'];
            $customerCountry = $info['ap_custcountry'];
            $customerZipCode = $info['ap_custzip'];
            $customerEmailAddress = $info['ap_custemailaddress'];
           
            //setting information about the purchased item from the IPN information array
            $myItemName = $info['ap_itemname'];
            $myItemCode = $info['ap_itemcode'];
            $myItemDescription = $info['ap_description'];
            $myItemQuantity = $info['ap_quantity'];
            $myItemAmount = $info['ap_amount'];
           
            //setting extra information about the purchased item from the IPN information array
            $additionalCharges = $info['ap_additionalcharges'];
            $shippingCharges = $info['ap_shippingcharges'];
            $taxAmount = $info['ap_taxamount'];
            $discountAmount = $info['ap_discountamount'];
           
            //setting your customs fields received from the IPN information array
            $myCustomField_1 = $info['apc_1'];  //idadsuser
            $myCustomField_2 = $info['apc_2'];  //trans type (Membership,Fund,etc...)
            $myCustomField_3 = $info['apc_3'];  //stream
            $myCustomField_4 = $info['apc_4'];    //combo promotion
            $myCustomField_5 = $info['apc_5']; 
            $myCustomField_6 = $info['apc_6'];
           
           
            if (trim($transactionStatus) =="Success")
            {               
                 //YOUR CODE GOES HERE               
                echo $details = "Name:".$customerFirstName." ".$customerLastName."\r\n Member:".$customerEmailAddress."\r\n status:".$transactionStatus."\r\n TransId:".$transactionReferenceNumber."\r\n type:".$transactionType."\r\n Date:".$transactionDate."\r\n  UserID:".$myCustomField_1."\r\n For:".$myCustomField_2;
                die;
            }
            else
            {
                $details = "merchant:".$customerEmailAddress." status:".$transactionStatus." transtype:".$transactionType." trans date".$transactionDate." trans".$transactionReferenceNumber." testmode:".$testModeStatus." ".$purchaseType." apc_1".$myCustomField_1;
            //THIS SHOULD BE USED IN CASE THE TRANSACTION IS NOT A SUCCESS
            //$sql="insert into ap_test values(NULL,'".$details."',NOW())";
            //$eres=$bd->ExecuteQuery($sql);
            }
        }
    }
    else
    {
        print "something is wrong, no response is received from Alertpay";
    }


$token = $_GET['token'];

$string = urlencode($token);
$alert_pay_id = $_SESSION['dccan_alert_pay_customer_id'];
if($alert_pay_id != 0)
{
	$date = date('Y-m-d');
	query_execute_sqli("insert into temp_payment (user_id , date , status , alert_token) values ('$alert_pay_id' , '$date' , 1 , '$string') ");
	unset($_SESSION['dccan_alert_pay_customer_id']);
	print "<font color=\"#313164\" size=\"+2\">Your Request has been Send To Admin !<br><br>Please Save this TOKEN for further use!!</font>";
}	

/*
print $string;
$ap_custfirstname = 'Rajesh';
$ap_custcity = 'Jaipur';
$ap_merchant = 'merchant';
$ap_customer_id = 1;
$ap_status = 'Sfuccess';
$ap_referencenumber = 5465;
$ap_currency = '$USD';
$amount = 125;

if($ap_status == 'Success')
{
	$date = date('Y-m-d');
	$pay_mode = "Alert Pay";
	query_execute_sqli("insert into add_funds (user_id , amount , date , mode , payment_mode) values ('$ap_customer_id' , '$amount' , '$date' , 1 , '$pay_mode') "); 
	
	$q = query_execute_sqli("select * from wallet where id = '$ap_customer_id' ");
	while($r = mysqli_fetch_array($q))
	{
		$wallet_amount = $r['amount'];
	}	
	$total_amount = $wallet_amount+$amount;
	query_execute_sqli("update wallet set amount = '$total_amount' where id = '$ap_customer_id' ");
	
	$log_username = get_user_name($ap_customer_id);
	$income_log = $amount;
	$income_type_log = "Add Fund via Alert Pay";
	include("function/logs_messages.php");
	data_logs($ap_customer_id,$data_log[4][0],$data_log[4][1],$log_type[4]);
	//print "Success";

}
else
{
	//print "<font color=\"#FF0000\" size=\"+2\">Some Error Occured !!<br>Please Contact to Admin !</font>";
}*/
?>

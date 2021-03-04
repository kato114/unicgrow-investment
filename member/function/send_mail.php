<?php
class SMTPClient
{

function SMTPClient ($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $subject, $body)
{
$from = "";
$body1="$body";
$body ="$body1";

 $url = "https://api.sendgrid.com/";
 $user = "comeonplaynow";
 $pass = "Goldin2008";
 //$pass = "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";
 
$params = array(
      'api_user' => $user,
      'api_key' => $pass,
      'to' => $to,
      'subject' => $subject,
      'html' => "$body",
      'text' => "$body",
      'from' => $from,
   );
   //print_r($params);

//$body = 'text test test';
$body = urlencode($body);
$url1 = "https://api.sendgrid.com/api/mail.send.json";
$url2 = "api_user=$user&api_key=$pass&to=$to&subject=$subject&html=".$body."&text=".$body."&from=$from";

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"$url1");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
            "$url2");
            
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($ch);

curl_close ($ch);



//print $fp = file_get_contents($url1);


//$request = $url.'api/mail.send.json';

 // Generate curl request
 //$session = curl_init($request);

 // Tell curl to use HTTP POST
 

 // Tell curl that this is the body of the POST
 

 // Tell curl not to return headers, but do return the response
 
 

 // obtain response
 //$response = curl_exec($session);
 //curl_close($session);

 // print everything out
 //print_r($response);
}


function send_email($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $from, $to, $subject, $body){
    
    require_once "Mail.php";
    $from = $from;    //your mail id
    $to = $to;                                    //recipient mail id 
    $subject = $subject;
    $body = $body;
    $host = "noreply@unicgrow.com";                                                 //where the mail id exists 
    $port = "465";
    $username = "support@unicgrow.com";                                              //your mail id
    $password = "Unic@123";                      //password of this mail id
    $debug = "true";
    $headers = array ('From' => $from,
    'To' => $to,
    'Subject' => $subject);
    $smtp = Mail::factory('smtp',
    array ('host' => $host,
    'port' => $port,
    'auth' => true,
    'username' => $username,
    'password' => $password));
    
    $mail = $smtp->send($to, $headers, $body);
    
    if (PEAR::isError($mail)) {
        echo("<p>" . $mail->getMessage() . "</p>");
    } else {
        echo("<p>Message successfully sent!</p>");
    }
    
}

}
?> 
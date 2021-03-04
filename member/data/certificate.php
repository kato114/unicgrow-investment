<?php
include('../security_web_validation.php');
?>
<?php
session_start();
ini_set("display_errors",'on');
header('Content-type: image/jpeg');
include '../config.php';

//get Username 


$id = $_SESSION['id'];

if($id != '')
{
 	$id = $id;
}
else
{
	$id = $_SESSION['id']; 
}
//Old Programme for Cerfificate Start Here

/*$sql = "select reg_fees_structure.id , users.id_user, users.f_name, users.l_name, users.country, reg_fees_structure.date,
reg_fees_structure.update_fees from users
INNER JOIN reg_fees_structure
ON users.id_user=reg_fees_structure.user_id
where id = '$id' ";

$result=query_execute_sqli($sql);

$row=mysqli_fetch_array($result);
//print_r($row);
$name = strtoupper($row['f_name']." ".$row['l_name']);
$country = strtoupper($row['country']);
$deposit_code = 'ECPT00000'.$id*9;
$description = 'Member Commitment Certificate';
$date = $row['date'];
$amount = $row['update_fees'];

$date = date('d-M-Y',strtotime($date));
//Get date and amount

$arr=array('name'=>$name,'country'=>$country,'deposit_code'=>$deposit_code,
              'description'=>$description,'date'=>$date,'amount'=>$amount);

function image_text($arr)
{
//print_r( $arr[0]);
$rImg = ImageCreateFromJPEG("../images/certificate.jpg");
 
//Definir cor
$cor = imagecolorallocate($rImg, 0, 0, 0);
$font = '../fonts/AVGARDMI_0.ttf';
imagettftext($rImg , 15, 0, 383, 315, $cor, $font, $arr['name']);
imagettftext($rImg , 13, 0, 377, 340, $cor, $font,$arr['country']);
imagettftext($rImg , 13, 0, 350, 365, $cor, $font, $arr['deposit_code']);
imagettftext($rImg , 13, 0, 325, 391, $cor, $font, $arr['description']);
imagettftext($rImg , 13, 0,400, 417, $cor, $font, $arr['amount']);
imagettftext($rImg , 13, 0,395, 442, $cor, $font, $arr['date']);
 
//Escrever nome
//imagestring($rImg,5,126,22,'hello world',$cor);
 
//Header e output

imagejpeg($rImg);
}
image_text($arr);*/
//print_r($arr);
//Old Programme for Cerfificate End Here


//New Programme for Cerfificate Start Here
$sql = "select reg_fees_structure.id , users.id_user, users.f_name, users.l_name, users.country, reg_fees_structure.date,
reg_fees_structure.update_fees from users
INNER JOIN reg_fees_structure
ON users.id_user=reg_fees_structure.user_id
where id = '$id' ";
$result=query_execute_sqli($sql);

$row=mysqli_fetch_array($result);
//print_r($row);
$name = strtoupper($row['f_name']." ".$row['l_name']);
$country = strtoupper($row['country']);
$deposit_code = 'ECPT00000'.$id*9;
$description = 'Member Commitment Certificate';
$date = $row['date'];
$amount = $row['update_fees'];


$date = date('d-M-Y',strtotime($date));
//Get date and amount

$arr=array('name'=>$name,'country'=>$country,'deposit_code'=>$deposit_code,
              'description'=>$description,'date'=>$date,'amount'=>$amount);

$_SESSION['names'] = $arr['name'];
function image_text($arr)
{
//print_r( $arr[0]);
$rImg = ImageCreateFromJPEG("certificate.jpg");
 
//Definir cor
$cor = imagecolorallocate($rImg, 0, 0, 0);
$font = 'AVGARDMI_0.TTF';
imagettftext($rImg , 15, 0, 383, 315, $cor, $font, $arr['name']);
imagettftext($rImg , 13, 0, 377, 340, $cor, $font,$arr['country']);
imagettftext($rImg , 13, 0, 350, 365, $cor, $font, $arr['deposit_code']);
imagettftext($rImg , 13, 0, 325, 391, $cor, $font, $arr['description']);
imagettftext($rImg , 13, 0,400, 417, $cor, $font, $arr['amount']);
imagettftext($rImg , 13, 0,395, 442, $cor, $font, $arr['date']);
 
//Escrever nome
//imagestring($rImg,5,126,22,'hello world',$cor);
 
//Header e output

imagejpeg($rImg);
}
$_SESSION['imgs'] = image_text($arr);
//print_r($arr);

//New Programme for Cerfificate End Here
?>
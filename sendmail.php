<?php
/**
 * BynaMailer.
 * PHP Version 5
 * @package PHPMailer
 * @link https://github.com/bynalab/Bynamailer-v3.0.1
 * @author Abubakar Abdusalam (bynalab) <jjidexy@gmail.com>
 * @copyright 2012 - 2014 Marcus Bointon
 * @note This program is distributed in the hope that it will be useful - WITHOUT
 */

//require_once 'PHPMailer-master/PHPMailerAutoload.php';
//require 'PHPMailer-master/class.smtp.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

$bynamailer = new PHPMailer(true);

$tos = b_trim($_POST['recipient']);
$subject = b_trim($_POST['subject']);
$message = b_letter($_POST['message']);
$bynaAttach = $_FILES['bynaAttach'];
$delay = $_POST['delay'];
$bynapostmaster = b_trim($_POST['bynapostmaster']);
$sender = b_trim($_POST['sender']);

$smtpserver = $_POST['smtpserver'];
$smtpuser = $_POST['smtpuser'];
$smtppass = $_POST['smtppass'];
//$regard = "<br/>&copy; $year Office Service Center<br/>";
//$urls = "https://www.google.com \n https://www.facebook.com \n https://www.twitter.com \n https://www.bynalab.com \n https://www.gmail.com" ;


function smtp_exist($smtpserver) {
    if( $smtpserver != ""){
        return true;
    }
    else {
        return false;
    }
}

function b_letter($letter){
	$letter= b_trim($letter);
	$letter = urlencode($letter);
	$letter = preg_replace("/%5C%22/", "/%22/", $letter);
	$letter = urldecode($letter);
	$letter = stripslashes($letter);
	return $letter;
}

function b_replace($text,$email){
	$user = explode('@',$email);
	$text = str_replace('b-rand', strtoupper(substr(md5(microtime()),10,10)), $text);
	$text = str_replace('b-md5', substr(md5(microtime()),10,10), $text);
	$text = str_replace('b-time', date("h:i:s A"), $text);
	$text = str_replace('b-date', date("m/d/Y"), $text);
    $text = str_replace('b-email', $email, $text);
    $text = str_replace('b-b64email', base64_encode($email), $text);
    $text = str_replace('b-user', $user[0], $text);
    $text = str_replace('b-domain', $user[1], $text);
	return $text;
}

function b_trim($string){
	return stripslashes(ltrim(rtrim($string)));
}

function b_check($email){
	$exp = "^[a-z\'0-9]+([._-][a-z\'0-9]+)*@([a-z0-9]+([._-][a-z0-9]+))+$";
	if(eregi($exp,$email)){
		if(@checkdnsrr(array_pop(explode("@",$email)),"MX")){return true;}
		else{return false;}
	}
	else{return false;}    
}

try {

    //Server settings
    
   if( smtp_exist($smtpserver) ){ 

        //$bynamailer->SMTPDebug = SMTP::DEBUG_SERVER;                                 
        $bynamailer->isSMTP();                           
        $bynamailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  
        $bynamailer->Host = $smtpserver;                   
        $bynamailer->SMTPAuth = true;                               
        $bynamailer->Username = $smtpuser;                        
        $bynamailer->Password = $smtppass;
        $bynamailer->Port = 587;    

   }                              
    
    //Recipients
    $bynamailer->setFrom($bynapostmaster, $sender);          
    $bynamailer->addReplyTo('no-reply@mail.org', 'No Reply');    

    $to = explode("\n", $tos);

    $countEmail = count($to);
    $countArray = 0;
    $over = 0;
    
while($to[$countArray])
{

    $mail = str_replace(array("\n","\r\n"),'',$to[$countArray]);
    $bynamailer->addAddress($mail);
    
    //Content
    $bynamailer->isHTML(true);                                 
    $bynamailer->Subject = b_replace($subject, $mail);
    $bynamailer->Body    = b_replace($message, $mail);
    if($bynaAttach['tmp_name'] != ""){
        $bynamailer->AddAttachment($bynaAttach['tmp_name'],$bynaAttach['name']);
    }
    $bynamailer->send();
     
$countArray++;
$over++;

$bynamailer->ClearAddresses();  

echo "<font color=green face=verdana size=2>Successfully sent to ".trim($mail)." (".$over." OF ".$countEmail.") <br>\n";

//sleep(rand(3, $delay));

}

} catch (Exception $e) {
    echo "<font color=red face=verdana size=2>Oops! Message could not be sent to ".trim($mail)."... Mailer Error:  {$bynamailer->ErrorInfo}. <br>\n";
}


?>
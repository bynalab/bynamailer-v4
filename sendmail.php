<?php
/**
 * BynaMailer.
 * PHP Version 5
 * @package PHPMailer
 * @link https://github.com/bynalab/bynamailer-v4
 * @author Abubakar Abdusalam (bynalab) <bynalabs@gmail.com, abdusalam@bynalab.com>
 * @website https://bynalab.com
 * @copyright 2012 - 2014 Marcus Bointon
 * @note This program is distributed in the hope that it will be useful - WITHOUT
 */

error_reporting(0);


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

//Checks for malicious url
require 'check_malicious.php';

$bynamailer = new PHPMailer(true);

$tos = b_trim($_POST['recipient']);
$subject = b_trim($_POST['subject']);
$message = b_letter($_POST['message']);
$bynaAttach = $_FILES['bynaAttach'];
$delay = $_POST['delay'];
$bynapostmaster = b_trim($_POST['bynapostmaster']);
$sender = b_trim($_POST['sender']);
$link = $_POST['link'];
$smtpserver = $_POST['smtpserver'];
$smtpuser = $_POST['smtpuser'];
$smtppass = $_POST['smtppass'];

$link = explode("\n", $link);
$clinks = count($link);

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

function b_replace($text,$email, $link = array()){
    $user = explode('@',$email);
    $host = explode('.', $user[1])[0];
	$text = str_replace('{random}', strtoupper(substr(md5(microtime()),10,10)), $text);
	$text = str_replace('{md5}', substr(md5(microtime()),10,10), $text);
	$text = str_replace('{time}', date("h:i:s A"), $text);
	$text = str_replace('{date}', date("m/d/Y"), $text);
    $text = str_replace('{email}', $email, $text);
    $text = str_replace('{base64email}', base64_encode($email), $text);
    $text = str_replace('{mename}', $user[0], $text);
    $text = str_replace('{domain}', $user[1], $text);
    $text = str_replace('{frmsite}', $host, $text);
    $text = str_replace('{link}', $link[rand(0, $GLOBALS['clinks']-1)], $text);
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
    $bynamailer->Body    = b_replace($message, $mail, $link);
    if($bynaAttach['tmp_name'] != ""){
        $bynamailer->AddAttachment($bynaAttach['tmp_name'],$bynaAttach['name']);
    }

    if($resp != 1){
        $bynamailer->send();
    } else {
        //Prevent malicious url from sending.
        echo "Link is Malicious.";
        break;
    }
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

<?php

//require_once '../configuration.php';

@session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



function envia_mail($nombre_from, $nombre_to, $email_to, $asunto, $mensaje, $nivel, $logo)
{

    
    $obj = new Config();

    $email_host = $obj->email_host;
    $email_user = $obj->email_user;
    $email_pass = $obj->email_pass;
    $email_from = $obj->email_from;
    $email_name = $obj->email_name;
    $email_reply = $obj->email_reply;
    $email_smtpport = $obj->email_smtpport;


    $cuerpo = "
<html>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<head>

<style>


body {
  font-family: Arial, serif;
  font-size: 14px;
  color: #313131;
}

.textos {
  font-family: Arial, serif;
  font-size: 12px;
  color: #1a1a1a;
}

h2{
	color:#1f278e;
	font-size: 18px;
	font-weight:bold;
	text-align:center;
	}
	
	
.boton {
    background-color: #dc3545;
    border-radius: 4px;
    color: #ffffff;
    display: inline-block;
    font-family: Verdana,sans-serif;
    font-size: 14px;
    line-height: 44px;
    text-align: center;
    text-decoration: none;
    width: 200px;
}


</style>

</head><body>
<div style='width:100%;'>
<p>
<div style='width:100%; border-bottom: 1px solid #cccccc;'>
<img src='https://rentalpartner.cl/templates/fuenzalida/images/logo-rp.png' border='0' alt='' style='height:60px; width:auto;'/> 
</div>
  <br> 
   <br>
   
   <body><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tbody>
    <tr>
         <td>
   
  $mensaje

         </td>
    </tr>
  </tbody>
</table>

</div>
   <br> 
   <br>

</div>
</body>
";



    //*************************************************************************




    if ($asunto != "" && $mensaje != "") {

        $pathPhpMailer = "";

        if ($nivel == 0)
            $pathPhpMailer = "includes/";

        if ($nivel == 1)
            $pathPhpMailer = "../includes/";

        if ($nivel == 2)
            $pathPhpMailer = "../../includes/";

        if ($nivel == 3)
            $pathPhpMailer = "../../../includes";

        if ($nivel == 4)
            $pathPhpMailer = "../../../../includes/";



        require_once "$pathPhpMailer/PHPMailer55/src/Exception.php";
        require_once "$pathPhpMailer/PHPMailer55/src/PHPMailer.php";
        require_once "$pathPhpMailer/PHPMailer55/src/SMTP.php";


        //**********************************************************************

        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = false;                      //Enable verbose debug output
            $mail->isSMTP();      
            
            $mail->Host       = $email_host;     
                                                 //Send using SMTP
                         //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $email_user;                     //SMTP username
            $mail->Password   = $email_pass;                              //SMTP password
            //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
            $mail->SMTPSecure = 'ssl';
            $mail->Port       = $email_smtpport;                //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            //$mail->AuthType = 'PLAIN'; // Puedes usar 'LOGIN', 'PLAIN'
            $mail->AuthType = 'LOGIN'; // Puedes usar 'LOGIN', 'PLAIN'
            $mail->CharSet = 'UTF-8';
            $mail->SMTPKeepAlive = true;
            $mail->Mailer = "smtp";

            //Recipients
            $mail->setFrom($email_user, $nombre_from);
            $mail->addAddress($email_to, $nombre_to);     //Add a recipient

            $mail->addReplyTo($email_reply, "Re: $asunto");


            //$mail->addCC($email_reply);
            //$mail->addBCC($email_reply);

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $asunto;
            $mail->Body    = $cuerpo;
            $mail->AltBody = $mensaje;



            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->send();

            
           return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
    } //if($asunto!="" && $mensaje!=""  )

} //function







<!--- mailer --->
<!--?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//if(isset($_POST['reg_email'])){
 //   $email = $_POST['reg_email'];

    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug =1;                      							//Enable verbose debug output
   
        //Recipients
        $mail->setFrom('kainat.signumconcepts@gmail.com', 'Mailer');
        $mail->addAddress('kainat.signumconcepts@gmail.com', 'Joe User');   //Add a recipient
    
        $body= '<p>hello, this is my first program </p>';
        //Content
        $mail->isHTML(true);                                  				//Set email format to HTML
        $mail->Subject = 'Here is the subject';
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body);

        $mail->send();
        echo 'Message has been sent';
        
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

//}
?-->
<!---------------->






<!--?php 
//password generate function
$string = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+";
$randompass = substr(str_shuffle($string), 0, 12);
?-->



<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

    if(isset($_POST['email'])){
        $email = $_POST['email'];
        
   
        
       
        
        // Get the username by slicing string
        $username = strstr($email, '@', true);
       
        require "app/Views/PHPMailer/src/Exception.php";
        require 'app/Views/PHPMailer/src/PHPMailer.php';
        require 'app/Views/PHPMailer/src/SMTP.php';
    	 
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);
    
    	//smtp setting
    	$mail->SMTPDebug = SMTP::DEBUG_SERVER;   
    	$mail->isSMTP();
    	$mail->Host = 'dev.zeeteck.com';
    	$mail->SMTPAuth = true;
    	$mail->Username = 'mail@dev.zeeteck.com';
    	$mail->Password = '?83kmfYclCr#';
    	$mail->port = 587;
    	$mail->SMTPSecure = "tls";
    
        //email settings
    	$mail->isHTML(true);
    	$mail->setFrom('mail@dev.zeeteck.com', '
                            Hot Numbers Coffee Roasters');
    	$mail->addAddress($email);
    	$mail->Subject ='Your Hot Numbers Coffee Roasters account has been created!';

    
    	$mail->Body = '     
    	
    	    <img src="https://dev.zeeteck.com/projects/hotnumbers/assets/images/email_logo.jpg"  style="margin-bottom: 12px;"> 
    	    
    	    <table border="0" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border:1px solid #dedede;border-radius:3px">
    	    
    	    <tbody>
    	        <tr>
    	            <td align="center" valign="top">
    	                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color:#d62034;color:#ffffff;border-bottom:0;font-weight:bold;line-height:100%;vertical-align:middle;font-family:"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;border-radius:3px 3px 0 0">
    	                    <tbody>
    	                        <tr>
    	                            <td style="padding:36px 48px;display:block">
    	                                <h1 style="color: #ffffff !important;
    	                                
                                        font-size: 30px !important;
                                        font-weight: 300 !important;
                                        line-height: 150% !important;
                                        margin: 0 !important;
                                        text-align: left !important;
                                        font-family:Helvetica Neue !important;
    	                                
    	                                "> Welcome! </h1>
    	                            </td>
    	                        </tr>
    	                    </tbody>
    	                </table>
    	            </td>
    	        </tr>
    	        
    	        <tr>
    	            <td align="center" valign="top">
    	                <table border="0" cellpadding="0" cellspacing="0" width="600">
    	                <tbody>
    	                    <tr>
    	                        <td valign="top" style="background-color:#ffffff">
    	                        <table  border="0" cellpadding="2" cellspacing="0" width="100%">
    	                            <tbody>
    	                                <tr>
    	                                    <td valign="top" style="padding:48px 48px 0">
    	                                    <div style="color:#414141;font-family:"Helvetica Neue",Helvetica,Roboto,Arial,sans-serif;font-size:14px;line-height:150%;text-align:left">
    	                                    </div>
    	                                    
    	                                    <p style="margin:0 0 16px"> Hello,</p>
    	                                    
    	                                <p style="margin:0 0 16px"> Thanks for creating an account on Hot Numbers Coffee Roasters. Your username is 
    	                                
    	                           	    <strong> '.$username.' </strong>
    	                                . You can access your account area to view orders, change your password, and more at:
    	                                
    	                                <a rel="nofollow" style="color:#d62034;font-weight:normal;text-decoration:underline" href="https://dev.zeeteck.com/projects/hotnumbers/my_account">
    	                                
    	                                https://dev.zeeteck.com/projects/hotnumbers/my_account</a> <br><br>
    	                                </p>
    	                                
    	                                 <p style="margin:0 0 16px"> Your password has been automatically generated: 
    	                                <strong> '.$randompass.'</strong></p>
    	                                 
    	                                <p style="margin:0 0 16px"> We look forward to seeing you soon.</p>
    	                                    	                                    </td>
    	                                </tr>
    	                            </tbody>
    	                        </table>
    	                        </td>
    	                    </tr>
    	                </tbody>
    	                </table>
    	            </td>
    	        </tr>
    	        
    	        <tr>
    	            <td align="center" valign="top">
    	                <table border="0"  cellpadding="10" cellspacing="0" width="600" >
    	                    <tboby>
    	                        <tr>
    	                            <td valign="top" style="padding:0">
    	                                
    	                                <table border="0"  cellpadding="10" cellspacing="0" width="100%">
    	                                
    	                                    <tbody>
    	                                        <tr>
    	                                            <td colspan="2" valign="middle" style="border:0; color:#e67985; font-family:Arial; font-size:12px; line-height:125%; text-align:center; padding:0 48px 48px 48px">
    	                                            
    	                                                <p>
    	                                                    <a href="https://dev.zeeteck.com/projects/hotnumbers/"  style="color:#d62034;font-weight:normal;text-decoration:underline"> Hot Numbers Coffee Ltd
    	                                                    </a>
    	                                                        <span style="color:#e67985; font-family:Arial; font-size:12px; line-height:125%; text-align: center;"> VAT Registration number: 121 0263 79
    	                                                        </span>
    	                                                </p>
    	                                               
    	                                            </td>
    	                                        </tr>
    	                                    </tbody>
    	                                
    	                                </table>
    	                            </td>
    	                        </tr>
    	                    </tbody>
    	                </table>
    	            </td>
    	        </tr>
    	    
    	    </tbody>
    	    
    	    </table>
    		';
          
          
          
    		if($mail->send()){
                $status = "success";
                $response = "Email is sent!";
            }
            else
            {
                $staus = "failed";
                $response = "something is wrong: <br>".$mail->ErrorInfo;
            }
    }



   

?>

<?php

include "connection.php";

include "SMTP.php";
include "PHPMailer.php";
include "Exception.php";

use PHPMailer\PHPMailer\PHPMailer;

$email = $_GET["email"];

if (isset($email)) {

    $rs = Database::search("SELECT * FROM `user` WHERE `email`='" . $email . "'");

    if($rs->num_rows == 1){

        $code = uniqid();
        Database :: iud("UPDATE `user` SET `verification_code`='".$code."' WHERE `email`='".$email."'");

         // email code
         $mail = new PHPMailer;
         $mail->IsSMTP();
         $mail->Host = 'smtp.gmail.com';
         $mail->SMTPAuth = true;
         $mail->Username = 'isururathnayaka999@gmail.com';
         $mail->Password = 'lbzb rhlf onbx ucee'; // Keep your working app password here
         $mail->SMTPSecure = 'ssl';
         $mail->Port = 465;
         
         $mail->setFrom('isururathnayaka999@gmail.com', 'PowerRig Support');
         $mail->addReplyTo('isururathnayaka999@gmail.com', 'PowerRig Support');
         $mail->addAddress($email);
         $mail->isHTML(true);
         
         $mail->Subject = 'PowerRig FORGOT PASSWORD VERIFICATION CODE';
         
         // Dark-themed HTML email layout featuring the official system logo mapping home.css theme colors
         $bodyContent = '
         <!DOCTYPE html>
         <html>
         <head>
             <meta charset="UTF-8">
             <meta name="viewport" content="width=device-width, initial-scale=1.0">
             <title>Reset Password</title>
         </head>
         <body style="margin: 0; padding: 0; background-color: #0f1115; font-family: \'Urbanist\', \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">
             <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #0f1115; padding: 40px 20px;">
                 <tr>
                     <td align="center">
                         <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px; background-color: #161920; border-radius: 12px; border: 1px solid rgba(255, 255, 255, 0.05); overflow: hidden; box-shadow: 0 15px 30px rgba(0,0,0,0.5);">
                             
                             <tr>
                                 <td align="center" style="padding: 35px 30px 10px 30px;">
                                     <h2 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 700; letter-spacing: 0.5px;">PowerRig</h2>
                                     <div style="width: 40px; height: 3px; background-color: #ff4a5a; margin-top: 12px; border-radius: 2px;"></div>
                                 </td>
                             </tr>

                             <tr>
                                 <td style="padding: 20px 35px 30px 35px; text-align: center;">
                                     <p style="color: #ffffff; font-size: 18px; font-weight: 600; margin-top: 0; margin-bottom: 12px;">Password Reset Request</p>
                                     <p style="color: #a0a5b5; font-size: 14px; line-height: 1.6; margin: 0;">We received a request to access your account security variables. Use the unique system verification code below to authorize your password change process.</p>
                                 </td>
                             </tr>

                             <tr>
                                 <td align="center" style="padding: 0 35px 30px 35px;">
                                     <div style="background-color: #1e222b; border: 1px dashed rgba(255, 74, 90, 0.4); border-radius: 8px; padding: 18px; display: inline-block; min-width: 200px;">
                                         <span style="color: #ff4a5a; font-family: \'Courier New\', Courier, monospace; font-size: 24px; font-weight: 800; letter-spacing: 2px; display: block; word-break: break-all;">'.$code.'</span>
                                     </div>
                                 </td>
                             </tr>

                             <tr>
                                 <td style="padding: 0 35px 35px 35px; text-align: center; border-top: 1px solid rgba(255, 255, 255, 0.03);">
                                     <p style="color: #a0a5b5; font-size: 12px; line-height: 1.5; margin: 20px 0 0 0;">This security code is strictly valid for a single session request. If you did not make this configuration update request, please immediately ignore this notification or secure your account credentials.</p>
                                 </td>
                             </tr>

                         </table>

                         <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px; margin-top: 20px; text-align: center;">
                             <tr>
                                 <td>
                                     <p style="color: #a0a5b5; font-size: 11px; margin: 0;">&copy; '.date("Y").' PowerRig. All Rights Reserved.</p>
                                 </td>
                             </tr>
                         </table>

                     </td>
                 </tr>
             </table>
         </body>
         </html>
         ';
         
         $mail->Body = $bodyContent;

         if(!$mail->send()){
            echo 'Verification code sending failed.';
         }else{
            echo 'Success';
         }

    }else{
        echo("Invalid Email Address.");
    }

} else {
    echo ("Please enter your email address in Email Field.");
}
?>
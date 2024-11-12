<?php
    require('../inc/essentials.php');

    // PHP Mailer
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
    require '../../inc/PHPMailer/src/Exception.php';
    require '../../inc/PHPMailer/src/PHPMailer.php';
    require '../../inc/PHPMailer/src/SMTP.php';

    date_default_timezone_set("Asia/Manila");

    if (isset($_POST['forgot_pass'])) {
        $data = filteration($_POST);
    
        // Log filtered data
        error_log("Filtered data: " . print_r($data, true));
    
        $u_exist = select("SELECT * FROM admin_cred WHERE admin_name=? LIMIT 1", [$data['admin_name_account']], "s");
    
        // Log query result
        error_log("Query result: " . print_r($u_exist, true));
    
        if (mysqli_num_rows($u_exist) == 0) {
            error_log("Invalid email: " . $data['admin_name_account']);
            echo 'inv_email';
        } else {
            $u_fetch = mysqli_fetch_assoc($u_exist);
    
            // Log fetched user data
            error_log("Fetched user data: " . print_r($u_fetch, true));
    
            if ($u_fetch['is_super_admin'] != 1) {
                error_log("Not a superadmin: " . $u_fetch['admin_name']);
                echo 'not_superadmin';
            } else if ($u_fetch['status'] == 0) {
                error_log("Account inactive: " . $u_fetch['admin_name']);
                echo 'inactive';
            } else {
                // send reset link to email
                $token = bin2hex(random_bytes(16));
    
                // Log the generated token
                error_log("Generated token: " . $token);

                $u_fetch_email = mysqli_fetch_assoc(select("SELECT * FROM contact_details WHERE sr_no=?", [1], "i"));
    
                $email = $u_fetch_email['email'];
                $admin_name = $data['admin_name_account'];
    
                // Log email sending attempt
                error_log("Sending email to: " . $email . " with token: " . $token);
    
                if (!send_mail($email, $token, 'admin_account_recovery', $admin_name)) {
                    error_log("Mail sending failed");
                    echo 'mail_failed';
                } else {
                    $date = date("Y-m-d");
    
                    // Log date and query details
                    error_log("Updating token with date: " . $date);
                    error_log("Executing query: UPDATE admin_cred SET token='$token', t_expire='$date' WHERE admin_name='$admin_name'");
    
                    $query = mysqli_query($con, "UPDATE admin_cred SET token='$token', t_expire='$date' WHERE admin_name='$admin_name'");
    
                    if ($query) {
                        error_log("Token updated successfully for admin name: " . $admin_name);
                        echo 1;
                    } else {
                        error_log("Token update failed for admin name: " . $admin_name);
                        echo 'upd_failed';
                    }
                }
            }
        }
    }    

    function send_mail($uemail,$token,$type,$admin_name)
    {
        if($type == "email_confirmation")
        {
            $page = 'email_confirm.php';
            $subject = "Account Verification Link";
            $content = "confirm your email";
        }
        else if($type == "admin_account_recovery")
        {
            $page = 'index.php';
            $subject = "Account Reset Link";
            $content = "reset your account";
        }

        $mail = new PHPMailer(true);
        
        //Server settings
        $mail->isSMTP();
        $mail->Host       = MAILHOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = USERNAME;
        $mail->Password   = PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ),
        );

        //Recipients
        $mail->setFrom(SEND_FROM, SEND_FROM_NAME);
        $mail->addAddress($uemail);
        $mail->addReplyTo(REPLY_TO, REPLY_TO_NAME);

        //Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = "
                Click the link to $content: <br>
                <a href='".SITE_URL."$page?$type&email=$uemail&admin_name=$admin_name&token=$token"."'>
                    Click Me
                </a>
            ";
        $mail->AltBody = "
                Click the link to $content: \n
                ".SITE_URL."$page?$type&email=$uemail&admin_name=$admin_name&token=$token
            ";

        if(!$mail->send())
        {
            return 0;
        }
        else
        {
            return 1;
        }

    }
    
?>
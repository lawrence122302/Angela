<?php
    require('../admin/inc/essentials.php');

    // PHP Mailer
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
    require '../inc/PHPMailer/src/Exception.php';
    require '../inc/PHPMailer/src/PHPMailer.php';
    require '../inc/PHPMailer/src/SMTP.php';

    date_default_timezone_set("Asia/Manila");

    function send_mail($uemail,$token,$type)
    {
        if($type == "email_confirmation")
        {
            $page = 'email_confirm.php';
            $subject = "Account Verification Link";
            $content = "confirm your email";
        }
        else
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
                <a href='".SITE_URL."$page?$type&email=$uemail&token=$token"."'>
                    Click Me
                </a>
            ";
        $mail->AltBody = "
                Click the link to $content: \n
                ".SITE_URL."$page?$type&email=$uemail&token=$token
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

    if(isset($_POST['track_booking']))
    {
        $frm_data = filteration($_POST);

        $result = json_encode(["email_mob"=>$frm_data['email_mob'], "gcash_ref"=>$frm_data['gcash_ref']]);
        echo $result;
    }

    if(isset($_POST['register']))
    {
        $data = filteration($_POST);

        // Match password and confirm password

        if($data['pass'] != $data['cpass'])
        {
            echo 'pass_mismatch';
            exit;
        }

        // Validate password strength
        
        if (strlen($data['pass']) < 12) {
            echo 'short_pass';
            exit;
        } else if (!preg_match('/[A-Z]/', $data['pass'])) {
            echo 'no_upper';
            exit;
        } else if (!preg_match('/[a-z]/', $data['pass'])) {
            echo 'no_lower';
            exit;
        } else if (!preg_match('/\d/', $data['pass'])) {
            echo 'no_number';
            exit;
        } else if (!preg_match('/[\W_]/', $data['pass'])) {
            echo 'no_symbol';
            exit;
        }        

        // Check user exists or not
        
        $u_exist = select("SELECT * FROM user_cred WHERE email=? OR phonenum=? LIMIT 1",
            [$data['email'],$data['phonenum']],"ss");

        if(mysqli_num_rows($u_exist)!=0)
        {
            $u_exist_fetch  = mysqli_fetch_assoc($u_exist);
            echo ($u_exist_fetch['email'] == $data['email']) ? 'email_already' : 'phone_already';
            exit;
        }

        // Upload user image to server

        $img = uploadUserImage($_FILES['profile']);

        if($img == 'inv_img')
        {
            echo 'inv_img';
            exit;
        }
        else if($img == 'upd_failed')
        {
            echo 'upd_failed';
            exit;
        }

        // send confirmation link to user's email
        
        $token = bin2hex(random_bytes(16));

        if(!send_mail($data['email'],$token,"email_confirmation"))
        {
            echo 'mail_failed';
            exit;
        }

        $enc_pass = password_hash($data['pass'],PASSWORD_BCRYPT);

        $query = "INSERT INTO user_cred(name,email,address,phonenum,
            pincode,dob,profile,password,token) VALUES(?,?,?,?,?,?,?,?,?)";

        $values = [$data['name'],$data['email'],$data['address'],$data['phonenum'],
            $data['pincode'],$data['dob'],$img,$enc_pass,$token];

        if(insert($query,$values,'sssssssss'))
        {
            echo 1;
        }
        else
        {
            echo 'ins_failed';
        }
    }

    if(isset($_POST['login']))
    {
        $data = filteration($_POST);

        $u_exist = select("SELECT * FROM user_cred WHERE email=? OR phonenum=? LIMIT 1",
            [$data['email_mob'],$data['email_mob']],"ss");

        if(mysqli_num_rows($u_exist)==0)
        {
            echo 'inv_email_mob';
        }
        else
        {
            $u_fetch  = mysqli_fetch_assoc($u_exist);
            if($u_fetch['is_verified']==0)
            {
                echo 'not_verified';
            }
            else if($u_fetch['status']==0)
            {
                echo 'inactive';
            }
            else
            {
                if(!password_verify($data['pass'],$u_fetch['password']))
                {
                    echo 'invalid_pass';
                }
                else
                {
                    session_name('user_session');
                    session_start();
                    $_SESSION['login'] = true;
                    $_SESSION['uId'] = $u_fetch['id'];
                    $_SESSION['uName'] = $u_fetch['name'];
                    $_SESSION['uPic'] = $u_fetch['profile'];
                    $_SESSION['uPhone'] = $u_fetch['phonenum'];
                    echo 1;
                }
            }
        }
    }

    if(isset($_POST['forgot_pass']))
    {
        $data = filteration($_POST);

        $u_exist = select("SELECT * FROM user_cred WHERE email=? LIMIT 1",[$data['email']],"s");

        if(mysqli_num_rows($u_exist)==0)
        {
            echo 'inv_email';
        }
        else
        {
            $u_fetch  = mysqli_fetch_assoc($u_exist);
            if($u_fetch['is_verified']==0)
            {
                echo 'not_verified';
            }
            else if($u_fetch['status']==0)
            {
                echo 'inactive';
            }
            else
            {
                // send reset link to email
                $token = bin2hex(random_bytes(16));

                if(!send_mail($data['email'],$token,'account_recovery'))
                {
                    echo 'mail_failed';
                }
                else
                {
                    $date = date("Y-m-d");

                    $query = mysqli_query($con, "UPDATE user_cred SET token='$token', t_expire='$date' 
                        WHERE id={$u_fetch['id']}");

                    if($query)
                    {
                        echo 1;
                    }
                    else
                    {
                        echo 'upd_failed';
                    }
                }
            }
        }
    }

    if(isset($_POST['recover_user']))
    {
        $data = filteration($_POST);

        $enc_pass = password_hash($data['pass'],PASSWORD_BCRYPT);

        $query = "UPDATE user_cred SET password=?, token=?, t_expire=? 
            WHERE email=? AND token=?";

        $values = [$enc_pass,null,null,$data['email'],$data['token']];

        if(update($query,$values,'sssss'))
        {
            echo 1;
        }
        else
        {
            echo 'failed';
        }
    }

?>
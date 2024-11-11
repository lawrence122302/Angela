<?php
    require('../inc/essentials.php');
    adminLogin();

    // PHP Mailer
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
    require '../../inc/PHPMailer/src/Exception.php';
    require '../../inc/PHPMailer/src/PHPMailer.php';
    require '../../inc/PHPMailer/src/SMTP.php';

    date_default_timezone_set("Asia/Manila");

    if(isset($_POST['pay_now']))
    {
        $frm_data = filteration($_POST);

        // Check in and out validations
        $today_date = new DateTime(date("Y-m-d H:i:s"));
        $checkin_date = new DateTime($frm_data['datetimeLocal_checkin']);
        $checkout_date = new DateTime($frm_data['datetimeLocal_checkout']);

        $ORDER_ID = 'BK_'.$frm_data['userId'].random_int(11111,9999999);
        $CUST_ID = $frm_data['userId'];

        $accommodationRes = selectAll('rooms WHERE id='.$frm_data['accommodationId']);
        $row = mysqli_fetch_assoc($accommodationRes);

        // Assign correct room price
        if ($frm_data['isWeekend'] == "false") {
            error_log("Weekday Detected");
            if ($frm_data['time_of_day'] == "Day Tour" && $frm_data['is_22hrs'] == "false") {
                error_log("Day Tour during Weekday, not 22 hours");
                $package_type = "Weekdays | Monday - Thursday | Day Tour";
                $payment = $row['price'];
            } else if ($frm_data['time_of_day'] == "Night Tour" && $frm_data['is_22hrs'] == "false") {
                error_log("Night Tour during Weekday, not 22 hours");
                $package_type = "Weekdays | Monday - Thursday | Night Tour";
                $payment = $row['price'];
            } else if (($frm_data['time_of_day'] == "Day Tour" && $frm_data['is_22hrs'] == "true")) {
                error_log("Day Tour during Weekday, 22 hours");
                $package_type = "Weekdays | Monday - Thursday | 22 Hours Day Tour";
                $payment = $row['price2'];
            } else if (($frm_data['time_of_day'] == "Night Tour" && $frm_data['is_22hrs'] == "true")) {
                error_log("Night Tour during Weekday, 22 hours");
                $package_type = "Weekdays | Monday - Thursday | 22 Hours Night Tour";
                $payment = $row['price2'];
            }
        } else if ($frm_data['isWeekend'] == "true") {
            error_log("Weekend Detected");
            if ($frm_data['time_of_day'] == "Day Tour" && $frm_data['is_22hrs'] == "false") {
                error_log("Day Tour during Weekend, not 22 hours");
                $package_type = "Weekends | Friday - Sunday | Day Tour";
                $payment = $row['price3'];
            } else if ($frm_data['time_of_day'] == "Night Tour" && $frm_data['is_22hrs'] == "false") {
                error_log("Night Tour during Weekend, not 22 hours");
                $package_type = "Weekends | Friday - Sunday | Night Tour";
                $payment = $row['price3'];
            } else if (($frm_data['time_of_day'] == "Day Tour" && $frm_data['is_22hrs'] == "true")) {
                error_log("Day Tour during Weekend, 22 hours");
                $package_type = "Weekends | Friday - Sunday | 22 Hours Day Tour";
                $payment = $row['price4'];
            } else if (($frm_data['time_of_day'] == "Night Tour" && $frm_data['is_22hrs'] == "true")) {
                error_log("Night Tour during Weekend, 22 hours Night Tour");
                $package_type = "Weekends | Friday - Sunday | 22 Hours Night Tour";
                $payment = $row['price4'];
            }
        }

        // Check if 50% down payment or full payment
        if ($frm_data['paidamount'] == 1) {
            $paid_amount = $payment / 2;
        } else if ($frm_data['paidamount'] == 2) {
            $paid_amount = $payment;
        }
        error_log('Paid amount: ' . $paid_amount);

        // Convert DateTime objects to strings
        $checkin_date_str = $checkin_date->format('Y-m-d H:i:s');
        $checkout_date_str = $checkout_date->format('Y-m-d H:i:s');
        error_log('Check-in date: ' . $checkin_date_str);
        error_log('Check-out date: ' . $checkout_date_str);

        // Insert payment into database
        $query1 = "INSERT INTO booking_order (
            user_id,
            room_id,
            check_in,
            check_out,
            package_type,
            order_id,
            trans_id,
            trans_amt
        )
        VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?
        )";

        $query1_params = [
            $CUST_ID,
            $frm_data['userId'],
            $checkin_date_str,
            $checkout_date_str,
            $package_type,
            $ORDER_ID,
            $frm_data['g_reference'],
            $paid_amount
        ];

        error_log('Query1: ' . $query1);
        error_log('Query1 Params: ' . print_r($query1_params, true));

        insert($query1, $query1_params, 'iissssss');

        $booking_id = mysqli_insert_id($con);
        error_log('Booking ID: ' . $booking_id);

        $query2 = "INSERT INTO booking_details (
            booking_id,
            room_name,
            price,
            total_pay,
            room_no,
            user_name,
            phonenum,
            address
        ) 
        VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?
        )";

        $userRes = selectAll('user_cred WHERE id='.$frm_data['userId']);
        $userRow = mysqli_fetch_assoc($userRes);

        $query2_params = [
            $booking_id,
            $row['name'],
            $payment,
            $payment,
            $row['name'],
            $userRow['name'],
            $userRow['phonenum'],
            $userRow['address']
        ];

        error_log('Query2: ' . $query2);
        error_log('Query2 Params: ' . print_r($query2_params, true));

        // Ensure parameters are correctly passed
        insert($query2, $query2_params, 'isssssss');

        $result = json_encode(["orderid" => $ORDER_ID]);
        error_log('Result: ' . $result);
        echo $result;
    }

    if(isset($_POST['without_account_pay_now']))
    {
        $frm_data = filteration($_POST);

        error_log(print_r($frm_data, true));

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

        $token = bin2hex(random_bytes(16));

        $query = "INSERT INTO user_cred(name,email,address,phonenum,
            pincode,dob,profile,token,is_verified) VALUES(?,?,?,?,?,?,?,?,?)";

        $values = [$frm_data['name'],$frm_data['email'],$frm_data['address'],$frm_data['phonenum'],
            $frm_data['pincode'],$frm_data['dob'],$img,$token,1];

        if (insert($query,$values,'ssssssssi')) {
            $userId = mysqli_insert_id($con);
        } else {
            echo 'ins_failed';
        }

        // Check in and out validations
        $today_date = new DateTime(date("Y-m-d H:i:s"));
        $checkin_date = new DateTime($frm_data['datetimeLocal_checkin']);
        $checkout_date = new DateTime($frm_data['datetimeLocal_checkout']);

        $ORDER_ID = 'BK_'.$userId.random_int(11111,9999999);
        $CUST_ID = $userId;

        $accommodationRes = selectAll('rooms WHERE id='.$frm_data['accommodationId']);
        $row = mysqli_fetch_assoc($accommodationRes);

        // Assign correct room price
        if ($frm_data['isWeekend'] == "false") {
            error_log("Weekday Detected");
            if ($frm_data['time_of_day'] == "Day Tour" && $frm_data['is_22hrs'] == "false") {
                error_log("Day Tour during Weekday, not 22 hours");
                $package_type = "Weekdays | Monday - Thursday | Day Tour";
                $payment = $row['price'];
            } else if ($frm_data['time_of_day'] == "Night Tour" && $frm_data['is_22hrs'] == "false") {
                error_log("Night Tour during Weekday, not 22 hours");
                $package_type = "Weekdays | Monday - Thursday | Night Tour";
                $payment = $row['price'];
            } else if (($frm_data['time_of_day'] == "Day Tour" && $frm_data['is_22hrs'] == "true")) {
                error_log("Day Tour during Weekday, 22 hours");
                $package_type = "Weekdays | Monday - Thursday | 22 Hours Day Tour";
                $payment = $row['price2'];
            } else if (($frm_data['time_of_day'] == "Night Tour" && $frm_data['is_22hrs'] == "true")) {
                error_log("Night Tour during Weekday, 22 hours");
                $package_type = "Weekdays | Monday - Thursday | 22 Hours Night Tour";
                $payment = $row['price2'];
            }
        } else if ($frm_data['isWeekend'] == "true") {
            error_log("Weekend Detected");
            if ($frm_data['time_of_day'] == "Day Tour" && $frm_data['is_22hrs'] == "false") {
                error_log("Day Tour during Weekend, not 22 hours");
                $package_type = "Weekends | Friday - Sunday | Day Tour";
                $payment = $row['price3'];
            } else if ($frm_data['time_of_day'] == "Night Tour" && $frm_data['is_22hrs'] == "false") {
                error_log("Night Tour during Weekend, not 22 hours");
                $package_type = "Weekends | Friday - Sunday | Night Tour";
                $payment = $row['price3'];
            } else if (($frm_data['time_of_day'] == "Day Tour" && $frm_data['is_22hrs'] == "true")) {
                error_log("Day Tour during Weekend, 22 hours");
                $package_type = "Weekends | Friday - Sunday | 22 Hours Day Tour";
                $payment = $row['price4'];
            } else if (($frm_data['time_of_day'] == "Night Tour" && $frm_data['is_22hrs'] == "true")) {
                error_log("Night Tour during Weekend, 22 hours Night Tour");
                $package_type = "Weekends | Friday - Sunday | 22 Hours Night Tour";
                $payment = $row['price4'];
            }
        }

        // Check if 50% down payment or full payment
        if ($frm_data['paidamount'] == 1) {
            $paid_amount = $payment / 2;
        } else if ($frm_data['paidamount'] == 2) {
            $paid_amount = $payment;
        }

        error_log('Paid amount: ' . $paid_amount);

        // Convert DateTime objects to strings
        $checkin_date_str = $checkin_date->format('Y-m-d H:i:s');
        $checkout_date_str = $checkout_date->format('Y-m-d H:i:s');

        error_log('Check-in date: ' . $checkin_date_str);
        error_log('Check-out date: ' . $checkout_date_str);
        
        // Insert payment into database
        $query1 = "INSERT INTO booking_order (
            user_id,
            room_id,
            check_in,
            check_out,
            package_type,
            order_id,
            trans_id,
            trans_amt
        )
        VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?
        )";

        $query1_params = [
            $CUST_ID,
            $frm_data['accommodationId'],
            $checkin_date_str,
            $checkout_date_str,
            $package_type,
            $ORDER_ID,
            $frm_data['g_reference'],
            $paid_amount
        ];

        error_log('Query1: ' . $query1);
        error_log('Query1 Params: ' . print_r($query1_params, true));

        if (insert($query1, $query1_params, 'iissssss')) {
            $booking_id = mysqli_insert_id($con);
            error_log('Booking ID: ' . $booking_id);
        } else {
            echo 'ins_failed';
        }

        $query2 = "INSERT INTO booking_details (
            booking_id,
            room_name,
            price,
            total_pay,
            room_no,
            user_name,
            phonenum,
            address
        ) 
        VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?
        )";

        $userRes = selectAll('user_cred WHERE id='.$userId);
        $userRow = mysqli_fetch_assoc($userRes);

        $query2_params = [
            $booking_id,
            $row['name'],
            $payment,
            $payment,
            $row['name'],
            $userRow['name'],
            $userRow['phonenum'],
            $userRow['address']
        ];

        error_log('Query2: ' . $query2);
        error_log('Query2 Params: ' . print_r($query2_params, true));

        // Ensure parameters are correctly passed
        if (!insert($query2, $query2_params, 'isssssss')) {
            echo 'ins_failed';
        }

        // Sending email to user to allow change password

        $u_fetch = mysqli_fetch_assoc(select("SELECT * FROM user_cred WHERE email=? LIMIT 1",[$frm_data['email']],"s"));

        $token = bin2hex(random_bytes(16));

        if(!send_mail($frm_data['email'],$token,'account_change_password'))
        {
            echo 'mail_failed';
        }
        else
        {
            $date = date('9999-12-31');

            $query = mysqli_query($con, "UPDATE user_cred SET token='$token', t_expire='$date' 
                WHERE id={$u_fetch['id']}");

            if($query)
            {
                echo 1;
            }
            else
            {
                echo 'ins_failed';
            }
        }    
    }

    function send_mail($uemail,$token,$type)
    {
        if($type == "account_change_password")
        {
            $page = 'index.php';
            $subject = "Account Change Password from Angela's Private Pool";
            $content = "set your password";
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
?>
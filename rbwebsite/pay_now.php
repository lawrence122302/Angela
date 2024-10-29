<?php
    require('admin/inc/essentials.php');

    date_default_timezone_set("Asia/Manila");

    session_name('user_session');
    session_start();

    // TODO payment method

    if(!(isset($_SESSION['login']) && $_SESSION['login']==true))
    {
        redirect('index.php');
    }

    if(isset($_POST['pay_now']))
    {
        $frm_data = filteration($_POST);

        // Check in and out validations
        $today_date = new DateTime(date("Y-m-d H:i:s"));
        $checkin_date = new DateTime($frm_data['datetimeLocal_checkin']);
        $checkout_date = new DateTime($frm_data['datetimeLocal_checkout']);

        $ORDER_ID = 'BK_'.$_SESSION['uId'].random_int(11111,9999999);
        $CUST_ID = $_SESSION['uId'];

        // Assign correct room price
        if ($frm_data['isWeekend'] == "false") {
            error_log("Weekday Detected");
            if ($frm_data['time_of_day'] == "Day Tour" && $frm_data['is_22hrs'] == "false") {
                error_log("Day Tour during Weekday, not 22 hours");
                $package_type = "Weekdays | Monday - Thursday | Day Tour";
                $payment = $_SESSION['room']['price'];
            } else if ($frm_data['time_of_day'] == "Night Tour" && $frm_data['is_22hrs'] == "false") {
                error_log("Night Tour during Weekday, not 22 hours");
                $package_type = "Weekdays | Monday - Thursday | Night Tour";
                $payment = $_SESSION['room']['price'];
            } else if (($frm_data['time_of_day'] == "Day Tour" && $frm_data['is_22hrs'] == "true")) {
                error_log("Day Tour during Weekday, 22 hours");
                $package_type = "Weekdays | Monday - Thursday | 22 Hours Day Tour";
                $payment = $_SESSION['room']['price2'];
            } else if (($frm_data['time_of_day'] == "Night Tour" && $frm_data['is_22hrs'] == "true")) {
                error_log("Night Tour during Weekday, 22 hours");
                $package_type = "Weekdays | Monday - Thursday | 22 Hours Night Tour";
                $payment = $_SESSION['room']['price2'];
            }
        } else if ($frm_data['isWeekend'] == "true") {
            error_log("Weekend Detected");
            if ($frm_data['time_of_day'] == "Day Tour" && $frm_data['is_22hrs'] == "false") {
                error_log("Day Tour during Weekend, not 22 hours");
                $package_type = "Weekends | Friday - Sunday | Day Tour";
                $payment = $_SESSION['room']['price3'];
            } else if ($frm_data['time_of_day'] == "Night Tour" && $frm_data['is_22hrs'] == "false") {
                error_log("Night Tour during Weekend, not 22 hours");
                $package_type = "Weekends | Friday - Sunday | Night Tour";
                $payment = $_SESSION['room']['price3'];
            } else if (($frm_data['time_of_day'] == "Day Tour" && $frm_data['is_22hrs'] == "true")) {
                error_log("Day Tour during Weekend, 22 hours");
                $package_type = "Weekends | Friday - Sunday | 22 Hours Day Tour";
                $payment = $_SESSION['room']['price4'];
            } else if (($frm_data['time_of_day'] == "Night Tour" && $frm_data['is_22hrs'] == "true")) {
                error_log("Night Tour during Weekend, 22 hours Night Tour");
                $package_type = "Weekends | Friday - Sunday | 22 Hours Night Tour";
                $payment = $_SESSION['room']['price4'];
            }
        }

       // Check if 50% down payment or full payment
       if($frm_data['paidamount']==1)
       {
        $paid_amount = $payment / 2;
       }
       else if($frm_data['paidamount']==2)
       {
        $paid_amount = $payment;
       }

        // Convert DateTime objects to strings
        $checkin_date_str = $checkin_date->format('Y-m-d H:i:s');
        $checkout_date_str = $checkout_date->format('Y-m-d H:i:s');

        // Insert payment into database
        $query1 = "INSERT INTO booking_order(user_id,room_id,check_in,check_out,order_id,trans_id,trans_amt) 
            VALUES(?,?,?,?,?,?,?)";

        $query1_params = [$CUST_ID, $_SESSION['room']['id'], $checkin_date_str, 
        $checkout_date_str, $ORDER_ID, $frm_data['g_reference'], $paid_amount];
        
        insert($query1, $query1_params, 'iisssss');

        $booking_id = mysqli_insert_id($con);

        $query2 = "INSERT INTO booking_details(booking_id, room_name, price, total_pay, room_no,
            user_name, phonenum, address) VALUES(?,?,?,?,?,?,?,?)";
        
        $query2_params = [$booking_id, $_SESSION['room']['name'], $payment, $payment, 
        $_SESSION['room']['name'], $frm_data['name'], $frm_data['phonenum'], $frm_data['address']];

        // Ensure parameters are correctly passed
        insert($query2, $query2_params, 'isssssss');

        $result = json_encode(["orderid"=>$ORDER_ID]);
        echo $result;
    }

?>
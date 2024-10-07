<?php
    require('admin/inc/db_config.php');
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

        $ORDER_ID = 'ORD_'.$_SESSION['uId'].random_int(11111,9999999);
        $CUST_ID = $_SESSION['uId'];
        $TXN_AMOUNT = $_SESSION['room']['payment'];

       // Check if 50% down payment or full payment
       if($frm_data['paidamount']==1)
       {
        $paid_amount = $TXN_AMOUNT / 2;
       }
       else if($frm_data['paidamount']==2)
       {
        $paid_amount = $TXN_AMOUNT;
       }

        // Convert DateTime objects to strings
        $checkin_date_str = $checkin_date->format('Y-m-d H:i:s');
        $checkout_date_str = $checkout_date->format('Y-m-d H:i:s');

        // Debugging values
       error_log("ORDER_ID: " . $ORDER_ID);
       error_log("CUST_ID: " . $CUST_ID);
       error_log("TXN_AMOUNT: " . $TXN_AMOUNT);
       error_log("Check-in Date: " . $checkin_date->format('Y-m-d H:i:s'));
       error_log("Check-out Date: " . $checkout_date->format('Y-m-d H:i:s'));

        // Insert payment into database
        $query1 = "INSERT INTO booking_order(user_id,room_id,check_in,check_out,order_id,trans_id,trans_amt) 
            VALUES(?,?,?,?,?,?,?)";

        $query1_params = [$CUST_ID, $_SESSION['room']['id'], $checkin_date_str, 
        $checkout_date_str, $ORDER_ID, $frm_data['g_reference'], $paid_amount];

        error_log("query1_params: " . print_r($query1_params, true));
        
        insert($query1, $query1_params, 'iisssss');

        $booking_id = mysqli_insert_id($con);

        $query2 = "INSERT INTO booking_details(booking_id, room_name, price, total_pay, room_no,
            user_name, phonenum, address) VALUES(?,?,?,?,?,?,?,?)";
        
        $query2_params = [$booking_id, $_SESSION['room']['name'], $_SESSION['room']['price'], $TXN_AMOUNT, 
        $_SESSION['room']['name'], $frm_data['name'], $frm_data['phonenum'], $frm_data['address']];
        
        error_log("query2_params: " . print_r($query2_params, true));

        // Ensure parameters are correctly passed
        insert($query2, $query2_params, 'isssssss');

        $result = json_encode(["orderid"=>$ORDER_ID]);
        echo $result;
    }

?>
<?php
    require('admin/inc/db_config.php');
    require('admin/inc/essentials.php');

    date_default_timezone_set("Asia/Manila");

    session_start();

    // TODO payment method

    if(!(isset($_SESSION['login']) && $_SESSION['login']==true))
    {
        redirect('index.php');
    }

    if(isset($_POST['pay_now']))
    {
        $ORDER_ID = 'ORD_'.$_SESSION['uId'].random_int(11111,9999999);
        $CUST_ID = $_SESSION['uId'];
        $TXN_AMOUNT = $_SESSION['room']['payment'];

        // Insert payment into database
        $frm_data = filteration($_POST);

        $query1 = "INSERT INTO booking_order(user_id,room_id,check_in,check_out,order_id,trans_amt) 
            VALUES(?,?,?,?,?,?)";
        
        insert($query1,[$CUST_ID,$_SESSION['room']['id'],$frm_data['checkin'],
            $frm_data['checkout'],$ORDER_ID,$frm_data['paidamount']],'issssi');

        $booking_id = mysqli_insert_id($con);

        $query2 = "INSERT INTO booking_details(booking_id,room_name,price,total_pay,
            user_name,phonenum,address) VALUES(?,?,?,?,?,?,?)";

        insert($query2,[$booking_id,$_SESSION['room']['name'],$_SESSION['room']['price'],
            $TXN_AMOUNT,$frm_data['name'],$frm_data['phonenum'],$frm_data['address']],'issssss');

        $result = json_encode(["orderid"=>$ORDER_ID]);
        echo $result;
    }

?>
<?php
    require('../inc/essentials.php');
    adminLogin();

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
?>
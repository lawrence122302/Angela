<?php
    require('../admin/inc/essentials.php');

    date_default_timezone_set("Asia/Manila");

        if(isset($_POST['check_availability']))
        {
            $frm_data = filteration($_POST);
            $status = "";        
            $result = "";        

            // check in and out validations
            $today_date = new DateTime(date("Y-m-d H:i:s"));
            $checkin_date = new DateTime($frm_data['datetimeLocal_checkin']);
            $checkout_date = new DateTime($frm_data['datetimeLocal_checkout']);

            // Format dates correctly for SQL
            $formatted_checkin = $checkin_date->format('Y-m-d H:i:s');
            $formatted_checkout = $checkout_date->format('Y-m-d H:i:s');

            // Log formatted dates to ensure correct transformation
            error_log("Formatted Check-in Date: " . $formatted_checkin);
            error_log("Formatted Check-out Date: " . $formatted_checkout);

            // Debug the value of isWeekend and is_22hrs
            error_log("isWeekend: " . $frm_data['isWeekend']);
            error_log("is_22hrs: " . $frm_data['is_22hrs']);

            // Debug dates
            error_log("Today's Date: " . $today_date->format('Y-m-d H:i:s'));
            error_log("Check-in Date: " . $checkin_date->format('Y-m-d H:i:s'));
            error_log("Check-out Date: " . $checkout_date->format('Y-m-d H:i:s'));

            if($checkin_date == $checkout_date)
            {
                $status = 'check_in_out_equal';
                $result = json_encode(["status"=>$status]);
            }
            else if($checkout_date < $checkin_date)
            {
                $status = 'check_out_earlier';
                $result = json_encode(["status"=>$status]);
            }
            else if($checkin_date < $today_date)
            {
                $status = 'check_in_earlier';
                $result = json_encode(["status"=>$status]);
            }

            header('Content-Type: application/json');

            // Check booking availability if status is blank else return the error

            if($status!='')
            {
                header('Content-Type: application/json');
                echo $result;
                exit;
            }
            else
            {
                session_name('user_session');
                session_start();

                // run query to check room is available or not
                $tb_query = "SELECT COUNT(*) AS total_bookings 
                    FROM booking_order

                    WHERE 
                    (
                    booking_status=? OR booking_status=? OR booking_status=?
                    ) 

                    AND room_id=? AND check_out > ? AND check_in < ?";

                $values = ['pending','reserved','pending',$_SESSION['room']['id'],$formatted_checkin,$formatted_checkout];
                $tb_fetch = mysqli_fetch_assoc(select($tb_query,$values,'sssiss'));

                $rq_result = select("SELECT quantity FROM rooms WHERE id=?",[$_SESSION['room']['id']],'i');
                $rq_fetch = mysqli_fetch_assoc($rq_result);

                // Check for blocked dates
                $checkin_date_only = explode(" ", $formatted_checkin)[0]; // Extract the date part (YYYY-MM-DD)
                $checkout_date_only = explode(" ", $formatted_checkout)[0]; // Extract the date part (YYYY-MM-DD)

                // Error log the values
                error_log("Check-in Date Only: " . $checkin_date_only);
                error_log("Original Check-in Value: " . $formatted_checkin);
                error_log("Checkout Date Only: " . $checkout_date_only);
                error_log("Original Checkout Value: " . $formatted_checkout);

                // Always check if check-in date is in blocked dates
                $blocked_checkin_query = "SELECT COUNT(*) AS blocked_count FROM blocked_dates
                    WHERE date=? AND room_id=? AND status=1";
                $blocked_checkin_values = [$checkin_date_only, $_SESSION['room']['id']];
                $blocked_checkin_fetch = mysqli_fetch_assoc(select($blocked_checkin_query, $blocked_checkin_values, 'si'));

                if ($blocked_checkin_fetch['blocked_count'] > 0) {
                    $status = 'unavailable';
                    $result = json_encode(['status' => $status]);
                    echo $result;
                    exit;
                }

                // Check number of pending bookings (Only allow 3)

                $number_of_pending_bookings_query = "SELECT COUNT(*) AS number_of_pending_bookings FROM booking_order WHERE user_id=? AND booking_status='pending'";
                $number_of_pending_bookings_values = [$_SESSION['uId']];
                $number_of_pending_bookings_values = mysqli_fetch_assoc(select($number_of_pending_bookings_query, $number_of_pending_bookings_values, 'i'));

                if ($number_of_pending_bookings_values['number_of_pending_bookings'] >= 3) {
                    $status = 'unavailable_pending';
                    $result = json_encode(['status' => $status]);
                    echo $result;
                    exit;
                }

                // Only check blocked dates for check-out if it's a Night Tour
                if ($frm_data['time_of_day'] == "Night Tour") {
                    $blocked_checkout_query = "SELECT COUNT(*) AS blocked_count FROM blocked_dates
                        WHERE date=? AND room_id=? AND status=1";
                    $blocked_checkout_values = [$checkout_date_only, $_SESSION['room']['id']];
                    $blocked_checkout_fetch = mysqli_fetch_assoc(select($blocked_checkout_query, $blocked_checkout_values, 'si'));

                    if ($blocked_checkout_fetch['blocked_count'] > 0) {
                        $status = 'unavailable';
                        $result = json_encode(['status' => $status]);
                        echo $result;
                        exit;
                    }
                }

                // Check room availability based on quantity and bookings
                if (($rq_fetch['quantity'] - $tb_fetch['total_bookings']) <= 0) {
                    $status = 'unavailable';
                    $result = json_encode(['status' => $status]);
                    echo $result;
                    exit;
                }

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

                // difference in hours
                $checkin_date_diff = new DateTime($frm_data['datetimeLocal_checkin'], new DateTimeZone('Asia/Manila'));
                $checkout_date_diff = new DateTime($frm_data['datetimeLocal_checkout'], new DateTimeZone('Asia/Manila'));    

                $interval = $checkin_date_diff->diff($checkout_date_diff);
                // Total difference in hours
                $hours = ($interval->days * 24) + $interval->h;

                $checkin_date = new DateTime($frm_data['datetimeLocal_checkin'], new DateTimeZone('Asia/Manila'));
                $checkin_formatted_time = $checkin_date->format('g:i A');
                error_log("Formatted Check-in Time: " . $checkin_formatted_time);

                $checkout_date = new DateTime($frm_data['datetimeLocal_checkout'], new DateTimeZone('Asia/Manila'));
                $checkout_formatted_time = $checkout_date->format('g:i A');
                error_log("Formatted Check-out Time: " . $checkout_formatted_time);

                error_log("Room Price: " . $payment);
                error_log("Difference in hours: " . $hours);

                $_SESSION['room']['payment'] = $payment;
                $_SESSION['room']['available'] = true;

                $result = json_encode(["status"=>'available', "package_type"=>$package_type, "hour1"=>$checkin_formatted_time, "hour2"=>$checkout_formatted_time, "payment"=>$payment]);
                echo $result;
            }
        }
        else
        {
            // Handle unexpected access or errors
            echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
        }
?>
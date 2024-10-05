<?php
    require('../admin/inc/db_config.php');
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

            // check booking availability if status is blank else return the error
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
                $tb_query = "SELECT COUNT(*) AS total_bookings FROM booking_order
                    WHERE booking_status=? AND room_id=?
                    AND check_out > ? AND check_in < ?";

                $values = ['booked',$_SESSION['room']['id'],$frm_data['datetimeLocal_checkin'],$frm_data['datetimeLocal_checkout']];
                $tb_fetch = mysqli_fetch_assoc(select($tb_query,$values,'siss'));

                $rq_result = select("SELECT quantity FROM rooms WHERE id=?",[$_SESSION['room']['id']],'i');
                $rq_fetch = mysqli_fetch_assoc($rq_result);

                if(($rq_fetch['quantity']-$tb_fetch['total_bookings'])==0)
                {
                    $status = 'unavailable';
                    $result = json_encode(['status'=>$status]);
                    echo $result;
                    exit;
                }

                if(!$frm_data['isWeekend'])
                {
                    if($frm_data['time_of_day'] == "Day Tour" && !$frm_data['is_22hrs'])
                    {
                        $package_type = "Monday - Thursday | Day Tour";
                        $payment = $_SESSION['room']['price'];
                    }
                    else if($frm_data['time_of_day'] == "Night Tour" && !$frm_data['is_22hrs'])
                    {
                        $package_type = "Monday - Thursday | Night Tour";
                        $payment = $_SESSION['room']['price2'];
                    }
                    else if(($frm_data['time_of_day'] == "Day Tour" && $frm_data['is_22hrs']) || ($frm_data['time_of_day'] == "Night Tour" && $frm_data['is_22hrs']))
                    {
                        $package_type = "Monday - Thursday | 22 Hours";
                        $payment = $_SESSION['room']['price2'];
                    }
                }
                else if($frm_data['isWeekend'])
                {

                }
                else if($frm_data['time_of_day'] == "Night Tour" && !$frm_data['is_22hrs'])
                {
                    if(!$frm_data['isWeekend'])
                    {
                        $package_type = "Monday - Thursday | Night Tour";
                        $payment = $_SESSION['room']['price1'];
                    }
                    else if($frm_data['isWeekend'])
                    {
                        $package_type = "Friday - Sunday | Night Tour";
                        $payment = $_SESSION['room']['price2'];
                    }
                }

                error_log("Room Price: " . $_SESSION['room']['price']);
                error_log("Payment: " . $payment);
                error_log("Difference in hours: " . $hours);

                $_SESSION['room']['payment'] = $payment;
                $_SESSION['room']['available'] = true;

                $result = json_encode(["status"=>'available', "days"=>$count_days, "payment"=>$payment]);
                echo $result;
            }
        }
        else
        {
            // Handle unexpected access or errors
            echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
        }
?>
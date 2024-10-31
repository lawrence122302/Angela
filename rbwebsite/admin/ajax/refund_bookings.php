<?php
    require('../inc/essentials.php');
    adminLogin();

    if(isset($_POST['get_bookings']))
    {
        $frm_data = filteration($_POST);

        $query = "SELECT bo.*, bd.* FROM booking_order bo 
            INNER JOIN booking_details bd ON bo.booking_id = bd.booking_id
            WHERE (bo.order_id LIKE ? OR bd.phonenum LIKE ? OR bd.user_name LIKE ? OR bo.trans_id LIKE ?) 
            AND (bo.booking_status=? AND bo.refund=?) ORDER BY bo.booking_id ASC";

        $res = select($query,["%$frm_data[search]%","%$frm_data[search]%","%$frm_data[search]%","%$frm_data[search]%","cancelled",0],'ssssss');
        $i=1;
        $table_data = "";

        if(mysqli_num_rows($res)==0)
        {
            echo"<b>No Data Found!</b>";
            exit;
        }

        while($data = mysqli_fetch_assoc($res))
        {
            $date = date("d-m-Y H:i:s",strtotime($data['datentime']));
            $checkin = date("d-m-Y H:i:s",strtotime($data['check_in']));
            $checkout = date("d-m-Y H:i:s",strtotime($data['check_out']));

            $date1 = new DateTime($checkin);
            $date2 = new DateTime($checkout);

            $package_type = "";

            $get_time = new DateTime($checkin);
            $hour = $get_time->format('H');
            $time_of_day = "";
            $time_of_day = ($hour >= 8 && $hour < 20) ? "Check In Day" : "Check In Night";

            $interval = $date1->diff($date2);
            $total_hours = ($interval->days * 24) + $interval->h;

            if($total_hours>=22)
            {
                if($time_of_day == "Check In Day")
                {
                    $package_type = "22 Hours Day Tour";
                }
                else if($time_of_day == "Check In Night")
                {
                    $package_type = "22 Hours Night Tour";
                }
            }
            else if($total_hours<=12)
            {
                if($time_of_day == "Check In Day")
                {
                    $package_type = "Day Tour";
                }
                else if($time_of_day == "Check In Night")
                {
                    $package_type = "Night Tour";
                }
            }

            if((strcasecmp($data['trans_id'], 'walk-in') != 0) && $data['trans_id']!='')
            {
                $gcash = "<span class='badge bg-primary'>
                    GCash: $data[trans_id]
                </span>";
            }
            else if (strcasecmp($data['trans_id'], 'walk-in') == 0)
            {
                $gcash = "<span class='badge bg-success'>
                    Walk-In
                </span>";
            }
            else if($data['trans_id']=='')
            {
                $gcash = "<span class='badge bg-success'>
                    Walk-In
                </span>";
            }

            $down_payment_confirmed_by = "";
            if (!empty($data['down_payment_confirmed_by'])) {
                $down_payment_confirmed_by = "<br>
                            {$data['down_payment_confirmed_by']} (Down Payment)";
            }

            $full_payment_confirmed_by = "";
            if (!empty($data['full_payment_confirmed_by'])) {
                $full_payment_confirmed_by = "<br>
                            {$data['full_payment_confirmed_by']} (Full Payment)";
            }

            $arrival_cancelled_by = "";
            if (!empty($data['arrival_cancelled_by'])) {
                $arrival_cancelled_by = "<br>
                            {$data['arrival_cancelled_by']} (Arrival Cancelled)";
            }

            if(!empty($down_payment_confirmed_by)
                || !empty($full_payment_confirmed_by)
                || !empty($arrival_cancelled_by))
            {
                $confirmed_by = "<br>
                    <br>
                    <b>Confirmed By</b>";
            } else {
                $confirmed_by = "";
            }

            $table_data.="
                <tr>
                    <td>$i</td>
                    <td>
                        <span class='badge bg-primary'>
                            Booking ID: $data[order_id]
                        </span>
                        <br>
                        $gcash
                        <br>
                        <b>Name:</b> $data[user_name]
                        <br>
                        <b>Phone No:</b> $data[phonenum]
                        $confirmed_by
                        $down_payment_confirmed_by
                        $full_payment_confirmed_by
                        $arrival_cancelled_by
                    </td>
                    <td>
                        <b>Accommodation:</b> $data[room_name]
                        <br>
                        <b>Package Type:</b> $package_type
                        <br>
                        <br>
                        <b>Date:</b> $date
                        <br>
                        <b>Check in:</b> $checkin
                        <br>
                        <b>Check out:</b> $checkout
                    </td>
                    <td>
                        <br>
                        <b>₱$data[trans_amt]</b>
                    </td>
                    <td>
                        <button type='button' onclick='refund_booking($data[booking_id])' class='btn btn-success btn-sm fw-bold shadow-none'>
                            <i class='bi bi-cash-stack  '></i> Refund
                        </button>
                    </td>
                </tr>
            ";

            $i++;
        }

        echo $table_data;
    }

    if(isset($_POST['refund_booking']))
    {
        $frm_data = filteration($_POST);

        $query = "UPDATE booking_order SET refund=?, refunded_by=? WHERE booking_id=?";
        $values = [1,$_SESSION['adminName'],$frm_data['booking_id']];
        $res = update($query,$values,'isi');

        echo $res;
    }
?>
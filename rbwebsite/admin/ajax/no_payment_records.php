<?php
    require('../inc/essentials.php');
    adminLogin();

    if(isset($_POST['get_bookings']))
    {
        $frm_data = filteration($_POST);

        $limit = 10;
        $page = $frm_data['page'];
        $start = ($page-1) * $limit;
        
        $query = "SELECT bo.*, bd.* 
            FROM booking_order bo 
            INNER JOIN booking_details bd 
            ON bo.booking_id = bd.booking_id

            WHERE (
                (bo.booking_status='payment_failed') 
            )
            AND (
                bo.order_id LIKE ? 
                OR bo.trans_id LIKE ?
                OR bd.user_name LIKE ? 
                OR bd.phonenum LIKE ? 
                OR bo.booking_status LIKE ? 
                OR bd.room_name LIKE ? 
                OR bo.package_type LIKE ? 
                OR bo.datentime LIKE ? 
                OR bo.check_in LIKE ? 
                OR bo.check_out LIKE ? 
            ) 
            AND bo.booking_status!='pending'

            ORDER BY bd.user_name, bo.booking_id DESC";

        $res = select(
            $query,
            [
                "%$frm_data[search]%",
                "%$frm_data[search]%",
                "%$frm_data[search]%",
                "%$frm_data[search]%",
                "%$frm_data[search]%",
                "%$frm_data[search]%",
                "%$frm_data[search]%",
                "%$frm_data[search]%",
                "%$frm_data[search]%",
                "%$frm_data[search]%"
            ],
            'ssssssssss'
        );

        $limit_query = $query . " LIMIT $start, $limit";

        $limit_res = select(
            $limit_query,
            [
                "%$frm_data[search]%",
                "%$frm_data[search]%",
                "%$frm_data[search]%",
                "%$frm_data[search]%",
                "%$frm_data[search]%",
                "%$frm_data[search]%",
                "%$frm_data[search]%",
                "%$frm_data[search]%",
                "%$frm_data[search]%",
                "%$frm_data[search]%"
            ],
            'ssssssssss'
        );

        $total_rows = mysqli_num_rows($res);
        
        if($total_rows==0)
        {
            $output = json_encode(['table_data'=>"<b>No Data Found!</b>", "pagination"=>'']);
            echo $output;
            exit;
        }

        $i=$start+1;
        $table_data = "";

        while($data = mysqli_fetch_assoc($limit_res))
        {
            $date = date("Y-m-d H:i:s",strtotime($data['datentime']));
            $checkin = date("Y-m-d H:i:s",strtotime($data['check_in']));
            $checkout = date("Y-m-d H:i:s",strtotime($data['check_out']));

            $refunded_status = "";
            if($data['booking_status']=='booked')
            {
                $status_bg = 'bg-success';
            }
            else if($data['booking_status']=='cancelled' && $data['refund']==1)
            {
                $status_bg = 'bg-danger';
                $refunded_status = "<span class='badge bg-warning text-dark'>refunded</span><br>";
            }
            else if($data['booking_status']=='payment_failed')
            {
                $status_bg = 'bg-danger';
            }
            else
            {
                $status_bg = 'bg-warning text-dark';
            }

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

            $full_payment = $data['trans_amt'];

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

            $id = $data['booking_id'];

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

            $booking_cancelled_by = "";
            if (!empty($data['booking_cancelled_by'])) {
                $booking_cancelled_by = "<br>
                {$data['booking_cancelled_by']} (Booking Cancelled)";
            }

            $arrival_confirmed_by = "";
            if (!empty($data['arrival_confirmed_by'])) {
                $arrival_confirmed_by = "<br>
                {$data['arrival_confirmed_by']} (Arrival)";
            }

            $arrival_cancelled_by = "";
            if (!empty($data['arrival_cancelled_by'])) {
                $arrival_cancelled_by = "<br>
                {$data['arrival_cancelled_by']} (Arrival Cancelled)";
            }

            $refunded_by = "";
            if (!empty($data['refunded_by'])) {
                $refunded_by = "<br>
                {$data['refunded_by']} (Refunded)";
            }

            if(!empty($down_payment_confirmed_by)
                || !empty($full_payment_confirmed_by)
                || !empty($booking_cancelled_by)
                || !empty($arrival_confirmed_by)
                || !empty($arrival_cancelled_by)
                || !empty($refunded_by))
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
                        $booking_cancelled_by
                        $arrival_confirmed_by
                        $arrival_cancelled_by
                        $refunded_by
                    </td>
                    <td style='max-width: 300px;'>
                        <b>Accommodation:</b> $data[room_name]
                        <br>
                        <b>Package Type:</b> $data[package_type]
                        <br>
                        <br>
                        <b>Total Pay:</b> ₱$data[total_pay]
                    </td>
                    <td>
                        <b>Date:</b> $date
                        <br>
                        <b>Check in:</b> $checkin
                        <br>
                        <b>Check out:</b> $checkout
                        <br>
                        <br>
                        <b>Paid:</b> ₱$data[trans_amt]
                        <br>
                        <br>
                    </td>
                    <td>
                        $refunded_status<span class='badge $status_bg'>$data[booking_status]</span>
                    </td>
                    <td>
                        <button type='button' onclick='download($id)' class='btn btn-outline-success btn-sm fw-bold shadow-none'>
                            <i class='bi bi-file-earmark-arrow-down'></i>
                        </button>
                    </td>
                </tr>
            ";

            $i++;
        }

        $pagination = "";

        if($total_rows>$limit)
        {
            $total_pages = ceil($total_rows/$limit);

            if($page!=1)
            {
                $disabled = ($page==$total_pages) ? "disabled" : "";
                $pagination .="<li class='page-item'>
                    <button onclick='change_page(1)' class='page-link shadow-none'>First</button>
                </li>";
            }

            $disabled = ($page==1) ? "disabled" : "";
            $prev = $page-1;
            $pagination .="<li class='page-item $disabled'>
                <button onclick='change_page($prev)' class='page-link shadow-none'>Prev</button>
            </li>";

            $disabled = ($page==$total_pages) ? "disabled" : "";
            $next = $page+1;
            $pagination .="<li class='page-item $disabled'>
                <button onclick='change_page($next)' class='page-link shadow-none'>Next</button>
            </li>";

            if($page!=$total_pages)
            {
                $disabled = ($page==$total_pages) ? "disabled" : "";
                $pagination .="<li class='page-item'>
                    <button onclick='change_page($total_pages)' class='page-link shadow-none'>Last</button>
                </li>";
            }
        }

        $output = json_encode(["table_data"=>$table_data,"pagination"=>$pagination]);

        echo $output;
    }
?>
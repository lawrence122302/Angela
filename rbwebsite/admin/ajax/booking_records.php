<?php
    require('../inc/db_config.php');
    require('../inc/essentials.php');
    adminLogin();

    if(isset($_POST['get_bookings']))
    {
        $frm_data = filteration($_POST);

        $limit = 10;
        $page = $frm_data['page'];
        $start = ($page-1) * $limit;
        
        $query = "SELECT bo.*, bd.* FROM booking_order bo 
            INNER JOIN booking_details bd ON bo.booking_id = bd.booking_id
            WHERE ((bo.booking_status='booked' AND bo.arrival=1) 
            OR (bo.booking_status='cancelled' AND bo.refund=1)
            OR (bo.booking_status='payment failed')) 
            OR (bo.order_id LIKE ? OR bd.phonenum LIKE ? OR bd.user_name LIKE ? OR bo.booking_status LIKE ? OR bo.trans_id LIKE ?)
            ORDER BY bo.booking_id DESC";

        $res = select($query,["%$frm_data[search]%","%$frm_data[search]%","%$frm_data[search]%","%$frm_data[search]%","%$frm_data[search]%"],'sssss');

        $limit_query = $query ." LIMIT $start,$limit";
        $limit_res = select($limit_query,["%$frm_data[search]%","%$frm_data[search]%","%$frm_data[search]%","%$frm_data[search]%","%$frm_data[search]%"],'sssss');

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
            $date = date("d-m-Y",strtotime($data['datentime']));
            $checkin = date("d-m-Y",strtotime($data['check_in']));
            $checkout = date("d-m-Y",strtotime($data['check_out']));

            $refunded_status = "";

            if($data['booking_status']=='booked')
            {
                $status_bg = 'bg-success';
            }
            else if($data['booking_status']=='cancelled')
            {
                $status_bg = 'bg-danger';
                $refunded_status = "<span class='badge bg-warning'>refunded</span><br>";
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

            $table_data.="
                <tr>
                    <td>$i</td>
                    <td>
                        <span class='badge bg-primary'>
                            Order ID: $data[order_id]
                        </span>
                        <br>
                        $gcash
                        <br>
                        <b>Name:</b> $data[user_name]
                        <br>
                        <b>Phone No:</b> $data[phonenum]
                    </td>
                    <td>
                        <b>Accommodation:</b> $data[room_name]
                        <br>
                        <b>Package Type:</b> $package_type
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
<?php
    require('../inc/db_config.php');
    require('../inc/essentials.php');
    adminLogin();

    if(isset($_POST['get_bookings']))
    {
        $frm_data = filteration($_POST);
        
        $query = "SELECT bo.*, bd.* FROM booking_order bo 
            INNER JOIN booking_details bd ON bo.booking_id = bd.booking_id
            WHERE (bo.order_id LIKE ? OR bd.phonenum LIKE ? OR bd.user_name LIKE ?) 
            AND (bo.booking_status=? AND bo.arrival=?) ORDER BY bo.booking_id ASC";

        $res = select($query,["%$frm_data[search]%","%$frm_data[search]%","%$frm_data[search]%","pending",0],'sssss');
        $i=1;
        $table_data = "";
        $down_payment = "";

        if(mysqli_num_rows($res)==0)
        {
            echo"<b>No Data Found!</b>";
            exit;
        }

        while($data = mysqli_fetch_assoc($res))
        {
            $date = date("d-m-Y",strtotime($data['datentime']));
            $checkin = date("d-m-Y",strtotime($data['check_in']));
            $checkout = date("d-m-Y",strtotime($data['check_out']));

            $down_payment = $data['total_pay'] * 0.5;

            if($data['trans_id']!='')
            {
                $gcash = "<span class='badge bg-primary'>
                    GCash: $data[trans_id]
                </span>";
            }
            else
            {
                $gcash = "<span class='badge bg-success'>
                    Walk-In
                </span>";
            }

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
                        <b>Room:</b> $data[room_name]
                        <br>
                        <b>Total Pay:</b> ₱$data[total_pay]
                        <br>
                        <br>
                        <b>Down Payment:</b> ₱$down_payment
                    </td>
                    <td>
                        <b>Check in:</b> $checkin
                        <br>
                        <b>Check in:</b> $checkout
                        <br>
                        <br>
                        <b>Paid:</b> ₱$data[trans_amt]
                        <br>
                        <br>
                        <b>Date:</b> $date
                    </td>
                    <td>
                        <button type='button' onclick='confirm_booking({$data['booking_id']}, {$down_payment})' class='btn text-white btn-sm fw-bold custom-bg shadow-none'>
                            <i class='bi bi-check'></i> Confirm Booking
                        </button>
                        <br>
                        <button type='button' onclick='cancel_booking($data[booking_id])' class='mt-2 btn btn-outline-danger btn-sm fw-bold shadow-none'>
                            <i class='bi bi-trash'></i> Cancel Booking
                        </button>
                    </td>
                </tr>
            ";

            $i++;
        }

        echo $table_data;
    }

    if(isset($_POST['confirm_booking']))
    {
        $frm_data = filteration($_POST);

        $query = "UPDATE booking_order SET booking_status=?, trans_amt=?, trans_status=? WHERE booking_id=?";
        $values = ['booked',$frm_data['down_payment'],'booked',$frm_data['booking_id']];
        $res = update($query,$values,'sssi');

        echo $res;
    }

    if(isset($_POST['cancel_booking']))
    {
        $frm_data = filteration($_POST);

        $query = "UPDATE booking_order SET booking_status=?, refund=? WHERE booking_id=?";
        $values = ['cancelled',0,$frm_data['booking_id']];
        $res = update($query,$values,'sii');

        echo $res;
    }
?>
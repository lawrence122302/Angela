<?php
    require('admin/inc/essentials.php');
    require('admin/inc/mpdf/vendor/autoload.php');

    session_name('user_session');
    session_start();

    if(!(isset($_SESSION['login']) && $_SESSION['login']==true))
    {
        redirect('index.php');
    }

    if(isset($_GET['gen_pdf']) && isset($_GET['id']))
    {
        $frm_data = filteration($_GET);

        $query = "SELECT bo.*, bd.*,uc.email FROM booking_order bo 
            INNER JOIN booking_details bd ON bo.booking_id = bd.booking_id
            INNER JOIN user_cred uc ON bo.user_id = uc.id
            WHERE ((bo.booking_status='booked' AND bo.arrival=1) 
            OR (bo.booking_status='cancelled' AND bo.refund=1)
            OR (bo.booking_status='payment failed')
            OR (bo.booking_status='reserved')
            OR (bo.booking_status='pending'))
            AND bo.booking_id  = '$frm_data[id]'";

        $res = mysqli_query($con,$query);

        $total_rows = mysqli_num_rows($res);
        
        if($total_rows==0)
        {
            header('location: index.php');
            exit;
        }

        $data = mysqli_fetch_assoc($res);
        $date = date("h:ia | d-m-Y",strtotime($data['datentime']));
        $checkin = date("d-m-Y",strtotime($data['check_in']));
        $checkout = date("d-m-Y",strtotime($data['check_out']));

        $table_data = "
        <h2>Booking Receipt</h2>
        <table border='1'>
            <tr>
                <td>Booking ID: $data[order_id]</td>
                <td>Booking Date: $date</td>
            </tr>
            <tr>
                <td colspan='2'>Status: $data[booking_status]</td>
            </tr>
            <tr>
                <td>Name:</b> $data[user_name]</td>
                <td>Email: $data[email]</td>
            </tr>
            <tr>
                <td>Phone Number:</b> $data[phonenum]</td>
                <td>Address: $data[address]</td>
            </tr>
            <tr>
                <td>Room Name:</b> $data[room_name]</td>
                <td>Cost: ₱$data[price]</td>
            </tr>
            <tr>
                <td>Check-in:</b> $checkin]</td>
                <td>Check-out: $checkout</td>
            </tr>
        ";

        if($data['booking_status']=='cancelled')
        {
            $refund = ($data['refund']) ? "Amount Refunded" : "Not Yet Refunded";

            $table_data.="<tr>
                <td>Amount Paid: ₱$data[trans_amt]</td>
                <td>Refund: $refund</td>
            </tr>";
        }
        else if($data['booking_status']=='payment failed')
        {
            $table_data.="<tr>
                <td>Transaction Amount: ₱$data[trans_amt]</td>
                <td>Failure Response: $data[trans_resp_msg]</td>
            </tr>";
        }
        else
        {
            $table_data.="<tr>
                <td>Room Number: $data[room_no]</td>
                <td>Amount Paid: ₱$data[trans_amt]</td>
            </tr>";
        }

        if (!empty($data['down_payment_confirmed_by'])) {
            $table_data .= '
            <tr>
                <td>Down Payment Confirmed By: ' . htmlspecialchars($data['down_payment_confirmed_by']) . '</td>
            </tr>';
        }
        
        if (!empty($data['full_payment_confirmed_by'])) {
            $table_data .= '
            <tr>
                <td>Full Payment Confirmed By: ' . htmlspecialchars($data['full_payment_confirmed_by']) . '</td>
            </tr>';
        }
        
        if (!empty($data['booking_cancelled_by'])) {
            $table_data .= '
            <tr>
                <td>Booking Cancelled By: ' . htmlspecialchars($data['booking_cancelled_by']) . '</td>
            </tr>';
        }
        
        if (!empty($data['arrival_confirmed_by'])) {
            $table_data .= '
            <tr>
                <td>Full Arrival Confirmed By: ' . htmlspecialchars($data['arrival_confirmed_by']) . '</td>
            </tr>';
        }
        
        if (!empty($data['arrival_cancelled_by'])) {
            $table_data .= '
            <tr>
                <td>Arrival Cancelled By: ' . htmlspecialchars($data['arrival_cancelled_by']) . '</td>
            </tr>';
        }
        
        if (!empty($data['refunded_by'])) {
            $table_data .= '
            <tr>
                <td>Refunded By: ' . htmlspecialchars($data['refunded_by']) . '</td>
            </tr>';
        }
        
        $table_data.="</table>";

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($table_data);
        $mpdf->Output($data['order_id'].'.pdf','D');
    }
    else
    {
        header('location: index.php');
    }
?>
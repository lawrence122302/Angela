<?php
    require('inc/essentials.php');
    require('inc/db_config.php');
    require('inc/mpdf/vendor/autoload.php');

    session_name('admin_session');
    adminLogin();

    if(isset($_GET['gen_pdf']) && isset($_GET['id']))
    {
        $frm_data = filteration($_GET);

// Log the filtered GET data
error_log("Filtered GET data: " . print_r($frm_data, true));

$query = "SELECT bo.*, bd.*, uc.email FROM booking_order bo 
    INNER JOIN booking_details bd ON bo.booking_id = bd.booking_id
    INNER JOIN user_cred uc ON bo.user_id = uc.id
    WHERE ((bo.booking_status='booked' AND bo.arrival=1) 
    OR (bo.booking_status='cancelled' AND bo.refund=1)
    OR (bo.booking_status='payment failed'))
    AND bo.booking_id  = '$frm_data[id]'";

// Log the query before execution
error_log("Query: " . $query);

$res = mysqli_query($con, $query);

// Log query result
if (!$res) {
    error_log("Query Error: " . mysqli_error($con));
} else {
    $total_rows = mysqli_num_rows($res);
    error_log("Total rows: " . $total_rows);
}

if ($total_rows == 0) {
    error_log("Redirecting to dashboard due to zero rows returned");
    header('location: dashboard.php');
    exit;
}

// Proceed with fetching and using $data
$data = mysqli_fetch_assoc($res);
error_log("Data fetched: " . print_r($data, true));

        
        $date = date("h:ia | d-m-Y",strtotime($data['datentime']));
        $checkin = date("d-m-Y",strtotime($data['check_in']));
        $checkout = date("d-m-Y",strtotime($data['check_out']));

        $table_data = "
        <h2>Booking Receipt</h2>
        <table border='1'>
            <tr>
                <td>Order ID: $data[order_id]</td>
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
                <td>Cost: ₱$data[price] per night</td>
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
        
        $table_data.="</table>";

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($table_data);
        $mpdf->Output($data['order_id'].'.pdf','D');
    }
    else
    {
        header('location: dashboard.php');
    }
?>
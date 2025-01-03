<?php
    require('inc/essentials.php');
    require('inc/mpdf/vendor/autoload.php');

    session_name('admin_session');
    adminLogin();

    if(isset($_GET['gen_pdf']) && isset($_GET['id']))
    {
        $frm_data = filteration($_GET);

        // Log the filtered GET data
        error_log("Filtered GET data: " . print_r($frm_data, true));

        $query = "SELECT bo.*, bd.*, uc.email 
          FROM booking_order bo 

          INNER JOIN booking_details bd ON bo.booking_id = bd.booking_id
          INNER JOIN user_cred uc ON bo.user_id = uc.id

        WHERE (
            (bo.booking_status='booked') 
            OR (bo.booking_status='reserved')
            OR (bo.booking_status='cancelled')
            OR (bo.booking_status='payment_failed')
        )

          AND bo.booking_id = '$frm_data[id]'";

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

        $date = date("d-m-Y | h:ia",strtotime($data['datentime']));
        $checkin = date("d-m-Y | h:ia",strtotime($data['check_in']));
        $checkout = date("d-m-Y | h:ia",strtotime($data['check_out']));

        $table_data = "
            <div style='padding: 14px; max-width: 670px; margin: auto; font-family: Arial, sans-serif; color: #333; background-color: #eaf2f2; border-radius: 5px;'>

            <!-- Header Section -->
            <div style='text-align: center; padding-bottom: 6px;'>
                <h1 style='font-size: 20px; color: #497082; margin: 0;'>Angela's Private Pool</h1>
                <p style='font-size: 10px; color: #497082;'>639560665043 | angelasprivatepool@gmail.com</p>
                <hr style='border: none; height: 1px; background-color: #b5c7c7; margin-top: 14px;'>
            </div>

            <!-- Booking Info -->
            <div style='text-align: center; padding: 6px 0; font-size: 14px; color: #2a5465;'>
                <strong>Accommodation Name:</strong> $data[room_name] &nbsp; | &nbsp; <strong>Booking ID:</strong> $data[order_id]
            </div>

            <!-- Status -->
            <div style='text-align: center; padding: 6px 0;'>
                <span style='display: inline-block; padding: 6px 14px; border-radius: 15px; background-color: #497082; color: white; font-weight: bold;'>
                    Status: $data[booking_status]
                </span>
            </div>

            <!-- Guest Details Section -->
            <div style='padding: 8px; background-color: #ffffff; border-radius: 5px; margin: 6px 0;'>
                <h3 style='color: #497082; border-bottom: 1px solid #b5c7c7; padding-bottom: 2px; margin: 0;'>Guest Details</h3>
                <p><strong>Name:</strong> $data[user_name]</p>
                <p><strong>Email:</strong> $data[email]</p>
                <p><strong>Phone Number:</strong> $data[phonenum]</p>
                <p><strong>Address:</strong> $data[address]</p>
            </div>

            <!-- Booking Date and Details Section -->
            <div style='padding: 8px; background-color: #ffffff; border-radius: 5px; margin: 6px 0;'>
                <h3 style='color: #497082; border-bottom: 1px solid #b5c7c7; padding-bottom: 2px; margin: 0;'>Booking Details</h3>
                <p><strong>Booking Date:</strong> $date</p>
                <p><strong>Check-in:</strong> $checkin</p>
                <p><strong>Check-out:</strong> $checkout</p>
                <p><strong>Package Type:</strong> $data[package_type]</p>
            </div>

            <!-- Payment Details Section -->
            <div style='padding: 8px; background-color: #ffffff; border-radius: 5px; margin: 6px 0;'>
                <h3 style='color: #497082; border-bottom: 1px solid #b5c7c7; padding-bottom: 2px; margin: 0;'>Payment Details</h3>
                <p><strong>Cost per Night:</strong> ₱$data[price]</p>
                <p><strong>Amount Paid:</strong> ₱$data[trans_amt]</p>";

        if ($data['booking_status'] == 'cancelled') {
            $refund = ($data['refund']) ? "Amount Refunded" : "Not Yet Refunded";
            $table_data .= "<p><strong>Refund:</strong> $refund</p>";
        } else if ($data['booking_status'] == 'payment failed') {
            $table_data .= "<p><strong>Failure Response:</strong> $data[trans_resp_msg]</p>";
        }

        $table_data .= "</div>

            <!-- Confirmed By Section -->
            <div style='padding: 8px; background-color: #ffffff; border-radius: 5px; margin: 6px 0;'>
                <h3 style='color: #497082; border-bottom: 1px solid #b5c7c7; padding-bottom: 2px; margin: 0;'>Confirmed By</h3>";

        if (!empty($data['down_payment_confirmed_by'])) {
            $table_data .= "<p><strong>Down Payment Confirmed By:</strong> {$data['down_payment_confirmed_by']}</p>";
        }

        if (!empty($data['full_payment_confirmed_by'])) {
            $table_data .= "<p><strong>Full Payment Confirmed By:</strong> {$data['full_payment_confirmed_by']}</p>";
        }

        if (!empty($data['booking_cancelled_by'])) {
            $table_data .= "<p><strong>Booking Cancelled By:</strong> {$data['booking_cancelled_by']}</p>";
        }

        if (!empty($data['arrival_confirmed_by'])) {
            $table_data .= "<p><strong>Full Arrival Confirmed By:</strong> {$data['arrival_confirmed_by']}</p>";
        }

        if (!empty($data['arrival_cancelled_by'])) {
            $table_data .= "<p><strong>Arrival Cancelled By:</strong> {$data['arrival_cancelled_by']}</p>";
        }

        if (!empty($data['refunded_by'])) {
            $table_data .= "<p><strong>Refunded By:</strong> {$data['refunded_by']}</p>";
        }

        $table_data .= "</div>

            <!-- Footer -->
            <div style='text-align: center; padding: 14px 0; color: #777; font-size: 10px;'>
                <p>Thank you for choosing us! We hope you have a pleasant stay.</p>
                <p style='font-size: 9px;'>For inquiries, contact us at angelasprivatepool@gmail.com</p>
            </div>
        </div>";

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($table_data);
        $mpdf->Output($data['order_id'].'.pdf','D');
    }
    else
    {
        header('location: dashboard.php');
    }
?>
<?php
    require('../admin/inc/essentials.php');

    date_default_timezone_set("Asia/Manila");
    session_name('user_session');
    session_start();

    if(!(isset($_SESSION['login']) && $_SESSION['login']==true))
    {
        redirect('index.php');
    }

    if (isset($_POST['edit_gcash'])) {
        $frm_data = filteration($_POST);
    
        // Log form data
        error_log("Form Data: " . print_r($frm_data, true));
    
        $q = "UPDATE booking_order SET trans_id=? WHERE booking_id=?";
        $v = [$frm_data['gcash'], $frm_data['edit_gcash_booking_id']];
    
        // Log query and values
        error_log("Query: " . $q);
        error_log("Values: " . print_r($v, true));
    
        if (update($q, $v, 'si')) {
            echo 1;
        } else {
            // Log update failure
            error_log("Update failed");
            echo 0;
        }
    }
?>
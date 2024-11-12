<?php
    require('../inc/essentials.php');
    superAdminLogin();

    if (isset($_POST['info_form'])) {
        $frm_data = filteration($_POST);
    
        // Log filtered data
        error_log("Filtered data: " . print_r($frm_data, true));
    
        $queryAdmin = "UPDATE admin_cred SET admin_name=? WHERE is_super_admin=?";
        $valuesAdmin = [$frm_data['admin_name'],1];
    
        // Log admin query and values
        error_log("Admin Query: " . $queryAdmin);
        error_log("Admin Values: " . print_r($valuesAdmin, true));
    
        if (update($queryAdmin, $valuesAdmin, 'ss')) {
            // Log session update
            $_SESSION['adminName'] = $frm_data['admin_name'];
            error_log("Session adminName updated to: " . $_SESSION['adminName']);
        } else {
            error_log("Admin update failed for admin_name: " . $frm_data['admin_name']);
            echo 0;
        }
    
        $queryContact = "UPDATE contact_details SET email=? WHERE sr_no=?";
        $valuesContact = [$frm_data['email'], 1];
    
        // Log contact query and values
        error_log("Contact Query: " . $queryContact);
        error_log("Contact Values: " . print_r($valuesContact, true));
    
        if (update($queryContact, $valuesContact, 'si')) {
            error_log("Contact update successful for email: " . $frm_data['email']);
            echo 1;
        } else {
            error_log("Contact update failed for email: " . $frm_data['email']);
            echo 0;
        }
    }

    if(isset($_POST['pass_form']))
    {
        $frm_data = filteration($_POST);

        if($frm_data['new_pass']!=$frm_data['confirm_pass'])
        {
            echo 'mismatch';
            exit;
        }

        $query = "UPDATE admin_cred SET admin_pass=? WHERE is_super_admin=? LIMIT 1";
        $values = [$frm_data['new_pass'],1];

        if(update($query,$values,'si'))
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }
?>
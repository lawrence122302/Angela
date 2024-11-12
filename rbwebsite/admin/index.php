<?php
    require('inc/essentials.php');

    session_name('admin_session');
    session_start();

    // Checks for existing admin login session

    if((isset($_SESSION['adminLogin']) && $_SESSION['adminLogin']==true))
    {
        redirect('dashboard.php');
    }

    // Shows alert

    // Check and Show Alerts
    $alert = isset($_GET['alert']) ? $_GET['alert'] : false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Panel</title>
    <?php require('inc/links.php'); ?>
    <style>
        div.login-form {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
        }
    </style>
</head>
<body class="bg-light">
    
    <div class="login-form text-center rounded bg-white shadow overflow-hidden sticky-top" style="z-index: 9;">
        <form method="POST">
            <h4 class="bg-dark text-white py-3">Admin Login Panel</h4>
            <div class="p-4">
                <div class="mb-3">
                    <input name="admin_name" required type="text" class="form-control shadow-none text-center" placeholder="Admin Name">
                </div>
                <div class="mb-4">
                    <input name="admin_pass" required type="password" class="form-control shadow-none text-center" placeholder="Password">
                </div>
                <div class="mb-2">
                    <button name="login" type="submit" class="btn text-white custom-bg shadow-none">Login</button>
                </div>
                <div>
                    <button type="button" class="btn text-secondary text-decoration-none shadow-none p-0" data-bs-toggle="modal" data-bs-target="#forgotModal" data-bs-dismiss="modal">
                        Forgot Password?
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="forgotModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="forgot-form">
                    <div class="modal-header">
                    <h5 class="modal-title d-flex align-items-center">
                        <i class="bi bi-person-circle fs-3 me-2"></i> Forgot Password
                    </h5>
                    </div>
                    <div class="modal-body">
                    <span class="badge rounded-pill bg-light text-dark mb-3 text-wrap lh-base">
                        Note: A link will be sent to your email to reset your password!
                    </span>
                    <div class="mb-4">
                        <label class="form-label">Admin Name</label>
                        <input type="text" name="admin_name_account" required class="form-control shadow-none">
                    </div>
                    <div class="mb-2 text-end">
                        <button type="button" class="btn shadow-none p-0 me-2" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">
                        Cancel
                        </button>
                        <button type="submit" class="btn btn-dark shadow-none">Send Link</button>
                    </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php

        if(isset($_POST['login']))
        {
            $frm_data = filteration($_POST);
            
            $query = "SELECT * FROM admin_cred WHERE admin_name=? AND BINARY admin_pass=?";
            $values = [$frm_data['admin_name'],$frm_data['admin_pass']];

            $res = select($query,$values,"ss");
            if($res->num_rows==1)
            {
                $row = mysqli_fetch_assoc($res);
                if
                ($row['status'] == 0)
                {
                    alert('error', 'Account inactive - Please contact the owner!');
                }
                else
                {
                    $_SESSION['adminLogin'] = true;
                    $_SESSION['adminId'] = $row['sr_no'];
                    $_SESSION['adminName'] = $row['admin_name'];
                    $_SESSION['status'] = $row['status'];
                    $_SESSION['isSuperAdmin'] = $row['is_super_admin'];

                    // Admin login session validation

                    $session_token = bin2hex(random_bytes(16)); // Generate a unique token
                    $_SESSION['session_token'] = $session_token;

                    $query = "UPDATE admin_cred SET session_token=? WHERE sr_no=?";
                    $values = [$session_token, $row['sr_no']];
                    update($query, $values, 'si');


                    redirect('dashboard.php');
                }
            }
            else
            {
                alert('error','Login failed - Invalid username or password!');
            }
        }

        // Check and Show Alerts

        if ($alert) {
            switch ($alert) {
                case 'another_login':
                    alert('error', 'Another login detected!');
                    break;
                case 'account_deactivated':
                    alert('error', 'Account deactivated!');
                    break;
                default:
                    alert('error', 'An unknown error occurred!');
                    break;
            }
        }
    ?>


    <?php require('inc/scripts.php'); ?>

    <script src="scripts/admin_forgot_password.js"></script>

</body>
</html>
<?php
    require('inc/essentials.php');
    adminLogin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Dashboard</title>
    <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

    <?php
        require('inc/header.php');

        // Query to check if the system is set to shutdown mode
        $is_shutdown = mysqli_fetch_assoc(mysqli_query($con,"SELECT shutdown FROM settings"));

        // Query to count different booking statuses: pending down payments, new bookings, and cancelled bookings needing refunds
        $current_bookings = mysqli_fetch_assoc(mysqli_query($con, "SELECT 
            COUNT(CASE 
                WHEN bo.booking_status = 'pending' 
                    AND bo.arrival = 0 
                    AND bo.trans_amt < bd.total_pay 
                THEN 1 
            END) AS confirm_down_payment,
            COUNT(CASE 
                WHEN bo.booking_status = 'reserved' 
                    AND bo.arrival = 0 
                THEN 1 
            END) AS new_bookings,
            COUNT(CASE 
                WHEN bo.booking_status = 'cancelled' 
                    AND bo.refund = 0 
                THEN 1 
            END) AS refund_bookings,
            COUNT(CASE 
                WHEN bo.booking_status = 'pending' 
                    AND bo.arrival = 0 
                    AND bo.trans_amt >= bd.total_pay 
                THEN 1 
            END) AS confirm_full_payment
        FROM booking_order bo 
        INNER JOIN booking_details bd ON bo.booking_id = bd.booking_id"));

        // Query to count the number of unread user queries
        $unread_queries = mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(sr_no) AS count 
            FROM user_queries WHERE seen=0"));

        // Query to count the number of unread user reviews
        $unread_reviews = mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(sr_no) AS count 
            FROM rating_review WHERE seen=0"));

        // Query to count the total number of users, and categorize them into active, inactive, and unverified users
        $current_users = mysqli_fetch_assoc(mysqli_query($con,"SELECT 
            COUNT(id) AS total,
            COUNT(CASE WHEN status=1 THEN 1 END) AS active,
            COUNT(CASE WHEN status=0 THEN 1 END) AS inactive,
            COUNT(CASE WHEN is_verified=0 THEN 1 END) AS unverified
            FROM user_cred"));
    ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3>Dashboard</h3>
                    <?php
                        if($is_shutdown['shutdown'])
                        {
                            echo<<<data
                                <h6 class="badge bg-danger py-2 px-3 rounded">Shutdown Mode is Active!</h6>
                            data;
                        }
                    ?>
                </div>

                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <a href="new_bookings.php" class="text-decoration-none">
                            <div class="card text-center text-warning p-3">
                                <h6>Confirm Down Payment</h6>
                                <h1 class="mt-2 mb-0"><?php echo $current_bookings['confirm_down_payment'] ?></h1>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="confirm_full_payment.php" class="text-decoration-none">
                            <div class="card text-center text-success p-3">
                                <h6>Confirm Full Payment</h6>
                                <h1 class="mt-2 mb-0"><?php echo $current_bookings['confirm_full_payment'] ?></h1>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="confirmed_bookings.php" class="text-decoration-none">
                            <div class="card text-center text-success p-3">
                                <h6>Confirm Arrival</h6>
                                <h1 class="mt-2 mb-0"><?php echo $current_bookings['new_bookings'] ?></h1>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="refund_bookings.php" class="text-decoration-none">
                            <div class="card text-center text-warning p-3">
                                <h6>Refund Bookings</h6>
                                <h1 class="mt-2 mb-0"><?php echo $current_bookings['refund_bookings'] ?></h1>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5>Booking Analytics</h5>
                    <div class="d-flex flex-row justify-content-end">
                        <select id="accommodationSelect" class="form-select shadow-none bg-light w-auto mx-2" onchange="booking_analytics()">
                            <option value="all">All Accommodations</option>
                            <?php
                                // Modified query to order by 'removed' first
                                $res = selectAll('rooms ORDER BY removed ASC, id ASC');
                                
                                // Loop through the fetched data and generate the options
                                while ($row = mysqli_fetch_assoc($res)) {
                                    $name = $row['name'];

                                    // Append "(Removed)" if 'removed' is 1
                                    if ($row['removed'] == 1) {
                                        $name .= " (Removed)";
                                    }

                                    echo '<option value="' . $row['id'] . '">' . $name . '</option>';
                                }
                            ?>
                        </select>
                        <select id="periodSelect" class="form-select shadow-none bg-light w-auto ml-2" onchange="booking_analytics()">
                            <option value="1">Past 30 Days</option>
                            <option value="2">Past 90 Days</option>
                            <option value="3">Past 1 Year</option>
                            <option value="4">All Time</option>
                        </select>
                    </div>
                </div>


                <div class="row mb-3">
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-primary p-3">
                            <h6>Total Bookings</h6>
                            <h1 class="mt-2 mb-0" id="total_bookings">0</h1>
                            <h4 class="mt-2 mb-0" id="total_amt">₱0</h4>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-success p-3">
                            <h6>Active Bookings</h6>
                            <h1 class="mt-2 mb-0" id="active_bookings">0</h1>
                            <h4 class="mt-2 mb-0" id="active_amt">₱0</h4>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-danger p-3">
                            <h6>Refunded Bookings</h6>
                            <h1 class="mt-2 mb-0" id="cancelled_bookings">0</h1>
                            <h4 class="mt-2 mb-0" id="cancelled_amt">₱0</h4>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5>User, Queries, Reviews, Analytics</h5>
                    <select class="form-select shadow-none bg-light w-auto" onchange="user_analytics(this.value)">
                        <option value="1">Past 30 Days</option>
                        <option value="2">Past 90 Days</option>
                        <option value="3">Past 1 Year</option>
                        <option value="4">All Time</option>
                    </select>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-success p-3">
                            <h6>New Registration</h6>
                            <h1 class="mt-2 mb-0" id="total_new_reg">0</h1>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-primary p-3">
                            <h6>Queries</h6>
                            <h1 class="mt-2 mb-0" id="total_queries">0</h1>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-primary p-3">
                            <h6>Reviews</h6>
                            <h1 class="mt-2 mb-0" id="total_reviews">0</h1>
                        </div>
                    </div>
                </div>

                <h5>Users</h5>
                <div class="row mb-3">
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-info p-3">
                            <h6>Total</h6>
                            <h1 class="mt-2 mb-0"><?php echo $current_users['total'] ?></h1>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-success p-3">
                            <h6>Active</h6>
                            <h1 class="mt-2 mb-0"><?php echo $current_users['active'] ?></h1>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-warning p-3">
                            <h6>Inactive</h6>
                            <h1 class="mt-2 mb-0"><?php echo $current_users['inactive'] ?></h1>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-danger p-3">
                            <h6>Unverified</h6>
                            <h1 class="mt-2 mb-0"><?php echo $current_users['unverified'] ?></h1>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php require('inc/scripts.php'); ?>
    <script src="scripts/dashboard.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - Booking Status</title>
</head>
<body class="bg-light">

    <?php require('inc/navbar.php'); ?>

    <div class="container">
        <div class="row">

            <div class="col-12 my-5 mb-3 px-4">
                <h2 class="fw-bold">Payment Status</h2>
            </div>
            
            <?php
                $frm_data = filteration($_GET);

                if(!(isset($_SESSION['login']) && $_SESSION['login']==true))
                {
                    $query = "SELECT bo.*,bd.*,uc.* 
                        FROM booking_order bo INNER JOIN booking_details bd ON bo.booking_id = bd.booking_id
                        INNER JOIN user_cred uc ON bo.user_id = uc.id
                        WHERE (uc.email=? AND uc.phonenum=?)
                        AND ((bo.booking_status='booked') 
                        OR (bo.booking_status='pending')
                        OR (bo.booking_status='reserved')
                        OR (bo.booking_status='cancelled')
                        OR (bo.booking_status='payment failed')) 
                        ORDER BY bo.booking_id DESC
                    ";

                    $result = select($query,[$frm_data['email_mob'],$frm_data['gcash_ref']],'ss');

                    if(mysqli_num_rows($result) > 0)
                    {
                        while($data = mysqli_fetch_assoc($result))
                        {
                            $date = date("d-m-Y",strtotime($data['datentime']));
                            $checkin = date("d-m-Y",strtotime($data['check_in']));
                            $checkout = date("d-m-Y",strtotime($data['check_out']));

                            $status_bg = "";
                            
                            $login = 0;
                            if(isset($_SESSION['login']) && $_SESSION['login']==true)
                            {
                                $login = 1;
                            }

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
                            
                            $btn = "";

                            if($data['booking_status']=='booked')
                            {
                                $status_bg = "bg-success";

                                if($data['arrival']==1)
                                {
                                    $btn="<a onclick='checkLogin($login)' class='btn btn-dark btn-sm shadow-none'>Download PDF</a>";

                                    if($data['rate_review']==0)
                                    {
                                        $btn.="<button type='button' onclick='checkLogin($login)' data-bs-toggle='modal' data-bs-target='#reviewModal' class='btn btn-dark btn-sm shadow-none ms-2'>Rate & Review</button>";
                                    }
                                }
                                else
                                {
                                    $btn="<button onclick='checkLogin($login)' type='button' class='btn btn-danger btn-sm shadow-none'>Cancel</button>";
                                }
                            }
                            else if($data['booking_status']=='cancelled')
                            {
                                $status_bg = "bg-danger";

                                if($data['refund']==0)
                                {
                                    $btn="<span class='badge bg-primary'>Refund in process!</span>";
                                }
                                else
                                {
                                    $btn="<a onclick='checkLogin($login)' class='btn btn-dark btn-sm shadow-none'>Download PDF</a>";
                                }
                            }
                            else
                            {
                                $status_bg = "bg-info";
                                $btn="<a onclick='checkLogin($login)' class='btn btn-dark btn-sm shadow-none'>Download PDF</a>";
                            }

                            echo<<<bookings
                                <div class='col-md-4 px-4 mb-4'>
                                    <div class='bg-white p-3 rounded shadow-sm'>
                                        <h5 class='fw-bold'>$data[room_name]</h5>
                                        <p>₱$data[price] per night</p>
                                        <p>
                                            <b>Check in: </b> $checkin <br>
                                            <b>Check out: </b> $checkout
                                        </p>
                                        <p>
                                            <b>Amount: </b> ₱$data[price] <br>
                                            <b>Order ID: </b> $data[order_id] <br>
                                            <b>Date: </b> $date
                                        </p>
                                        <p>
                                            $gcash
                                            <span class='badge $status_bg'>$data[booking_status]</span>
                                        </p>
                                        $btn
                                    </div>
                                </div>
                            bookings;
                        }
                    }
                    else
                    {
                        echo<<<data
                            <div class="col-12 px-4">
                                <p class="fw-bold alert alert-warning">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    No Bookings Found.
                                    <br><br>
                                    <a href='bookings.php'>Go to Bookings<a/>
                                </p>
                            </div>
                        data;
                    }
                }
                else if(isset($_SESSION['login']) && $_SESSION['login']==true)
                {
                    redirect('bookings.php');
                }
                else
                {
                    $booking_q = "SELECT bo.*, bd.* FROM booking_order bo
                        INNER JOIN booking_details bd ON bo.booking_id = bd.booking_id
                        WHERE bo.order_id=? AND bo.user_id=?";

                    $booking_res = select($booking_q,[$frm_data['order'],$_SESSION['uId']],'si');

                    $booking_fetch = mysqli_fetch_assoc($booking_res);

                    if($booking_fetch['trans_status']=="booked")
                    {
                        echo<<<data
                            <div class="col-12 px-4">
                                <p class="fw-bold alert alert-success">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Payment done! Booking successful.
                                    <br><br>
                                    <a href='bookings.php'>Go to Bookings<a/>
                                </p>
                            </div>
                        data;
                    }
                    else if($booking_fetch['trans_status']=="pending")
                    {
                        echo<<<data
                            <div class="col-12 px-4">
                                <p class="fw-bold alert alert-warning">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    Payment pending.
                                    <br><br>
                                    <a href='bookings.php'>Go to Bookings<a/>
                                </p>
                            </div>
                        data;
                    }
                    else if($booking_fetch['trans_status']=="reserved")
                    {
                        echo<<<data
                            <div class="col-12 px-4">
                                <p class="fw-bold alert alert-warning">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    Booking Reserved.
                                    <br><br>
                                    <a href='bookings.php'>Go to Bookings<a/>
                                </p>
                            </div>
                        data;
                    }
                    else if($booking_fetch['trans_status']=="payment failed")
                    {
                        echo<<<data
                            <div class="col-12 px-4">
                                <p class="fw-bold alert alert-danger">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    Payment failed.
                                    <br><br>
                                    <a href='bookings.php'>Go to Bookings<a/>
                                </p>
                            </div>
                        data;
                    }
                }
            ?>
        </div>
    </div>

    <?php require('inc/footer.php'); ?>
</body>
</html>
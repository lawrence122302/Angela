<?php
    require('../admin/inc/essentials.php');

    date_default_timezone_set("Asia/Manila");

    session_name('user_session');
    session_start();

    if(isset($_GET['fetch_rooms']))
    {
        // check availability data decode
        $chk_avail = json_decode($_GET['chk_avail'],true);

        // checkin and checkout validation
        if($chk_avail['checkin']!='' && $chk_avail['checkout']!='')
        {
            // check in and out validations
            $today_date = new DateTime(date("Y-m-d"));
            $checkin_date = new DateTime($chk_avail['checkin']);
            $checkout_date = new DateTime($chk_avail['checkout']);

            if($checkin_date == $checkout_date)
            {
                echo "<div class='alert alert-warning text-center' role='alert'>
                    <strong>Notice:</strong> Check-in and Check-out dates cannot be the same.
                </div>";
                exit;
            }
            else if($checkout_date < $checkin_date)
            {
                echo "<div class='alert alert-warning text-center' role='alert'>
                    <strong>Notice:</strong> Check-out date cannot be earlier than Check-in date.
                </div>";
                exit;
            }
            else if($checkin_date < $today_date)
            {
                echo "<div class='alert alert-warning text-center' role='alert'>
                    <strong>Notice:</strong> Check-in date cannot be in the past.
                </div>";
                exit;
            }
        }

        // guests data decode
        $guests = json_decode($_GET['guests'],true);
        $adults = ($guests['adults']!='') ? $guests['adults'] : 0;

        // facilities data decode
        $facility_list = json_decode($_GET['facility_list'],true);

        // count no. of rooms and output variable to store room cards
        $count_rooms = 0;
        $output = "";

        // fetching settings table to check website shutdown
        $settings_q = "SELECT * FROM settings WHERE sr_no=1";
        $settings_r = mysqli_fetch_assoc(mysqli_query($con,$settings_q));

        // query for rooms card with guests filter
        $room_res = select("SELECT * FROM rooms WHERE adult>=? AND status=? AND removed=? ORDER BY id DESC", [$adults,1,0],'iii');

        while($room_data = mysqli_fetch_assoc($room_res))
        {
            // check availability filter
            if($chk_avail['checkin']!='' && $chk_avail['checkout']!='')
            {
                $tb_query = "SELECT COUNT(*) AS total_bookings FROM booking_order
                    WHERE (booking_status=? OR booking_status=? OR booking_status=?) AND room_id=?
                    AND check_out > ? AND check_in < ?";

                $values = ['booked','reserved','pending',$room_data['id'],$chk_avail['checkin'],$chk_avail['checkout']];
                $tb_fetch = mysqli_fetch_assoc(select($tb_query,$values,'sssiss'));

                // Check for blocked dates
                $checkin_date_only = explode("T", $chk_avail['checkin'])[0]; // Extract the date part (YYYY-MM-DD)
                $checkout_date_only = explode("T", $chk_avail['checkout'])[0]; // Extract the date part (YYYY-MM-DD)

                // Error log the values
                error_log("Check-in Date Only: " . $checkin_date_only);
                error_log("Original Check-in Value: " . $chk_avail['checkin']);
                error_log("Checkout Date Only: " . $checkout_date_only);
                error_log("Original Checkout Value: " . $chk_avail['checkout']);
                error_log("Time of Day: " . $chk_avail['time_of_day']); // Log time_of_day

                // Always check if check-in date is in blocked dates
                $blocked_checkin_query = "SELECT COUNT(*) AS blocked_count FROM blocked_dates
                                        WHERE date=? AND room_id=? AND status=1";
                $blocked_checkin_values = [$checkin_date_only, $room_data['id']];
                $blocked_checkin_fetch = mysqli_fetch_assoc(select($blocked_checkin_query, $blocked_checkin_values, 'si'));

                if ($blocked_checkin_fetch['blocked_count'] > 0) {
                    continue;
                }

                // Only check blocked dates for check-out if it's a Night Tour
                if ($chk_avail['time_of_day'] == "Night Tour") {
                    $blocked_checkout_query = "SELECT COUNT(*) AS blocked_count FROM blocked_dates
                                            WHERE date=? AND room_id=? AND status=1";
                    $blocked_checkout_values = [$checkout_date_only, $room_data['id']];
                    $blocked_checkout_fetch = mysqli_fetch_assoc(select($blocked_checkout_query, $blocked_checkout_values, 'si'));

                    if ($blocked_checkout_fetch['blocked_count'] > 0) {
                        continue;
                    }
                }

                // Check room availability based on quantity and bookings
                if (($room_data['quantity'] - $tb_fetch['total_bookings']) <= 0) {
                    continue;
                }
            }

            // get facilities of room with filters
            $fac_count = 0;

            $fac_q = mysqli_query($con,"SELECT f.name, f.id FROM facilities f 
                INNER JOIN room_facilities rfac ON f.id = rfac.facilities_id 
                WHERE rfac.room_id = $room_data[id]");

            $facilities_data = "";
            while($fac_row = mysqli_fetch_assoc($fac_q))
            {
                if(in_array($fac_row['id'],$facility_list['facilities']))
                {
                    $fac_count++;
                }

                $facilities_data .="<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                    $fac_row[name]
                </span>";
            }

            if(count($facility_list['facilities'])!=$fac_count)
            {
                continue;
            }

            // get features of room
            $fea_q = mysqli_query($con,"SELECT f.name FROM features f 
                INNER JOIN room_features rfea ON f.id = rfea.features_id 
                WHERE rfea.room_id = $room_data[id]");

            $features_data = "";
            while($fea_row = mysqli_fetch_assoc($fea_q))
            {
                $features_data .="<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                    $fea_row[name]
                </span>";
            }

            // get thumbnail of image
            $room_thumb = ROOMS_IMG_PATH."thumbnail.jpg";
            $thumb_q = mysqli_query($con,"SELECT * FROM room_images 
                WHERE room_id=$room_data[id] 
                AND thumb=1");

            if(mysqli_num_rows($thumb_q)>0)
            {
                $thumb_res = mysqli_fetch_assoc($thumb_q);
                $room_thumb = ROOMS_IMG_PATH.$thumb_res['image'];
            }

            $book_btn = "";
            if(!$settings_r['shutdown'])
            {
                $login = 0;
                if(isset($_SESSION['login']) && $_SESSION['login']==true)
                {
                    $login = 1;
                }
                $book_btn = "<button onclick='checkLoginToBook($login,$room_data[id])' class='btn btn-sm w-100 text-white custom-bg shadow-none mb-2'>Book Now</button>";
            }

            // print room card
            $output.="
                <div class='card mb-4 border-0 shadow'>
                    <div class='row g-0 p-3 d-flex justify-content-center align-items-center'>
                        <div class='col-md-4 mb-lg-0 mb-md-0 mb-3'>
                            <div class='aspect-ratio-16-9'>
                                <img src='$room_thumb' class='img-fluid rounded'>
                            </div>
                        </div>
                        <div class='col-md-4 px-lg-3 px-md-3 px-0'>
                            <h5 class='mb-3'>$room_data[name]</h5>
                            <div class='features mb-3'>
                                <h6 class='mb-1'>Amenities</h6>
                                $features_data
                            </div>
                            <div class='facilities mb-3'>
                                <h6 class='mb-1'>Inclusions</h6>
                                $facilities_data
                            </div>
                            <div class='guest mb-3'>
                                <h6 class='mb-1'>Guests</h6>
                                <span class='badge rounded-pill bg-light text-dark text-wrap'>
                                    $room_data[adult] Pax
                                </span>
                            </div>
                        </div>
                        <div class='col-md-4 mt-lg-0 mt-md-0'>
                            <div class='mb-3'>
                                <h6 class='mb-1'>Monday - Thursday</h6>
                                ₱$room_data[price] - Day/Night Swim
                                <br>
                                ₱$room_data[price2] - 22 Hours
                            </div>
                            <div class='mb-3'>
                                <h6 class='mb-1'>Friday - Sunday</h6>
                                ₱$room_data[price3] - Day/Night Swim
                                <br>
                                ₱$room_data[price4] - 22 Hours
                            </div>
                            <div class='w-50'>
                                $book_btn
                                <a href='room_details.php?id=$room_data[id]' class='btn btn-sm w-100 btn-outline-dark shadow-none'>More details</a>
                            </div>
                        </div>
                    </div>
                </div>
            ";
            $count_rooms++;
        }

        if($count_rooms>0)
        {
            echo $output;
        }
        else
        {
            echo "<div class='alert alert-warning text-center' role='alert'>
                <strong>Notice:</strong> No rooms available at the moment. Please try different dates or room types.
            </div>";
        }
    }
?>
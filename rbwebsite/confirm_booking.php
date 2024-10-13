<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - Confirm Booking</title>
</head>
<body class="bg-light">

    <?php require('inc/navbar.php'); ?>

    <?php
        /*
            Check room id from url is present or not
            Shutdown mode is active or not
            User is logged in or not
        */
        if(!isset($_GET['id']) || $settings_r['shutdown']==true)
        {
            redirect('rooms.php');
        }
        else if(!(isset($_SESSION['login']) && $_SESSION['login']==true))
        {
            redirect('rooms.php');
        }

        // filter and get room and user data
        $data = filteration($_GET);

        $room_res = select("SELECT * FROM rooms WHERE id=? AND status=? AND removed=?", [$data['id'],1,0],'iii');

        if(mysqli_num_rows($room_res)==0)
        {
            redirect('rooms.php');
        }

        $room_data = mysqli_fetch_assoc($room_res);

        $_SESSION['room'] = [
            "id" => $room_data['id'],
            "name" => $room_data['name'],
            "price" => $room_data['price'],
            "price2" => $room_data['price2'],
            "price3" => $room_data['price3'],
            "price4" => $room_data['price4'],
            "payment" => null,
            "available" => false,
        ];

        $user_res = $u_exist = select("SELECT * FROM user_cred WHERE id=? LIMIT 1",[$_SESSION['uId']],"i");
        $user_data = mysqli_fetch_assoc($user_res);
    ?>

    

    <div class="container">
        <div class="row">

            <div class="col-12 my-5 mb-4 px-4">
                <h2 class="fw-bold">Confirm Booking</h2>
                <div style="font-size: 14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">Home</a>
                    <span class="text-secondary"> > </span>
                    <a href="rooms.php" class="text-secondary text-decoration-none">Rooms</a>
                    <span class="text-secondary"> > </span>
                    <a href="#" class="text-secondary text-decoration-none">Confirm</a>
                </div>
            </div>

            <div class="col-lg-7 col-md-12 px-4">
                <?php
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

                    echo<<<data
                        <div class="card p-3 shadow-sm rounded">
                            <img src="$room_thumb" class="img-fluid rounded mb-3" style='max-height: 50vh;'>
                            <div class="mb-4">
                                <h6 class="mb-1">Monday - Thursday</h6>
                                ₱$room_data[price] - Day/Night Swim
                                <br>
                                ₱$room_data[price2] - 22 Hours
                            </div>
                            <div class="mb-4">
                                <h6 class="mb-1">Friday - Sunday</h6>
                                ₱$room_data[price3] - Day/Night Swim
                                <br>
                                ₱$room_data[price4] - 22 Hours
                            </div>
                        </div>
                    data;
                ?>
            </div>

            <div class="col-lg-5 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <form id="booking_form">
                            <h6 class="mb-3">Booking Details</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Name</label>
                                    <input name="name" type="text" value="<?php echo $user_data['name'] ?>" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input name="phonenum" type="number" value="<?php echo $user_data['phonenum'] ?>" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control shadow-none" rows="1" required><?php echo $user_data['address'] ?></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Check-in</label>
                                    <input name="checkin" onchange="check_availability()" type="date" class="form-control shadow-none mb-2" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Check-out</label>
                                    <select name="checkout" onchange="check_availability()" class="form-select shadow-none">
                                        <option value="">Select Package Type</option>
                                        <option value="1">Day Tour (08:00am - 06:00pm)</option>
                                        <option value="2">Night Tour (08:00pm - 06:00am)</option>
                                        <option value="3">22 Hours Day Tour (08:00am - 06:00am)</option>
                                        <option value="4">22 Hours Night Tour (08:00pm - 06:00pm)</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <div class="spinner-border text-info mb-3 d-none" id="info_loader" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>

                                    <div id="pay_info" class='alert alert-warning text-center' role='alert'>
                                        <strong>Notice:</strong> Provide check-in & check-out date to proceed.
                                    </div>

                                    <button type="button" name="pay_now" class="btn w-100 text-white custom-bg shadow-none mb-1" onclick="openModal()" disabled>Pay Now</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

   <!-- Pay Now Modal -->
    <div class="modal fade" id="pay-now" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="pay_now_form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Payment</h5>
                    </div>
                    <div class="modal-body">

                    <div class="row">
                        <div class="col text-center mb-3">
                            <img src="images/settings/gcash.jpeg" class="img-fluid mb-2" style="max-height: 80vh;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col text-center mb-3">
                            <span class="badge rounded-pill bg-dark text-white text-wrap">Gcash: 123456778</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label fw-bold">Paid Amount</label>
                            <select name="paid_amount" class="form-control shadow-none" required>
                                <option value="">Select Payment</option>
                                <option value="1">50% Down Payment</option>
                                <option value="2">Full Payment</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label fw-bold">Mode of Payment</label><br>
                            <label>
                                <input type="radio" name="g_reference" value="walk-in">
                                Walk-In
                            </label><br>
                            <label>
                                <input type="radio" name="g_reference" id="customRadio" value="">
                                GCash
                            </label>
                            <input type="text" id="customValue" name="customValue" class="form-control shadow-none" placeholder="Enter GCash Reference" disabled>
                        </div>
                    </div>

                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn custom-bg text-white shadow-none">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php require('inc/footer.php'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const radioButtons = document.querySelectorAll('input[name="g_reference"]');
            const customInput = document.getElementById('customValue');
            const customRadio = document.getElementById('customRadio');

            radioButtons.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === '') {
                customInput.disabled = false;
                customInput.focus();  // Automatically focus the input field
                } else {
                customInput.disabled = true;
                customInput.value = ''; // Clear the input if not custom
                }
            });
            });

            customInput.addEventListener('input', function() {
            customRadio.value = customInput.value;
            });
        });

        let booking_form = document.getElementById('booking_form');
        let pay_now_form = document.getElementById('pay_now_form');
        let info_loader = document.getElementById('info_loader');
        let pay_info = document.getElementById('pay_info');

        function check_availability()
        {
            let checkin_val = booking_form.elements['checkin'].value;
            let checkout_val = booking_form.elements['checkout'].value;

            // Needed to convert check-in and checkout date
            let checkin_date1 = new Date(checkin_val + "T00:00:00");
            let checkin_date2 = new Date(checkin_val + "T00:00:00");

            // Debug check-in dates
            console.log("Check-in Date 1: " + checkin_date1);
            console.log("Check-in Date 2: " + checkin_date2);

            // Check if weekend
            let dayOfWeek = checkin_date1.getDay();
            let isWeekend = (dayOfWeek === 0 || dayOfWeek === 5 || dayOfWeek === 6); // 0 is Sunday, 5 is Friday, 6 is Saturday
            if(isWeekend)
            {
                isWeekend = "true";
            }
            else if(!isWeekend)
            {
                isWeekend = "false";
            }

            // Debug day of the week
            console.log("Day of Week: " + dayOfWeek);
            console.log("Is Weekend: " + isWeekend);

            // Create new check-in value
            // Check if day or night
            let time_of_day = "";
            let new_checkin_val;
            if ((checkout_val % 2) == 0) {
                time_of_day = "Night Tour";
                new_checkin_val = new Date(checkin_date2.getTime() + 20 * 60 * 60 * 1000); // Add 20 hours
            }
            else
            {
                time_of_day = "Day Tour";
                new_checkin_val = new Date(checkin_date2.getTime() + 8 * 60 * 60 * 1000); // Add 8 hours
            }

            // Debug new check-in value and time of day
            console.log("Time of Day: " + time_of_day);
            console.log("New Check-in Value: " + new_checkin_val);

            // Check if 22 hours
            let is_22hrs;
            if (checkout_val == 3 || checkout_val == 4)
            {
                is_22hrs = "true";
            }
            else if (checkout_val == 1 || checkout_val == 2)
            {
                is_22hrs = "false";
            }

            // Debugging the value
            console.log("Checkout Value: " + checkout_val);
            console.log("Is 22 hours: " + is_22hrs);

            // Further Check-out Value Adjustments (if needed)
            // Creting new check-out value
            let new_checkout_val;
            if (checkout_val == 1 || checkout_val == 2) {
                new_checkout_val = new Date(new_checkin_val.getTime() + 10 * 60 * 60 * 1000); // 10
            }
            else if (checkout_val == 3 || checkout_val == 4) {
                new_checkout_val = new Date(new_checkin_val.getTime() + 22 * 60 * 60 * 1000); // 22
            }
            // else if (checkout_val == 5 || checkout_val == 6) {
            //     new_checkout_val = new Date(new_checkin_val.getTime() + 34 * 60 * 60 * 1000); // 34
            // }
            // else if (checkout_val == 7 || checkout_val == 8) {
            //     new_checkout_val = new Date(new_checkin_val.getTime() + 46 * 60 * 60 * 1000); // 46
            // }
            // else if (checkout_val == 9 || checkout_val == 10) {
            //     new_checkout_val = new Date(new_checkin_val.getTime() + 58 * 60 * 60 * 1000); // 58
            // }
            // else if (checkout_val == 11 || checkout_val == 12) {
            //     new_checkout_val = new Date(new_checkin_val.getTime() + 70 * 60 * 60 * 1000); // 70
            // }
            // else if (checkout_val == 13 || checkout_val == 14) {
            //     new_checkout_val = new Date(new_checkin_val.getTime() + 82 * 60 * 60 * 1000); // 82
            // }
            // else if (checkout_val == 15 || checkout_val == 16) {
            //     new_checkout_val = new Date(new_checkin_val.getTime() + 94 * 60 * 60 * 1000); // 94
            // }
            // else if (checkout_val == 17 || checkout_val == 18) {
            //     new_checkout_val = new Date(new_checkin_val.getTime() + 106 * 60 * 60 * 1000); // 106
            // }
            // else if (checkout_val == 19 || checkout_val == 20) {
            //     new_checkout_val = new Date(new_checkin_val.getTime() + 118 * 60 * 60 * 1000); // 118
            // }
            // else if (checkout_val == 21 || checkout_val == 22) {
            //     new_checkout_val = new Date(new_checkin_val.getTime() + 118 * 60 * 60 * 1000); // 130
            // }
            // else if (checkout_val == 23 || checkout_val == 24) {
            //     new_checkout_val = new Date(new_checkin_val.getTime() + 142 * 60 * 60 * 1000); // 142
            // }
            // else if (checkout_val == 25 || checkout_val == 26) {
            //     new_checkout_val = new Date(new_checkin_val.getTime() + 154 * 60 * 60 * 1000); // 154
            // }
            // else if (checkout_val == 27 || checkout_val == 28) {
            //     new_checkout_val = new Date(new_checkin_val.getTime() + 166 * 60 * 60 * 1000); // 166
            // }

            // Debug new check-out value
            console.log("New Check-out Value: " + new_checkout_val);

            // Formating check-in and check-out values
            let final_checkin_val = new Date(new_checkin_val.getTime() - new_checkin_val.getTimezoneOffset() * 60000);
            let isoStr1 = final_checkin_val.toISOString();
            let datetimeLocal_checkin = isoStr1.slice(0, 16);

            let final_checkout_val = new Date(new_checkout_val.getTime() - new_checkin_val.getTimezoneOffset() * 60000);
            let isoStr2 = final_checkout_val.toISOString();
            let datetimeLocal_checkout = isoStr2.slice(0, 16);

            // Debug final ISO string values
            console.log("Final Check-in ISO String: " + datetimeLocal_checkin);
            console.log("Final Check-out ISO String: " + datetimeLocal_checkout);

            booking_form.elements['pay_now'].setAttribute('disabled',true);

            if(datetimeLocal_checkin!='' && datetimeLocal_checkout!='')
            {
                pay_info.classList.add('d-none');
                pay_info.classList.replace('alert-warning','alert-success');
                info_loader.classList.remove('d-none');
                
                let data = new FormData();

                data.append('check_availability','');
                data.append('datetimeLocal_checkin',datetimeLocal_checkin);
                data.append('datetimeLocal_checkout',datetimeLocal_checkout);
                data.append('isWeekend',isWeekend);
                data.append('time_of_day',time_of_day);
                data.append('is_22hrs',is_22hrs);

                let xhr = new XMLHttpRequest();
                xhr.open("POST","ajax/confirm_booking.php",true);

                xhr.onload = function()
                {
                    console.log("XHR onload triggered");
                    let data = JSON.parse(this.responseText);
                    console.log("Response data:", data);

                    if(data.status == 'check_in_out_equal')
                    {
                        pay_info.classList.replace('alert-success','alert-warning');
                        pay_info.innerHTML = "<strong>Notice:</strong> You cannot check-out on the same day.";
                    }
                    else if(data.status == 'check_out_earlier')
                    {
                        pay_info.classList.replace('alert-success','alert-warning');
                        pay_info.innerHTML = "<strong>Notice:</strong> Check-out date is earlier than the check-in date.";
                    }
                    else if(data.status == 'check_in_earlier')
                    {
                        pay_info.classList.replace('alert-success','alert-warning');
                        pay_info.innerHTML = "<strong>Notice:</strong> Check-in date is earlier than today's date.";
                    }
                    else if(data.status == 'unavailable')
                    {
                        pay_info.classList.replace('alert-success','alert-warning');
                        pay_info.innerHTML = "<strong>Notice:</strong> Room unavailable for this date.";
                    }
                    else
                    {
                        pay_info.innerHTML = "Package Type:<br><strong>"+data.package_type+"</strong><br><br>Hours:<br><strong>"+data.hour1+" - "+data.hour2+"</strong><br><br>Total Amount to Pay:<br><strong>₱"+data.payment+"</strong>";
                        pay_info.classList.replace('alert-warning','alert-success');
                        booking_form.elements['pay_now'].removeAttribute('disabled');
                    }

                    pay_info.classList.remove('d-none');
                    info_loader.classList.add('d-none');
                    console.log("Completed updating UI");
                }
                console.log("Request sent with data: ", data);
                
                xhr.onerror = function () {
                    console.error("XHR error occurred");
                };
                console.log("Sending request...");
                xhr.send(data);
                console.log("Request sent");
            }
        }

        function openModal()
        {
            let name_val = booking_form.elements['name'].value;
            let phonenum_val = booking_form.elements['phonenum'].value;
            let address_val = booking_form.elements['address'].value;
            let checkin_val = booking_form.elements['checkin'].value;
            let checkout_val = booking_form.elements['checkout'].value;

            // Needed to convert check-in and checkout date
            let checkin_date1 = new Date(checkin_val + "T00:00:00");
            let checkin_date2 = new Date(checkin_val + "T00:00:00");

            // Debug check-in dates
            console.log("Check-in Date 1: " + checkin_date1);
            console.log("Check-in Date 2: " + checkin_date2);

            // Check if weekend
            let dayOfWeek = checkin_date1.getDay();
            let isWeekend = (dayOfWeek === 0 || dayOfWeek === 5 || dayOfWeek === 6); // 0 is Sunday, 5 is Friday, 6 is Saturday
            if(isWeekend)
            {
                isWeekend = "true";
            }
            else if(!isWeekend)
            {
                isWeekend = "false";
            }

            // Debug day of the week
            console.log("Day of Week: " + dayOfWeek);
            console.log("Is Weekend: " + isWeekend);

            // Create new check-in value
            // Check if day or night
            let time_of_day = "";
            let new_checkin_val;
            if ((checkout_val % 2) == 0) {
                time_of_day = "Night Tour";
                new_checkin_val = new Date(checkin_date2.getTime() + 20 * 60 * 60 * 1000); // Add 20 hours
            }
            else
            {
                time_of_day = "Day Tour";
                new_checkin_val = new Date(checkin_date2.getTime() + 8 * 60 * 60 * 1000); // Add 8 hours
            }

            // Debug new check-in value and time of day
            console.log("Time of Day: " + time_of_day);
            console.log("New Check-in Value: " + new_checkin_val);

            // Check if 22 hours
            let is_22hrs;
            if (checkout_val == 3 || checkout_val == 4)
            {
                is_22hrs = "true";
            }
            else if (checkout_val == 1 || checkout_val == 2)
            {
                is_22hrs = "false";
            }

            // Debugging the value
            console.log("Checkout Value: " + checkout_val);
            console.log("Is 22 hours: " + is_22hrs);

            // Further Check-out Value Adjustments (if needed)
            // Creting new check-out value
            let new_checkout_val;
            if (checkout_val == 1 || checkout_val == 2) {
                new_checkout_val = new Date(new_checkin_val.getTime() + 10 * 60 * 60 * 1000); // 10
            }
            else if (checkout_val == 3 || checkout_val == 4) {
                new_checkout_val = new Date(new_checkin_val.getTime() + 22 * 60 * 60 * 1000); // 22
            }

            // Debug new check-out value
            console.log("New Check-out Value: " + new_checkout_val);

            // Formating check-in and check-out values
            let final_checkin_val = new Date(new_checkin_val.getTime() - new_checkin_val.getTimezoneOffset() * 60000);
            let isoStr1 = final_checkin_val.toISOString();
            let datetimeLocal_checkin = isoStr1.slice(0, 16);

            let final_checkout_val = new Date(new_checkout_val.getTime() - new_checkin_val.getTimezoneOffset() * 60000);
            let isoStr2 = final_checkout_val.toISOString();
            let datetimeLocal_checkout = isoStr2.slice(0, 16);

            let modal = new bootstrap.Modal(document.getElementById('pay-now'));
            modal.show();

            document.getElementById('pay_now_form').addEventListener('submit', function(event) {
                event.preventDefault();

                let paidamount_val = pay_now_form.elements['paid_amount'].value;
                let g_reference_val = pay_now_form.elements['g_reference'].value;

                let data = new FormData();
                data.append('pay_now','');
                data.append('name',name_val);
                data.append('phonenum',phonenum_val);
                data.append('address',address_val);
                data.append('datetimeLocal_checkin',datetimeLocal_checkin);
                data.append('datetimeLocal_checkout',datetimeLocal_checkout);
                data.append('isWeekend',isWeekend);
                data.append('time_of_day',time_of_day);
                data.append('is_22hrs',is_22hrs);
                data.append('paidamount',paidamount_val);
                data.append('g_reference',g_reference_val);

                let xhr = new XMLHttpRequest();
                xhr.open("POST", "pay_now.php", true);

                xhr.onload = function()
                {
                    console.log("XHR onload triggered");
                    console.log("Raw Response Text:", this.responseText);

                    try {
                        let data = JSON.parse(this.responseText);
                        console.log("Response data:", data);

                        if (data.orderid != '') {
                            window.location.href = 'pay_status.php?order=' + data.orderid;
                        }
                    } catch (e) {
                        console.error("Error parsing JSON:", e);
                        console.log("Response Text:", this.responseText); // Log entire response
                    }
                };
                
                console.log("Sending request...");
                xhr.send(data);
                console.log("Request sent");

                let modalInstance = bootstrap.Modal.getInstance(document.getElementById('pay-now'));
                modalInstance.hide();
            });
        }
    </script>
</body>
</html>
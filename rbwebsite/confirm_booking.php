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
                            <img src="$room_thumb" class="img-fluid rounded mb-3">
                            <h5>$room_data[name]</h5>
                            <h6>₱$room_data[price] per night</h6>
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
                                        <option value="1">Day Tour (08:00am - 06:00pm)</option>
                                        <option value="2">Night Tour (08:00pm - 06:00am)</option>
                                        <option value="3">22 Hours Day Tour (08:00am - 06:00am)</option>
                                        <option value="4">22 Hours Night Tour (08:00pm - 06:00pm)</option>
                                        <option value="5">1 and 1/2 Day Tour</option>
                                        <option value="6">1 and 1/2 Night Tour</option>
                                        <option value="7">2 Days Tour</option>
                                        <option value="8">2 Nights Tour</option>
                                        <option value="9">2 and 1/2 Day Tour</option>
                                        <option value="10">2 and 1/2 Night Tour</option>
                                        <option value="11">3 Days Tour</option>
                                        <option value="12">3 Nights Tour</option>
                                        <option value="13">3 and 1/2 Day Tour</option>
                                        <option value="14">3 and 1/2 Night Tour</option>
                                        <option value="15">4 Days Tour</option>
                                        <option value="16">4 Nights Tour</option>
                                        <option value="17">4 and 1/2 Days Tour</option>
                                        <option value="18">4 and 1/2 Nights Tour</option>
                                        <option value="19">5 Days Tour</option>
                                        <option value="20">5 Nights Tour</option>
                                        <option value="21">5 and 1/2 Days Tour</option>
                                        <option value="22">5 and 1/2 Nights Tour</option>
                                        <option value="23">6 Days Tour</option>
                                        <option value="24">6 Nights Tour</option>
                                        <option value="25">6 and 1/2 Days Tour</option>
                                        <option value="26">6 and 1/2 Nights Tour</option>
                                        <option value="27">7 Days Tour</option>
                                        <option value="28">7 Nights Tour</option>
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
                        <div class="mb-3 text-center">
                            <img src="images/settings/asd.jpeg" class="img-fluid mb-2">
                            <label class="form-label fw-bold badge bg-primary text-white rounded-pill px-3 py-2">GCash: 0912-345-6789</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Paid Amount</label>
                            <input type="number" name="paid_amount" class="form-control shadow-none" required>
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
        let booking_form = document.getElementById('booking_form');
        let pay_now_form = document.getElementById('pay_now_form');
        let info_loader = document.getElementById('info_loader');
        let pay_info = document.getElementById('pay_info');

        function check_availability()
        {
            let checkin_val = booking_form.elements['checkin'].value;
            let checkout_val = booking_form.elements['checkout'].value;

            // Convert check-in date
            let checkin_date1 = new Date(checkin_val + "T00:00:00");
            let checkin_date2 = new Date(checkin_val + "T00:00:00");

            // Check if weekend
            let dayOfWeek = checkin_date1.getDay();
            let isWeekend = (dayOfWeek === 0 || dayOfWeek === 5 || dayOfWeek === 6); // 0 is Sunday, 5 is Friday, 6 is Saturday

            // Check if day or night and create new check-in value
            let time_of_day = "";
            let new_checkin_val;
            if ((checkout_val % 2) == 0) {
                time_of_day = "Night Tour";
                new_checkin_val = new Date(checkin_date2.getTime() + 20 * 60 * 60 * 1000); // Add 20 hours
            } else {
                time_of_day = "Day Tour";
                new_checkin_val = new Date(checkin_date2.getTime() + 8 * 60 * 60 * 1000); // Add 8 hours
            }

            // Further Check-out Value Adjustments (if needed)
            let new_checkout_val;
            if (checkout_val == 1 || checkout_val == 2) {
                new_checkout_val = new Date(new_checkin_val.getTime() + 10 * 60 * 60 * 1000); // 10
            }
            else if (checkout_val == 3 || checkout_val == 4) {
                new_checkout_val = new Date(new_checkin_val.getTime() + 22 * 60 * 60 * 1000); // 22
            }
            else if (checkout_val == 5 || checkout_val == 6) {
                new_checkout_val = new Date(new_checkin_val.getTime() + 34 * 60 * 60 * 1000); // 34
            }
            else if (checkout_val == 7 || checkout_val == 8) {
                new_checkout_val = new Date(new_checkin_val.getTime() + 46 * 60 * 60 * 1000); // 46
            }
            else if (checkout_val == 9 || checkout_val == 10) {
                new_checkout_val = new Date(new_checkin_val.getTime() + 58 * 60 * 60 * 1000); // 58
            }
            else if (checkout_val == 11 || checkout_val == 12) {
                new_checkout_val = new Date(new_checkin_val.getTime() + 70 * 60 * 60 * 1000); // 70
            }
            else if (checkout_val == 13 || checkout_val == 14) {
                new_checkout_val = new Date(new_checkin_val.getTime() + 82 * 60 * 60 * 1000); // 82
            }
            else if (checkout_val == 15 || checkout_val == 16) {
                new_checkout_val = new Date(new_checkin_val.getTime() + 94 * 60 * 60 * 1000); // 94
            }
            else if (checkout_val == 17 || checkout_val == 18) {
                new_checkout_val = new Date(new_checkin_val.getTime() + 106 * 60 * 60 * 1000); // 106
            }
            else if (checkout_val == 19 || checkout_val == 20) {
                new_checkout_val = new Date(new_checkin_val.getTime() + 118 * 60 * 60 * 1000); // 118
            }
            else if (checkout_val == 21 || checkout_val == 22) {
                new_checkout_val = new Date(new_checkin_val.getTime() + 118 * 60 * 60 * 1000); // 130
            }
            else if (checkout_val == 23 || checkout_val == 24) {
                new_checkout_val = new Date(new_checkin_val.getTime() + 142 * 60 * 60 * 1000); // 142
            }
            else if (checkout_val == 25 || checkout_val == 26) {
                new_checkout_val = new Date(new_checkin_val.getTime() + 154 * 60 * 60 * 1000); // 154
            }
            else if (checkout_val == 27 || checkout_val == 28) {
                new_checkout_val = new Date(new_checkin_val.getTime() + 166 * 60 * 60 * 1000); // 166
            }

            console.log(new_checkout_val);
            console.log(new_checkin_val);

            booking_form.elements['pay_now'].setAttribute('disabled',true);

            if(new_checkin_val!='' && new_checkout_val!='')
            {
                pay_info.classList.add('d-none');
                pay_info.classList.replace('text-dark','text-danger');
                info_loader.classList.remove('d-none');
                
                let data = new FormData();

                data.append('check_availability','');
                data.append('new_checkin_val',checkin_val);
                data.append('new_checkout_val',checkout_val);

                let xhr = new XMLHttpRequest();
                xhr.open("POST","ajax/confirm_booking.php",true);

                xhr.onload = function()
                {
                    let data = JSON.parse(this.responseText);

                    if(data.status == 'check_in_out_equal')
                    {
                        pay_info.innerHTML = "<strong>Notice:</strong> You cannot check-out on the same day.";
                    }
                    else if(data.status == 'check_out_earlier')
                    {
                        pay_info.innerHTML = "<strong>Notice:</strong> Check-out date is earlier than the check-in date.";
                    }
                    else if(data.status == 'check_in_earlier')
                    {
                        pay_info.innerHTML = "<strong>Notice:</strong> Check-in date is earlier than today's date.";
                    }
                    else if(data.status == 'unavailable')
                    {
                        pay_info.innerHTML = "<strong>Notice:</strong> Room unavailable for this date.";
                    }
                    else
                    {
                        pay_info.innerHTML = "No. of Days: <strong>"+data.days+"</strong><br>Total Amount to Pay: <strong>₱"+data.payment+"</strong>";
                        pay_info.classList.replace('alert-warning','alert-success');
                        booking_form.elements['pay_now'].removeAttribute('disabled');
                    }

                    pay_info.classList.remove('d-none');
                    info_loader.classList.add('d-none');
                }
                xhr.send(data);
            }
        }

        function openModal()
        {
            let name_val = booking_form.elements['name'].value;
            let phonenum_val = booking_form.elements['phonenum'].value;
            let address_val = booking_form.elements['address'].value;
            let checkin_val = booking_form.elements['checkin'].value;
            let checkout_val = booking_form.elements['checkout'].value;

            let modal = new bootstrap.Modal(document.getElementById('pay-now'));
            modal.show();

            document.getElementById('pay_now_form').addEventListener('submit', function(event) {
                event.preventDefault();

                let paidamount_val = pay_now_form.elements['paid_amount'].value;

                let data = new FormData();
                data.append('pay_now','');
                data.append('name',name_val);
                data.append('phonenum',phonenum_val);
                data.append('address',address_val);
                data.append('checkin',checkin_val);
                data.append('checkout',checkout_val);
                data.append('paidamount',paidamount_val);

                let xhr = new XMLHttpRequest();
                xhr.open("POST", "pay_now.php", true);

                xhr.onload = function()
                {
                    let data = JSON.parse(this.responseText);

                    if (data.orderid!='') {
                        window.location.href = 'pay_status.php?order=' + data.orderid;
                    }
                };
                
                xhr.send(data);

                let modalInstance = bootstrap.Modal.getInstance(document.getElementById('pay-now'));
                modalInstance.hide();
            });
        }
    </script>
</body>
</html>
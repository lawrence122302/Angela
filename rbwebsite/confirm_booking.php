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
                                    <select id="checkout_time" name="checkout_time" class="form-select shadow-none">
                                        <option value="10:00">Day Tour</option>
                                        <option value="10:00">Night Tour</option>
                                        <option value="22:00">Day Tour (22 Hours)</option>
                                        <option value="22:00">Night Tour (22 Hours)</option>
                                        <option value="33:00">Day Tour (1 and 1/2 Day)</option>
                                        <option value="33:00">Night Tour (1 and 1/2 Day)</option>
                                        <option value="44:00">Day Tour (2 Days)</option>
                                        <option value="44:00">Night Tour (2 Days)</option>
                                        <option value="55:00">Day Tour (2 and 1/2 Days)</option>
                                        <option value="55:00">Night Tour (2 and 1/2 Days)</option>
                                        <option value="66:00">Day Tour (3 Days)</option>
                                        <option value="66:00">Night Tour (3 Days)</option>
                                        <option value="77:00">Day Tour (3 and 1/2 Days)</option>
                                        <option value="77:00">Night Tour (3 and 1/2 Days)</option>
                                        <option value="88:00">Day Tour (4 Days)</option>
                                        <option value="88:00">Night Tour (4 Days)</option>
                                        <option value="99:00">Day Tour (4 and 1/2 Days)</option>
                                        <option value="99:00">Night Tour (4 and 1/2 Days)</option>
                                        <option value="110:00">Day Tour (5 Days)</option>
                                        <option value="110:00">Night Tour (5 Days)</option>
                                        <option value="121:00">Day Tour (5 and 1/2 Days)</option>
                                        <option value="121:00">Night Tour (5 and 1/2 Days)</option>
                                        <option value="132:00">Day Tour (6 Days)</option>
                                        <option value="132:00">Night Tour (6 Days)</option>
                                        <option value="143:00">Day Tour (6 and 1/2 Days)</option>
                                        <option value="143:00">Night Tour (6 and 1/2 Days)</option>
                                        <option value="154:00">Day Tour (7 Days)</option>
                                        <option value="154:00">Night Tour (7 Days)</option>
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

            booking_form.elements['pay_now'].setAttribute('disabled',true);

            if(checkin_val!='' && checkout_val!='')
            {
                pay_info.classList.add('d-none');
                pay_info.classList.replace('text-dark','text-danger');
                info_loader.classList.remove('d-none');
                
                let data = new FormData();

                data.append('check_availability','');
                data.append('check_in',checkin_val);
                data.append('check_out',checkout_val);

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
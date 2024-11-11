<?php
    require('inc/essentials.php');
    adminLogin();

    $userRes = selectAll('user_cred WHERE is_verified=1 AND status=1');
    $accommodationRes = selectAll('rooms WHERE removed=0');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Add New Bookings</title>
    <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

    <?php require('inc/header.php') ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">Add New Bookings</h3>

                <form id="booking_form">
                    <h4 class="mb-3">Booking Details</h4>
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <h6>Accommodation Name:</h6>
                            <select class="form-select shadow-none bg-light mb-4" id="accommodationDropdown" name="accommodationId" onchange="check_availability()">
                                <option value="" selected disabled>Select an accommodation</option>
                                <?php
                                    while ($row = mysqli_fetch_assoc($accommodationRes)) {
                                        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label">Check-in</label>
                            <input id="checkin" name="checkin" onchange="check_availability()" type="date" class="form-control shadow-none mb-2" required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Check-out</label>
                            <select id="checkout" name="checkout" onchange="check_availability()" class="form-select shadow-none">
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
                                <strong>Notice:</strong> Provide necessary information to proceed.
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name</label>
                            <input id="customerName" name="customerName" type="text" class="form-control shadow-none" required placeholder="Enter your full name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email address</label>
                            <input id="name" name="email" type="email" class="form-control shadow-none" required placeholder="example@gmail.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone number</label>
                            <input id="phonenum" name="phonenum" type="number" class="form-control shadow-none" required oninput="this.value = this.value.slice(0, 11);" placeholder="09123456789">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Government ID</label>
                            <input id="profile" name="profile" type="file" accept=".jpg, .jpeg, .png, .webp" class="form-control shadow-none" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Address</label>
                            <textarea id="address" name="address" class="form-control shadow-none" rows="1" required placeholder="Enter your address"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Zip Code</label>
                            <input id="pincode" name="pincode" type="number" class="form-control shadow-none" required placeholder="1920">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date of birth</label>
                            <input id="dob" name="dob" type="date" class="form-control shadow-none" required>
                        </div>
                        <div class="col-12">
                            <button type="submit" name="pay_now" class="btn w-100 text-white custom-bg shadow-none mb-1" disabled>Pay Now</button>
                        </div>
                    </div>
                </form>

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
                            <img src="../images/settings/gcash_qr.jpg" class="img-fluid mb-2" style="max-height: 80vh;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col text-center mb-3">
                            <span class="badge rounded-pill bg-dark text-white text-wrap">Gcash: 09178520213</span>
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
                                <input type="radio" name="g_reference" value="walk-in" required>
                                Walk-In
                            </label><br>
                            <label>
                                <input type="radio" name="g_reference" id="customRadio1" value="gcash" required>
                                GCash
                            </label>
                            <input type="number" id="customValue1" name="customValue" class="form-control shadow-none" placeholder="Enter GCash Reference" oninput="this.value = this.value.slice(0, 13);" required disabled>
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

    <?php require('inc/scripts.php'); ?>

    <script src="scripts/without_account_add_bookings.js"></script>

</body>
</html>
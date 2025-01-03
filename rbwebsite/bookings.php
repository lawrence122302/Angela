<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - Bookings</title>
</head>
<body class="bg-light">

    <?php
        require('inc/navbar.php');
        if(!(isset($_SESSION['login']) && $_SESSION['login']==true))
        {
            redirect('index.php');
        }
    ?>

    <div class="container">
        <div class="row">

            <div class="col-12 my-5 px-4">
                <h2 class="fw-bold">Bookings</h2>
                <div style="font-size: 14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">Home</a>
                    <span class="text-secondary"> > </span>
                    <a href="#" class="text-secondary text-decoration-none">Bookings</a>
                </div>
            </div>

            <?php
                $query = "SELECT bo.*, bd.* FROM booking_order bo 
                    INNER JOIN booking_details bd ON bo.booking_id = bd.booking_id
                    WHERE ((bo.booking_status='booked') 
                    OR (bo.booking_status='reserved')
                    OR (bo.booking_status='pending')
                    OR (bo.booking_status='cancelled')
                    OR (bo.booking_status='payment failed')) 
                    AND (bo.user_id=?)
                    ORDER BY bo.booking_id DESC";

                $result = select($query,[$_SESSION['uId']],'i');

                while($data = mysqli_fetch_assoc($result))
                {
                    $date = date("d-m-Y | h:ia",strtotime($data['datentime']));
                    $checkin = date("d-m-Y | h:ia",strtotime($data['check_in']));
                    $checkout = date("d-m-Y | h:ia",strtotime($data['check_out']));

                    if($data['trans_id']!='walk-in')
                    {
                        $gcash = "<span class='badge bg-primary'>
                            GCash: $data[trans_id]
                        </span>";
                    }
                    else
                    {
                        $gcash = "<span class='badge bg-primary'>
                            Walk-In
                        </span>";
                    }

                    $status_bg = "";

                    $btn = "";

                    if($data['booking_status']=='booked')
                    {
                        $status_bg = "bg-success";

                        if($data['arrival']==1)
                        {
                            $btn="<a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark btn-sm shadow-none'><i class='bi bi-download'></i> Download PDF</a>";

                            if($data['rate_review']==0)
                            {
                                $btn.="<button type='button' onclick='review_room($data[booking_id],$data[room_id])' data-bs-toggle='modal' data-bs-target='#reviewModal' class='btn btn-warning btn-sm shadow-none ms-2'><i class='bi bi-star-fill'></i> Rate & Review</button>";
                            }
                        }
                        else
                        {
                            $btn="<button onclick='cancel_booking($data[booking_id])' type='button' class='btn btn-danger btn-sm shadow-none'>Cancel</button>";
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
                            $btn="<a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark btn-sm shadow-none'><i class='bi bi-download'></i> Download PDF</a>";
                        }
                    }
                    else if($data['booking_status']=='reserved')
                    {
                        $status_bg = "bg-warning";
                        $btn="<span class='badge bg-dark'>Waiting Arrival!</span>";
                    }
                    else if($data['booking_status']=='pending')
                    {
                        $status_bg = "bg-warning text-dark";
                    }
                    // else
                    // {
                    //     $status_bg = "bg-warning";
                    //     $btn="<a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark btn-sm shadow-none'><i class='bi bi-download'></i> Download PDF</a>";
                    // }

                    $pending_notice = "";
                    if ($data['booking_status']=='pending' && $data['trans_id']!='walk-in') {
                        $pending_notice = "
                            <p class='mt-4'>
                                <b>Notice:</b>
                                <br>
                                <span class='badge bg-light text-dark mb-2'><small>Check Gcash Reference and Gov. ID is correct.</small></span>
                                <br>
                                <button type='button' onclick='openEditModal($data[booking_id])' class='btn btn-dark shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#editGcashModal'>
                                    <i class='bi bi-pencil-square'></i> <small>Edit GCash Refence</small>
                                </button>
                            </p>";
                    }

                    echo<<<bookings
                        <div class='col-md-4 px-4 mb-4'>
                            <div class='bg-white p-3 rounded shadow-sm'>
                                <h5 class='fw-bold'>$data[room_name]</h5>
                                <p>
                                    <b>Booking ID: </b> $data[order_id] <br>
                                </p
                                <p>
                                    <b>Date: </b> $date <br>
                                    <b>Check in: </b> $checkin <br>
                                    <b>Check out: </b> $checkout
                                </p>
                                <p>
                                    <b>Package Type: </b> $data[package_type]
                                </p>
                                <p>
                                    <b>Amount: </b> ₱$data[price]
                                </p>
                                <p>
                                    <b>Paid:</b> ₱$data[trans_amt]
                                </p>
                                <p>
                                    $gcash
                                    <span class='badge $status_bg'>$data[booking_status]</span>
                                </p>
                                $btn
                                $pending_notice
                            </div>
                        </div>
                    bookings;
                }
            ?>
            

        </div>
    </div>

    <div class="modal fade" id="reviewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="review-form">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center">
                            <i class="bi bi-chat-square-heart-fill fs-3 me-2"></i> Rate & Review
                        </h5>
                        <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Rating</label>
                            <select class="form-select shadow-none" name="rating">
                                <option value="5">Excellent</option>
                                <option value="4">Good</option>
                                <option value="3">Ok</option>
                                <option value="2">Poor</option>
                                <option value="1">Bad</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Review</label>
                            <textarea type="password" name="review" rows="3" required class="form-control shadow-none"></textarea>
                        </div>

                        <input type="hidden" name="booking_id">
                        <input type="hidden" name="room_id">

                        <div class="text-end">
                            <button type="submit" class="btn custom-bg text-white shadow-none">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit gcash modal -->
    <div class="modal fade" id="editGcashModal" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form id="editGcashForm" autocomplete="off">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit GCash Reference</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">GCash Reference</label>
                                <input type="number" name="gcash" class="form-control shadow-none" oninput="this.value = this.value.slice(0, 13);" required>
                            </div>
                            <input type="hidden" name="edit_gcash_booking_id">
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

    <?php
        if(isset($_GET['cancel_status']))
        {
            alert('success','Booking Cancelled!');
        }
        else if(isset($_GET['review_status']))
        {
            alert('success','Thank you for rating & review!');
        }
    ?>

    <?php require('inc/footer.php'); ?>
    <script>
        function cancel_booking(id)
        {
            if(confirm('Are you sure to cancel booking?'))
            {
                let xhr = new XMLHttpRequest();
                xhr.open("POST","ajax/cancel_booking.php",true);
                xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

                xhr.onload = function()
                {
                    if(this.responseText==1)
                    {
                        window.location.href="bookings.php?cancel_status=true";
                    }
                    else
                    {
                        alert('error','Cancellation Failed!');
                    }
                }

                xhr.send('cancel_booking&id='+id);
            }
        }

        let review_form = document.getElementById('review-form');

        function review_room(bid, rid)
        {
            review_form.elements['booking_id'].value =  bid;
            review_form.elements['room_id'].value =  rid;
        }

        review_form.addEventListener('submit',function(e){
            e.preventDefault();

            let data = new FormData();

            data.append('review_form','');
            data.append('rating',review_form.elements['rating'].value);
            data.append('review',review_form.elements['review'].value);
            data.append('booking_id',review_form.elements['booking_id'].value);
            data.append('room_id',review_form.elements['room_id'].value);

            let xhr = new XMLHttpRequest();
            xhr.open("POST","ajax/review_room.php",true);

            xhr.onload = function()
            {
                if(this.responseText == 1)
                {
                    window.location.href = 'bookings.php?review_status=true';
                }
                else
                {
                    var myModal = document.getElementById('reviewModal');
                    var modal = bootstrap.Modal.getInstance(myModal);
                    modal.hide();
    
                    alert('error','Rating & Review Failed!');
                }
            }

            xhr.send(data);
        });

        const editGcashForm = document.getElementById('editGcashForm');

        editGcashForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitEditGcash();
        });

        function openEditModal(id) {
            document.querySelector('input[name="edit_gcash_booking_id"]').value = id;
        }

        function submitEditGcash() {
            const data = new FormData(editGcashForm);

            data.append('edit_gcash', '');
            data.append('edit_gcash_booking_id', editGcashForm.elements['edit_gcash_booking_id'].value);
            data.append('gcash', editGcashForm.elements['gcash'].value);

            const xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/edit_gcash_reference.php", true);

            xhr.onload = function() {
                const myModal = document.getElementById('editGcashModal');
                const modal = bootstrap.Modal.getInstance(myModal);
                modal.hide();

                if (xhr.status === 200 && xhr.responseText == 1) {
                    alert('success', 'Gcash reference changed!');
                    editGcashForm.reset();

                    // Wait for 0.5 seconds before changing the page
                    setTimeout(function() {
                        window.location.href = 'bookings.php';
                    }, 500);
                } else {
                    alert('error', 'Server Down!');
                }
            };

            xhr.onerror = function() {
                alert('error', 'Request error!');
            };

            xhr.send(data);
        }
    </script>
</body>
</html>
<?php
    require('inc/essentials.php');
    adminLogin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Reserved Bookings</title>
    <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

    <?php require('inc/header.php') ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">Reserved Bookings</h3>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <div class="text-end mb-4">
                            <input type="text" oninput="get_bookings(this.value)" class="form-control shadow-none w-25 ms-auto" placeholder="Type to search...">
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover border" style="min-width: 1200px;">
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col">#</th>
                                        <th scope="col">User Details</th>
                                        <th scope="col">Accommodation Details</th>
                                        <th scope="col">Booking Details</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="table-data">
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap Modal for Confirmation -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirm Arrival</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Confirm Arrival?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmPaymentBtn">Confirm</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Modal for Cancellation -->
    <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Cancel Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Cancel Booking?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="confirmCancelBtn">Yes, Cancel</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
            </div>
            </div>
        </div>
    </div>

    <?php require('inc/scripts.php'); ?>

    <script src="scripts/confirmed_bookings.js"></script>

</body>
</html>
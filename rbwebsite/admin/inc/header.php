<div class="container-fluid bg-dark text-light px-3 d-flex align-items-center justify-content-between sticky-top">
    <a class="logo-image" href="index.php">
      <img src="../images/settings/logo-angela.jpg" class="circular_image my-1" alt="Angela's Logo" />
    </a>
    <div class="d-flex flex-column align-items-center">
        <div><h5 class="mb-0 text-center text-uppercase font-weight-bold">Admin Panel</h5></div>
        <div><small class="text-muted">Angela's Private Pool</small></div>
    </div>
    <a href="logout.php" class="btn btn-light btn-sm">Log Out</a>
</div>

<div class="col-lg-2 bg-dark border-top border-3 border-secondary" id="dashboard-menu">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid flex-lg-column align-items-stretch">
            <div>
                <h5 class="mt-2 text-warning text-uppercase text-center"><?= $_SESSION['adminName'] ?></h5>
            </div>
            <div class="h-line bg-white"></div>
            <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#adminDropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="adminDropdown">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="calendar.php">Calendar</a>
                    </li>
                    <?php
                        if($_SESSION['isSuperAdmin']==1)
                        {
                            echo<<<data
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="admins.php">Admins</a>
                                </li>
                            data;
                        }
                    ?>
                    <li class="nav-item">
                    <button class="btn text-white px-3 w-100 shadow-none text-start d-flex align-items-center justify-content-between" type="button" data-bs-toggle="collapse" data-bs-target="#bookingLinks">
                        <span>Bookings</span>
                        <span><i class="bi bi-caret-down-fill"></i></span>
                    </button>
                    <div class="collapse px-3 small mb-1" id="bookingLinks">
                        <ul class="nav nav-pills flex-column rounded border border-secondary">
                            <li class="nav-item">
                                <a class="nav-link text-white" href="new_bookings.php">Confirm Down Payment</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="confirm_full_payment.php">Confirm Full Payment</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="confirmed_bookings.php">Reserved Bookings</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="refund_bookings.php">Refund Bookings</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="booking_records.php">Booking Records</a>
                            </li>
                        </ul>
                    </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="users.php">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="user_queries.php">User Queries</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="rate_review.php">Ratings & Review</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="rooms.php">Accomodations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="features_facilities.php">Amenities & Inclusions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="carousel.php">Carousel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="settings.php">Settings</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>
<?php
    require('inc/essentials.php');
    adminLogin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Calendar</title>
    <link rel="stylesheet" href="css/calendar.css">
    <!-- Font Awesome 6.6.0 CSS from cdnjs CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body class="bg-light">

    <div class="container">

        <div class="left">
            <div class="calendar">
                <div class="month">
                    <i class="fa fa-angle-left prev"></i>
                    <div class="date"></div>
                    <i class="fa fa-angle-right next"></i>
                </div>
                <div class="weekdays">
                    <div>Sun</div>
                    <div>Mon</div>
                    <div>Tue</div>
                    <div>Wed</div>
                    <div>Thu</div>
                    <div>Fri</div>
                    <div>Sat</div>
                </div>
                <div class="days"></div>
                <div class="goto-today">
                    <div class="goto">
                        <input type="text" placeholder="mm/yyyy" class="date-input">
                        <button class="goto-btn">go</button>
                    </div>
                    <button class="today-btn">today</button>
                </div>
            </div>
        </div>

        <div class="right">
            <div class="today-date">
                <div class="event-day">Wed</div>
                <div class="event-date">16 November 2022</div>
            </div>
            <div class="events"></div>
            <div class="add-event-wrapper">
                <div class="add-event-header">
                    <div class="title">Add Event</div>
                    <i class="fas fa-times close"></i>
                </div>
                <div class="add-event-body">
                    <div class="add-event-input">
                        <input type="text" placeholder="Event Name" class="event-name">
                    </div>
                    <div class="add-event-input">
                        <input type="text" placeholder="Event Time From" class="event-time-from">
                    </div>
                    <div class="add-event-input">
                        <input type="text" placeholder="Event Time To" class="event-time-to">
                    </div>
                </div>
                <div class="add-event-footer">
                    <button class="add-event-btn">add event</button>
                </div>
            </div>
        </div>
        <button class="add-event">
            <i class="fas fa-plus"></i>
        </button>

    </div>

    <script src="js/calendar.js"></script>

    <!-- <script src="scripts/admins.js"></script> -->

</body>
</html>
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
    <?php require('inc/links.php'); ?>

</head>
<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">

            <div class="d-flex align-items-center justify-content-between mb-4">
                <h3>Calendar</h3>
                <div>
                    <h5>Accommodation Name:</h5>
                    <select class="form-select shadow-none bg-light w-100" id="accommodationDropdown" onchange="filteraccommodation(this.value)">
                        <?php
                        $res = selectAll('rooms WHERE removed!=1 ORDER BY id ASC');
                        $first = true;
                        while($row = mysqli_fetch_assoc($res)) {
                            $selected = $first ? 'selected' : ''; // Select the first item by default
                            echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['name'].'</option>';
                            $first = false;
                        }
                        ?>
                    </select>
                </div>
            </div>

                <div class="d-flex align-items-center justify-content-between mb-1">

                    <div class="container">

                        <div class="left">
                            <div class="calendar">
                                <div class="month">
                                    <i class="fa fa-angle-left prev"></i>
                                    <div class="date"></div>
                                    <i class="fa fa-angle-right next"></i>
                                </div>
                                <div class="legend">
                                    <div class="legend-item">
                                        <span class="legend-color" style="background-color: var(--yellow-clr);"></span>
                                        <span class="legend-label">Day Booked</span>
                                    </div>
                                    <div class="legend-item">
                                        <span class="legend-color" style="background-color: var(--blue-clr);"></span>
                                        <span class="legend-label">Night Booked</span>
                                    </div>
                                    <div class="legend-item">
                                        <span class="legend-color" style="background-color: var(--red-clr);"></span>
                                        <span class="legend-label">Fully Booked</span>
                                    </div>
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

                </div>
            </div>
        </div>
    </div>

    <?php require('inc/scripts.php'); ?>
    <script src="scripts/calendar.js"></script>

</body>
</html>
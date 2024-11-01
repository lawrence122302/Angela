<?php
    require('../inc/essentials.php');
    adminLogin();

    $accommodation = isset($_POST['accommodation']) ? $_POST['accommodation'] : '';
    $condition = "WHERE room_id='$accommodation' AND booking_status IN ('reserved', 'booked') ORDER BY check_in ASC";

    $res = selectAll("booking_order $condition");
    $events = array();
    if ($res->num_rows > 0) {
        while($row = $res->fetch_assoc()) {
            $events[] = $row;
        }
    } else {
        error_log("No rows fetched.");
    }
    header('Content-Type: application/json');
    echo json_encode($events);
?>
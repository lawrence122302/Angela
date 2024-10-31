<?php
require('../inc/essentials.php');
adminLogin();

$res = selectAll('booking_order ORDER BY check_in ASC');
$events = array();

if ($res->num_rows > 0) {
    while($row = $res->fetch_assoc()) {
        $events[] = $row;
    }
} else {
    error_log("No rows fetched.");
}

echo json_encode($events);
?>

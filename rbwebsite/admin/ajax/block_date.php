<?php
    require('../inc/essentials.php');
    superAdminLogin();

    header('Content-Type: application/json');

    $data = json_decode(file_get_contents('php://input'), true);
    $date = $data['date'];
    $accommodationId = $data['accommodationId'];

    $response = [];

    $query = "INSERT INTO blocked_dates (date, room_id) VALUES (?, ?)";
    $query_params = [$date, $accommodationId];

    if (insert($query, $query_params, 'ss') > 0) {
        $response['success'] = true;
    } else {
        $response['success'] = false;
        error_log('Insert query failed: ' . mysqli_error($GLOBALS['con']));
    }

    echo json_encode($response);
?>
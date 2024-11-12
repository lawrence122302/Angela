<?php
    require('../inc/essentials.php');

    header('Content-Type: application/json');

    if (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        $rawData = file_get_contents('php://input');
        $data = json_decode($rawData, true);
    } else {
        $data = $_POST;
    }

    if (!isset($data['action'])) {
        echo json_encode(['success' => false, 'message' => 'No action specified']);
        exit;
    }

    $action = $data['action'];

    if ($action == 'filter_bookings') {
        adminLogin();

        $accommodation = isset($data['accommodation']) ? $data['accommodation'] : '';
        $condition = "WHERE room_id='$accommodation' AND booking_status IN ('pending', 'reserved', 'booked') ORDER BY check_in ASC";

        $res = selectAll("booking_order $condition");
        $events = array();
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $events[] = $row;
            }
        }
        echo json_encode($events);
    } elseif ($action == 'filter_blocked_dates') {
        adminLogin();

        $accommodation = isset($data['accommodation']) ? $data['accommodation'] : '';
        $condition = "WHERE room_id='$accommodation' AND status=1 ORDER BY date ASC";

        $res = selectAll("blocked_dates $condition");
        $blockedDates = array();
        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $blockedDates[] = $row;
            }
        }
        echo json_encode($blockedDates);
    } else if ($action == 'block_date') {
        superAdminLogin();
    
        $date = $data['date'];
        $accommodationId = $data['accommodationId'];
    
        $response = [];
    
        // Check if there's already a row with the same date and room_id
        $check_query = "SELECT * FROM blocked_dates WHERE date = ? AND room_id = ?";
        $check_params = [$date, $accommodationId];
        $check_result = select($check_query, $check_params, 'ss');
    
        if ($check_result->num_rows > 0) {
            $row = $check_result->fetch_assoc();
            if ($row['status'] == 1) {
                $response['success'] = false;
                $response['message'] = 'This date is already blocked for the selected room.';
            } elseif ($row['status'] == 0) {
                $update_query = "UPDATE blocked_dates SET status = 1 WHERE date = ? AND room_id = ?";
                $update_params = [$date, $accommodationId];
                if (update($update_query, $update_params, 'ss') > 0) {
                    $response['success'] = true;
                } else {
                    $response['success'] = false;
                    $response['message'] = 'Failed to update block status.';
                }
            }
        } else {
            $query = "INSERT INTO blocked_dates (date, room_id) VALUES (?, ?)";
            $query_params = [$date, $accommodationId];
    
            if (insert($query, $query_params, 'ss') > 0) {
                $response['success'] = true;
            } else {
                $response['success'] = false;
                $response['message'] = 'Failed to block date.';
            }
        }
    
        echo json_encode($response);
    } else if ($action == 'unblock_date') {
        adminLogin();

        if($_SESSION['isSuperAdmin']!=1)
        {
            echo json_encode(['success' => false, 'message' => 'Only superadmins can unblock dates.']);
            exit;
        }
    
        $date = $data['date'];
        $accommodationId = $data['accommodationId'];
    
        $response = [];
    
        // Update the status to 0 for the matching date and room_id
        $update_query = "UPDATE blocked_dates SET status = 0 WHERE date = ? AND room_id = ?";
        $update_params = [$date, $accommodationId];
    
        if (update($update_query, $update_params, 'ss') > 0) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to unblock date.';
        }
    
        echo json_encode($response);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action specified']);
    }
?>
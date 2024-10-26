<?php
    require('inc/essentials.php');
    require('inc/db_config.php');
    adminLogin();

    if(isset($_GET['seen']))
    {
        $frm_data = filteration($_GET);

        if($frm_data['seen']=='all')
        {
            $q = "UPDATE user_queries SET seen=?";
            $values = [1];
            if(update($q,$values,'i'))
            {
                alert('success','Marked all as read!');
            }
            else
            {
                alert('error','Operation failed!');
            }
        }
        else
        {
            $q = "UPDATE user_queries SET seen=? WHERE sr_no=?";
            $values = [1,$frm_data['seen']];
            if(update($q,$values,'ii'))
            {
                alert('success','Marked as read!');
            }
            else
            {
                alert('error','Operation failed!');
            }
        }
    }

    if(isset($_GET['del']))
    {
        $frm_data = filteration($_GET);

        if($frm_data['del']=='all')
        {
            $q = "DELETE FROM user_queries";
            if(mysqli_query($con,$q))
            {
                alert('success','All data deleted!');
            }
            else
            {
                alert('error','Operation failed!');
            }
        }
        else
        {
            $q = "DELETE FROM user_queries WHERE sr_no=?";
            $values = [$frm_data['del']];
            if(delete($q,$values,'i'))
            {
                alert('success','Data deleted!');
            }
            else
            {
                alert('error','Operation failed!');
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - User Queries</title>
    <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

    <?php
        require('inc/header.php');
        $query = select("SELECT is_super_admin FROM admin_cred WHERE sr_no=?",[$_SESSION['adminId']],'i');
        $res = mysqli_fetch_assoc($query);

        if ($res['is_super_admin'] == 1) {
            $is_super_admin = 'true';
        } else {
            $is_super_admin = 'false';
        }
    ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">User Queries</h3>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <div class="text-end mb-4">
                            <a href="#" class="btn btn-dark rounded-pill shadow-none btn-sm" onclick="markAllAsRead()">
                                <i class="bi bi-check-all"></i> Mark all read
                            </a>
                            <?php
                                if($res['is_super_admin']==1)
                                {
                                    echo<<<data
                                        <a href="#" class="btn btn-danger rounded-pill shadow-none btn-sm" onclick="deleteAllQueries()">
                                            <i class="bi bi-trash"></i> Delete all
                                        </a>
                                    data;
                                }
                            ?>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover border text-center" style="min-width: 1500px;">
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col" width="20%">Subject</th>
                                        <th scope="col" width="30%">Message</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $q = "SELECT * FROM user_queries ORDER BY sr_no DESC";
                                        $data = mysqli_query($con,$q);
                                        $i=1;

                                        while($row = mysqli_fetch_assoc($data))
                                        {
                                            $date = date('d-m-Y',strtotime($row['datentime']));
                                            $seen='';
                                            $sr_no = $row['sr_no'];
                                            if($row['seen']!=1)
                                            {
                                                $seen = "<a href='#' class='btn btn-sm rounded-pill btn-primary' onclick='markAsRead($sr_no)'><i class='bi bi-check'></i> Mark as read</a><br>";
                                            }
                                            if($res['is_super_admin']==1)
                                            {
                                                $seen .= "<a href='#' class='btn btn-sm rounded-pill btn-danger mt-2' onclick='deleteQuery($sr_no)'><i class='bi bi-trash'></i> Delete</a>";
                                            }

                                            echo<<<query
                                                <tr>
                                                    <td>$i</td>
                                                    <td>$row[name]</td>
                                                    <td>$row[email]</td>
                                                    <td>$row[subject]</td>
                                                    <td>$row[message]</td>
                                                    <td>$date</td>
                                                    <td>$seen</td>
                                                </tr>
                                            query;
                                            $i++;
                                        }
                                    ?>
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
                    <h5 class="modal-title" id="confirmModalLabel">Confirmation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="confirmModalBody">
                    <!-- Content to be updated dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmActionBtn">Confirm</button>
                </div>
            </div>
        </div>
    </div>


    <?php require('inc/scripts.php'); ?>
    <script>
        function showModal(message, confirmCallback) {
            let confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
            document.getElementById('confirmModalBody').textContent = message;
            
            document.getElementById('confirmActionBtn').onclick = function() {
                confirmCallback();
                confirmModal.hide();
            };
            
            confirmModal.show();
        }

        function markAsRead(sr_no) {
            showModal('Mark as read?', function() {
                window.location.href = '?seen=' + sr_no;
            });
        }

        function deleteQuery(sr_no) {
            showModal('Are you sure you want to delete this?', function() {
                window.location.href = '?del=' + sr_no;
            });
        }

        function markAllAsRead() {
            showModal('Mark all as read?', function() {
                window.location.href = '?seen=all';
            });
        }

        function deleteAllQueries() {
            showModal('Are you sure you want to delete all?', function() {
                window.location.href = '?del=all';
            });
        }
    </script>  

</body>
</html>
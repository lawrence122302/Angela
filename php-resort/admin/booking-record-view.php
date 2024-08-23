<?php
include('authentication.php');
include('includes/header.php');
?>

<div class="container-fluid px-4">
   
    <div class="row mt-4">
        <div class="col-md-12">
            
            <?php include('message.php'); ?>

            <div class="card">
                <div class="card-header">
                    <h4>View Booking Records
                        <a href="category-add.php" class="btn btn-primary float-end">Add Record</a>
                    </h4>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="myDataTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>First Name</th>
                                    <th>Middle Name</th>
                                    <th>Last Name</th>
                                    <th>Phone Number</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Edit</th>
                                    <?php if($_SESSION['auth_role'] == '2') : ?>
                                    <th>Delete</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $booking_records = "SELECT * FROM booking_records WHERE status!='2' ";
                                    $booking_records_run = mysqli_query($con, $booking_records);

                                    if(mysqli_num_rows($booking_records_run) > 0)
                                    {
                                        foreach($booking_records_run as $item)
                                        {
                                            ?>
                                            <tr>
                                                <td><?= $item['id'] ?></td>
                                                <td><?= $item['fname'] ?></td>
                                                <td><?= $item['mname'] ?></td>
                                                <td><?= $item['lname'] ?></td>
                                                <td><?= $item['phone'] ?></td>
                                                <td><?= $item['email'] ?></td>
                                                <td>
                                                    <?= $item['status'] == '1' ? 'hidden':'visible' ?>
                                                </td>
                                                <td><?= $item['date'] ?></td>
                                                <td>
                                                    <a href="booking-record-edit.php?id=<?= $item['id'] ?>" class="btn btn-success btn-sm">Edit</a>
                                                </td>
                                                <?php if($_SESSION['auth_role'] == '2') : ?>
                                                <td>
                                                    <form action="code-superadmin.php" method="POST">
                                                        <button type="submit" name="booking-record_delete_btn" value="<?= $item['id'] ?>" class="btn btn-danger btn-sm">Delete</button>
                                                    </form>
                                                </td>
                                                <?php endif; ?>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        ?>
                                            <tr>
                                                <td colspan="5">No Records Found</td>
                                            </tr>
                                        <?php
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

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>
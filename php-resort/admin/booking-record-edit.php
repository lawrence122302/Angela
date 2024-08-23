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
                    <h4>Edit Booking Record</h4>
                </div>
                <div class="card-body">

                    <?php
                    if(isset($_GET['id']))
                    {
                        $booking_record_id = $_GET['id'];
                        $booking_record_edit = "SELECT * FROM booking_records WHERE id='$booking_record_id' LIMIT 1";
                        $booking_record_run = mysqli_query($con, $booking_record_edit);

                        if(mysqli_num_rows($booking_record_run) > 0)
                        {
                            $row = mysqli_fetch_array($booking_record_run);
                            ?>

                            <form action="code.php" method="POST">
                                
                                <input type="hidden" name="booking_record_id" value="<?= $row['id'] ?>">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="">Name</label>
                                        <input type="text" name="name" value="<?= $row['name'] ?>" required class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Slug (URL)</label>
                                        <input type="text" name="slug" value="<?= $row['slug'] ?>" required class="form-control">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="">Description</label>
                                        <textarea name="description"required  class="form-control" rows="4"><?= $row['description'] ?>"</textarea>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label for="">Meta Title</label>
                                        <input type="text" name="meta_title" value="<?= $row['meta_title'] ?>" max="191" required class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Meta Description</label>
                                        <textarea name="meta_description"required  class="form-control" rows="4"><?= $row['meta_description'] ?></textarea>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="">Meta Keyword</label>
                                        <textarea name="meta_keyword"required  class="form-control" rows="4"><?= $row['meta_keyword'] ?></textarea>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="">Navbar Status</label>
                                        <input type="checkbox" name="navbar_status" <?= $row['navbar_status'] == '1' ? 'checked':'' ?> width="70px" height="70px" />
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="">Status</label>
                                        <input type="checkbox" name="status" <?= $row['status'] == '1' ? 'checked':'' ?> width="70px" height="70px" />
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <button type="submit" name="category_update" class="btn btn-primary">Update Record</button>
                                        <a href="booking-record-view.php" class="btn btn-danger">Cancel</a>
                                    </div>

                                </div>
                            </form>

                            <?php
                        }
                        else
                        {
                            ?>
                            <h4>No Records Found</h4>
                            <?php
                        }
                    }

                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>
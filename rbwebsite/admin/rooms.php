<?php
    require('inc/essentials.php');
    adminLogin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Accomodations</title>
    <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

    <?php require('inc/header.php') ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">Accomodations</h3>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <div class="text-end mb-4">
                            <?php
                                if($_SESSION['isSuperAdmin']==1)
                                {
                                    echo<<<data
                                        <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal" data-bs-target="#add-room">
                                            <i class="bi bi-plus-square"></i> Add
                                        </button>
                                    data;
                                }
                            ?>
                        </div>

                        <div class="table-responsive-lg" style="height: 450px; overflow-y: scroll;">
                            <table class="table table-hover border text-center">
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Guests</th>
                                        <th scope="col">Price</th>
                                        <!-- <th scope="col">Quantity</th> -->
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="room-data">
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Add room modal -->
    <div class="modal fade" id="add-room" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="add_room_form" autocomplete="off">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Accommodation</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Name</label>
                                <input type="text" name="name" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">PAX (Max.)</label>
                                <input type="number" min="1" name="adult" class="form-control shadow-none" required>
                            </div>
                            <span class='col-md-12 mb-3 badge rounded-pill bg-light text-dark'>
                                <h6>Monday - Thursday</h6>
                            </span>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Day/Night Swim Price</label>
                                <input type="number" min="1" name="price" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">22 Hours</label>
                                <input type="number" min="1" name="price2" class="form-control shadow-none" required>
                            </div>
                            <span class='col-md-12 mb-3 badge rounded-pill bg-light text-dark'>
                                <h6>Friday - Sunday</h6>
                            </span>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Day/Night Swim Price</label>
                                <input type="number" min="1" name="price3" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">22 Hours</label>
                                <input type="number" min="1" name="price4" class="form-control shadow-none" required>
                            </div>
                            <!-- <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Quantity</label>
                                <input type="number" min="1" name="quantity" class="form-control shadow-none" required>
                            </div> -->
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Amenities</label>
                                <div class="row">
                                    <?php
                                        $res = selectAll('features');
                                        while($opt = mysqli_fetch_assoc($res))
                                        {
                                            echo"
                                                <div class='col-md-3 mb-1'>
                                                    <label>
                                                        <input type='checkbox' name='features' value='$opt[id]' class='form-check-input shadow-none'>
                                                        $opt[name]
                                                    </label>
                                                </div>
                                            ";
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Inclusions</label>
                                <div class="row">
                                    <?php
                                        $res = selectAll('facilities');
                                        while($opt = mysqli_fetch_assoc($res))
                                        {
                                            echo"
                                                <div class='col-md-3 mb-1'>
                                                    <label>
                                                        <input type='checkbox' name='facilities' value='$opt[id]' class='form-check-input shadow-none'>
                                                        $opt[name]
                                                    </label>
                                                </div>
                                            ";
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <textarea name="desc" rows="4" class="form-control shadow-none" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn custom-bg text-white shadow-none">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit room modal -->
    <div class="modal fade" id="edit-room" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="edit_room_form" autocomplete="off">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Accommodation</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Name</label>
                                <input type="text" name="name" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">PAX (Max.)</label>
                                <input type="number" min="1" name="adult" class="form-control shadow-none" required>
                            </div>
                            <span class='col-md-12 mb-3 badge rounded-pill bg-light text-dark'>
                                <h6>Monday - Thursday</h6>
                            </span>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Day/Night Swim Price</label>
                                <input type="number" min="1" name="price" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">22 Hours</label>
                                <input type="number" min="1" name="price2" class="form-control shadow-none" required>
                            </div>
                            <span class='col-md-12 mb-3 badge rounded-pill bg-light text-dark'>
                                <h6>Friday - Sunday</h6>
                            </span>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Day/Night Swim Price</label>
                                <input type="number" min="1" name="price3" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">22 Hours</label>
                                <input type="number" min="1" name="price4" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Quantity</label>
                                <input type="number" min="1" name="quantity" class="form-control shadow-none" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Amenities</label>
                                <div class="row">
                                    <?php
                                        $res = selectAll('features');
                                        while($opt = mysqli_fetch_assoc($res))
                                        {
                                            echo"
                                                <div class='col-md-3 mb-1'>
                                                    <label>
                                                        <input type='checkbox' name='features' value='$opt[id]' class='form-check-input shadow-none'>
                                                        $opt[name]
                                                    </label>
                                                </div>
                                            ";
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Inclusions</label>
                                <div class="row">
                                    <?php
                                        $res = selectAll('facilities');
                                        while($opt = mysqli_fetch_assoc($res))
                                        {
                                            echo"
                                                <div class='col-md-3 mb-1'>
                                                    <label>
                                                        <input type='checkbox' name='facilities' value='$opt[id]' class='form-check-input shadow-none'>
                                                        $opt[name]
                                                    </label>
                                                </div>
                                            ";
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <textarea name="desc" rows="4" class="form-control shadow-none" required></textarea>
                            </div>
                            <input type="hidden" name="room_id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn text-secondary shadow-none" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn custom-bg text-white shadow-none">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Manage room images modal -->
    <div class="modal fade" id="room-images" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Accommodation Name</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="image-alert">

                    </div>
                    <div class="border-bottom border-3 pb-3 mb-3">
                        <form id="add_image_form">
                            <label class="form-label fw-bold">Add Image</label>
                            <input type="file" name="image" accept=".jpg,.png,.webp,.jpeg" class="form-control shadow-none mb-3" required>
                            <button class="btn custom-bg text-white shadow-none">Add</button>
                            <input type="hidden" name="room_id">
                        </form>
                    </div>
                    <div class="table-responsive-lg" style="height: 350px; overflow-y: scroll;">
                        <table class="table table-hover border text-center">
                            <thead>
                                <tr class="bg-dark text-light sticky-top">
                                    <th scope="col" width="60%">Image</th>
                                    <th scope="col">Thumb</th>
                                    <th scope="col">Delete</th>
                                </tr>
                            </thead>
                            <tbody id="room-image-data">
                            </tbody>
                        </table>
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
                    <h5 class="modal-title" id="confirmModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="confirmModalBody">
                    <!-- Content to be updated dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="confirmActionBtn">Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>


    <?php require('inc/scripts.php'); ?>

    <script src="scripts/rooms.js"></script>

</body>
</html>
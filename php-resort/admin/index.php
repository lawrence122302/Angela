<?php
include('authentication.php');
include('includes/header.php');
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Admin Panel for Angela's Private Pool</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>

    <?php include('message.php'); ?>

    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    Total Categories
                    <?php
                        $dash_query_category = "SELECT * FROM categories";
                        $dash_query_category_run = mysqli_query($con, $dash_query_category);

                        if($category_total = mysqli_num_rows($dash_query_category_run))
                        {
                            echo '<h4 class="mb-0"> '.$category_total.' </h4>';
                        }
                        else
                        {
                            echo '<h4 class="mb-0"> No Data </h4>';
                        }
                    ?>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="category-view.php">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">Total Posts
                    <?php
                        $dash_post_category = "SELECT * FROM posts";
                        $dash_post_category_run = mysqli_query($con, $dash_post_category);

                        if($post_total = mysqli_num_rows($dash_post_category_run))
                        {
                            echo '<h4 class="mb-0"> '.$post_total.' </h4>';
                        }
                        else
                        {
                            echo '<h4 class="mb-0"> No Data </h4>';
                        }
                    ?>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="post-view.php">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>

        <?php if($_SESSION['auth_role'] == '2') : ?>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">Total Users
                    <?php
                        $dash_user_category = "SELECT * FROM users";
                        $dash_user_category_run = mysqli_query($con, $dash_user_category);

                        if($user_total = mysqli_num_rows($dash_user_category_run))
                        {
                            echo '<h4 class="mb-0"> '.$user_total.' </h4>';
                        }
                        else
                        {
                            echo '<h4 class="mb-0"> No Data </h4>';
                        }
                    ?>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="view-register.php">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if($_SESSION['auth_role'] == '2') : ?>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">Total Blocked Users
                    <?php
                        $dash_user_block_category = "SELECT * FROM users WHERE status='1' ";
                        $dash_user_block_category_run = mysqli_query($con, $dash_user_block_category);

                        if($userBlock_total = mysqli_num_rows($dash_user_block_category_run))
                        {
                            echo '<h4 class="mb-0"> '.$userBlock_total.' </h4>';
                        }
                        else
                        {
                            echo '<h4 class="mb-0"> 0 </h4>';
                        }
                    ?>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a class="small text-white stretched-link" href="#">View Details</a>
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div>
    
</div>

<?php
include('includes/footer.php');
include('includes/scripts.php');
?>
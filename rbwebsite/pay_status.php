<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - Booking Status</title>
</head>
<body class="bg-light">

    <?php require('inc/navbar.php'); ?>

    <div class="container">
        <div class="row">

            <div class="col-12 my-5 mb-3 px-4">
                <h2 class="fw-bold">Payment Status</h2>
            </div>
            
            <?php
                if(false)
                {
                    echo<<<data
                        <div clas="col-12 px-4">
                            <p class="fw-bold alert alert-success">
                                <i class="bi bi-check-circle-fill"></i>
                                Payment done! Booking successful.
                                <br><br>
                                <a href='bookings.php'>Go to Bookings<a/>
                            </p>
                        </div>
                    data;
                }
                else
                {
                    echo<<<data
                        <div clas="col-12 px-4">
                            <p class="fw-bold alert alert-warning">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                Payment pending.
                                <br><br>
                                <a href='bookings.php'>Go to Bookings<a/>
                            </p>
                        </div>
                    data;
                }
            ?>
        </div>
    </div>

    <?php require('inc/footer.php'); ?>
</body>
</html>
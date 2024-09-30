<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - Not Found</title>
</head>
<body class="bg-light">

    <?php require('inc/navbar.php'); ?>

    <div class="container">
        <div class="row">

            <div class="col-12 my-5 mb-3 px-4">
                <h2 class="fw-bold">Not Found</h2>
            </div>
            
            <div clas="col-12 px-4">
                <p class="fw-bold alert alert-warning">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    The requested URL was not found.
                    <br><br>
                    <a href='index.php'>Go to Home</a>
                </p>
            </div>
        </div>
    </div>

    <?php require('inc/footer.php'); ?>
</body>
</html>
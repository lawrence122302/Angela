<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - Virtual Tour</title>
    <style>
        .iframe-container {
            width: 80vw;
            height: 80vh;
        }
        .iframe-container iframe {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body class="bg-light">

    <?php require('inc/navbar.php'); ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">Angela 2</h2>
        <div class="h-line bg-dark"></div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-auto mb-5 px-auto">
                <div class="bg-white rounded shadow py-4 d-flex flex-column align-items-center">
                    <div class="iframe-container">
                        <iframe allowFullScreen="true" allow="accelerometer; magnetometer; gyroscope" src="https://panoraven.com/en/embed/jjgBRmOO23"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require('inc/footer.php'); ?>
    
</body>
</html>
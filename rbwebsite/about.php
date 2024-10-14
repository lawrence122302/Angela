<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - About</title>
    <style>
        .box {
            border-top-color: var(--teal) !important;
        }
    </style>
</head>
<body class="bg-light">

    <?php require('inc/navbar.php'); ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">About Us</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            Angela’s Private Pool offers a serene escape in Antipolo, 
            featuring private pools with stunning city views,<br> 
            perfect for family gatherings and special celebrations<br> 
            just minutes from the Cathedral.
        </p>
    </div>

    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
                <h3 class="mb-3">Get to know us!</h3>
                <p>
                    Angela’s Private Pool offers a tranquil retreat in Antipolo, Rizal, with seven private pools designed for comfort and relaxation, each offering stunning city views.<br>
                    Located minutes from the Antipolo Cathedral, our resort is perfect for those seeking a peaceful getaway from the city’s hustle.<br><br>
                    Ideal for family celebrations, gatherings, or a day of leisure, our resort provides spacious accommodations, air-conditioned rooms, grilling areas, and kitchen facilities to ensure a comfortable stay.<br>
                    Our dedicated team strives to make each visit memorable, from booking to check-out.<br><br>
                    At Angela’s Private Pool, we’re more than just a resort—we’re a place to relax, connect, and celebrate life’s special moments.
                </p>
            </div>
            <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1">
                <img src="images/settings/footer.jpeg" class="w-100">
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box" style="min-height: 35vh;">
                    <img src="images/about/hotel.svg" width="70px">
                    <h4 class="mt-3">7 Angelas</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box" style="min-height: 35vh;">
                    <img src="images/about/customers.svg" width="70px">
                    <h4 class="mt-3">200+ Customers</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box" style="min-height: 35vh;">
                    <img src="images/about/rating.svg" width="70px">
                    <h4 class="mt-3">100+ Reviews</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-4">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box" style="min-height: 35vh;">
                    <img src="images/about/staff.svg" width="70px">
                    <h4 class="mt-3">7 Caretakers</h4>
                </div>
            </div>
        </div>
    </div>

    <?php require('inc/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        var swiper = new Swiper(".mySwiper", {
            spaceBetween: 40,
            pagination: {
                el: ".swiper-pagination",
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                },
                640: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 3,
                },
                1024: {
                    slidesPerView: 3,
                },
            }
        });
    </script>
    
</body>
</html>
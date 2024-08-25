<?php
include('includes/config.php');

$page_title = "Angela's Private Pool - Home";
$meta_description = "Home page description resort website";
$meta_keywords = "resort, swimming pools, serenity, luxury, retreat, wellness, relaxation, celebration";

include('includes/header.php');
include('includes/navbar.php');
?>

<div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center">About Us</h2>
    <div class="h-line bg-dark"></div>
    <p class="text-center mt-3">
        Lorem ipsum dolor sit amet consectetur adipisicing elit. 
        Rem dolor deleniti id est <br> doloremque incidunt 
        temporibus neque nostrum aliquam suscipit.
    </p>
</div>

<div class="container">
    <div class="row justify-content-between align-items-center">
        <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
            <h3 class="mb-3">Lorem ipsum dolor sit</h3>
            <p>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                Quasi molestias culpa nulla ducimus alias odio corporis.
                Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                Quasi molestias culpa nulla ducimus alias odio corporis.
            </p>
        </div>
        <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1">
            <img src="admin/assets/images/about/about.jpg" class="w-100">
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col-lg-3 col-md-6 mb-4 px-4">
            <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                <img src="admin/assets/images/about/hotel.svg" width="70px">
                <h4 class="mt-3">100+ Rooms</h4>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4 px-4">
            <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                <img src="admin/assets/images/about/customers.svg" width="70px">
                <h4 class="mt-3">200 Customers</h4>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4 px-4">
            <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                <img src="admin/assets/images/about/rating.svg" width="70px">
                <h4 class="mt-3">150+ Reviews</h4>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4 px-4">
            <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                <img src="admin/assets/images/about/staff.svg" width="70px">
                <h4 class="mt-3">200+ Staffs</h4>
            </div>
        </div>
    </div>
</div>

<h3 class="my-5 fw-bold h-font text-center">Management Team</h3>

<div class="container px-4">
    <div class="swiper mySwiper">
        <div class="swiper-wrapper mb-5">
            <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                <img src="admin/assets/images/about/IMG_17352.jpg" class="w-100">
                <h5 class="mt-2">Random Name</h5>
            </div>
            <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                <img src="admin/assets/images/about/IMG_17352.jpg" class="w-100">
                <h5 class="mt-2">Random Name</h5>
            </div>
            <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                <img src="admin/assets/images/about/IMG_17352.jpg" class="w-100">
                <h5 class="mt-2">Random Name</h5>
            </div>
            <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                <img src="admin/assets/images/about/IMG_17352.jpg" class="w-100">
                <h5 class="mt-2">Random Name</h5>
            </div>
            <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                <img src="admin/assets/images/about/IMG_17352.jpg" class="w-100">
                <h5 class="mt-2">Random Name</h5>
            </div>
            <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                <img src="admin/assets/images/about/IMG_17352.jpg" class="w-100">
                <h5 class="mt-2">Random Name</h5>
            </div>
        </div>
        <div class="swiper-pagination"></div>
    </div>
</div>

<?php
include('includes/footer.php');
?>
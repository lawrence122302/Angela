<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Angela's Private Pool</title> -->

    <title><?php if(isset($page_title)) { echo "$page_title"; } else { echo "Angela's Private Pool"; } ?></title>

    <meta name="description" content="<?php if(isset($meta_description)) { echo "$meta_description"; } ?>" />
    <meta name="keywords" content="<?php if(isset($meta_keywords)) { echo "$meta_keywords"; } ?>" />
    <meta name="author" content="Angela's Private Pool" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    <?php require('includes/links.php'); ?>
    <style>
        .navbar-brand-me-5 {
            text-decoration: none; /* Remove underline */
            color: inherit; /* Inherit color from parent element */
        }

        .navbar-brand-me-5:hover {
            color: inherit; /* Maintain color on hover */
            text-decoration: none; /* Remove underline on hover */
        }

        .logo-image {
            width: 60px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            margin: 10px;
        }

        .logo-image img {
            width: 100%; /* Makes sure the image fills the circle */
            height: 100%; /* Makes sure the image fills the circle */
            object-fit: cover; /* Ensures the image fills the circle without stretching */
            object-position: center center; /* Centers the image within the circular container */
        }

        @media (max-width: 576px) {
            .logo-image {
                width: 80px; /* Adjust this size for even smaller screens */
                height: 80px; /* Adjust this size for even smaller screens */
            }
        }

        .navbar{
            padding: 0px;
        }

        .nav-link{
            border-right: 1px solid #fff;
            padding: 8px 20px !important;
        }

        .underline{
            height: 4px;
            width: 50px;
            background-color: red;
            margin-bottom: 20px;
        }

        .availability-form {
            margin-top: -50px;
            z-index: 2;
            position: relative;
        }

        @media screen and (max-width: 575px) {
            .availability-form {
                margin-top: 25px;
                padding: 0 35px;
            }
        }

        .pop:hover {
            border-top-color: var(--teal) !important;
            transform: scale(1.03);
            transition: all 0.3s;
        }

        .box {
            border-top-color: var(--teal) !important;
        }
    </style>
</head>
<body class="bg-light">

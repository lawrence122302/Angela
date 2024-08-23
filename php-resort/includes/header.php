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

    <link rel="stylesheet" href="<?= base_url('assets/css/custom-styles.css') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

    <link href="https://fonts.googleapis.com/css2?family=Merienda:wght@300..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        .custom-bg {
            background-color: #2ec1ac;
        }

        .custom-bg:hover {
            background-color: #279e8c;
        }

        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }

        /* Firefox */
        input[type=number] {
        -moz-appearance: textfield;
        }

        .navbar-brand-me-5 {
            text-decoration: none; /* Remove underline */
            color: inherit; /* Inherit color from parent element */
        }

        .navbar-brand-me-5:hover {
            color: inherit; /* Maintain color on hover */
            text-decoration: none; /* Remove underline on hover */
        }

        .h-font {
            font-family: 'Merienda', cursive;
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

    </style>
</head>
<body class="bg-light">

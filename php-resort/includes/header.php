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

    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap5.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/custom-styles.css') ?>">
</head>
<body>

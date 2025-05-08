<?php
// Get relative path prefix based on if we're in admin or not
$path_prefix = isset($is_admin) && $is_admin ? '../' : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_TITLE ?><?= isset($page_title) ? ' - ' . $page_title : '' ?></title>
    <meta name="description" content="<?= SITE_DESCRIPTION ?>">
    
    <!-- Bootstrap 5 and Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?= $path_prefix ?>assets/css/style.css" rel="stylesheet">
    <?php if (isset($is_admin) && $is_admin): ?>
    <link href="<?= $path_prefix ?>assets/css/admin.css" rel="stylesheet">
    <?php endif; ?>
    
    <!-- Preload critical assets -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= $path_prefix ?>assets/img/favicon.ico" type="image/x-icon">
</head>
<body>
    <div class="site-wrapper">

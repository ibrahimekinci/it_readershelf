<?php

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= htmlspecialchars($pageTitle ?? "ITReaderShelf", ENT_QUOTES, 'UTF-8')?>
    </title>
    <link rel="icon" href="/images/favicon.ico?v=2" type="image/x-icon">
    <link rel="shortcut icon" href="/images/favicon.ico?v=2" type="image/x-icon">
    <!-- Apple/Safari Specific -->
    <link rel="apple-touch-icon" href="/images/favicon.ico?v=2">
    <!-- bs -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

    <!-- nav -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand font-weight-bold" href="index.php">
                <img src="images/logo.jpg" alt="<?= htmlspecialchars("ITReaderShelf", ENT_QUOTES, 'UTF-8')?> Logo"
                    height="40" class="d-inline-block align-top" style="border-radius: 4px;">
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <!-- search -->
                <form class="form-inline mx-auto my-2 my-lg-0 w-50" action="search_results.php" method="GET">
                    <div class="input-group w-100">
                        <input class="form-control" name="q" type="search"
                            placeholder="Search by title, author, or ISBN..." aria-label="Search"
                            value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q'], ENT_QUOTES, 'UTF-8') : ''?>"
                            required>
                        <div class="input-group-append">
                            <button class="btn btn-primary px-4" type="submit">Search</button>
                        </div>
                    </div>
                </form>

                <!-- profile dropdown -->
                <ul class="navbar-nav ml-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white font-weight-bold" href="#" id="accountDropdown"
                            role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Hello,
                            <?= htmlspecialchars($_SESSION['full_name'] ?? 'User', ENT_QUOTES, 'UTF-8')?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="accountDropdown">
                            <a class="dropdown-item" href="profile.php">Profile</a>
                            <a class="dropdown-item" href="favorites.php">My Favourites</a>
                            <a class="dropdown-item" href="my_reviews.php">My Reviews</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="logout.php">Logout</a>
                        </div>
                    </li>
                    <?php
else: ?>

                    <li class="nav-item">
                        <a class="btn btn-outline-light font-weight-bold mt-1" href="login.php">Login / Sign Up</a>
                    </li>
                    <?php
endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- breadcrumbs -->
        <?php if (!empty($breadcrumbs)): ?>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <?php foreach ($breadcrumbs as $crumb): ?>
                <?php if (isset($crumb['active']) && $crumb['active']): ?>
                <li class="breadcrumb-item active" aria-current="page">
                    <?= htmlspecialchars($crumb['label'], ENT_QUOTES, 'UTF-8')?>
                </li>
                <?php
        else: ?>
                <li class="breadcrumb-item">
                    <a href="<?= htmlspecialchars($crumb['url'], ENT_QUOTES, 'UTF-8')?>">
                        <?= htmlspecialchars($crumb['label'], ENT_QUOTES, 'UTF-8')?>
                    </a>
                </li>
                <?php
        endif; ?>
                <?php
    endforeach; ?>
            </ol>
        </nav>
        <?php
endif; ?>

        <!-- flash -->
        <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8')?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
        <?php
endif; ?>

        <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8')?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
        <?php
endif; ?>

        <!-- content -->
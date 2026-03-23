<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/models/FavoriteModel.php';

$favoriteModel = new FavoriteModel($db);
$userId = $_SESSION['user_id'];
$error = '';
$success = '';

// handle post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_favorite'])) {
        try {
            $bookId = (int)$_POST['book_id'];
            $favoriteModel->create(['user_id' => $userId, 'book_id' => $bookId]);
            $_SESSION['flash_success'] = "Book successfully added to favourites!";

            // back routing
            if (isset($_SERVER['HTTP_REFERER'])) {
                header("Location: " . $_SERVER['HTTP_REFERER']);
            }
            else {
                header("Location: book_detail.php?id=$bookId");
            }
            exit;
        }
        catch (Exception $e) {
            die($e->getMessage());
        }
    }
    elseif (isset($_POST['remove_favorite'])) {
        try {
            $bookId = (int)$_POST['book_id'];
            $favoriteModel->removeFavorite($userId, $bookId);
            $_SESSION['flash_success'] = "Book removed from favourites.";

            if (isset($_SERVER['HTTP_REFERER'])) {
                header("Location: " . $_SERVER['HTTP_REFERER']);
            }
            else {
                header("Location: favorites.php");
            }
            exit;
        }
        catch (Exception $e) {
            $error = "Unable to process favorite removal.";
        }
    }
}

$favData = ['total' => 0, 'books' => []];
try {
    $books = $favoriteModel->getFavoritesByUser($userId);
    $favData['books'] = $books;
    $favData['total'] = count($books);
}
catch (Exception $e) {
    $error = "Error fetching favorites data.";
}

$pageTitle = "My Favourites";
$breadcrumbs = [
    ['label' => 'Home', 'url' => 'index.php'],
    ['label' => 'Dashboard', 'url' => 'profile.php'],
    ['label' => 'My Favourites', 'url' => 'favorites.php', 'active' => true]
];

require_once __DIR__ . '/includes/header.php';
?>

<div class="row mt-4 mb-5">
    <!-- sidebar -->
    <div class="col-md-3 mb-4">
        <div class="list-group shadow-sm border-0">
            <a href="profile.php" class="list-group-item list-group-item-action p-3">Profile</a>
            <a href="favorites.php" class="list-group-item list-group-item-action active font-weight-bold p-3">My
                Favourites</a>
            <a href="my_reviews.php" class="list-group-item list-group-item-action p-3">My Reviews</a>
            <a href="logout.php" class="list-group-item list-group-item-action text-danger p-3">Logout</a>
        </div>
    </div>

    <!-- favs -->
    <div class="col-md-9">
        <h4 class="mb-4 font-weight-bold text-dark">My Favourites <span class="badge badge-primary rounded-pill ml-2"
                style="font-size: 0.9rem;">
                <?= $favData['total']?>
            </span></h4>

        <?php if (!empty($error)): ?>
        <div class="alert alert-danger shadow-sm border-0">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8')?>
        </div>
        <?php
endif; ?>
        <?php if (!empty($success)): ?>
        <div class="alert alert-success shadow-sm border-0">
            <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8')?>
        </div>
        <?php
endif; ?>

        <?php if (empty($favData['books'])): ?>
        <div class="card shadow-sm border-0">
            <div class="card-body text-center p-5 text-muted">
                <h5 class="mb-3">Your wishlist is empty!</h5>
                <p class="mb-4">Explore our library and save the titles you want to read later.</p>
                <a href="index.php" class="btn btn-primary font-weight-bold shadow-sm px-4">Browse Collection</a>
            </div>
        </div>
        <?php
else: ?>
        <div class="row">
            <?php foreach ($favData['books'] as $fav): ?>
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-0 position-relative">
                    <form action="favorites.php" method="POST" class="position-absolute"
                        style="top: 10px; right: 10px; z-index: 10;">
                        <input type="hidden" name="book_id" value="<?=(int)$fav['book_id']?>">
                        <button type="submit" name="remove_favorite"
                            class="btn btn-light btn-sm rounded-circle shadow-sm text-danger"
                            title="Remove from Favorites"
                            style="width: 35px; height: 35px; padding: 0; line-height: 33px; font-size: 1.2rem;">
                            ❤️
                        </button>
                    </form>
                    <div class="row no-gutters">
                        <div class="col-4">
                            <?php $cover = !empty($fav['cover_image_url']) ? htmlspecialchars($fav['cover_image_url'], ENT_QUOTES, 'UTF-8') : 'https://via.placeholder.com/150'; ?>
                            <a href="book_detail.php?id=<?=(int)$fav['book_id']?>">
                                <img src="<?= $cover?>" class="card-img h-100" alt="Cover" style="object-fit: cover;">
                            </a>
                        </div>
                        <div class="col-8">
                            <div class="card-body d-flex flex-column h-100 py-3 pr-3 pl-2">
                                <h6 class="card-title font-weight-bold mb-1"
                                    style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <a href="book_detail.php?id=<?=(int)$fav['book_id']?>"
                                        class="text-dark text-decoration-none">
                                        <?= htmlspecialchars($fav['title'], ENT_QUOTES, 'UTF-8')?>
                                    </a>
                                </h6>
                                <p class="card-text small text-muted mb-auto">By
                                    <?= htmlspecialchars($fav['author_name'] ?? 'Unknown', ENT_QUOTES, 'UTF-8')?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
    endforeach; ?>
        </div>

        <?php
endif; ?>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
<?php

session_start();
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/includes/Constants.php';
require_once __DIR__ . '/models/BookModel.php';

$bookModel = new BookModel($db);

$query = $_GET['q'] ?? '';
$results = ['total' => 0, 'books' => []];
$error = '';

try {

    $userId = $_SESSION['user_id'] ?? 0;
    $results = $bookModel->getBooksFiltered($query, 0, 'Top Rated', $userId);
}
catch (Exception $e) {
    $error = "Unable to complete search request. Database error logged.";
}

$pageTitle = htmlspecialchars($query, ENT_QUOTES, 'UTF-8') . " - Search Results";
$breadcrumbs = [
    ['label' => 'Home', 'url' => 'index.php'],
    ['label' => 'Global Search', 'url' => '#', 'active' => true]
];

require_once __DIR__ . '/includes/header.php';
?>

<div class="row mt-2 mb-5 justify-content-center">
    <div class="col-md-11">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h3 class="m-0 font-weight-bold text-dark">
                Results for "
                <?= htmlspecialchars($query, ENT_QUOTES, 'UTF-8')?>"
            </h3>
            <span class="badge badge-primary py-2 px-3 align-self-center" style="font-size: 1rem;">
                <?= $results['total']?> Books Found
            </span>
        </div>

        <?php if (!empty($error)): ?>
        <div class="alert alert-danger shadow-sm border-0">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8')?>
        </div>
        <?php
endif; ?>

        <?php if (empty($results['books'])): ?>
        <div class="card shadow-sm border-0">
            <div class="card-body text-center p-5 text-muted">
                <h5 class="mb-3 font-weight-bold">No results found!</h5>
                <p>Try searching using different keywords, author names, or ISBNs.</p>
                <a href="index.php" class="btn btn-outline-primary mt-3">Back to Homepage</a>
            </div>
        </div>
        <?php
else: ?>
        <div class="row">
            <?php foreach ($results['books'] as $book): ?>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm border-0 position-relative">
                    <!-- fav icon -->
                    <?php if (!isset($_SESSION['user_id'])): ?>
                    <a href="login.php"
                        class="position-absolute btn btn-light btn-sm rounded-circle shadow-sm text-secondary"
                        title="Login to Favorite"
                        style="top: 10px; right: 10px; z-index: 10; width: 35px; height: 35px; padding: 0; line-height: 33px; font-size: 1.2rem; text-decoration: none; text-align: center;">
                        🤍
                    </a>
                    <?php
        else: ?>
                    <?php if (!empty($book['is_favorited'])): ?>
                    <form action="favorites.php" method="POST" class="position-absolute"
                        style="top: 10px; right: 10px; z-index: 10;">
                        <input type="hidden" name="book_id" value="<?= $book['id']?>">
                        <button type="submit" name="remove_favorite"
                            class="btn btn-light btn-sm rounded-circle shadow-sm text-danger"
                            title="Remove from Favorites"
                            style="width: 35px; height: 35px; padding: 0; line-height: 33px; font-size: 1.2rem;">
                            ❤️
                        </button>
                    </form>
                    <?php
            else: ?>
                    <form action="favorites.php" method="POST" class="position-absolute"
                        style="top: 10px; right: 10px; z-index: 10;">
                        <input type="hidden" name="book_id" value="<?= $book['id']?>">
                        <button type="submit" name="add_favorite"
                            class="btn btn-light btn-sm rounded-circle shadow-sm text-secondary"
                            title="Add to Favorites"
                            style="width: 35px; height: 35px; padding: 0; line-height: 33px; font-size: 1.2rem;">
                            🤍
                        </button>
                    </form>
                    <?php
            endif; ?>
                    <?php
        endif; ?>

                    <?php $cover = !empty($book['cover_image_url']) ? htmlspecialchars($book['cover_image_url'], ENT_QUOTES, 'UTF-8') : 'https://via.placeholder.com/300x400?text=No+Cover'; ?>
                    <a href="book_detail.php?id=<?= $book['id']?>">
                        <img src="<?= $cover?>" class="card-img-top" alt="Cover"
                            style="height: 250px; object-fit: cover;">
                    </a>
                    <div class="card-body p-3 d-flex flex-column">
                        <h6 class="card-title font-weight-bold text-truncate mb-1"
                            title="<?= htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8')?>">
                            <a href="book_detail.php?id=<?= $book['id']?>" class="text-dark text-decoration-none">
                                <?= htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8')?>
                            </a>
                        </h6>
                        <p class="text-muted small mb-2 text-truncate"
                            title="<?= htmlspecialchars($book['author_name'] ?? 'Unknown', ENT_QUOTES, 'UTF-8')?>">
                            By:
                            <?= htmlspecialchars($book['author_name'] ?? 'Unknown', ENT_QUOTES, 'UTF-8')?>
                        </p>

                        <div class="mb-2 mt-auto">
                            <span class="text-warning" style="font-size: 1.1rem;">
                                <?php
        $rating = round($book['avg_rating']);
        echo str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
?>
                            </span>
                            <small class="text-muted d-block">(
                                <?= $book['review_count']?> Reviews)
                            </small>
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
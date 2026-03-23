<?php

session_start();
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/models/BookModel.php';

$bookModel = new BookModel($db);

$categoryId = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$sort = $_GET['sort'] ?? 'Top Rated';

$categories = [];
$topBooksData = ['total' => 0, 'books' => []];
$error = '';

try {
    $categories = $bookModel->getAllCategories();
    $userId = $_SESSION['user_id'] ?? 0;
    $topBooksData = $bookModel->getBooksFiltered('', $categoryId, $sort, $userId);
}
catch (Exception $e) {
    $error = "Unable to load homepage data.";
}

// map cat
$activeCategoryTitle = "Books";
if ($categoryId > 0) {
    foreach ($categories as $cat) {
        if ((int)$cat['id'] === $categoryId) {
            $activeCategoryTitle = $cat['name'];
            break;
        }
    }
}

$pageTitle = "Home";
require_once __DIR__ . '/includes/header.php';
?>

<div class="row mt-2">
    <!-- sidebar -->
    <div class="col-md-3 mb-4">
        <h5 class="font-weight-bold mb-3">Categories</h5>
        <div class="list-group shadow-sm category-filter">
            <a href="index.php?category=0&sort=<?= urlencode($sort)?>"
                class="list-group-item list-group-item-action <?= $categoryId === 0 ? 'active font-weight-bold' : ''?>">All
                Books</a>
            <?php foreach ($categories as $cat): ?>
            <a href="index.php?category=<?= $cat['id']?>&sort=<?= urlencode($sort)?>"
                class="list-group-item list-group-item-action <?= $categoryId === (int)$cat['id'] ? 'active font-weight-bold' : ''?>">
                <?= htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8')?>
            </a>
            <?php
endforeach; ?>
        </div>
    </div>

    <!-- items -->
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
            <h4 class="m-0 font-weight-bold text-dark" id="results-title">
                <?= htmlspecialchars($activeCategoryTitle, ENT_QUOTES, 'UTF-8')?> <span class="badge badge-secondary"
                    style="font-size: 0.9rem;">
                    <?= count($topBooksData['books'])?>
                </span>
            </h4>
            <div class="d-flex align-items-center">
                <form action="index.php" method="GET" class="m-0 d-flex align-items-center">
                    <input type="hidden" name="category" value="<?= $categoryId?>">
                    <label for="sort-by" class="mr-2 mb-0 font-weight-bold text-muted">Sort by:</label>
                    <select id="sort-by" name="sort" class="custom-select custom-select-sm w-auto shadow-sm"
                        onchange="this.form.submit()">
                        <option value="Top Rated" <?= $sort === 'Top Rated' ? 'selected' : '' ?>>Top Rated</option>
                        <option value="Newest" <?= $sort === 'Newest' ? 'selected' : '' ?>>Newest</option>
                        <option value="A-Z" <?= $sort === 'A-Z' ? 'selected' : '' ?>>A-Z</option>
                    </select>
                </form>
            </div>
        </div>

        <?php if (!empty($error)): ?>
        <div class="alert alert-danger shadow-sm border-0">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8')?>
        </div>
        <?php
endif; ?>

        <?php if (empty($topBooksData['books'])): ?>
        <div class="alert alert-info border-0 shadow-sm text-center p-4">
            <h5>No books found in this category.</h5>
        </div>
        <?php
else: ?>
        <div class="row" id="books-container">
            <!-- list -->
            <?php foreach ($topBooksData['books'] as $book): ?>
            <div class="col-md-4 mb-4">
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

                        <div class="mb-2">
                            <span class="text-warning">
                                <?php
        $rating = round($book['avg_rating']);
        echo str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
?>
                            </span>
                            <small class="text-muted">(
                                <?= $book['review_count']?>)
                            </small>
                        </div>

                        <p class="card-text small text-muted flex-grow-1"
                            style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                            <?= htmlspecialchars($book['description'] ?? 'No description available.', ENT_QUOTES, 'UTF-8')?>
                        </p>
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
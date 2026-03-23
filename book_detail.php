<?php

session_start();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/models/BookModel.php';
require_once __DIR__ . '/models/ReviewModel.php';

$bookId = (int)$_GET['id'];
$bookModel = new BookModel($db);
$reviewModel = new ReviewModel($db);

$book = null;
$authors = [];
$reviews = [];
$error = '';
$success = '';

try {

    $userId = $_SESSION['user_id'] ?? 0;
    $book = $bookModel->getBookDetail($bookId, $userId);
    if (!$book) {
        header("Location: index.php");
        exit;
    }
    $authors = $bookModel->getAuthorsForBook($bookId);
    $reviews = $reviewModel->getReviewsForBook($bookId);

    // avg rating
    $totalRating = 0;
    $reviewCount = count($reviews);
    foreach ($reviews as $rev) {
        $totalRating += (int)$rev['rating'];
    }
    $avgRating = $reviewCount > 0 ? ($totalRating / $reviewCount) : 0;

}
catch (Exception $e) {
    $error = "Error loading book details.";
}

// user review
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    if (!isset($_SESSION['user_id'])) {
        $error = "You must be logged in to submit a review.";
    }
    else {
        $rating = (int)($_POST['rating'] ?? 0);
        $reviewText = trim($_POST['review_text'] ?? '');

        if ($rating < 1 || $rating > 5) {
            $error = "Please provide a valid rating between 1 and 5.";
        }
        else {
            try {
                $reviewModel->create([
                    'user_id' => $_SESSION['user_id'],
                    'book_id' => $bookId,
                    'rating' => $rating,
                    'review_text' => $reviewText
                ]);

                $_SESSION['flash_success'] = "Your review was successfully added.";
                header("Location: book_detail.php?id=$bookId");
                exit;
            }
            catch (Exception $e) {
                $error = "Failed to submit review. You may have already reviewed this book.";
            }
        }
    }
}

$pageTitle = $book['title'] ?? 'Book Details';
$breadcrumbs = [
    ['label' => 'Home', 'url' => 'index.php'],
    ['label' => 'Categories', 'url' => 'index.php'],
    ['label' => htmlspecialchars($book['category_name'] ?? 'Uncategorized', ENT_QUOTES, 'UTF-8'), 'url' => 'index.php?category=' . $book['category_id']],
    ['label' => htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8'), 'url' => '#', 'active' => true]
];
require_once __DIR__ . '/includes/header.php';
?>

<div class="row mt-4 mb-5">
    <?php if (!empty($error)): ?>
    <div class="col-12">
        <div class="alert alert-danger shadow-sm">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8')?>
        </div>
    </div>
    <?php
endif; ?>
    <?php if (isset($_SESSION['flash_success'])): ?>
    <div class="col-12">
        <div class="alert alert-success shadow-sm">
            <?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8')?>
        </div>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
    <?php
endif; ?>

    <!-- cover & rating -->
    <div class="col-md-4 mb-4 text-center">
        <?php $cover = !empty($book['cover_image_url']) ? htmlspecialchars($book['cover_image_url'], ENT_QUOTES, 'UTF-8') : 'https://via.placeholder.com/400x600?text=No+Cover'; ?>
        <img src="<?= $cover?>" class="img-fluid rounded shadow mb-3" alt="Cover"
            style="max-height: 500px; object-fit: cover; width: 100%;">

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body p-3">
                <h4 class="mb-1 text-warning">
                    <?= str_repeat('★', round($avgRating)) . str_repeat('☆', 5 - round($avgRating))?>
                </h4>
                <p class="text-muted small font-weight-bold mb-0">
                    <?= number_format($avgRating, 1)?> Average Rating &middot;
                    <?= $reviewCount?> Reviews
                </p>
            </div>
        </div>

        <?php if (!isset($_SESSION['user_id'])): ?>
        <a href="login.php" class="btn btn-outline-primary btn-block font-weight-bold shadow-sm py-2">🤍 Login to Add
            Favourite</a>
        <?php
else: ?>
        <?php if (!empty($book['is_favorited'])): ?>
        <form action="favorites.php" method="POST">
            <input type="hidden" name="book_id" value="<?= $bookId?>">
            <button type="submit" name="remove_favorite"
                class="btn btn-outline-danger btn-block font-weight-bold shadow-sm py-2">
                ❤️ Remove from Favourites
            </button>
        </form>
        <?php
    else: ?>
        <form action="favorites.php" method="POST">
            <input type="hidden" name="book_id" value="<?= $bookId?>">
            <button type="submit" name="add_favorite"
                class="btn btn-outline-primary btn-block font-weight-bold shadow-sm py-2">
                🤍 Add to Favourites
            </button>
        </form>
        <?php
    endif; ?>
        <?php
endif; ?>
    </div>

    <!-- info -->
    <div class="col-md-8">
        <h2 class="font-weight-bold mb-2 text-dark">
            <?= htmlspecialchars($book['title'], ENT_QUOTES, 'UTF-8')?>
        </h2>

        <h5 class="text-muted mb-3 pb-3 border-bottom">
            By
            <?php if (!empty($authors)): ?>
            <?= implode(', ', array_map(function ($a) {
        return htmlspecialchars($a['full_name'], ENT_QUOTES, 'UTF-8'); }, $authors))?>
            <?php
else: ?>
            Unknown Author
            <?php
endif; ?>
        </h5>

        <div class="row mb-4">
            <div class="col-md-4">
                <p class="mb-1 text-muted small text-uppercase font-weight-bold">Category</p>
                <p class="font-weight-bold"><span class="badge badge-primary px-3 py-2">
                        <?= htmlspecialchars($book['category_name'] ?? 'Uncategorized', ENT_QUOTES, 'UTF-8')?>
                    </span></p>
            </div>
            <div class="col-md-4">
                <p class="mb-1 text-muted small text-uppercase font-weight-bold">Publication Year</p>
                <p class="font-weight-bold">
                    <?=(int)$book['publication_year']?>
                </p>
            </div>
            <div class="col-md-4">
                <p class="mb-1 text-muted small text-uppercase font-weight-bold">ISBN</p>
                <p class="font-weight-bold">
                    <?= htmlspecialchars($book['isbn'] ?? 'N/A', ENT_QUOTES, 'UTF-8')?>
                </p>
            </div>
        </div>

        <h5 class="font-weight-bold mt-4 mb-3">Description</h5>
        <div class="bg-white p-4 rounded shadow-sm border-0 text-justify text-muted"
            style="line-height: 1.8; font-size: 1.05rem;">
            <?= nl2br(htmlspecialchars($book['description'] ?? 'No description provided.', ENT_QUOTES, 'UTF-8'))?>
        </div>
    </div>
</div>

<hr class="my-5">

<!-- reviews -->
<div class="row justify-content-center">
    <div class="col-md-10">
        <h4 class="font-weight-bold mb-4 text-center">Community Reviews</h4>

        <!-- review form -->
        <div class="card shadow-sm border-0 mb-5">
            <div class="card-header bg-primary text-white font-weight-bold">
                Leave a Review
            </div>
            <div class="card-body p-4 bg-light">
                <?php if (isset($_SESSION['user_id'])): ?>
                <form action="book_detail.php?id=<?= $bookId?>" method="POST">
                    <div class="form-group">
                        <label class="font-weight-bold text-secondary">Star Rating</label>
                        <select name="rating" class="form-control custom-select w-25 shadow-sm" required>
                            <option value="5" selected>5 Stars - Excellent</option>
                            <option value="4">4 Stars - Very Good</option>
                            <option value="3">3 Stars - Good</option>
                            <option value="2">2 Stars - Fair</option>
                            <option value="1">1 Star - Poor</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-secondary">Your Comment</label>
                        <textarea name="review_text" class="form-control shadow-sm border-0" rows="4"
                            placeholder="Share your thoughts about this book..." required></textarea>
                    </div>
                    <button type="submit" name="submit_review"
                        class="btn btn-primary font-weight-bold shadow-sm px-4">Submit Review</button>
                </form>
                <?php
else: ?>
                <div class="text-center py-4">
                    <p class="text-muted">Please log in or sign up to leave a review for this book.</p>
                    <a href="login.php" class="btn btn-primary shadow-sm font-weight-bold">Login to Review</a>
                </div>
                <?php
endif; ?>
            </div>
        </div>

        <!-- comments -->
        <?php if (empty($reviews)): ?>
        <div class="alert alert-secondary text-center border-0 shadow-sm py-4">
            No reviews yet. Be the first to share your thoughts!
        </div>
        <?php
else: ?>
        <div class="list-group shadow-sm">
            <?php foreach ($reviews as $review): ?>
            <div class="list-group-item list-group-item-action border-0 mb-2 rounded pt-4 pb-4">
                <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                    <h6 class="mb-0 font-weight-bold text-primary">

                        <span class="badge badge-pill badge-primary mr-2" style="font-size: 1.1em;">👤</span>
                        <?= htmlspecialchars($review['reviewer_name'], ENT_QUOTES, 'UTF-8')?>
                    </h6>
                    <small class="text-muted font-italic">
                        <?= date('M j, Y', strtotime($review['created_at']))?>
                    </small>
                </div>
                <div class="mb-2 text-warning" style="font-size: 1.2rem;">
                    <?php
        $r = (int)$review['rating'];
        echo str_repeat('★', $r) . str_repeat('☆', 5 - $r);
?>
                </div>
                <p class="mb-0 text-muted" style="line-height: 1.6;">
                    <?= nl2br(htmlspecialchars($review['review_text'], ENT_QUOTES, 'UTF-8'))?>
                </p>
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
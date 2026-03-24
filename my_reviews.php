<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/includes/Constants.php';
require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/models/ReviewModel.php';

$reviewModel = new ReviewModel($db);
$userId = $_SESSION['user_id'];
$error = '';
$success = '';

// handle post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_review'])) {
        $reviewId = (int)$_POST['review_id'];
        try {
            if ($reviewModel->deleteReview($reviewId, $userId)) {
                $_SESSION['flash_success'] = "Review deleted successfully.";
                header("Location: my_reviews.php");
                exit;
            } else {
                $error = "Failed to delete review. You might not have permission.";
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    } elseif (isset($_POST['update_review'])) {
        $reviewId = (int)$_POST['review_id'];
        $rating = (int)$_POST['rating'];
        $reviewText = trim($_POST['review_text']);

        if ($rating < 1 || $rating > 5 || empty($reviewText)) {
            $error = "Please provide a valid rating and review text.";
        } else {
            try {
                if ($reviewModel->updateReview($reviewId, $userId, $rating, $reviewText)) {
                    $_SESSION['flash_success'] = "Review updated successfully.";
                    header("Location: my_reviews.php");
                    exit;
                } else {
                    $error = "Failed to update review. You might not have permission or no changes were made.";
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
        }
    }
}

$myReviews = [];
try {
    $myReviews = $reviewModel->getReviewsByUser($userId);
} catch (Exception $e) {
    $error = "Error fetching user reviews.";
}

$pageTitle = "My Reviews";
$breadcrumbs = [
    ['label' => 'Home', 'url' => 'index.php'],
    ['label' => 'Dashboard', 'url' => 'profile.php'],
    ['label' => 'My Reviews', 'url' => 'my_reviews.php', 'active' => true]
];

require_once __DIR__ . '/includes/header.php';
?>

<div class="row mt-4 mb-5">
    <!-- sidebar -->
    <div class="col-md-3 mb-4">
        <div class="list-group shadow-sm border-0">
            <a href="profile.php" class="list-group-item list-group-item-action p-3">Profile</a>
            <a href="favorites.php" class="list-group-item list-group-item-action p-3">My Favourites</a>
            <a href="my_reviews.php" class="list-group-item list-group-item-action font-weight-bold active p-3">My Reviews</a>
            <a href="logout.php" class="list-group-item list-group-item-action text-danger p-3">Logout</a>
        </div>
    </div>

    <!-- list -->
    <div class="col-md-9">
        <h4 class="mb-4 font-weight-bold text-dark">My Reviews</h4>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger shadow-sm border-0"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success shadow-sm border-0"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <?php if (empty($myReviews)): ?>
            <div class="card shadow-sm border-0">
                <div class="card-body text-center p-5 text-muted">
                    <h5 class="mb-3">Start reviewing!</h5>
                    <p class="mb-4">You haven't left any reviews yet. Help the community by sharing your thoughts.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="list-group shadow-sm border-0">
                <?php foreach ($myReviews as $review): ?>
                    <div class="list-group-item list-group-item-action border-0 mb-3 rounded border shadow-sm p-4">
                        <div class="d-flex w-100 justify-content-between align-items-center mb-2">
                            <h5 class="mb-0 font-weight-bold">
                                <a href="book_detail.php?id=<?= $review['book_id'] ?>" class="text-primary text-decoration-none">
                                    <?= htmlspecialchars($review['book_title'] ?? 'Unknown Book', ENT_QUOTES, 'UTF-8') ?>
                                </a>
                            </h5>
                            <small class="text-muted font-italic"><?= date('M j, Y', strtotime($review['created_at'])) ?></small>
                        </div>

                        <div class="mb-3 text-warning lead">
                            <?php 
                            $r = (int)$review['rating'];
                            echo str_repeat('★', $r) . str_repeat('☆', 5 - $r);
                            ?>
                        </div>

                        <p class="mb-3 text-muted text-justify" style="line-height: 1.6;">
                            <?= nl2br(htmlspecialchars($review['review_text'], ENT_QUOTES, 'UTF-8')) ?>
                        </p>

                        <!-- actions -->
                        <div class="d-flex justify-content-end border-top pt-3">
                            <button type="button" class="btn btn-outline-primary btn-sm px-4 font-weight-bold shadow-sm mr-2" data-toggle="modal" data-target="#editReviewModal<?= (int)$review['id'] ?>">
                                Edit Review
                            </button>
                            <form action="my_reviews.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this review? This action cannot be undone.');">
                                <input type="hidden" name="review_id" value="<?= (int)$review['id'] ?>">
                                <button type="submit" name="delete_review" class="btn btn-outline-danger btn-sm px-4 font-weight-bold shadow-sm">
                                    Delete Review
                                </button>
                            </form>
                        </div>


                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($myReviews)): ?>
    <?php foreach ($myReviews as $review): ?>
        <!-- edit modal -->
        <div class="modal fade" id="editReviewModal<?= (int)$review['id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0 shadow">
                    <form action="my_reviews.php" method="POST">
                        <div class="modal-header bg-light">
                            <h5 class="modal-title font-weight-bold text-dark">Edit Review</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="review_id" value="<?= (int)$review['id'] ?>">
                            <div class="form-group">
                                <label class="font-weight-bold text-muted">Rating</label>
                                <select name="rating" class="custom-select shadow-sm" required>
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                        <option value="<?= $i ?>" <?= (int)$review['rating'] === $i ? 'selected' : '' ?>><?= $i ?> Star<?= $i > 1 ? 's' : '' ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-bold text-muted">Review</label>
                                <textarea name="review_text" class="form-control shadow-sm" rows="4" required><?= htmlspecialchars($review['review_text'], ENT_QUOTES, 'UTF-8') ?></textarea>
                            </div>
                        </div>
                        <div class="modal-footer bg-light border-0">
                            <button type="button" class="btn btn-outline-secondary font-weight-bold px-4" data-dismiss="modal">Cancel</button>
                            <button type="submit" name="update_review" class="btn btn-primary font-weight-bold px-4">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php
require_once __DIR__ . '/includes/footer.php';
?>

<?php

session_start();

header('Content-Type: application/json');

// auth check
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'You must be logged in to submit a review.']);
    exit;
}

// POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/models/ReviewModel.php';

$bookId = isset($_POST['book_id']) ? (int)$_POST['book_id'] : 0;
$rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
$reviewText = isset($_POST['review_text']) ? trim($_POST['review_text']) : '';

// validate book_id
if ($bookId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid book ID.']);
    exit;
}

// validate rating
if ($rating < 1 || $rating > 5) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please provide a valid rating between 1 and 5.']);
    exit;
}

// validate review text
if (empty($reviewText)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Review text cannot be empty.']);
    exit;
}

$userId = (int)$_SESSION['user_id'];
$reviewModel = new ReviewModel($db);

try {
    // insert review via model
    $newId = $reviewModel->create([
        'user_id' => $userId,
        'book_id' => $bookId,
        'rating' => $rating,
        'review_text' => $reviewText
    ]);

    // build star string for frontend
    $stars = str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);

    // sanitize for safe DOM insertion
    $safeReviewerName = htmlspecialchars($_SESSION['full_name'] ?? 'User', ENT_QUOTES, 'UTF-8');
    $safeReviewText = htmlspecialchars($reviewText, ENT_QUOTES, 'UTF-8');
    $dateFormatted = date('M j, Y');

    echo json_encode([
        'success' => true,
        'message' => 'Your review was successfully added.',
        'review' => [
            'id' => $newId,
            'reviewer_name' => $safeReviewerName,
            'rating' => $rating,
            'stars' => $stars,
            'review_text' => $safeReviewText,
            'created_at' => $dateFormatted
        ]
    ]);
} catch (Exception $e) {
    Logger::error("submit_review.php", $e);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to submit review. You may have already reviewed this book.']);
}

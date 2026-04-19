<?php

session_start();

header('Content-Type: application/json');

// auth check
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
    exit;
}

// POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

require_once __DIR__ . '/includes/db_connect.php';
require_once __DIR__ . '/models/FavoriteModel.php';

$bookId = isset($_POST['book_id']) ? (int)$_POST['book_id'] : 0;

if ($bookId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid book ID.']);
    exit;
}

$userId = (int)$_SESSION['user_id'];
$favoriteModel = new FavoriteModel($db);

try {
    // check if favorite record exists
    $sqlCheck = "SELECT id, is_deleted FROM favorites WHERE user_id = ? AND book_id = ?";
    $stmtCheck = $db->prepare($sqlCheck);
    $stmtCheck->bind_param("ii", $userId, $bookId);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        if ($row['is_deleted'] == 0) {
            // already favorited -> soft delete
            $favoriteModel->removeFavorite($userId, $bookId);
            echo json_encode([
                'success' => true,
                'action' => 'removed',
                'message' => 'Book removed from favourites.'
            ]);
        } else {
            // soft-deleted -> restore
            $favoriteModel->create(['user_id' => $userId, 'book_id' => $bookId]);
            echo json_encode([
                'success' => true,
                'action' => 'added',
                'message' => 'Book added to favourites!'
            ]);
        }
    } else {
        // no record -> insert new
        $favoriteModel->create(['user_id' => $userId, 'book_id' => $bookId]);
        echo json_encode([
            'success' => true,
            'action' => 'added',
            'message' => 'Book added to favourites!'
        ]);
    }
} catch (Exception $e) {
    Logger::error("toggle_favorite.php", $e);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'A server error occurred. Please try again.']);
}

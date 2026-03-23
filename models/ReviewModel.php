<?php

require_once __DIR__ . '/BaseModel.php';

class ReviewModel extends BaseModel
{
    protected string $table = 'reviews';

    public function create(array $data)
    {
        try {
            $sql = "INSERT INTO reviews (user_id, book_id, rating, review_text, created_at, updated_at) 
                    VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->db->error);
            }

            $stmt->bind_param("iiis", $data['user_id'], $data['book_id'], $data['rating'], $data['review_text']);
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            return $stmt->insert_id;
        }
        catch (Exception $e) {
            Logger::error("ReviewModel Create", $e);
            throw new Exception("A database error occurred. We have logged the issue.");
        }
    }

    public function updateReview(int $reviewId, int $userId, int $rating, string $reviewText): bool
    {
        try {
            $sql = "UPDATE reviews SET rating = ?, review_text = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ? AND user_id = ? AND is_deleted = 0";
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->db->error);
            }

            $stmt->bind_param("isii", $rating, $reviewText, $reviewId, $userId);
            $stmt->execute();
            return $stmt->affected_rows > 0;
        }
        catch (Exception $e) {
            Logger::error("ReviewModel updateReview", $e);
            throw new Exception("A database error occurred. We have logged the issue.");
        }
    }

    public function update(int $id, array $data): bool
    {
        return false;
    }

    public function getReviewsForBook(int $bookId): array
    {
        try {
            // user data
            $sql = "SELECT r.*, u.full_name AS reviewer_name 
                    FROM reviews r
                    JOIN users u ON r.user_id = u.id AND u.is_deleted = 0
                    WHERE r.book_id = ? AND r.is_deleted = 0 
                    ORDER BY r.created_at DESC";

            $stmt = $this->db->prepare($sql);
            if (!$stmt)
                throw new Exception("Prepare failed: " . $this->db->error);

            $stmt->bind_param("i", $bookId);
            $stmt->execute();
            $result = $stmt->get_result();

            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
        catch (Exception $e) {
            Logger::error("ReviewModel getReviewsForBook", $e);
            throw new Exception("A database error occurred. We have logged the issue.");
        }
    }

    public function getBookRatingStats(int $bookId): array
    {
        try {
            $sql = "SELECT IFNULL(AVG(rating), 0) as avg_rating, COUNT(id) as total_reviews 
                    FROM reviews 
                    WHERE book_id = ? AND is_deleted = 0";

            $stmt = $this->db->prepare($sql);
            if (!$stmt)
                throw new Exception("Prepare failed: " . $this->db->error);

            $stmt->bind_param("i", $bookId);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            return [
                'avg_rating' => (float)$row['avg_rating'],
                'total_reviews' => (int)$row['total_reviews']
            ];
        }
        catch (Exception $e) {
            Logger::error("ReviewModel getBookRatingStats", $e);
            throw new Exception("A database error occurred. We have logged the issue.");
        }
    }

    public function getReviewsByUser(int $userId): array
    {
        try {
            // load books with reviews
            $sql = "SELECT r.*, b.title AS book_title 
                    FROM reviews r
                    JOIN books b ON r.book_id = b.id AND b.is_deleted = 0
                    WHERE r.user_id = ? AND r.is_deleted = 0 
                    ORDER BY r.created_at DESC";

            $stmt = $this->db->prepare($sql);
            if (!$stmt)
                throw new Exception("Prepare failed: " . $this->db->error);

            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
        catch (Exception $e) {
            Logger::error("ReviewModel getReviewsByUser", $e);
            throw new Exception("A database error occurred. We have logged the issue.");
        }
    }

    public function deleteReview(int $reviewId, int $userId): bool
    {
        try {
            $sql = "UPDATE reviews SET is_deleted = 1, deleted_at = CURRENT_TIMESTAMP WHERE id = ? AND user_id = ?";
            $stmt = $this->db->prepare($sql);
            if (!$stmt)
                throw new Exception("Prepare failed: " . $this->db->error);
            $stmt->bind_param("ii", $reviewId, $userId);
            return $stmt->execute();
        }
        catch (Exception $e) {
            Logger::error("ReviewModel deleteReview", $e);
            throw new Exception("A database error occurred. We have logged the issue.");
        }
    }
}
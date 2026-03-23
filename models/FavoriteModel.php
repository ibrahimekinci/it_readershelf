<?php

require_once __DIR__ . '/BaseModel.php';

class FavoriteModel extends BaseModel
{
    protected string $table = 'favorites';

    public function create(array $data)
    {
        try {
            // fav exists?
            $sqlCheck = "SELECT id, is_deleted FROM {$this->table} WHERE user_id = ? AND book_id = ?";
            $stmtCheck = $this->db->prepare($sqlCheck);
            if (!$stmtCheck)
                throw new Exception("Prepare failed: " . $this->db->error);

            $stmtCheck->bind_param("ii", $data['user_id'], $data['book_id']);
            $stmtCheck->execute();
            $result = $stmtCheck->get_result();

            if ($row = $result->fetch_assoc()) {
                if ($row['is_deleted'] == 1) {
                    // restore fav
                    $sqlRest = "UPDATE {$this->table} SET is_deleted = 0, deleted_at = NULL WHERE id = ?";
                    $stmtRest = $this->db->prepare($sqlRest);
                    $stmtRest->bind_param("i", $row['id']);
                    $stmtRest->execute();
                }
                return $row['id'];
            }

            $sql = "INSERT INTO {$this->table} (user_id, book_id, created_at) VALUES (?, ?, CURRENT_TIMESTAMP)";
            $stmt = $this->db->prepare($sql);
            if (!$stmt)
                throw new Exception("Prepare failed: " . $this->db->error);

            $stmt->bind_param("ii", $data['user_id'], $data['book_id']);
            $stmt->execute();
            return $stmt->insert_id;

        }
        catch (Exception $e) {
            Logger::error("FavoriteModel Create", $e);
            throw new Exception("A database error occurred. We have logged the issue.");
        }
    }

    public function update(int $id, array $data): bool
    {
        return false;
    }

    public function getFavoritesByUser(int $userId): array
    {
        try {
            $sql = "SELECT f.*, b.title, b.cover_image_url, GROUP_CONCAT(a.full_name SEPARATOR ', ') as author_name 
                    FROM {$this->table} f
                    JOIN books b ON f.book_id = b.id AND b.is_deleted = 0
                    LEFT JOIN book_authors ba ON b.id = ba.book_id
                    LEFT JOIN authors a ON ba.author_id = a.id AND a.is_deleted = 0
                    WHERE f.user_id = ? AND f.is_deleted = 0
                    GROUP BY f.id
                    ORDER BY f.created_at DESC";

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
            Logger::error("FavoriteModel getFavoritesByUser", $e);
            throw new Exception("A database error occurred. We have logged the issue.");
        }
    }



    public function removeFavorite(int $userId, int $bookId): bool
    {
        try {
            $sql = "UPDATE {$this->table} SET is_deleted = 1, deleted_at = CURRENT_TIMESTAMP WHERE user_id = ? AND book_id = ?";
            $stmt = $this->db->prepare($sql);
            if (!$stmt)
                throw new Exception("Prepare failed: " . $this->db->error);

            $stmt->bind_param("ii", $userId, $bookId);
            return $stmt->execute();
        }
        catch (Exception $e) {
            Logger::error("FavoriteModel removeFavorite", $e);
            throw new Exception("A database error occurred. We have logged the issue.");
        }
    }
}
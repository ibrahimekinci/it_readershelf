<?php

require_once __DIR__ . '/BaseModel.php';

class BookModel extends BaseModel
{
    protected string $table = 'books';

    public function create(array $data)
    {
        $sql = "INSERT INTO books (title, description, cover_image_url, category_id, publication_year, isbn, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $stmt = $this->db->prepare($sql);
        if (!$stmt)
            throw new Exception("Prepare failed: " . $this->db->error);

        $stmt->bind_param("sssiss",
            $data['title'], $data['description'], $data['cover_image_url'],
            $data['category_id'], $data['publication_year'], $data['isbn']
        );
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function update(int $id, array $data): bool
    {
        return false;
    }

    public function getPromotedBooks(int $limit = 4): array
    {
        try {
            // load basic info
            $sql = "SELECT b.id, b.title, b.cover_image_url, b.description,
                           GROUP_CONCAT(DISTINCT a.full_name SEPARATOR ', ') as author_name,
                           IFNULL(AVG(r.rating), 0) as avg_rating,
                           COUNT(DISTINCT r.id) as review_count
                    FROM books b
                    JOIN promoted_books pb ON b.id = pb.book_id
                    LEFT JOIN book_authors ba ON b.id = ba.book_id
                    LEFT JOIN authors a ON ba.author_id = a.id AND a.is_deleted = 0
                    LEFT JOIN reviews r ON b.id = r.book_id AND r.is_deleted = 0
                    WHERE b.is_deleted = 0
                    GROUP BY b.id, b.title, b.cover_image_url, b.description, pb.display_order
                    ORDER BY pb.display_order ASC
                    LIMIT ?";
            $stmt = $this->db->prepare($sql);
            if (!$stmt)
                throw new Exception("Prepare failed: " . $this->db->error);

            $stmt->bind_param("i", $limit);
            $stmt->execute();
            $result = $stmt->get_result();

            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
        catch (Exception $e) {
            Logger::error("BookModel getPromotedBooks", $e);
            throw new Exception("A database error occurred. We have logged the issue.");
        }
    }

    public function getAllCategories(): array
    {
        try {
            $sql = "SELECT id, name FROM categories WHERE is_deleted = 0 ORDER BY name ASC";
            $res = $this->db->query($sql);
            $cats = [];
            if ($res) {
                while ($r = $res->fetch_assoc()) {
                    $cats[] = $r;
                }
            }
            return $cats;
        }
        catch (Exception $e) {
            Logger::error("BookModel getAllCategories", $e);
            throw new Exception("A database error occurred. We have logged the issue.");
        }
    }

    public function getBooksFiltered(string $query = '', int $categoryId = 0, string $sort = 'Top Rated', int $userId = 0): array
    {
        $data = ['total' => 0, 'books' => []];
        try {
            $where = "b.is_deleted = 0";

            if (!empty($query)) {
                $where .= " AND (b.title LIKE ? OR a.full_name LIKE ? OR b.isbn LIKE ?)";
            }
            if ($categoryId > 0) {
                $where .= " AND b.category_id = ?";
            }

            $joins = "FROM books b
                      LEFT JOIN book_authors ba ON b.id = ba.book_id
                      LEFT JOIN authors a ON ba.author_id = a.id AND a.is_deleted = 0
                      LEFT JOIN reviews r ON b.id = r.book_id AND r.is_deleted = 0";

            $orderClause = "ORDER BY b.created_at DESC";
            if ($sort === 'Top Rated') {
                $orderClause = "ORDER BY avg_rating DESC, review_count DESC";
            }
            elseif ($sort === 'A-Z') {
                $orderClause = "ORDER BY b.title ASC";
            }

            $favoritedSelect = $userId > 0 ? ", (SELECT COUNT(*) FROM favorites f WHERE f.book_id = b.id AND f.user_id = $userId AND f.is_deleted = 0) as is_favorited" : ", 0 as is_favorited";

            $sqlBooks = "SELECT b.id, b.title, b.cover_image_url, b.description, b.publication_year,
                                GROUP_CONCAT(DISTINCT a.full_name SEPARATOR ', ') as author_name,
                                IFNULL(AVG(r.rating), 0) as avg_rating,
                                COUNT(DISTINCT r.id) as review_count
                                $favoritedSelect
                         $joins
                         WHERE $where
                         GROUP BY b.id, b.title, b.cover_image_url, b.description, b.publication_year
                         $orderClause";

            $stmtB = $this->db->prepare($sqlBooks);
            if (!$stmtB)
                throw new Exception("Prepare failed on distinct data query: " . $this->db->error);

            if (!empty($query) && $categoryId > 0) {
                $qStr = '%' . $query . '%';
                $stmtB->bind_param("sssi", $qStr, $qStr, $qStr, $categoryId);
            }
            elseif (!empty($query)) {
                $qStr = '%' . $query . '%';
                $stmtB->bind_param("sss", $qStr, $qStr, $qStr);
            }
            elseif ($categoryId > 0) {
                $stmtB->bind_param("i", $categoryId);
            }

            $stmtB->execute();
            $resB = $stmtB->get_result();
            while ($rowB = $resB->fetch_assoc()) {
                $data['books'][] = $rowB;
            }

            $data['total'] = count($data['books']);
            return $data;
        }
        catch (Exception $e) {
            Logger::error("BookModel getBooksFiltered", $e);
            throw new Exception("A database error occurred. We have logged the issue.");
        }
    }

    public function getBookDetail(int $bookId, int $userId = 0): ?array
    {
        try {
            $favoritedSelect = $userId > 0 ? ", (SELECT COUNT(*) FROM favorites f WHERE f.book_id = b.id AND f.user_id = $userId AND f.is_deleted = 0) as is_favorited" : ", 0 as is_favorited";

            $sql = "SELECT b.*, c.name AS category_name $favoritedSelect
                    FROM books b
                    LEFT JOIN categories c ON b.category_id = c.id AND c.is_deleted = 0
                    WHERE b.id = ? AND b.is_deleted = 0 LIMIT 1";
            $stmt = $this->db->prepare($sql);
            if (!$stmt)
                throw new Exception("Prepare failed: " . $this->db->error);

            $stmt->bind_param("i", $bookId);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc() ?: null;
        }
        catch (Exception $e) {
            Logger::error("BookModel getBookDetail", $e);
            throw new Exception("A database error occurred. We have logged the issue.");
        }
    }

    public function getAuthorsForBook(int $bookId): array
    {
        try {
            $sql = "SELECT a.* 
                    FROM authors a
                    JOIN book_authors ba ON a.id = ba.author_id
                    WHERE ba.book_id = ? AND a.is_deleted = 0
                    ORDER BY ba.display_order ASC";
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
            Logger::error("BookModel getAuthorsForBook", $e);
            throw new Exception("A database error occurred. We have logged the issue.");
        }
    }
}
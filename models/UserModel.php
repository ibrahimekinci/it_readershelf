<?php

require_once __DIR__ . '/BaseModel.php';

class UserModel extends BaseModel
{
    protected string $table = 'users';

    public function create(array $data)
    {
        $this->db->begin_transaction();

        try {
            $sqlUsers = "INSERT INTO users (full_name, email, created_at, updated_at) VALUES (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
            $stmtUsers = $this->db->prepare($sqlUsers);
            if (!$stmtUsers)
                throw new Exception("Prepare failed: " . $this->db->error);
            $stmtUsers->bind_param("ss", $data['full_name'], $data['email']);
            $stmtUsers->execute();
            $userId = $stmtUsers->insert_id;

            $sqlCreds = "INSERT INTO user_credentials (user_id, password_hash, updated_at) VALUES (?, ?, CURRENT_TIMESTAMP)";
            $stmtCreds = $this->db->prepare($sqlCreds);
            if (!$stmtCreds)
                throw new Exception("Prepare failed: " . $this->db->error);

            $hash = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmtCreds->bind_param("is", $userId, $hash);
            $stmtCreds->execute();

            $this->db->commit();
            return $userId;
        }
        catch (Exception $e) {
            $this->db->rollback();
            Logger::error("UserModel Create", $e);
            throw new Exception("A database error occurred. We have logged the issue.");
        }
    }

    public function findById(int $id): ?array
    {
        try {
            $sql = "SELECT u.*, uc.password_hash 
                    FROM users u 
                    JOIN user_credentials uc ON u.id = uc.user_id 
                    WHERE u.id = ? AND u.is_deleted = 0 LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc() ?: null;
        }
        catch (Exception $e) {
            Logger::error("UserModel findById", $e);
            throw new Exception("A database error occurred. We have logged the issue.");
        }
    }

    public function update(int $id, array $data): bool
    {
        try {
            $sql = "UPDATE users SET full_name = ?, email = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ssi", $data['full_name'], $data['email'], $id);
            return $stmt->execute();
        }
        catch (Exception $e) {
            Logger::error("UserModel Update", $e);
            throw new Exception("A database error occurred. We have logged the issue.");
        }
    }

    public function updatePassword(int $userId, string $newPassword): bool
    {
        try {
            $hash = password_hash($newPassword, PASSWORD_DEFAULT);
            $sql = "UPDATE user_credentials SET password_hash = ?, updated_at = CURRENT_TIMESTAMP WHERE user_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("si", $hash, $userId);
            return $stmt->execute();
        }
        catch (Exception $e) {
            Logger::error("UserModel updatePassword", $e);
            throw new Exception("A database error occurred. We have logged the issue.");
        }
    }

    public function findByEmail(string $email): ?array
    {
        try {
            // verify auth hash
            $sql = "SELECT u.*, uc.password_hash 
                    FROM users u 
                    JOIN user_credentials uc ON u.id = uc.user_id 
                    WHERE u.email = ? AND u.is_deleted = 0 
                    LIMIT 1";

            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->db->error);
            }

            $stmt->bind_param("s", $email);
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                return $row;
            }

            return null;
        }
        catch (Exception $e) {
            Logger::error("UserModel findByEmail", $e);
            throw new Exception("A database error occurred. We have logged the issue.");
        }
    }
}
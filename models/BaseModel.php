<?php

abstract class BaseModel
{

    protected mysqli $db;

    protected string $table;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        $data = [];
        try {

            $sql = "SELECT * FROM {$this->table} WHERE is_deleted = 0";


            $result = $this->db->query($sql);

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
            else {
                throw new Exception("Query Failed in findAll(): " . $this->db->error);
            }
        }
        catch (Exception $e) {
            Logger::error("DB Error in findAll() for table {$this->table}", $e);
            throw new Exception("A database error occurred. We have logged the issue.");
        }
        return $data;
    }

    public function findById(int $id): ?array
    {
        try {

            $sql = "SELECT * FROM {$this->table} WHERE id = ? AND is_deleted = 0 LIMIT 1";
            $stmt = $this->db->prepare($sql);

            if (!$stmt) {
                throw new Exception("Prepare Failed in findById(): " . $this->db->error);
            }

            $stmt->bind_param("i", $id);
            if (!$stmt->execute()) {
                throw new Exception("Execute Failed in findById(): " . $stmt->error);
            }

            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                return $row;
            }

            return null;
        }
        catch (Exception $e) {
            Logger::error("DB Error in findById() for table {$this->table}", $e);
            throw new Exception("A database error occurred. We have logged the issue.");
        }
    }

    public function softDelete(int $id): bool
    {
        try {


            $sql = "UPDATE {$this->table} SET is_deleted = 1, deleted_at = CURRENT_TIMESTAMP, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
            $stmt = $this->db->prepare($sql);

            if (!$stmt) {
                throw new Exception("Prepare Failed in softDelete(): " . $this->db->error);
            }

            $stmt->bind_param("i", $id);
            if (!$stmt->execute()) {
                throw new Exception("Execute Failed in softDelete(): " . $stmt->error);
            }
            return true;
        }
        catch (Exception $e) {
            Logger::error("DB Error in softDelete() for table {$this->table}", $e);
            throw new Exception("A database error occurred. We have logged the issue.");
        }
    }

    abstract public function create(array $data);

    abstract public function update(int $id, array $data);
}
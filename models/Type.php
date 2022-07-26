<?php
class Type
{
    // Connection
    private $conn;
    // Table
    private $db_table = 'bonus_type';
    // Columns
    public $id;
    public $type;
    public $saved_minutes;
    // Db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }
    // GET ALL
    public function read()
    {
        $sqlQuery = "SELECT * FROM $this->db_table";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    // CREATE
    public function create()
    {
        $sqlQuery =
            "INSERT INTO
                " .
            $this->db_table .
            "
                SET
                    type=:type, saved_minutes = :saved_minutes;
                ";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->type = htmlspecialchars(strip_tags($this->type));
        $this->saved_minutes = htmlspecialchars(
            strip_tags($this->saved_minutes)
        );

        // bind data
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':saved_minutes', $this->saved_minutes);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // UPDATE
    public function update()
    {
        $sqlQuery =
            'UPDATE' .
            $this->db_table .
            'SET
            type = :type,
            saved_minutes = :saved_minutes,
        WHERE
            id = :id';

        $stmt = $this->conn->prepare($sqlQuery);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->type = htmlspecialchars(strip_tags($this->type));
        $this->saved_minutes = htmlspecialchars(
            strip_tags($this->saved_minutes)
        );

        // bind data
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':saved_minutes', $this->saved_minutes);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // DELETE
    public function delete()
    {
        $sqlQuery = 'DELETE FROM ' . $this->db_table . ' WHERE id = ?';
        $stmt = $this->conn->prepare($sqlQuery);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(1, $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read_saved_minutes()
    {
        $sqlQuery = "SELECT SUM(saved_minutes) AS 'total_saved_minutes' FROM $this->db_table;";

        $stmt = $this->conn->prepare($sqlQuery);
        // execute query
        $stmt->execute();
        return $stmt;
    }
}
?>

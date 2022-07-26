<?php

class Service
{
    // Connection
    private $conn;
    // Tables
    private $db_table = 'bonus_service';
    private $db_type = 'bonus_type';
    // Columns
    public $id;
    public $name;
    public $type_id;
    public $quantity;
    public $sold_at;
    // Db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // GET ALL
    public function read(array $params)
    {
        $types = htmlspecialchars(strip_tags($params['types']), ENT_NOQUOTES);
        $from_date = htmlspecialchars(
            strip_tags($params['from_date']),
            ENT_NOQUOTES
        );
        $to_date = htmlspecialchars(
            strip_tags($params['to_date']),
            ENT_NOQUOTES
        );

        $category = [];

        if (!empty($types)) {
            $category[] = "b.type = $types";
        }
        if (!empty($from_date)) {
            $category[] = "a.sold_at >= $from_date";
        }
        if (!empty($to_date)) {
            $category[] = "a.sold_at <= $to_date";
        }

        $sqlQuery = "SELECT a.id, a.name, b.type, a.quantity, a.sold_at FROM $this->db_table a INNER JOIN $this->db_type b ON a.type_id = b.id";
        if (!empty($category)) {
            $sqlQuery .= ' WHERE ' . implode(' AND ', $category);
        }

        $stmt = $this->conn->prepare($sqlQuery);
        // execute query
        $stmt->execute();
        return $stmt;
    }

    public function create()
    {
        $sqlQuery =
            "INSERT INTO
                " .
            $this->db_table .
            "
            SET
                name=:name, type_id=:type_id, quantity = :quantity, sold_at = :sold_at;
            ";

        $stmt = $this->conn->prepare($sqlQuery);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->type_id = htmlspecialchars(strip_tags($this->type_id));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->sold_at = htmlspecialchars(strip_tags($this->sold_at));

        // binding
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':type_id', $this->type_id);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':sold_at', $this->sold_at);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function update()
    {
        $sqlQuery =
            "UPDATE
        " .
            $this->db_table .
            "
        SET
            name = :name,
            type_id = :type_id,
            quantity = :quantity,
            sold_at = :sold_at
        WHERE
            id = :id";

        $stmt = $this->conn->prepare($sqlQuery);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->type_id = htmlspecialchars(strip_tags($this->type_id));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->sold_at = htmlspecialchars(strip_tags($this->sold_at));

        // binding
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':type_id', $this->type_id);
        $stmt->bindParam(':quantity', $this->quantity);
        $stmt->bindParam(':sold_at', $this->sold_at);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete()
    {
        $sqlQuery = 'DELETE FROM ' . $this->db_table . ' WHERE id = ?';

        $stmt = $this->conn->prepare($sqlQuery);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(1, $this->id);

        // execute query
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}

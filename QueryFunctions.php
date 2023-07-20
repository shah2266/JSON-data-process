<?php
class QueryFunctions
{
    private $connection;

    public function __construct($db)
    {
        $this->connection = $db->createConnection();
    }

    public function insert_data($data)
    {
        foreach ($data as $item) {
            $participation_id = $this->db_scape($item['participation_id']);
            $employee_name = $this->db_scape($item['employee_name']);
            $employee_mail = $this->db_scape($item['employee_mail']);
            $event_id = $this->db_scape($item['event_id']);
            $event_name = $this->db_scape($item['event_name']);
            $participation_fee = $this->db_scape($item['participation_fee']);
            $event_date = $this->db_scape($item['event_date']);
            $version = isset($item['version']) ? $this->db_scape($item['version']) : null;

            $sql = "INSERT INTO participation (
                           participation_id, 
                           employee_name, 
                           employee_mail, 
                           event_id, 
                           event_name, 
                           participation_fee, 
                           event_date, 
                           version) 
                    VALUES (
                            '$participation_id', 
                            '$employee_name', 
                            '$employee_mail', 
                            '$event_id', 
                            '$event_name', 
                            '$participation_fee', 
                            '$event_date', 
                            '$version')";

            if (!$this->connection->query($sql)) {
                echo "Inserting error: " . $this->connection->error;
            }
        }
    }

    public function get_existing_participation_ids(): array
    {
        $ids = array();

        try {
            $query = "SELECT participation_id FROM participation";
            $result = $this->connection->query($query);

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $ids[] = $row['participation_id'];
                }
                $result->free();
            } else {
                echo "Error: " . $this->connection->error;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }

        return $ids;
    }

    public function filtered($filters)
    {
        $sql = "SELECT * FROM participation";

        $where = array();
        foreach ($filters as $column => $value) {
            if (!empty($value)) {
                $where[] = "$column = '$value'";
            }
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $result = $this->connection->query($sql);

        if (!$result) {
            echo "Retrieving error: " . $this->connection->error;
            return null;
        }

        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    public function get_unique_record($column)
    {
        $sql = "SELECT DISTINCT $column FROM participation";
        $result = $this->connection->query($sql);

        if (!$result) {
            echo "Error retrieving unique values for $column: " . $this->connection->error;
            return null;
        }

        $values = array();
        while ($row = $result->fetch_assoc()) {
            $values[] = $row[$column];
        }

        return $values;
    }

    private function db_scape($data): string
    {
        return $this->connection->real_escape_string($data);
    }
}

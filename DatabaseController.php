<?php

require_once 'Database.php';

class DatabaseController extends Database
{
    public function getRow($query, $params = [])
    {
        try {
            $statement = $this->db->prepare($query);
            $statement->execute($params);
            return $statement->fetch();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }

    public function getRows($query, $params = [])
    {
        try {
            $statement = $this->db->prepare($query);
            $statement->execute($params);
            return $statement->fetchAll();
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage());
        }
    }

    public function insertRow($query, $params = [])
    {
        return $this->query($query, $params);
    }

    public function updateRow($query, $params = [])
    {
        return $this->query($query, $params);
    }

    public function deleteRow($query, $params = [])
    {
        return $this->query($query, $params);
    }

    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }

    private function query($query, $params = [])
    {
        try {
            $statement = $this->db->prepare($query);
            $statement->execute($params);
            return true;
        } catch (PDOException $e) {
            throw new PDOException($e->getmessage());
        }
    }
}
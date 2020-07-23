<?php

class Dump
{
    private $_db;

    /**
     * Init DB
     */
    public function __construct()
    {
        $this->_db = new Database;
    }

    /**
     * Récupère toutes les entrées
     * @return array $result
     */
    public function getAll()
    {
        $this->_db->query('SELECT * FROM dbsave ORDER BY id DESC');

        $result = $this->_db->resultSet();
        return $result;
    }

    /**
     * Afficher via l'id
     * @param int $id
     * @return array $row
     */
    public function getById(int $id)
    {
        $this->_db->query('SELECT filename FROM dbsave WHERE id = :id');

        $this->_db->bind(':id', $id);
        $row = $this->_db->single();
        return $row;
    }
    
    /**
     * Insérer sauvegarde
     * @param string $filename
     * @return bool
     */
    public function insert(string $filename): bool
    {
        $this->_db->query('INSERT INTO dbsave(filename)  VALUES (:filename)');
        $this->_db->bind(':filename', $filename);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Recherche par ID
     * @param $id
     * @return true
     */
    public function  findById($id): bool
    {
        $this->_db->query("SELECT id FROM dbsave WHERE id = :id");
        $this->_db->bind(':id', $id);
        $row = $this->_db->single();
        
        if($this->_db->rowCount() > 0) return true;
        return false;
    }

    /**
     * Supression
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $this->_db->query('DELETE FROM dbsave WHERE id = :id');
        $this->_db->bind(':id', $id);

        if($this->_db->execute()) return true;
        return false;
    }
}
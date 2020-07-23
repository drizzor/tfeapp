<?php

/**
 * Class gérant toutes les fonctionnalités liées à l'utilisateur (hors gestion admin)
 */
class Worker 
{
    private $_db;

    /**
     * Constructeur initiant la class DB permettant ainsi d'utiliser les méthodes associées
     */
    public function __construct()
    {
        $this->_db = new Database;
    }

    /**
     * Afficher l'ensemble des ouvriers
     * @return array $result
     */
    public function getAll()
    {
        $this->_db->query('SELECT * FROM workers ORDER BY id DESC');

        $result = $this->_db->resultSet();
        return $result;
    }

    /**
     * Récupérer les infos de l'ouvrier via ID
     * @param int $id
     * @return $row
     */
    public function getById(int $id)
    {
        $this->_db->query('SELECT * FROM workers WHERE id = :id');

        $this->_db->bind(':id', $id);
        $row = $this->_db->single();
        return $row;
    }

    /**
     * Trouver un ouvrier via ID
     * @param int $id
     * @param bool $deleted
     * @return bool 
     */
    public function findById(int $id, int $deleted = 0)
    {
        $this->_db->query('SELECT * FROM workers WHERE id = :id');

        $this->_db->bind(':id', $id);
        $row = $this->_db->single();

        if($this->_db->rowCount() > 0) return true;
        return false;
    }   

    /**
     * Y a t'il un ouvrier ?
     * @return bool
     */
    public function findSomething(): bool
    {
        $this->_db->query('SELECT id FROM workers');
        
        $row = $this->_db->single();
        if($this->_db->rowCount() > 0) return true;
        return false;
    }

    /**
     * Insérer  un ouvrier
     * @param array $data
     * @return bool
     */
    public function insert(array $data): bool
    {
        $this->_db->query('INSERT INTO workers(firstname, lastname, salary, email)  VALUES (:firstname, :lastname, :salary, :email)');

        $this->_db->bind(':firstname', $data['firstname']);
        $this->_db->bind(':lastname', $data['lastname']);
        $this->_db->bind(':salary', $data['salary']);
        $this->_db->bind(':email', $data['email']);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Méthode permettant la mise à jour d'un profil ouvrier
     * @param array $data
     * @return bool
     */
    public function update(array $data): bool
    {
       
        $this->_db->query('UPDATE workers SET lastname = :lastname, firstname = :firstname, salary = :salary, email = :email WHERE id = :id');

        $this->_db->bind(':id', $data['id']);
        $this->_db->bind(':lastname', $data['lastname']);
        $this->_db->bind(':firstname', $data['firstname']); 
        $this->_db->bind(':salary', $data['salary']);
        $this->_db->bind(':email', $data['email']);
        
        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Update statut out/in
     * @param bool $isOut
     * @return bool
     */
    public function updateOutStatut(int $id, bool $isOut): bool
    {
       
        $this->_db->query('UPDATE workers SET deleted = :isOut WHERE id = :id');

        $this->_db->bind(':id', $id);
        $this->_db->bind(':isOut', $isOut);

        if($this->_db->execute()) return true;
        return false;
    }


    /**
     * Définir si l'ouvrier cible est déclaré sorti ou non
     * @param int $id
     * @return $row
     */
    public function isOut(int $id)
    {
        $this->_db->query('SELECT deleted FROM workers where id = :id');
        $this->_db->bind(':id', $id);

        $row = $this->_db->single();
        return $row;
    }    
    
    /**
     * Suppression d'un ouvrier
     * @param int $id
     * @return bool 
     */
    public function delete(int $id): bool
    {
        $this->_db->query('DELETE FROM workers WHERE id = :id');
        $this->_db->bind(':id', $id);

        if($this->_db->execute()) return true;
        return false;
    }
}
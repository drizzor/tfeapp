<?php

/**
 * Class gérant toutes les fonctionnalités liées à l'utilisateur (hors gestion admin)
 */
class Planning 
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
     * Obtenir l'id du  planning
     * @param array $data
     * @return int $row
     */
    public function getPlanningId(array $data)
    {
        $this->_db->query('SELECT id FROM planning WHERE REF_workers = :id_w AND REF_constructions = :id_c');
        $this->_db->bind(':id_w', $data['id_w']);
        $this->_db->bind(':id_c', $data['id_c']);

        $result = $this->_db->single();
        return $result;
    }

    /**
     * Récupérer les infos via ID du chantier
     * @param int $id
     * @return array $result
     */
    public function getById(int $id)
    {
        $this->_db->query(' SELECT 
                            planning.id as planning_id,
                            planning.REF_workers as planning_ref_workers,
                            planning.REF_constructions as planning_ref_constructions,
                            planning.hours as planning_hour,
                            planning.salary as planning_salary,
                            planning.hours * planning.salary as planning_total,                            
                            workers.id as worker_id,
                            workers.firstname as firstname,
                            workers.lastname as lastname,
                            workers.salary as worker_salary,
                            constructions.id as construction_id,
                            constructions.name as construction_name
                            FROM planning
                            INNER JOIN constructions
                            ON constructions.id = planning.REF_constructions AND constructions.id = :id
                            RIGHT JOIN workers
                            ON workers.id = planning.REF_workers');

        $this->_db->bind(':id', $id);
        $result = $this->_db->resultSet();
        return $result;
    }

    /**
     * Trouver une entrée dans le planning cible
     * @param array $data
     * @return bool
     */
    public function findById(array $data)
    {
        $this->_db->query('SELECT * FROM planning WHERE REF_workers = :id_w AND REF_constructions = :id_c');

        $this->_db->bind(':id_w', $data['id_w']);
        $this->_db->bind(':id_c', $data['id_c']);
        $row = $this->_db->single();

        if($this->_db->rowCount() > 0) return true;
        return false;
    }   

    /**
     * Insertion
     * @param array $data
     * @return bool
     */
    public function insert(array $data): bool
    {
        $this->_db->query('INSERT INTO planning(REF_workers, REF_constructions, hours, salary)  VALUES (:id_w, :id_c, :hour, :salary)');
        $this->_db->bind(':id_w', $data['id_w']);
        $this->_db->bind(':id_c', $data['id_c']);
        $this->_db->bind(':hour', $data['hour']);
        $this->_db->bind(':salary', $data['salary']);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Méthode permettant la mise à jour d'un planning
     * @param array $data
     * @return bool
     */
    public function update(array $data): bool
    {
       
        $this->_db->query('UPDATE planning SET hours = :hour, salary = :salary, dateInsert = NOW() WHERE id = :id_p');

        $this->_db->bind(':id_p', $data['id_p'][0]);
        $this->_db->bind(':hour', $data['hour']);
        $this->_db->bind(':salary', $data['salary']);
        if($this->_db->execute()) return true;
        return false;
    }

     /**
     * L'ouvrier a t'il créé des prestations?
     * @param int $id 
     * @return bool
     */
    public function refPresta(int $id)
    {
        $this->_db->query('SELECT * FROM planning WHERE REF_workers = :id');

        $this->_db->bind(':id', $id);
        $row = $this->_db->single();
        if($this->_db->rowCount() > 0) return true;
        return false;
    }   
    
    /**
     * L'ouvrier a t'il créé des prestations sur le chantier cible?
     * @param int $id_c
     * @param int $id_w 
     * @return bool
     */
    public function refPrestaFromConstruct(int $id_c, int $id_w)
    {
        $this->_db->query('SELECT * FROM planning WHERE REF_constructions = :id_c AND REF_workers = :id_w');

        $this->_db->bind(':id_c', $id_c);
        $this->_db->bind(':id_w', $id_w);
        $row = $this->_db->single();
        if($this->_db->rowCount() > 0) return true;
        return false;
    }   

    /** REMPLISSAGE VIGNETTES */

    /**
     * Sommation TOTAL des salaires 
     * @param int $id
     * @return $row
     */
    public function sumAllPresta(int $id)
    {
        $this->_db->query(' SELECT 
                            SUM(hours * salary) as total_presta
                            FROM planning
                            WHERE REF_constructions = :id');

        $this->_db->bind(':id', $id);
        $row = $this->_db->single();
        return $row;
    }

    /**
     * Sommation simple des heures et des salaires pour sortir la moyenne
     * @param int $id
     * @return $row
     */
    public function sumPresta(int $id)
    {
        $this->_db->query(' SELECT 
                            SUM(hours) as total_hours,
                            SUM(salary) as total_salary
                            FROM planning
                            WHERE REF_constructions = :id');

        $this->_db->bind(':id', $id);
        $row = $this->_db->single();
        return $row;
    }

    /**
     * Retourne le total heures de l'ouvrier
     * @param int $id
     * @return bool 
     */
    public function SumPrestaByWorker(int $id)
    {
        $this->_db->query('');

        $this->_db->bind(':id', $id);
        $row = $this->_db->single();        
        return $row;
    }
    
    /**
     * Compter les prestations réelles
     * @param int $id
     * @return int $result
     */
    public function countPresta(int $id)
    {
        $this->_db->query(' SELECT id
                            FROM planning
                            WHERE REF_constructions = :id and hours > 0');

        $this->_db->bind(':id', $id);
        $result = $this->_db->resultSet();
        return $this->_db->rowCount();
    }

    /**
     * Suppression
     * @param int $id_p
     * @return bool
     */
    public function delete(int $id_p)
    {
        $this->_db->query('DELETE FROM planning WHERE id = :id_p');

        $this->_db->bind(':id_p', $id_p);
        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Suppression
     * @param int $id_c
     * @return bool
     */
    public function deleteAllFromConstruct(int $id_c)
    {
        $this->_db->query('DELETE FROM planning WHERE REF_constructions = :id_c');

        $this->_db->bind(':id_c', $id_c);
        if($this->_db->execute()) return true;
        return false;
    }
}
<?php

class City
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
     * Requete lancée afin de réaliser l'autocomplete
     * @param string $request
     * @return array
     */
    public function getCitiesLike(string $request)
    {
        $this->_db->query("SELECT * FROM cities WHERE name LIKE '%$request%' || zipcode LIKE '%$request%'");
        $result = $this->_db->resultSet();
        return $result;
    }

    /**
     * Recherche des la données passée par l'utilisateur 
     * @param string name
     * @return bool
     */
    public function findCityByName(string $name): bool 
    {
        $this->_db->query('SELECT name FROM cities WHERE name = :name');
        $this->_db->bind(':name', $name);
        $row = $this->_db->single();

        if($this->_db->rowCount() > 0) return true;
        return false;
    }

    /**
     * Je récupère la ville souhaite et toutes les infos nécessaires
     * @param string $name
     * @return $row
     */
    public function getCityByName(string $name)
    {
        $this->_db->query("SELECT * FROM cities WHERE name = :name");
        $this->_db->bind(':name', $name);
        $row = $this->_db->single();
        return $row;
    }
}
<?php

class Supplier
{
    private $_db;

    /**
     * Init class DB
     */
    public function __construct()
    {
        $this->_db = new Database;
    }

    /**
     * Méthode d'enregistrement d'un chantier
     * @param array $data
     * @return bool
     */
    public function insert(array $data): bool
    {
        $this->_db->query('INSERT INTO suppliers (REF_city, name, address, contactName, phone, email) VALUES (:REF_city, :name, :address, :contactName, :phone, :email)');
        $this->_db->bind(':REF_city', $data['REF_city']);
        $this->_db->bind(':name', $data['name']);
        $this->_db->bind(':address', $data['address']);
        $this->_db->bind(':contactName', $data['contactName']);
        $this->_db->bind(':phone', $data['phone']);
        $this->_db->bind(':email', $data['email']);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Insérer les données
     * @param array $data
     * @return bool
     */
    public function update(array $data): bool
    {
        $this->_db->query('UPDATE suppliers SET REF_city = :REF_city, name = :name, address = :address, contactName = :contactName, phone = :phone, email = :email WHERE id = :id');
        $this->_db->bind(':id', $data['id']);
        $this->_db->bind(':REF_city', $data['REF_city']);
        $this->_db->bind(':name', $data['name']);
        $this->_db->bind(':address', $data['address']);
        $this->_db->bind(':contactName', $data['contactName']);
        $this->_db->bind(':phone', $data['phone']);
        $this->_db->bind(':email', $data['email']);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Suppression d'un forunisseur
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool 
    {
        $this->_db->query('DELETE FROM suppliers WHERE id = :id');
        $this->_db->bind(':id', $id);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Retourne touts  les entrées des fournisseurs
     * @return array $result
     */
    public function getSuppliers()
    {
        $this->_db->query('SELECT * FROM suppliers ORDER BY name ASC');
        $result = $this->_db->resultSet();
        return $result;
    }

    /**
     * Retourne le nombre de références
     * @param int $id
     * @return int
     */
    public function references(int $id): int
    {
        $this->_db->query(' SELECT suppliers.id, suppliers_purchases.REF_suppliers
                            FROM suppliers
                            INNER JOIN suppliers_purchases
                            ON suppliers.id = suppliers_purchases.REF_suppliers AND suppliers.id = :id');

        $this->_db->bind(':id', $id);
        $result = $this->_db->resultSet();
        return $this->_db->rowCount();
    }

    /**
     * S'assurer de l'existance d'au moins un fournisseur (exploité dans encodage facture)
     * @return bool
     */
    public function findSupplier(): bool 
    {
        $this->_db->query("SELECT * FROM suppliers");

        $row = $this->_db->single();
        if($this->_db->rowCount() > 0) return true;
        return false;
    }

    /**
     * Trouver le fournisseur via le nom (utile pour gérer l'unicité)
     * @param string $name
     * @return bool
     */
    public function findSupplierByName(string $name): bool 
    {
        $id = explodeGET($_GET['url']);
        if(!isset($id[2])) $id[2] = 0; 
        $this->_db->query("SELECT * FROM suppliers WHERE name = :name AND id != $id[2]");

        $this->_db->bind(':name', $name);
        $row = $this->_db->single();
        if($this->_db->rowCount() > 0) return true;
        return false;
    }

    /**
     * Trouver le fournisseur par ID (identifie l'existence de l'id passé en GET pour l'affichage)
     * @param int $id
     * @return bool
     */
    public function findSupplierById($id): bool 
    {
        $this->_db->query("SELECT * FROM suppliers WHERE id = :id");
        $this->_db->bind(':id', $id);
        $row = $this->_db->single();
        
        if($this->_db->rowCount() > 0) return true;
        return false;
    }    

    /**
     * Récupère le fournisseur courante à afficher dans la vue
     * @param int $id
     * @return array $result
     */
    public function currentSupplier(int $id)
    {
        $this->_db->query(' SELECT *,
                            suppliers.id as supplier_id,
                            cities.id as city_id,
                            suppliers.name as supplier_name,
                            cities.name as city_name
                            FROM suppliers 
                            INNER JOIN cities
                            ON suppliers.REF_city = cities.id AND suppliers.id = :id');
        $this->_db->bind(':id', $id);
        $result = $this->_db->single();

        return $result;
    }
}
<?php

class Customer
{
    private $_db;

    public function __construct()
    {
        $this->_db = new Database;
    }    

    /**
     * Liste complète des clients
     * @return array $result
     */
    public function getAll()
    {
        $this->_db->query(" SELECT *,
                            customers.id as customer_id,
                            customers.name as customer_name,
                            customers.address as customer_address,
                            customers.email as customer_email,
                            members.id as member_id,
                            members.email as member_email
                            FROM customers
                            LEFT JOIN members
                            ON customers.REF_members = members.id");
        $result = $this->_db->resultSet();
        return $result;
    } 

    /**
     * Requete d'autocompletion 
     * @param string request
     * @return array $result
     */
    public function getLike(string $request)
    {
        $this->_db->query("SELECT * FROM customers WHERE name LIKE \"%$request%\"");
        $result = $this->_db->resultSet();
        return $result;
    }

    /**
     * Requete d'inserttion d'une nouveau client
     * @param array $data
     * @return bool 
     */
    public function insert(array $data): bool
    {
        $this->_db->query(' INSERT INTO customers (REF_members, name, country, city, zipcode, address, tva_number, email) 
                            VALUES (:REF_members, :name, :country, :city, :zipcode, :address, :tva_number, :email)');

        $this->_db->bind(':REF_members', $data['currentUser']['member_id']);
        $this->_db->bind(':name', $data['name']);
        $this->_db->bind(':country', $data['country']);
        $this->_db->bind(':city', $data['city']);
        $this->_db->bind(':zipcode', $data['zipcode']);
        $this->_db->bind(':address', $data['address']);
        $this->_db->bind(':tva_number', $data['tva_number']);
        $this->_db->bind(':email', $data['email']);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Mise à jour
     * @param array $data
     * @return bool
     */
    public function update(array $data): bool
    {
        $this->_db->query('UPDATE customers SET REF_members = :currentUser, name = :name, country = :country, city = :city, zipcode = :zipcode, address = :address, tva_number = :tva_number, email = :email WHERE id = :id');
        $this->_db->bind(':id', $data['id']);
        $this->_db->bind(':currentUser', $data['currentUser']['member_id']);
        $this->_db->bind(':name', $data['name']);
        $this->_db->bind(':country', $data['country']);
        $this->_db->bind(':city', $data['city']);
        $this->_db->bind(':zipcode', $data['zipcode']);
        $this->_db->bind(':address', $data['address']);
        $this->_db->bind(':tva_number', $data['tva_number']);
        $this->_db->bind(':email', $data['email']);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Suppression
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool 
    {
        $this->_db->query('DELETE FROM customers WHERE id = :id');
        $this->_db->bind(':id', $id);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Vérifier l'existence de facture attribuée à un client
     * @param int $id
     * @return int
     */
    public function refInvoice(int $id): int
    {
        $this->_db->query(' SELECT customers.id, invoices.REF_customers
                            FROM customers
                            INNER JOIN invoices
                            ON customers.id = invoices.REF_customers AND customers.id = :id');

        $this->_db->bind(':id', $id);
        $result = $this->_db->resultSet();
        return $this->_db->rowCount();
    }

    /**
     * Recherche par nom
     * @param string $name
     * @return bool
     */
    public function findByName(string $name): bool 
    {
        $id = explodeGET($_GET['url']);
        if(!isset($id[2])) $id[2] = 0; 
        $this->_db->query("SELECT * FROM customers WHERE name = :name AND id != $id[2]");

        $this->_db->bind(':name', $name);
        $row = $this->_db->single();
        
        if($this->_db->rowCount() > 0) return true;
        return false;
    }

    /**
     * Recherche par ID
     * @param $id
     * @return true
     */
    public function  findById($id): bool
    {
        $this->_db->query("SELECT * FROM customers WHERE id = :id");
        $this->_db->bind(':id', $id);
        $row = $this->_db->single();
        
        if($this->_db->rowCount() > 0) return true;
        return false;
    }

    /**
     * Obtenir par id
     * @param $id
     * @return $result
     */
    public function getById(int $id)
    {
        $this->_db->query(' SELECT *,
                            customers.id as customer_id,
                            customers.name as customer_name,
                            customers.address as customer_address,
                            customers.email as customer_email,
                            members.id as member_id,
                            members.email as member_email
                            FROM customers                            
                            LEFT JOIN members
                            ON customers.REF_members = members.id WHERE customers.id = :id');
        
        $this->_db->bind(':id', $id);

        $result = $this->_db->single();
        return $result;
    }

    /**
     * Compte le nombre de client et permeet la redirection d'encodage facture si aucun client
     * @return bool
     */
    public function countAll()
    {
        $this->_db->query('SELECT id FROM customers');
        $row = $this->_db->single();
        
        if($this->_db->rowCount() > 0) return true;
        return false;
    }

}
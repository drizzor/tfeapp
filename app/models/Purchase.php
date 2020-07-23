<?php

class Purchase
{
    private $_db;

    public function __construct()
    {
        $this->_db = new Database;
    }

    /**
     * Requete executée pour l'ajout d'une facture
     * @param array $data
     * @param $REF_categories
     * @param $product
     * @param $quantity
     * @param $price
     * @param $tax
     * @return bool
     */
    public function insert(array $data, $REF_categories, $product, $quantity, $price, $tax): bool 
    {
        $this->_db->query(' INSERT INTO suppliers_purchases (id_purchase, REF_members, REF_constructions, REF_categories, REF_suppliers, invoiceNumber, invoicePDF, product, quantity, price, tva, dateInvoice) 
                            VALUES (:id_purchase, :REF_members, :REF_constructions, :REF_categories, :REF_suppliers, :invoiceNumber, :invoicePDF, :product, :quantity, :price, :tva, :dateInvoice)');
        
        $this->_db->bind(':id_purchase', $data['id_purchase']);
        $this->_db->bind(':REF_members', $_SESSION['user_id']);
        $this->_db->bind(':REF_constructions', $data['construction_id']);
        $this->_db->bind(':REF_categories', $REF_categories);
        $this->_db->bind(':REF_suppliers', $data['supplier_id']);
        $this->_db->bind(':invoiceNumber', $data['invoiceNo']);
        $this->_db->bind(':invoicePDF', $data['invoiceFile']);
        $this->_db->bind(':product', $product);
        $this->_db->bind(':quantity', $quantity);
        $this->_db->bind(':price', $price);
        $this->_db->bind(':tva', $tax);
        $this->_db->bind(':dateInvoice', $data['invoiceDate']);
        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Obtenir la liste complète des tous les achats fournisseurs
     * @return array $result
     */
    public function getAll()
    {
        $this->_db->query(' SELECT *,
                            suppliers_purchases.id as suppliers_purchases_id,
                            DATE_FORMAT(suppliers_purchases.dateInsert, "%d/%m/%Y") as dateInsert,
                            suppliers.id as suppliers_id,
                            suppliers.name as suppliers_name,
                            constructions.id as constructions_id,
                            constructions.name as constructions_name,
                            SUM(price * quantity) as total_notax,
                            SUM(price * quantity * (1 + (tva/100))) as total_tax
                            FROM suppliers_purchases
                            INNER JOIN suppliers
                            ON suppliers.id = suppliers_purchases.REF_suppliers
                            INNER JOIN constructions
                            ON constructions.id = suppliers_purchases.REF_constructions
                            GROUP BY suppliers_purchases.id_purchase');

        $result = $this->_db->resultset();
        return $result;
    }

    /**
     * Obtenir la liste complète des tous les achats fournisseurs
     * @return array $result
     */
    public function getLastOne()
    {
        $this->_db->query(' SELECT *,
                            suppliers_purchases.id as suppliers_purchases_id,
                            suppliers_purchases.id_purchase as id_purchase,
                            DATE_FORMAT(suppliers_purchases.dateInsert, "%d/%m/%Y") as dateInsert,
                            suppliers.id as suppliers_id,
                            suppliers.name as suppliers_name,
                            constructions.id as constructions_id,
                            constructions.name as constructions_name,
                            SUM(price * quantity) as total_notax,
                            SUM(price * quantity * (1 + (tva/100))) as total_tax
                            FROM suppliers_purchases
                            INNER JOIN suppliers
                            ON suppliers.id = suppliers_purchases.REF_suppliers
                            INNER JOIN constructions
                            ON constructions.id = suppliers_purchases.REF_constructions
                            GROUP BY suppliers_purchases.id_purchase
                            ORDER BY suppliers_purchases.id_purchase DESC
                            LIMIT 5');        

        $result = $this->_db->resultset();
        return $result;
    }

    /**
     * obtenir la liste des achats du chantier cible
     * @param int $id
     * @return array $result
     */
    public function getByConstructionId(int $id)
    {
        $this->_db->query(' SELECT 
                            suppliers_purchases.invoiceNumber,
                            suppliers_purchases.id_purchase,
                            SUM(suppliers_purchases.quantity * suppliers_purchases.price * (1 + (suppliers_purchases.tva/100))) as total_tax,
                            DATE_FORMAT(suppliers_purchases.dateInvoice, "%d/%m/%Y") as dateInvoice,
                            suppliers_purchases.invoicePDF,
                            suppliers.name as supplier_name,
                            constructions.name as construction_name
                            FROM `suppliers_purchases` 
                            INNER JOIN suppliers ON suppliers.id = suppliers_purchases.REF_suppliers
                            INNER JOIN constructions ON constructions.id = suppliers_purchases.REF_constructions AND suppliers_purchases.REF_constructions = :id
                            GROUP BY id_purchase');
        
        $this->_db->bind(':id', $id);

        $result = $this->_db->resultset();
        return $result;
    }

     /**
     * Obtenir la liste des achats du fournisseur cible
     * @param int $id
     * @return array $result
     */
    public function getBySupplierId(int $id)
    {
        $this->_db->query(' SELECT  
                            id_purchase,
                            invoiceNumber,
                            SUM((quantity * price) * (1 + (tva / 100))) as total_TVAC,
                            constructions.name as construction_name
                            FROM `suppliers_purchases`
                            INNER JOIN constructions
                            ON constructions.id = suppliers_purchases.REF_constructions AND REF_suppliers = :id
                            GROUP BY id_purchase');
        
        $this->_db->bind(':id', $id);
        $result = $this->_db->resultset();
        return $result;
    }

    /**
     * Afficher un achat via l'id
     * @param int $id
     * @return array $result
     */
    public function getById(int $id)
    {
        $this->_db->query('SELECT *, DATE_FORMAT(dateInvoice, "%d-%m-%Y") as dateInvoice FROM suppliers_purchases WHERE id_purchase = :id_purchase ORDER BY id');

        $this->_db->bind(':id_purchase', $id);
        $result = $this->_db->resultset();
        return $result;
    }

    /**
     * Suppression achat
     * @param int $id
     * @return bool 
     */
    public function delete(int $id): bool
    {
        $this->_db->query('DELETE FROM suppliers_purchases WHERE id_purchase = :id');
        $this->_db->bind(':id', $id);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Suppression de toutes occurences du chantier cible
     * @param int $id
     * @return bool
     */
    public function deleteAllFromConstruct(int $id): bool
    {
        $this->_db->query('DELETE FROM suppliers_purchases WHERE REF_constructions = :id');
        $this->_db->bind(':id', $id);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Trouver l'existance d'un achat cible
     * @param int $id
     * @return bool
     */
    public function findById(int $id)
    {
        $this->_db->query("SELECT * FROM suppliers_purchases WHERE id_purchase = :id_purchase");
        $this->_db->bind(':id_purchase', $id);
        $row = $this->_db->single();
        
        if($this->_db->rowCount() > 0) return true;
        return false;
    }

    /**
     * Permet de savoir si il y au moins une entrée existante - Utile pour la génération ID du système de gestion des achats
     * @return bool
     */
    public function findOne(): bool 
    {
        $this->_db->query("SELECT * FROM suppliers_purchases");
        $row = $this->_db->single();
        
        if($this->_db->rowCount() > 0) return true;
        return false;
    }

    /**
     * Fonctionne en  parallèle avec findOne et permet de générer l'id suivant 
     * @return $row
     */
    public function checkLastId()
    {
       $this->_db->query('SELECT id_purchase FROM `suppliers_purchases` ORDER BY id_purchase DESC LIMIT 1');

       $row = $this->_db->single();
       return $row;
    }    

    /**
     * Sommation des achats du chantiers cible
     * @param int $id
     * @return $row
     */
    public function sumByConstruct(int $id)
    {
        $this->_db->query('SELECT SUM(price * quantity) as total FROM suppliers_purchases WHERE REF_constructions = :id');

        $this->_db->bind(':id', $id);
        $row = $this->_db->single();
        return $row;
    }

    /**
     * Sommation des achats par chantier et par mois
     * @param int $id
     * @return array $result
     */
    public function SumByConstructAndMonth(int $id)
    {
        $this->_db->query(' SELECT 
                            SUM(quantity * price) as total,
                            MONTH(dateInvoice) as month,
                            YEAR(dateInvoice) as year
                            FROM `suppliers_purchases` 
                            WHERE REF_constructions = :id
                            GROUP BY MONTH(dateInvoice)
                            ORDER BY dateInvoice DESC
                            LIMIT 6');

        $this->_db->bind(':id', $id);
        $result = $this->_db->resultSet();
        return $result;
    }
}
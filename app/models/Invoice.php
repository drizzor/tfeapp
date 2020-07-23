<?php

class Invoice
{
    private $_db;

    public function __construct()
    {
        $this->_db = new Database;
    }    

    /**
     * Obtenir la liste des factures
     * @return array $result
     */
    public function getAll()
    {
        $this->_db->query(" SELECT
                            invoices.id as invoice_id,
                            invoices.id_invoice as id_invoice,
                            invoices_taxes.id as invoices_taxes_id,
                            DATE_FORMAT(invoices.invoice_date, '%d/%m/%Y') AS invoice_createdAt,
                            taxes.id as tax_id,
                            customers.id as customer_id,
                            customers.name as  customer_name,
                            SUM(invoices.notax_amount * invoices.quantity) as total_notax,
                            SUM(invoices.notax_amount * invoices.quantity * (1 + (taxes.amount/100))) as total_tax,
                            invoices.invoice_number as invoice_number
                            FROM invoices_taxes
                            INNER JOIN taxes
                            ON taxes.id = invoices_taxes.REF_taxes 
                            INNER JOIN invoices 
                            ON invoices.id = invoices_taxes.REF_invoices 
                            INNER JOIN customers
                            ON customers.id = invoices.REF_customers
                            GROUP BY invoices.invoice_number");            
       
        $result = $this->_db->resultSet();
        return $result;
    } 

    /**
     * Obtenir le top 5 factures
     * @return array $result
     */
    public function getLastOne()
    {
        $this->_db->query(" SELECT
                            invoices.id as invoice_id,
                            invoices.id_invoice as id_invoice,
                            invoices_taxes.id as invoices_taxes_id,
                            DATE_FORMAT(invoices.invoice_date, '%d/%m/%Y') AS invoice_createdAt,
                            taxes.id as tax_id,
                            customers.id as customer_id,
                            customers.name as  customer_name,
                            SUM(invoices.notax_amount * invoices.quantity) as total_notax,
                            SUM(invoices.notax_amount * invoices.quantity * (1 + (taxes.amount/100))) as total_tax,
                            invoices.invoice_number as invoice_number
                            FROM invoices_taxes
                            INNER JOIN taxes
                            ON taxes.id = invoices_taxes.REF_taxes 
                            INNER JOIN invoices 
                            ON invoices.id = invoices_taxes.REF_invoices 
                            INNER JOIN customers
                            ON customers.id = invoices.REF_customers
                            GROUP BY invoices.invoice_number
                            ORDER BY invoices.id_invoice DESC
                            LIMIT 5");            
       
        $result = $this->_db->resultSet();
        return $result;
    }

    /**
     * Afficher le listing des factures du  client cible
     * @param int $id
     * @return array $result
     */
    public function getCustomerInvoices(int $id)
    {
        $this->_db->query(" SELECT
                            invoices.id as invoice_id,
                            invoices.id_invoice  as id_invoice,
                            invoices_taxes.id as invoices_taxes_id,
                            DATE_FORMAT(invoices.invoice_date, '%d/%m/%Y') AS invoice_createdAt,
                            taxes.id as tax_id,
                            customers.id as customer_id,
                            customers.name as  customer_name,
                            SUM(invoices.notax_amount * invoices.quantity) as total_notax,
                            SUM(invoices.notax_amount * invoices.quantity * (1 + (taxes.amount/100))) as total_tax,
                            invoices.invoice_number as invoice_number
                            FROM invoices_taxes
                            INNER JOIN taxes
                            ON taxes.id = invoices_taxes.REF_taxes 
                            INNER JOIN invoices 
                            ON invoices.id = invoices_taxes.REF_invoices 
                            INNER JOIN customers
                            ON customers.id = invoices.REF_customers
                            WHERE customers.id = :id
                            GROUP BY invoices.invoice_number");

        $this->_db->bind(':id', $id); 
        $result = $this->_db->resultSet();
        return $result;
    }

    /**
     * Obtenir avec précision toutes informations liée à une facture
     * @param $id
     * @return array $result
     */
    public function getInvoiceInfo(int $id)
    {
        $this->_db->query(' SELECT
                            invoices.id as invoice_id,
                            invoices.id_invoice as id_invoice,
                            invoices_taxes.id as invoices_taxes_id,
                            -- DATE_FORMAT(invoices.invoice_date, "%d/%m/%Y") AS invoice_createdAt,
                            taxes.id as tax_id,
                            taxes.amount as tax,
                            customers.id as customer_id,
                            customers.name as  customer_name,
                            customers.country as customer_country,
                            customers.city as customer_city,
                            customers.zipcode as customer_zipcode,
                            customers.address as customer_address,
                            customers.tva_number as customer_tva,
                            invoices.description as invoice_description,
                            invoices.comment as invoice_comment,
                            invoices.quantity as quantity,
                            invoices.notax_amount as notax_amount,
                            invoices.invoice_number as invoice_number,
                            DATE_FORMAT(invoices.invoice_date, "%Y/%m/%d") as invoice_date
                            FROM invoices_taxes
                            INNER JOIN taxes
                            ON taxes.id = invoices_taxes.REF_taxes 
                            INNER JOIN invoices 
                            ON invoices.id = invoices_taxes.REF_invoices AND invoices.id_invoice = :id
                            INNER JOIN customers
                            ON customers.id = invoices.REF_customers');

        $this->_db->bind(':id', $id);
        $result = $this->_db->resultSet();
        return $result;
    }   

    /**
     * Récupérer les montants TVA
     * @return array $result
     */
    public function getTaxes()
    {
        $this->_db->query('SELECT *, amount as tax_amount, id as tax_id FROM taxes ORDER BY amount DESC');

        $result = $this->_db->resultSet();
        return $result;
    }

    /**
     * Récupérer ID d'une taxe
     * @param double $amount
     * @return $row
     */
    public function getTaxeIdByAmount($amount)
    {
        $this->_db->query('SELECT id as tax_id FROM taxes WHERE amount = :amount');

        $this->_db->bind(':amount', $amount);
        $row = $this->_db->single();
        return $row;
    }

    /**
     * Ajouter une entrée
     * @param array $data
     * @param double $price
     * @param int $qty
     * @param string $description
     * @return bool
     */
    public function insert(array $data, $price, $qty, $description): bool
    {
        $this->_db->query(' INSERT INTO invoices (id_invoice, REF_members, REF_customers, invoice_number, notax_amount, quantity, description, comment) 
                            VALUES (:id_invoice, :REF_members, :REF_customers, :invoice_number, :notax_amount, :quantity, :description, :comment)');

        $this->_db->bind(':id_invoice', $data['invoice_id']);
        $this->_db->bind(':REF_members', $data['currentUser']['member_id']);
        $this->_db->bind(':REF_customers', $data['customer_id']);
        $this->_db->bind(':invoice_number', $data['invoice_number']);
        $this->_db->bind(':notax_amount', $price);
        $this->_db->bind(':quantity', $qty);
        $this->_db->bind(':description', $description);
        $this->_db->bind(':comment', $data['comment']);
       
        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Ajout des  ID dans la classe association
     * @param int $id
     * @param int $tax_id
     * @return bool
     */
    public function insertKey($id, $tax_id)
    {
        $this->_db->query(' INSERT INTO invoices_taxes (REF_taxes, REF_invoices)
                            VALUES (:REF_taxes, :REF_invoices)');

        $this->_db->bind(':REF_taxes', $tax_id);
        $this->_db->bind(':REF_invoices', $id);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Supression
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool 
    {
        $this->_db->query('DELETE FROM invoices WHERE id_invoice = :id');
        $this->_db->bind(':id', $id);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Supression classe association
     * @param int $id
     * @return bool
     */
    public function deleteKey(int $id): bool 
    {
        $this->_db->query('DELETE FROM invoices_taxes WHERE REF_invoices = :id');
        $this->_db->bind(':id', $id);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Permet de vérifier l'existance d'une  facture
     */
    public function  findSomething(): bool
    {
        $this->_db->query("SELECT * FROM invoices");

        $row = $this->_db->single();
        
        if($this->_db->rowCount() > 0) return true;
        return false;
    }

    /**
     * Trouver via ID
     * @param int $id
     * @return bool
     */
    public function  findById(int $id): bool
    {
        $this->_db->query("SELECT * FROM invoices WHERE id_invoice = :id");
        $this->_db->bind(':id', $id);
        $row = $this->_db->single();
        
        if($this->_db->rowCount() > 0) return true;
        return false;
    }

    /**
     * Trouver une taxe via le montant
     * @param double $amount
     * @return bool
     */
    public function  findTaxByAmount($amount): bool
    {
        $this->_db->query("SELECT * FROM taxes WHERE amount = :amount");
        $this->_db->bind(':amount', $amount);
        $row = $this->_db->single();
        
        if($this->_db->rowCount() > 0) return true;
        return false;
    }

    /**
     * Récupération via id
     * @param int $id
     * @return array $result
     */
    public function getById(int $id)
    {
        $this->_db->query('SELECT * FROM invoices WHERE id_invoice = :id');

        $this->_db->bind(':id', $id);
        $result = $this->_db->resultSet();
        return $result;
    }     

    /**
     * Récupère la dernière facture
     * @return $row
     */
    public function getLastInvoiceNo()
    {
        $this->_db->query('SELECT invoice_number FROM invoices ORDER BY invoice_number DESC');

        $row = $this->_db->single();
        return $row;
    }

    /**
     * Récupère la date de la dernière facture
     * @return $row
     */
    public function getLastInvoiceDate()
    {
        $this->_db->query('SELECT DATE_FORMAT(invoice_date, "%Y/%m/%d") as invoice_date FROM invoices ORDER BY invoice_date DESC');

        $row = $this->_db->single();
        return $row;
    }

    /**
     * Récupère l'id de la dernière facture en date
     * @return $row
     */
    public function getLastInvoiceId()
    {
        $this->_db->query('SELECT id, id_invoice FROM invoices ORDER BY id_invoice DESC');

        $row = $this->_db->single();
        return $row;
    }
}
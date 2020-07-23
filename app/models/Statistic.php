<?php

class Statistic
{
    private $_db;

    public function __construct()
    {
        $this->_db = new Database;
    }    

    /**
     * Compteur utilisateurs pour vignette
     * @return int
     */
    public function countActiveUsers()
    {
        $this->_db->query("SELECT id FROM members WHERE activate = 1");
        
        $result = $this->_db->resultSet();
        return $this->_db->rowCount();
    }

    /**
     * Compteur chantiers terminés pour vignette
     * @return int
     */
    public function countSoldConstructions()
    {
        $this->_db->query("SELECT id FROM sold");
        
        $result = $this->_db->resultSet();
        return $this->_db->rowCount();
    }

    /**
     * Compteur chantiers en cours pour vignette
     * @return int
     */
    public function countConstructions()
    {
        $this->_db->query("SELECT id FROM constructions");
        
        $result = $this->_db->resultSet();
        return $this->_db->rowCount();
    }

    /**
     * Compteur achats année en cours pour vignette
     * @return int
     */
    public function purchasesThisYear()
    {
        $this->_db->query(" SELECT
                            SUM(price * quantity) as total_ht
                            FROM `suppliers_purchases` 
                            WHERE YEAR(dateInvoice) = :year");

        $this->_db->bind(':year', date('Y'));        
        $result = $this->_db->single();
        return $result;
    }

    /**
     * Compteur ventes année en cours pour vignette
     * @return double $result
     */
    public function salesThisYear()
    {
        $this->_db->query(" SELECT 
                            SUM(quantity * notax_amount)as total_ht
                            FROM `invoices` 
                            WHERE YEAR(invoice_date) = :year");

        $this->_db->bind(':year', date('Y'));
        
        $result = $this->_db->single();
        return $result;
    }

    /**
     * Totalise la valeur d'achat des bâtiments déclaré vendu
     * @return double $result
     */
    public function sumConstructions()
    {
        $this->_db->query(" SELECT 
                            SUM(buyingPrice + taxes) as total
                            FROM constructions
                            WHERE EXISTS
                            (
                                SELECT * FROM sold WHERE constructions.id = sold.REF_constructions AND YEAR(date) = :year
                            )");

        $this->_db->bind(':year', date('Y'));
        
        $result = $this->_db->single();
        return $result;
    }

    /**
     * Totalise les ventes de bâtiments
     * @return double $result
     */
    public function sumSoldConstructions()
    {
        $this->_db->query("SELECT SUM(price) as total FROM `sold` WHERE YEAR(date) = :year");

        $this->_db->bind(':year', date('Y'));
        
        $result = $this->_db->single();
        return $result;
    }

    /**
     * Totalise la valeur des achats effectué pour rénovation bâtiments et déclaré vendu
     * @return double $result
     */
    public function sumPurchasesSold()
    {
        $this->_db->query(" SELECT 
                            SUM(quantity * price) as total
                            FROM suppliers_purchases
                            WHERE EXISTS
                            (
                                SELECT * FROM sold WHERE suppliers_purchases.REF_constructions = sold.REF_constructions AND YEAR(date) = :year
                            )");

        $this->_db->bind(':year', date('Y'));
        
        $result = $this->_db->single();
        return $result;
    }

    /**
     * Totalise la valeur des prestations chantiers déclarés vendus
     * @return double $result
     */
    public function sumPlanningSold()
    {
        $this->_db->query(" SELECT 
                            SUM(hours * salary) as total
                            FROM planning
                            WHERE EXISTS
                            (
                                SELECT * FROM sold WHERE planning.REF_constructions = sold.REF_constructions AND YEAR(date) = :year
                            )");

        $this->_db->bind(':year', date('Y'));
        
        $result = $this->_db->single();
        return $result;
    }

    /**
     * Totalise les achats par mois 
     * @param int $month
     * @param int $year
     * @return double $result
     */
    public function purchasesPerMonth(int $month, int $year)
    {
        $this->_db->query(" SELECT 
                            SUM(price * quantity) as total
                            FROM `suppliers_purchases` 
                            WHERE MONTH(dateInvoice) = :month AND YEAR(dateInvoice) = :year");

        $this->_db->bind(':month', $month);        
        $this->_db->bind(':year', $year);
        
        $result = $this->_db->single();
        return $result;
    }
    
}
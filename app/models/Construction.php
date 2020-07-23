<?php

class Construction
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
        if(empty($data['image']))
        {
            $this->_db->query(" INSERT INTO constructions (REF_city, name, buyingDate, address, taxes, estimatePrice, surface, comment, buyingPrice) 
                                VALUES (:REF_city, :name, :buyingDate, :address, :taxes, :estimatePrice, :surface, :comment, :buyingPrice)");
            $this->_db->bind(':REF_city', $data['REF_city']);
            $this->_db->bind(':name', $data['name']);
            $this->_db->bind(':buyingDate', $data['buyingDate']);
            $this->_db->bind(':address', $data['address']);
            $this->_db->bind(':taxes', $data['taxes']);
            $this->_db->bind(':estimatePrice', $data['estimatePrice']);
            $this->_db->bind(':surface', $data['surface']);
            $this->_db->bind(':comment', $data['comment']);
            $this->_db->bind(':buyingPrice', $data['buyingPrice']);
        }
        else
        {
            $this->_db->query(" INSERT INTO constructions (REF_city, name, buyingDate, address, taxes, estimatePrice, surface, imageCover, comment, buyingPrice) 
                                VALUES (:REF_city, :name, :buyingDate, :address, :taxes, :estimatePrice, :surface, :imageCover, :comment, :buyingPrice)");
            $this->_db->bind(':REF_city', $data['REF_city']);
            $this->_db->bind(':name', $data['name']);
            $this->_db->bind(':buyingDate', $data['buyingDate']);
            $this->_db->bind(':address', $data['address']);
            $this->_db->bind(':taxes', $data['taxes']);
            $this->_db->bind(':estimatePrice', $data['estimatePrice']); 
            $this->_db->bind(':surface', $data['surface']);
            $this->_db->bind(':imageCover', $data['image']);
            $this->_db->bind(':comment', $data['comment']);
            $this->_db->bind(':buyingPrice', $data['buyingPrice']);
        }        

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
        if(empty($data['image']))
        {
            $this->_db->query(' UPDATE constructions 
                                SET REF_city = :REF_city, 
                                    name = :name,
                                    buyingDate = :buyingDate, 
                                    address = :address, 
                                    taxes = :taxes, 
                                    estimatePrice = :estimatePrice, 
                                    surface = :surface, 
                                    comment = :comment, 
                                    buyingPrice = :buyingPrice
                                WHERE id = :id');
            $this->_db->bind(':id', $data['id']);
            $this->_db->bind(':REF_city', $data['REF_city']);
            $this->_db->bind(':name', $data['name']);
            $this->_db->bind(':buyingDate', $data['buyingDate']);
            $this->_db->bind(':address', $data['address']);
            $this->_db->bind(':taxes', $data['taxes']);
            $this->_db->bind(':estimatePrice', $data['estimatePrice']); 
            $this->_db->bind(':surface', $data['surface']);
            $this->_db->bind(':comment', $data['comment']);
            $this->_db->bind(':buyingPrice', $data['buyingPrice']);
        }
        else
        {
            $this->_db->query(' UPDATE constructions 
                                SET REF_city = :REF_city, 
                                    name = :name,
                                    buyingDate = :buyingDate, 
                                    address = :address, 
                                    taxes = :taxes, 
                                    estimatePrice = :estimatePrice, 
                                    surface = :surface, 
                                    imageCover = :imageCover, 
                                    comment = :comment, 
                                    buyingPrice = :buyingPrice
                                WHERE id = :id');
            $this->_db->bind(':id', $data['id']);
            $this->_db->bind(':REF_city', $data['REF_city']);
            $this->_db->bind(':name', $data['name']);
            $this->_db->bind(':buyingDate', $data['buyingDate']);
            $this->_db->bind(':address', $data['address']);
            $this->_db->bind(':taxes', $data['taxes']);
            $this->_db->bind(':estimatePrice', $data['estimatePrice']); 
            $this->_db->bind(':surface', $data['surface']);
            $this->_db->bind(':imageCover', $data['image']);
            $this->_db->bind(':comment', $data['comment']);
            $this->_db->bind(':buyingPrice', $data['buyingPrice']);
        }
        

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Suppression d'un chantier
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool 
    {
        $this->_db->query('DELETE FROM constructions WHERE id = :id');
        $this->_db->bind(':id', $id);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Suppression table 'sold'
     * @param int $id
     * @return bool
     */
    public function cancelSold(int $id): bool 
    {
        $this->_db->query('DELETE FROM sold WHERE REF_constructions = :id');
        $this->_db->bind(':id', $id);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Obtenir tous les channtiers sans distinction
     * @return array $result
     */
    public function getAll()
    {
        $this->_db->query(' SELECT * FROM constructions ORDER BY name');

        $result = $this->_db->resultSet();
        return $result;
    }

    /**
     * Récupère les informations d'un chantier
     * @param int $id
     */
    public function getById(int $id)
    {
        $this->_db->query(' SELECT * FROM constructions WHERE id = :id');
        $this->_db->bind(':id', $id);

        $result = $this->_db->single();
        return $result;
    }

    /**
     * Retourne toutes  les entrées des chantiers actif
     * @return array $result
     */
    public function getConstructions()
    {
        $this->_db->query(' SELECT *,
                            constructions.id as constructions_id,
                            cities.id as cities_id,
                            constructions.name as constructions_name,
                            cities.name as cities_name,
                            DATE_FORMAT(constructions.buyingDate, "%d/%m/%Y") AS buyingDate
                            FROM constructions
                            INNER JOIN cities
                            ON constructions.REF_city = cities.id
                            WHERE NOT EXISTS
                            (
                                SELECT * FROM sold WHERE constructions.id = sold.REF_constructions
                            )
                            ORDER BY constructions.name ASC');
        $result = $this->_db->resultSet();
        return $result;
    }

    /**
     * Retourne toutes  les entrées des bâtiments déclaré vendu
     * @return array $result
     */
    public function getSolds()
    {
        $this->_db->query(' SELECT *,
                            constructions.id as constructions_id,
                            cities.id as cities_id,
                            constructions.name as constructions_name,
                            cities.name as cities_name,
                            sold.price as sold_price,
                            DATE_FORMAT(constructions.buyingDate, "%d/%m/%Y") AS buyingDate
                            FROM constructions
                            INNER JOIN cities
                            ON constructions.REF_city = cities.id
                            INNER JOIN sold
                            ON sold.REF_constructions = constructions.id
                            WHERE EXISTS
                            (
                                SELECT * FROM sold WHERE constructions.id = sold.REF_constructions
                            )');

        $result = $this->_db->resultSet();
        return $result;
    }

    /**
     * Récupère la valeur de vente du chantier
     * @param int $id
     * @return $result
     */
    public function getSoldByid(int $id)
    {   
        $this->_db->query('SELECT price FROM sold WHERE REF_constructions = :id');

        $this->_db->bind(':id', $id);
        $result = $this->_db->single();
        return $result;
    }

    /**
     * Retourne le nombre de références (empeche la suppression en cas de factures liées)
     * @param int $id
     * @return int
     */
    public function refPurchases(int $id): int
    {
        $this->_db->query(' SELECT constructions.id, suppliers_purchases.REF_constructions
                            FROM constructions
                            INNER JOIN suppliers_purchases
                            ON constructions.id = suppliers_purchases.REF_constructions AND constructions.id = :id
                            GROUP BY suppliers_purchases.id_purchase');
        
        $this->_db->bind(':id', $id);
        $result = $this->_db->resultSet();
        return $this->_db->rowCount();
    }

    /**
     * Y a t'il des galeries liées au chantier ciblé?
     * @param int $id
     * @return int
     */
    public function refGalleries(int $id): int
    {
        $this->_db->query(' SELECT constructions.id
                            FROM constructions
                            INNER JOIN galleries
                            ON galleries.REF_constructions = constructions.id AND constructions.id = :id');
        
        $this->_db->bind(':id', $id);
        $result = $this->_db->resultSet();
        return $this->_db->rowCount();
    }

    /**
     * Permet de savoir si il y au moins une entrée existante (exploité dans encodage facture)
     * @return bool
     */
    public function findConstruction(): bool 
    {
        $this->_db->query("SELECT * FROM constructions");
        $row = $this->_db->single();
        
        if($this->_db->rowCount() > 0) return true;
        return false;
    }

    /**
     * Trouver le chantier actif par ID 
     * @param int $id
     * @return bool
     */
    public function findNoSoldById($id): bool 
    {
        $this->_db->query(" SELECT * FROM constructions
                            WHERE NOT EXISTS
                            (
                                SELECT * FROM sold WHERE constructions.id = sold.REF_constructions
                            ) AND constructions.id = :id");

        $this->_db->bind(':id', $id);
        $row = $this->_db->single();
        
        if($this->_db->rowCount() > 0) return true;
        return false;
    }

    /**
     * Trouver la construction via le nom (utile pour gérer l'unicité)
     * @param string $name
     * @return bool
     */
    public function findConstructionByName(string $name): bool 
    {
        $id = explodeGET($_GET['url']);
        if(!isset($id[2])) $id[2] = 0; 
        $this->_db->query("SELECT * FROM constructions WHERE name = :name AND id != $id[2]");
        $this->_db->bind(':name', $name);
        $row = $this->_db->single();
        
        if($this->_db->rowCount() > 0) return true;
        return false;
    }

    /**
     * Trouver le chantier par ID (identifie l'existence de l'id passé en GET pour l'affichage)
     * @param int $id
     * @return bool
     */
    public function findConstructionById($id): bool 
    {
        $this->_db->query("SELECT * FROM constructions WHERE id = :id");
        $this->_db->bind(':id', $id);
        $row = $this->_db->single();
        
        if($this->_db->rowCount() > 0) return true;
        return false;
    }

    /**
     * Récupère le chantier courant à afficher dans la vue
     * @param int $id
     * @return array $result
     */
    public function currentConstruction(int $id)
    {
        $this->_db->query(' SELECT *,
                            constructions.id as construction_id,
                            cities.id as city_id,
                            constructions.name as construction_name,
                            cities.name as city_name,
                            DATE_FORMAT(constructions.createDate, "%d/%m/%Y") as createDate
                            FROM constructions 
                            INNER JOIN cities
                            ON constructions.REF_city = cities.id AND constructions.id = :id');
        $this->_db->bind(':id', $id);
        $result = $this->_db->single();

        return $result;
    }

    /**
     * Renvoi vrai si le bâtiment est déclaré vendu autrement renvoi faux
     * @param int $id
     * @return bool
     */
    public function isSold(int $id): bool 
    {
        $this->_db->query(' SELECT constructions.id, sold.REF_constructions
                            FROM constructions
                            INNER JOIN sold
                            ON constructions.id = sold.REF_constructions AND constructions.id = :id');
        $this->_db->bind(':id', $id);
        $row = $this->_db->single();

        if($this->_db->rowCount() > 0) return true;
        return false;
    }

    /**
     * Compte le nombre de bâtiment vendu pour l'affichage des badge
     * @return int
     */
    public function countSoldList() 
    {
        $this->_db->query('SELECT id FROM sold');
        $result = $this->_db->resultSet();
        return $this->_db->rowCount();
    }

    /**
     * Compte le nombre de chantier en cours pour l'affichage des badge
     * @return int
     */
    public function countOnGoingList() 
    {
        $this->_db->query('SELECT id FROM constructions
                            WHERE NOT EXISTS
                            (
                                SELECT * FROM sold WHERE constructions.id = sold.REF_constructions
                            )');
        $result = $this->_db->resultSet();
        return $this->_db->rowCount();
    }

    /**
     * Déclarer un chantier vendu
     * @param int $price
     * @param double $price
     * @return bool
     */
    public function sold(int $id, $price, $date)
    {
        $this->_db->query("INSERT INTO sold (REF_constructions, price, date) 
                                  VALUES (:REF_constructions, :price, :date)");
         $this->_db->bind(':REF_constructions', $id);
         $this->_db->bind(':price', $price);
         $this->_db->bind(':date', $date);

         if($this->_db->execute()) return true;
         return false;
    }
}
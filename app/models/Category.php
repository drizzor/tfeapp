<?php 

class Category
{
    private $_db;

    public function __construct()
    {
        $this->_db = new Database;
    }

    /**
     * Insérer les données
     * @param array $data
     * @return bool
     */
    public function insert(array $data): bool
    {
        $this->_db->query('INSERT INTO categories (name, description) VALUES (:name, :description)');
        $this->_db->bind(':name', $data['name']);
        $this->_db->bind(':description', $data['description']);

        if($this->_db->execute()) return true;
        else return false;
    }

    /**
     * Insérer les données
     * @param array $data
     * @return bool
     */
    public function update(array $data): bool
    {
        $this->_db->query('UPDATE categories SET name = :name, description = :description WHERE id = :id');
        $this->_db->bind(':id', $data['id']);
        $this->_db->bind(':name', $data['name']);
        $this->_db->bind(':description', $data['description']);

        if($this->_db->execute()) return true;
        else return false;
    }

    /**
     * Suppression d'une catégorie
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool 
    {
        $this->_db->query('DELETE FROM categories WHERE id = :id');
        
        $this->_db->bind(':id', $id);
        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Retourne toutes  les entrées des catégories
     * @return array $result
     */
    public function getCategories()
    {
        $this->_db->query('SELECT * FROM categories ORDER BY name ASC');

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
        $this->_db->query(' SELECT categories.id, suppliers_purchases.REF_categories
                            FROM categories
                            INNER JOIN suppliers_purchases
                            ON categories.id = suppliers_purchases.REF_categories AND categories.id = :id');

        $this->_db->bind(':id', $id);
        $result = $this->_db->resultSet();
        return $this->_db->rowCount();
    }

       /**
     * Permet de s'assurer de l'existance d'au moins une catégorie (exploité dans le systeme de facturation)
     * @return bool
     */
    public function findCategory(): bool
    {
        $this->_db->query("SELECT id FROM categories");
        
        $row = $this->_db->single();        
        if($this->_db->rowCount() > 0) return true;
        return false;
    }

    /**
     * Trouver une catégorie via son nom - Utilisé pour l'unicité
     * @param string $name
     * @return bool
     */
    public function findCategoryByName(string $name): bool
    {
        $id = explodeGET($_GET['url']);
        // $categoryId = h($categoryId); inutile la vérif est déjà effectuée dans le coeur de l'app
        if(!isset($id[2])) $id[2] = 0; 
        $this->_db->query("SELECT * FROM categories WHERE name = :name AND id != $id[2]");
        $this->_db->bind(':name', $name);
        $row = $this->_db->single();
        
        if($this->_db->rowCount() > 0) return true;
        return false;
    } 

    /**
     * Permet de trouver une catégorie pas son ID et vérifier les id passé en URL
     * @param int $id
     * @return bool
     */
    public function findCategoryById($id): bool
    {
        $this->_db->query("SELECT id FROM categories WHERE id = :id");
        $this->_db->bind(':id', $id);
        $row = $this->_db->single();
        
        if($this->_db->rowCount() > 0) return true;
        return false;
    }

    /**
     * Récupère la catégorie courante à afficher dans la vue
     * @param int $id
     * @return array $result
     */
    public function currentCategory(int $id)
    {
        $this->_db->query('SELECT * FROM categories WHERE id = :id');
        $this->_db->bind(':id', $id);
        $result = $this->_db->single();

        return $result;
    }

    /**
     * Récupère la/les catégories exploiter pour l'affichage des achats et différentes catégories
     * @param int $id
     * @return array $result
     */
    public function getById(int $id)
    {
        $this->_db->query('SELECT name FROM categories WHERE id = :id');
        $this->_db->bind(':id', $id);
        $result = $this->_db->resultSet();

        return $result;
    }
}
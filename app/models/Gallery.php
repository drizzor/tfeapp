<?php

/**
 * Class gérant toutes les fonctionnalités liées à l'utilisateur (hors gestion admin)
 */
class Gallery 
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
     * Afficher l'ensemble des galeries existante
     * @return array $result
     */
    public function getAll()
    {
        $this->_db->query(' SELECT
                            galleries.id as gallery_id,
                            constructions.id as construction_id,
                            constructions.name as construction_name,
                            constructions.imageCover as construction_cover
                            FROM `galleries` 
                            INNER JOIN constructions
                            ON constructions.id = galleries.REF_constructions
                            GROUP BY galleries.REF_constructions
                            ORDER BY constructions.name');
                            
        $result = $this->_db->resultSet();
        return $result;
    }

    /**
     * Récupérer les infos de la galerie via ID
     * @param int $id
     * @return array
     */
    public function getById(int $id)
    {
        $this->_db->query(' SELECT  
                            constructions.id as construction_id,
                            constructions.name as construction_name,
                            galleries.id as gallery_id,
                            galleries.REF_members,
                            galleries.title,
                            galleries.image,
                            galleries.type,
                            galleries.size,
                            galleries.comment as gallery_comment,
                            galleries.inProgress
                            FROM galleries
                            INNER JOIN constructions
                            ON constructions.id = galleries.REF_constructions AND galleries.REF_constructions = :id');
    
        $this->_db->bind(':id', $id);
        $row = $this->_db->resultSet();
        return $row;
    }

    /**
     * Récupérer l'image par ID
     * @param int $id
     * @return array
     */
    public function getByIdI(int $id)
    {
        $this->_db->query('SELECT * FROM galleries WHERE id = :id');
    
        $this->_db->bind(':id', $id);
        $row = $this->_db->resultSet();
        return $row;
    }

    /**
     * Rechercher via l'id
     * @param int $id
     * @return bool
     */
    public function findGById(int $id)
    {
        $this->_db->query('SELECT REF_constructions FROM galleries WHERE REF_constructions = :id');
        $this->_db->bind(':id', $id);

        $row = $this->_db->single();
        if($this->_db->rowCount() > 0) return true;
        return false;
    }   

    /**
     * Trouver une image cible
     * @param int $id
     * @return bool
     */
    public function findIById(int $id)
    {
        $this->_db->query('SELECT id FROM galleries WHERE id = :id');
        $this->_db->bind(':id', $id);

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
        $this->_db->query('INSERT INTO galleries(REF_constructions, REF_members, title, comment, image, type, size, inProgress)  VALUES (:construction_id, :member_id, :title, :comment, :image, :type, :size, :inProgress)');
        $this->_db->bind(':construction_id', $data['construction_id']);
        $this->_db->bind(':member_id', $_SESSION['user_id']);
        $this->_db->bind(':title', $data['title']);
        $this->_db->bind(':comment', $data['description']);
        $this->_db->bind(':image', $data['image']);
        $this->_db->bind(':type', $data['type']);
        $this->_db->bind(':size', $data['size']);
        $this->_db->bind(':inProgress', $data['checkMode']);

        if($this->_db->execute()) return true;
        else return false;
    }

    /**
     * Méthode permettant la mise à jour d'un profil ouvrier
     * @param array $data
     * @return bool
     */
    public function update(array $data): bool
    {
       
        $this->_db->query('UPDATE galleries SET title = :title, comment = :description, inProgress = :inProgress WHERE id = :id');
        $this->_db->bind(':id', $data['id_i']);
        $this->_db->bind(':title', $data['title']);
        $this->_db->bind(':description', $data['description']); 
        $this->_db->bind(':inProgress', $data['checkMode']); 
        
        if($this->_db->execute()) return true;
        return false;
    }
    
    /**
     * Suppression d'une gallerie entière
     * @param int $id
     * @return bool 
     */
    public function deleteG(int $id): bool
    {
        $this->_db->query('DELETE FROM galleries WHERE REF_constructions = :id');
        $this->_db->bind(':id', $id);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Suppression d'une image
     * @param int $id
     * @return bool 
     */
    public function deleteI(int $id): bool
    {
        $this->_db->query('DELETE FROM galleries WHERE id = :id');
        $this->_db->bind(':id', $id);

        if($this->_db->execute()) return true;
        return false;
    }


    /**
     * Sommation du poid des images par chantier
     * @param int $id
     * @return $row
     */
    public function sumSize(int $id)
    {
        $this->_db->query('SELECT SUM(size) as size FROM galleries WHERE REF_constructions = :id');
        $this->_db->bind(':id', $id);

        $row = $this->_db->single();
        return $row;
    }

    /**
     * Sortir  le nombre d'image présente dans la galerie
     * @param int $id
     * @return array $result
     */
    public function references(int $id)
    {
        $this->_db->query('SELECT REF_constructions FROM galleries WHERE REF_constructions = :id');
        $this->_db->bind(':id', $id);

        $result = $this->_db->resultSet();
        return $this->_db->rowCount();
    }
}
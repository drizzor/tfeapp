<?php

/**
 * Class gérant toutes les fonctionnalités liées à l'utilisateur (hors gestion admin)
 */
class User 
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
     * Méthode d'enregistrement d'un utilisateur
     * @param array $data
     * @return bool
     */
    public function register(array $data): bool
    {
        $this->_db->query('INSERT INTO members (username, email, password, ip) VALUES (:username, :email, :password, :ip)');
        $this->_db->bind(':username', $data['username']);
        $this->_db->bind(':email', $data['email']);
        $this->_db->bind(':password', $data['password']);
        $this->_db->bind(':ip', $_SERVER['REMOTE_ADDR']);

        if($this->_db->execute()) return true;
        else return false;
    }

    /**
     * Méthode d'enregistrement d'un admin
     * @param array $data
     * @return bool
     */
    public function registerAdmin(array $data): bool
    {
        $this->_db->query('INSERT INTO members (id, REF_level, username, email, password, ip, activate) VALUES (1, 1, :username, :email, :password, :ip, 1)');
        $this->_db->bind(':username', $data['username']);
        $this->_db->bind(':email', $data['email']);
        $this->_db->bind(':password', $data['password']);
        $this->_db->bind(':ip', $_SERVER['REMOTE_ADDR']);

        if($this->_db->execute()) return true;
        else return false;
    }

    /**
     * Méthode d'enregistrement d'un utilisateur par l'administrateur
     * @param array $data
     * @return bool
     */
    public function insert(array $data): bool
    {
        $this->_db->query('INSERT INTO members (REF_level, username, email, password, activate) VALUES (:REF_level, :username, :email, :password, 1)');
        $this->_db->bind(':REF_level', $data['level_id']);
        $this->_db->bind(':username', $data['username']);
        $this->_db->bind(':email', $data['email']);
        $this->_db->bind(':password', $data['password']);

        if($this->_db->execute()) return true;
        else return false;
    }

    /**
     * Méthode permettant la mise d'un profil propre à l'utilisateur
     * @param array $data
     * @return bool
     */
    public function update(array $data): bool
    {
        if(empty($data['password']) && empty($data['image']))
        {
            $this->_db->query('UPDATE members SET username = :username, email = :email WHERE id = :id');
            $this->_db->bind(':id', $data['current']['member_id']);
            $this->_db->bind(':username', $data['username']);
            $this->_db->bind(':email', $data['email']);
        }
        else if(empty($data['password']))
        {
            $this->_db->query('UPDATE members SET username = :username, email = :email, image = :image WHERE id = :id');
            $this->_db->bind(':id', $data['current']['member_id']);
            $this->_db->bind(':username', $data['username']);
            $this->_db->bind(':email', $data['email']);
            $this->_db->bind(':image', $data['image']);
        }
        else if(empty($data['image']))
        {
            $this->_db->query('UPDATE members SET username = :username, email = :email, password = :password WHERE id = :id');
            $this->_db->bind(':id', $data['current']['member_id']);
            $this->_db->bind(':username', $data['username']);
            $this->_db->bind(':email', $data['email']);
            $this->_db->bind(':password', $data['password']);
        }
        else
        {
            $this->_db->query('UPDATE members SET username = :username, email = :email, image = :image, password = :password WHERE id = :id');
            $this->_db->bind(':id', $data['current']['member_id']);
            $this->_db->bind(':username', $data['username']);
            $this->_db->bind(':email', $data['email']);
            $this->_db->bind(':image', $data['image']); 
            $this->_db->bind(':password', $data['password']);
        }    
        
        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Méthode permettant de mettre à jour l'utilisateur via le dashboard admin
     * @param array $data
     * @return bool
     */
    public function updateByAdmin(array $data): bool
    {
        if(empty($data['password']) && empty($data['image']))
        {
            $this->_db->query('UPDATE members SET REF_level = :REF_level, username = :username, email = :email WHERE id = :id');
            $this->_db->bind(':id', $data['current']['member_id']);
            $this->_db->bind(':REF_level', $data['level_id']);
            $this->_db->bind(':username', $data['username']);
            $this->_db->bind(':email', $data['email']);
        }
        else if(empty($data['password']))
        {
            $this->_db->query('UPDATE members SET REF_level = :REF_level, username = :username, email = :email, image = :image WHERE id = :id');
            $this->_db->bind(':id', $data['current']['member_id']);
            $this->_db->bind(':REF_level', $data['level_id']);
            $this->_db->bind(':username', $data['username']);
            $this->_db->bind(':email', $data['email']);
            $this->_db->bind(':image', $data['image']);
        }
        else if(empty($data['image']))
        {
            $this->_db->query('UPDATE members SET REF_level = :REF_level, username = :username, email = :email, password = :password WHERE id = :id');
            $this->_db->bind(':id', $data['current']['member_id']);
            $this->_db->bind(':REF_level', $data['level_id']);
            $this->_db->bind(':username', $data['username']);
            $this->_db->bind(':email', $data['email']);
            $this->_db->bind(':password', $data['password']);
        }
        else
        {
            $this->_db->query('UPDATE members SET REF_level = :REF_level, username = :username, email = :email, image = :image, password = :password WHERE id = :id');
            $this->_db->bind(':id', $data['current']['member_id']);
            $this->_db->bind(':REF_level', $data['level_id']);
            $this->_db->bind(':username', $data['username']);
            $this->_db->bind(':email', $data['email']);
            $this->_db->bind(':image', $data['image']); 
            $this->_db->bind(':password', $data['password']);
        }    
        
        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Suppression d'un utilisateur
     * @param int $id
     * @return bool 
     */
    public function delete(int $id): bool
    {
        $this->_db->query('DELETE FROM members WHERE id = :id');
        $this->_db->bind(':id', $id);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * L'utilisateur a t'il créé des achats?
     * @param int $id 
     * @return int
     */
    public function refPurchases(int $id)
    {
        $this->_db->query(' SELECT members.id, suppliers_purchases.REF_members
                            FROM members
                            INNER JOIN suppliers_purchases
                            ON members.id = suppliers_purchases.REF_members AND members.id = :id');

        $this->_db->bind(':id', $id);
        $result = $this->_db->resultSet();
        return $this->_db->rowCount();
    }

    /**
     * L'utilisateur a t'il créé des galeries?
     * @param int $id 
     * @return int
     */
    public function refGalleries(int $id)
    {
        $this->_db->query(' SELECT members.id, galleries.REF_members
                            FROM members
                            INNER JOIN galleries
                            ON members.id = galleries.REF_members AND members.id = :id');

        $this->_db->bind(':id', $id);
        $result = $this->_db->resultSet();
        return $this->_db->rowCount();
    }

    /**
     * Permet d'injecter un ID pour "utilisateur_supprimé" avant suppression définitive
     * @param int $idNew      => le nouveau ID de remplacement
     * @param int $idToSearch => l'id à trouver et remplacer
     * @return bool
     */
    public function migrateRefPurchases(int $idNew, int $idToSearch): bool 
    {
        $this->_db->query('UPDATE suppliers_purchases SET REF_members = :idNew WHERE id = :idToSearch');
        $this->_db->bind(':idToSearch', $idToSearch);
        $this->_db->bind(':idNew', $idNew);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Permet d'injecter un ID pour "utilisateur_supprimé" avant suppression définitive
     * @param int $idNew      => le nouveau ID de remplacement
     * @param int $idToSearch => l'id à trouver et remplacer
     * @return bool
     */
    public function migrateRefGalleries(int $idNew, int $idToSearch): bool 
    {
        $this->_db->query('UPDATE galleries SET REF_members = :idNew WHERE id = :idToSearch');
        $this->_db->bind(':idToSearch', $idToSearch);
        $this->_db->bind(':idNew', $idNew);

        if($this->_db->execute()) return true;
        return false;
    }
    
    /**
     * Afficher tous les niveaux d'utilisateur existant
     */
    public function getLevels()
    {
        $this->_db->query('SELECT * FROM level ORDER BY level ASC');
        $result = $this->_db->resultSet();
        return $result;
    }

    /**
     * Récupère le niveau de l'utilisateur à mettre dans la session
     * @param int $REF_level
     */
    public function getUserLevel(int $REF_level)
    {
        $this->_db->query('SELECT * FROM level WHERE id = :id');
        $this->_db->bind(':id', $REF_level);

        $row = $this->_db->single();
        return $row;
    }

    /**
     * Requete permettant la connexion à l'application ou le refus
     * @param string $username
     * @param string password
     * @return bool||array
     */
    public function login(string $username, string $password)
    {
        $this->_db->query('SELECT * FROM members WHERE username = :username');
        $this->_db->bind(':username', $username);

        $row = $this->_db->single();
        $hashed_password = $row['password'];

        if(password_verify($password, $hashed_password)) return $row;
        return false;
    }

    /**
     * Permet de vérifier si la donnée envoyée correspond à celle en DB (vérification changement de MDP)
     * @param int $id
     * @param string $current_password
     * @return bool
     */
    public function passwordVerify(int $id, string $current_password): bool
    {
        $this->_db->query('SELECT * FROM members WHERE id = :id');
        $this->_db->bind(':id', $id);

        $row = $this->_db->single();
        $hashed_password = $row['password'];

        if(password_verify($current_password, $hashed_password)) return true;
        return false;
    }

    /**
     * Obtenir la liste de tous les utilisateurs
     * @param bool $activate
     * @return array
     */
    public function getUsers(bool $activate = true)
    {
        ($activate)? 1 : 0;
        $this->_db->query(" SELECT *,
                            members.id as member_id,
                            level.id as level_id,
                            activities.id as activity_id,
                            DATE_FORMAT(members.dateCreate, '%d/%m/%Y') as dateCreate 
                            FROM members
                            INNER JOIN level
                            ON level.id = members.REF_level AND members.activate = :activate
                            LEFT JOIN activities
                            ON activities.REF_members = members.id");
        $this->_db->bind(':activate', $activate);
        $result = $this->_db->resultSet();
        return $result;
    }   

     /**
     * Obtenir la liste de toutes les tentatives de connexions
     * @return array
     */
    public function getAttempts()
    {
        $this->_db->query("SELECT *, DATE_FORMAT(lastLogin, '%d/%m/%Y') as day, DATE_FORMAT(lastLogin, '%H:%i:%s') as hour FROM login_attempts");
        $result = $this->_db->resultSet();
        return $result;
    } 
    
    /**
     * Récupérer les infos de utilisateur via ID
     * @param int $id
     * @return array
     */
    public function getUserById(int $id)
    {
        $this->_db->query('SELECT *, 
                           members.id as member_id,
                           level.id as level_id,
                           level.name as level_name,
                           DATE_FORMAT(members.dateCreate, "%d/%m/%Y") as member_dateCreate                  
                           FROM members 
                           INNER JOIN level
                           ON members.REF_level = level.id AND members.id = :id');
        $this->_db->bind(':id', $id);
        $row = $this->_db->single();
        return $row;
    }
    
    /**
     * Permet d'obtenir un utlisateur par son nom
     * @param string $username
     * @return array
     */
    public function getUserByUsername(string $username): array
    {
        $this->_db->query("SELECT * FROM members WHERE username = :username");
        $this->_db->bind(':username', $username);
        $row = $this->_db->single();
        return $row;
    }

    /**
     * Trouver un utilisateur via son email (utile pour l'unicité)
     * @param string $email
     * @return bool
     */
    public function findUserByEmail(string $email): bool
    {
        // Je ne vérifier pas l'unicité vis à vis de l'utilisateur même
        $id = explodeGET($_GET['url']);
        if(!isset($id[2])) $id[2] = 0; 
        $this->_db->query("SELECT * FROM members WHERE email = :email AND id != $id[2]");
        $this->_db->bind(':email', $email);
        $row = $this->_db->single();
        
        if($this->_db->rowCount() > 0) return true;
        return false;
    }   

    /**
     * Permet de trouver l'email qand l'utilisateur souhaite récupérer son compte
     * @param string $email
     * @return bool
     */
    public function recovFindEmail(string $email)
    {
        $this->_db->query("SELECT * FROM members WHERE email = :email");
        $this->_db->bind(':email', $email);
        $row = $this->_db->single();
        
        if($this->_db->rowCount() > 0) return true;
        return false;
    }

    /**
     * Bascule le bit afin ensuite de déterminer si un compte est en mode recovery ou non
     * @param string $email
     * @param bool $active
     * @return bool
     */
    public function recovMod(string $email, $active = 0)
    {
        $this->_db->query("UPDATE members SET recovery = :recovery WHERE email = :email");
        $this->_db->bind(':email', $email);
        $this->_db->bind(':recovery', $active);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Génère un nouveau mot de passe pour le compte cible
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function recovPass(string $email, string $password)
    {
        $this->_db->query("UPDATE members SET password = :password WHERE email = :email");
        $this->_db->bind(':email', $email);
        $this->_db->bind(':password', $password);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Trouver un utilisateur via  id & adaptation pour les requests users
     * @param int $id
     * @param bool $activate
     * @return bool
     */
    public function findUserById(int $id, ?bool $activate = null): bool
    {
        if(empty($activate) && !isset($activate)){
            $this->_db->query('SELECT * FROM members WHERE id = :id');
            $this->_db->bind(':id', $id);
            $row = $this->_db->single();
            if($this->_db->rowCount() > 0) return true;
            return false;
        }
        else
        {
            $this->_db->query('SELECT * FROM members WHERE id = :id AND activate = :activate');
            $this->_db->bind(':id', $id);
            $this->_db->bind(':activate', $activate);
            $row = $this->_db->single();
            if($this->_db->rowCount() > 0) return true;
            return false;
        }        
    }

    /**
     * Trouver le niveau sélectionné
     * @param int $id
     * @return bool
     */
    public function findLevelById(int $id): bool
    {
        $this->_db->query('SELECT id FROM level WHERE id = :id');
        $this->_db->bind(':id', $id);
        $row = $this->_db->single();

        if($this->_db->rowCount() > 0) return true;
        return false;
    }

    /**
     * Trouver les tentatives de connexion par l'internet protocol
     * @param string $ip
     * @return bool||array
     */
    public function findAttempt($value, $byIp = true)
    {
        if($byIp){
            $this->_db->query('SELECT * FROM login_attempts WHERE IP = :IP');

            $this->_db->bind(':IP', $value);        
            $row = $this->_db->single();
            if($this->_db->rowCount() > 0) return $row;
            return false;
        }
        else{
            $this->_db->query('SELECT * FROM login_attempts WHERE id = :id');

            $this->_db->bind(':id', $value);        
            $row = $this->_db->single();
            if($this->_db->rowCount() > 0) return $row;
            return false;
        }
        
    }

    /**
     * Trouver un utilisateur via son nom d'utilisateur
     * @param string $username
     * @return bool
     */
    public function findUserByUsername(string $username): bool
    {
        // Je ne vérifier pas l'unicité vis à vis de l'utilisateur même
        $id = explodeGET($_GET['url']);
        if(!isset($id[2])) $id[2] = 0; 
        $this->_db->query("SELECT * FROM members WHERE username = :username AND id != $id[2]");
        $this->_db->bind(':username', $username);
        $row = $this->_db->single();
        
        if($this->_db->rowCount() > 0) return true;
        return false;
    }  

    /**
     * Vérifie si l'utilisateur est bloqué ou non
     * @param int $id
     * @return bool
     */
    public function isBlocked(int $id)
    {
        $this->_db->query('SELECT * FROM blocked WHERE REF_M_blocked = :id');
        $this->_db->bind(':id', $id);
        $row = $this->_db->single();

        if($this->_db->rowCount() > 0) return true;
        return false;
    }
    
    /**
     * Permet de bloquer l'accès à un utilisateur
     * @param int $id
     * @return bool
     */
    public function lock(int $id): bool 
    {
        $this->_db->query('INSERT INTO blocked (REF_M_admin, REF_M_blocked) VALUES (:REF_M_admin, :REF_M_blocked)');
        $this->_db->bind(':REF_M_blocked', $id);
        $this->_db->bind(':REF_M_admin', $_SESSION['user_id']);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Permet de débloquer l'accès à un utilisateur
     * @param int $id
     * @return bool
     */
    public function unlock(int $id): bool 
    {
        $this->_db->query('DELETE FROM blocked WHERE REF_M_blocked = :REF_M_blocked');
        $this->_db->bind(':REF_M_blocked', $id);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Ajout d'une nouvelle identification de tentative de connexion via IP
     * @param string $ip
     * @param bool $adminInsert => sert à différencier l'ajout par admin et automatique
     * @return bool 
     */
    public function insertAttemptByIp(string $ip, bool $adminInsert = false): bool
    {
        if(!$adminInsert){
            $this->_db->query('INSERT INTO login_attempts (IP) VALUES (:ip)');
            $this->_db->bind(':ip', $ip);

            if($this->_db->execute()) return true;
            return false;
        }
        else{
            $this->_db->query('INSERT INTO login_attempts (IP, attempts) VALUES (:ip, :attempts)');
            $this->_db->bind(':ip', $ip);
            $this->_db->bind(':attempts', 4);

            if($this->_db->execute()) return true;
            return false;
        }
        
    }
    
    /**
     * Mise à jour de l'identification de tentative de connexion via IP
     * @param string $ip
     * @return bool 
     */
    public function updateAttemptByIp(string $ip): bool
    {
        $this->_db->query('UPDATE login_attempts SET attempts = attempts + 1 WHERE IP = :ip');
        $this->_db->bind(':ip', $ip);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Lors du login, récupération de l'ip de l'utilisateur
     * @param int $id
     * @param string $ip
     * @return bool
     */
    public function refreshIp(int $id, string $ip): bool 
    {
        $this->_db->query('UPDATE members SET ip = :ip WHERE id = :id');
        $this->_db->bind(':id', $id);
        $this->_db->bind(':ip', $ip);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Suppression des tentatives en cas de connexion
     * @param string||int $value => Pouvant prendre soit uen suppression par IP soit par ID
     * @param bool $byIp => permet de définir la requete à executer
     * @return bool 
     */
    public function deleteAttempt($value, bool $byIp = true): bool
    {
        if($byIp){
            $this->_db->query('DELETE FROM login_attempts WHERE IP = :IP');
            $this->_db->bind(':IP', $value);

            if($this->_db->execute()) return true;
            return false;
        }
        else{
            $this->_db->query('DELETE FROM login_attempts WHERE id = :id');
            $this->_db->bind(':id', $value);

            if($this->_db->execute()) return true;
            return false;
        }
        
    }

    /**
     * Supprimer un utilisateur par ID
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id): bool
    {
        $this->_db->query('DELETE FROM members WHERE id = :id');
        $this->_db->bind(':id', $id);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Compte le nombre de comtpe existant - exploité pour l'affichage de page nouvelle install
     */
    public function countAll()
    {
        $this->_db->query('SELECT count(id) as count FROM members');

        $row = $this->_db->single();
        return $row;
    }

    /**
     * Compte le nombre de demande d'adhésion en cours
     * @return int
     */
    public function countRequest() 
    {
        $this->_db->query('SELECT id FROM members WHERE activate = 0');
        $result = $this->_db->resultSet();
        return $this->_db->rowCount();
    }

     /**
     * Compte le nombre de demande d'adhésion en cours
     * @return int
     */
    public function countIntruder() 
    {
        $this->_db->query('SELECT id FROM login_attempts');
        $result = $this->_db->resultSet();
        return $this->_db->rowCount();
    }

    /**
     * Permet la validation des utilisateurs non activés
     * @param int $id
     * @return bool
     */
    public function validate(int $id): bool 
    {
        $this->_db->query('UPDATE members SET activate = :activate WHERE id = :id');
        $this->_db->bind(':id', $id);
        $this->_db->bind(':activate', 1);

        if($this->_db->execute()) return true;
        return false;
    }


    /**** GESTION ACTIVITE UTILISATEUR (connecté / déconnecté) ****/

    /**
     * Insertion de l'acttivité - première connexion
     * @param int $id
     * @return bool
     */
    public function insertActivity(int $id): bool
    {
        $this->_db->query('INSERT INTO activities (REF_members) VALUES (:id)');
        $this->_db->bind(':id', $id);

        if($this->_db->execute()) return true;
        return false;
    }

    /**
     * Mise à jour du timestamp
     * @param int $id -> id utilisateur
     * @return bool
     */
    public function updateActivity(int $id): bool
    {
        $this->_db->query('UPDATE activities SET lastActivity = NOW() WHERE REF_members = :id');
        $this->_db->bind(':id', $id);

        if($this->_db->execute()) return true;
        return false;
    }

    public function getActivity(int $id)
    {
        $this->_db->query('SELECT lastActivity FROM activities WHERE REF_members = :id');
        $this->_db->bind(':id', $id);

        $row = $this->_db->single();
        return $row;
    }

    /**
     * Trouver existance d'activités pour l'utilisateur
     * @param int $id -> id utilisateur
     * @return int
     */
    public function findActivity(int $id): bool
    {
        $this->_db->query("SELECT id FROM activities WHERE REF_members = :id");
        $this->_db->bind(':id', $id);

        $row = $this->_db->single();
        if($this->_db->rowCount() > 0) return true;
        return false;
    }

    /**
     * Si utilisateur supprimé, supprimé également l'activité reliée
     * @param int $id -> id utilisateur
     * @return bool
     */
    public function deleteActivity(int $id): bool
    {
        $this->_db->query('DELETE FROM activities WHERE REF_members = :id');
        $this->_db->bind(':id', $id);

        if($this->_db->execute()) return true;
        return false;
    }
}
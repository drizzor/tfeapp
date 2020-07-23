<?php
/*
 * PDO Database classe
 *  Permet de se connecter à la DB
 *  Requete préparée
 *  Binder les valeurs
 *  Retourner les lignes et le résultat
 */
class Database
{
    private $_host      = DB_HOST;
    private $_user      = DB_USER;
    private $_pass      = DB_PASS;
    private $_dbname    = DB_NAME;
    private $_charset   = DB_CHARSET;
    
    private $_dbh; // data base handler : les requetes préparées seront établies sur cette variable
    private $_statement;
    private $_error;

    public function __construct()
    {
        // Set DSN
        $dsn     = 'mysql:host=' . $this->_host . ';dbname=' . $this->_dbname . ';charset=' . $this->_charset; // Data Source Name - Chaine de connexion
        $options = array(
            PDO::ATTR_PERSISTENT => true, // Augmente les performances lors de la vérification de co à la DB
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION //Permet de renvoyer les erreurs PHP des façon plus "élégante"
        );

        try
        {
            $this->_dbh = new PDO($dsn, $this->_user, $this->_pass, $options);
        }
        catch(PDOException $e)
        {
            $this->_error = $e->getMessage();
            echo $this->_error;
        }
    }


    // Prépare les statements pour nos requetes
    public function query($sql)
    {
        $this->_statement = $this->_dbh->prepare($sql);
    }

    /**
     * Binder les valeurs d'une requête
     * 
     */
    public function bind($param, $value, $type = null)
    {
        if (is_null($type))
        {
            switch(true)
            {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break; 
                default:
                    $type = PDO::PARAM_STR; 
            }
        }
        
        $this->_statement->bindValue($param, $value, $type); // $q->bindValue(':pseudo', $p->getPseudo(), PDO::PARAM_STR);
    }

    // Execute la requete préparée
    public function execute()
    {
        return $this->_statement->execute();
    }

    // Récupérer les résultats de la requete dans un tableau 
    public function  resultSet()
    {
        $this->execute();
        return $this->_statement->fetchAll(/*PDO::FETCH_OBJ*/);
    }   

    // Récupérer le résultat 
    public function single()
    {
        $this->execute();
        return $this->_statement->fetch(/*PDO::FETCH_OBJ*/);
    }

    // Obtenir le nombre de ligne
    public function rowCount()
    {
        return $this->_statement->rowCount();
    }
}
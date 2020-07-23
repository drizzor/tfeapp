<?php
/*
 * App core Class
 *  Créer des URL & charger le controller principal s'il existe
 *  URL FORMAT : /controller/method/params
 * 
 */ 
class Core
{
    protected $currentController = 'Start';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->getUrl();

        if(file_exists('../app/controllers/' . ucwords($url[0]) . '.php'))
        {
            $this->currentController = ucwords($url[0]);
            unset($url[0]);
        }

        require_once '../app/controllers/' . $this->currentController . '.php';
        $this->currentController = new $this->currentController; 

        if(isset($url[1]))
        {
            if(method_exists($this->currentController, $url[1]))
            {
                $this->currentMethod = $url[1];
                unset($url[1]);
            }
            else
            {
                e404();
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    /**
     * Retourne l'URL sous forme de tableau. Permettant de vérifier les éléments distinctement
     */
    public function getUrl() 
    {
        if(isset($_GET['url']))
        {
            // Supprimer le dernier "/" de l'url : .../post/5"/" <-
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
             return $url; 
        }        
    }
}
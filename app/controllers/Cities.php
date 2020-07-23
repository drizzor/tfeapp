<?php

/**
 * Cette classe n'est utile que pour l'autocompletion
 */
class Cities extends Controller
{
    /**
     * INIT du modele 
     */
    public function __construct()
    {
        $this->userModel = $this->model('User');

        if(isLoggedIn())
        {
            $isExist = $this->userModel->findUserById($_SESSION['user_id']);
            if(!$isExist)
            {
                flash('delete_account', 'Votre compte a été supprimé !');
                redirect('users/logout');
            }          
        } 
        else redirect('users/login');           

        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if($this->userModel->isBlocked($currentUser['member_id'])) redirect('users/logout');

        // mise à jour de la session du statut utilisateur
        $_SESSION['user_level']['name'] = $currentUser['level_name'];
        $_SESSION['user_level']['id'] = $currentUser['level_id'];
        $this->userModel->updateActivity($_SESSION['user_id']);
        
        $this->cityModel = $this->model('City');
    }

    /**
     * Index non exploité, e404()
     */
    public function index()
    {
        e404();
    }

    /**
     * Ajax va récupérer les données depuis cette méthode
     */
    public function autocomplete()
    {      
        if(isset($_POST['search']))
        {
            $response = "<ul class='dropdownField'><li>Aucune ville trouvée!</li></ul>"; 
        
            $q = h($_POST['q']);
        
            // J'execute la requete
            $result = $this->cityModel->getCitiesLike($q);

            // dd(count($result));
            // die();

            if(count($result) > 0)
            {
                $response = "<ul class='dropdownField'>";

                foreach($result as $data)
                    $response .= "<li class='city'>{$data['name']} ({$data['zipcode']})</li>";

                $response .= "</ul>";
            }
        
            exit($response);
        }
    }
}
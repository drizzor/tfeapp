<?php

class Start extends Controller
{
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

        // mise à jour constante de la session du statut utilisateur & activité
        $_SESSION['user_level']['name'] = $currentUser['level_name'];
        $_SESSION['user_level']['id'] = $currentUser['level_id'];
        $this->userModel->updateActivity($_SESSION['user_id']);
    }

    public function index()
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);

        $data = [
            'currentUser' => $currentUser
        ];

        $this->view('start/index', $data);
    }
}
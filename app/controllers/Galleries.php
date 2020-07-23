<?php

class Galleries extends Controller
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

        // mise à jour de la session du statut utilisateur
        $_SESSION['user_level']['name'] = $currentUser['level_name'];
        $_SESSION['user_level']['id'] = $currentUser['level_id'];
        $this->userModel->updateActivity($_SESSION['user_id']);

        $this->galleryModel = $this->model('Gallery');  
    }

    public function index()
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);

        $data = [
            'currentUser' => $currentUser,
            'galleries' => $this->galleryModel->getAll()
        ];

        if(count($data['galleries']) === 0) redirect('galleries/nodata');

        $this->view('galleries/list', $data);
    }

    public function show($id = null)
    {
        if(is_null($id) || !intval($id)) redirect('galleries');        
        if(!$this->galleryModel->findGById($id)) redirect('galleries');

        $_SESSION['construction_id'] = $id;

        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);

        $data = [
            'currentUser' => $currentUser,
            'galleries' => $this->galleryModel->getById($id),
        ];

        $this->view('galleries/show', $data);
    }

    public function nodata()
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);

        $data = [
            'currentUser' => $currentUser
        ];

        $this->view('galleries/nodata', $data);
    }
}
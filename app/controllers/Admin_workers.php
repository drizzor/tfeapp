<?php

class Admin_workers extends Controller
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

        if($currentUser['level_name'] !== 'Admin'){
            redirect('index');
            return; // empeche tout processus suivant
        } 
        if($this->userModel->isBlocked($currentUser['member_id'])) redirect('users/logout');

        // mise à jour de la session du statut utilisateur
        $_SESSION['user_level']['name'] = $currentUser['level_name'];
        $_SESSION['user_level']['id'] = $currentUser['level_id'];
        $this->userModel->updateActivity($_SESSION['user_id']);

        $this->workerModel = $this->model('Worker');
        $this->planningModel = $this->model('Planning');
    }

    public function index()
    {
       
        $data = [
            'workers' => $this->workerModel->getAll()
        ];

        $this->view('dashoard/workers/list', $data);
    }

    public function show($id = null)
    {
        if(is_null($id) || !intval($id)) redirect('admin_users');
        if(!$this->userModel->findUserById($id)) redirect('admin_users');

        $currentUser = $this->userModel->getUserById($id);

        $data = [
            'id'         => $id,
            'username'   => $currentUser['username'],
            'email'      => $currentUser['email'],
            'level'      => $currentUser['level_name'],
            'ip'         => $currentUser['ip'],
            'dateCreate' => $currentUser['member_dateCreate'],  
            'image'      => $currentUser['image']
        ];

        $this->view('dashoard/users/show', $data);
    }
    
    public function insert()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $errors = [];
            $validator = new WorkersValidator();
            $errors = $validator->validates($_POST);

            $data = [
                "firstname" => $_POST['firstname'],
                "lastname" => $_POST['lastname'],
                "salary" => $_POST['salary'],
                "email" => $_POST['email'],
                "errors" => $errors
            ];

            if(empty($errors))
            {                
                if($this->workerModel->insert($data))
                {
                    flash('success', 'L\'ouvrier a bien été enregistré');
                    redirect('admin_workers');
                } 
                else die('Une erreur de DB est survenue... :(');
            }
            else $this->view('dashoard/workers/insert', $data);
        }
        else 
        {
            $data = [
                "firstname" => "",
                "lastname" => "",
                "salary" => "",
                "email" => "",
                "errors" => ""     
            ];

            $this->view('dashoard/workers/insert', $data);
        }        
    }

    public function update($id = null)
    {
        if(is_null($id) || !intval($id)) redirect('admin_workers');
        if(!$this->workerModel->findById($id)) redirect('admin_workers');

        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $errors = [];
            $validator = new WorkersValidator();
            $errors = $validator->validates($_POST);

            $data = [
                'id'        => $id,
                'lastname'  => $_POST['lastname'],
                'firstname' => $_POST['firstname'],
                'salary'    => $_POST['salary'],  
                'email'     => $_POST['email'],     
                'errors'    => $errors
            ];

            if(empty($errors))
            {
                if($this->workerModel->update($data))
                {       
                    flash('success', 'L\'ouvrier a bien été mis à jour.');
                    redirect('admin_workers');
                } 
                else{
                    die('Une erreur DB est survenue... :(');
                } 

            }
            else $this->view('dashoard/workers/update', $data);
        }
        else
        {
            $currentWorker = $this->workerModel->getById($id);

            $data = [
                'lastname'  => $currentWorker['lastname'],
                'firstname' => $currentWorker['firstname'],
                'salary'    => $currentWorker['salary'],
                'email'     => $currentWorker['email'], 
                'errors'    => ''
            ];
    
            $this->view('dashoard/workers/update', $data);
        }        
    }

    public function out($id = null)
    {
        if(is_null($id) || !intval($id)) redirect('admin_workers');
        if(!$this->workerModel->findById($id)){
            flash("fail", "<i class='fas fa-exclamation-circle'></i> L'ouvrier sélectionné n'existe pas ou a déjà été supprimé.", "alert alert-danger alert-dismissible");
            redirect('admin_workers');
            return; 
        } 
        
        $currentWorker = $this->workerModel->getById($id);

        if(!$this->workerModel->isOut($id)[0]) $this->workerModel->updateOutStatut($id, 1);
        else $this->workerModel->updateOutStatut($id, 0);

        flash("success", "<i class='fas fa-check-circle'></i> Le statut de <b>{$currentWorker['lastname']}</b> a bien été mis à jour.");
        redirect('admin_workers');
    }

    public function delete($id = null)
    {    
        if(is_null($id) || !intval($id)) redirect('admin_workers');
        if(!$this->workerModel->findById($id)){
            flash("fail", "<i class='fas fa-exclamation-circle'></i> L'ouvrier sélectionné n'existe pas ou a déjà été supprimé.", "alert alert-danger alert-dismissible");
            redirect('admin_workers');
            return; 
        } 

        $currentWorker = $this->workerModel->getById($id);

        if($this->planningModel->refPresta($id)){
            flash("fail", "<i class='fas fa-exclamation-circle'></i> L'ouvrier <b>{$currentWorker['lastname']}</b> ne peut pas être supprimé. Des prestations existe déjà.");
            redirect('admin_workers');
            return;
        } 
    
        if($this->workerModel->delete($id)){
            flash("success", "<i class='fas fa-check-circle'></i> L'ouvrier <b>{$currentWorker['lastname']}</b> a été supprimé.");
        } 
        else  flash("fail", "<i class='fas fa-exclamation-circle'></i> La suppression de l'ouvrier a échoué. Veuillez réessayer.", "alert alert-danger alert-dismissible");
        redirect('admin_workers');
        return;                          
    }
}
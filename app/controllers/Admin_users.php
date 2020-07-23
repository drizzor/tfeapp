<?php

class Admin_users extends Controller
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
            return;
        } 
        if($this->userModel->isBlocked($currentUser['member_id'])) redirect('users/logout');

        // mise à jour de la session du statut utilisateur
        $_SESSION['user_level']['name'] = $currentUser['level_name'];
        $_SESSION['user_level']['id'] = $currentUser['level_id'];
        $this->userModel->updateActivity($_SESSION['user_id']);

    }

    public function index()
    {  
        $data = [
            'users' => $this->userModel->getUsers()
        ];

        $this->view('dashoard/users/list', $data);
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

    public function request()
    {
        $data = [
            'users' => $this->userModel->getUsers(false)
        ];

        $this->view('dashoard/users/request', $data);
    }

    public function attempts()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $errors = [];
            $validator = new UsersValidator();
            $errors = $validator->validates($_POST);

            $data = [
                'attempts' => $this->userModel->getAttempts(),
                "ip" => $_POST['ip'], 
                "errors" => $errors
            ];

            if(empty($errors))
            {                
                if($this->userModel->insertAttemptByIp($data['ip'], true))
                {
                    flash('success', 'L\'IP a bien été ajoutée');
                    redirect('admin_users/attempts');
                } 
                else die('Une erreur de DB est survenue... :(');
            }
            else $this->view('dashoard/users/attempts', $data);
        }
        else 
        {
            $data = [
                'attempts' => $this->userModel->getAttempts(),
                "ip" => "",
                "errors" => ""     
            ];

            $this->view('dashoard/users/attempts', $data);
        }                
    }

    public function insert()
    {
        $levels = $this->userModel->getLevels();
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $errors = [];
            $validator = new UsersValidator();
            $errors = $validator->validates($_POST);

            $data = [
                "level_id" => $_POST['level'],
                "username" => $_POST['username'],
                "email" => $_POST['email'],
                "levels" => $levels,
                "password" => $_POST['password'],
                "confirm_password" => $_POST['confirm_password'],
                "errors" => $errors
            ];

            if(empty($errors))
            {                
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                if($this->userModel->insert($data))
                {
                    flash('success', 'L\'utilisateur a bien été enregistré');
                    redirect('admin_users');
                } 
                else die('Une erreur de DB est survenue... :(');
            }
            else $this->view('dashoard/users/insert', $data);
        }
        else 
        {
            $data = [
                "level_id" => "",
                "username" => "",
                "email" => "",
                "levels" => $levels,
                "password" => "",
                "confirm_password" => "",
                "errors" => ""      
            ];

            $this->view('dashoard/users/insert', $data);
        }        
    }

    public function update($id = null)
    {
        if(is_null($id) || !intval($id)) redirect('admin_users');
        if(!$this->userModel->findUserById($id)) redirect('admin_users');

        $currentUser = $this->userModel->getUserById($id);
        $levels = $this->userModel->getLevels();
        $errors = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            if(isset($_FILES['image'])) $_FILES['image']['name'] = h($_FILES['image']['name']);
            $errors = $error_upload = [];
            $validator = new UsersValidator();
            $errors = $validator->validates($_POST);
            $error_upload = $validator->validates($_FILES);

            $data = [
                'id'                    => $id,
                'current'               => $currentUser,
                'username'              => $_POST['username'],
                'email'                 => $_POST['email'],
                "level_id"              => $_POST['level'],
                'levels'                => $levels,
                'password'              => $_POST['admin_set_password'],
                'confirm_password'      => $_POST['confirm_password'],
                'image'                 => $_FILES['image']['name'],
                'img_tmp'               => $_FILES['image']['tmp_name'],
                'errors'                => $errors,
                'error_upload'          => $error_upload
            ];

            if(!empty($data['image']))
            {
                $data['image'] = renameFile($data['image']);
                if(fileExist($data['image'], 'images/users')) 
                    $data['error_upload']['image'] = "Cette image existe déjà !";
                if(!empty($data['error_upload'])) $data['image'] = "";     
            }    

            if(empty($errors) && empty($error_upload))
            {
                if(!empty($data['password'])) $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);                
                if(!empty($data['image'])){
                    if(!move_uploaded_file($data['img_tmp'], '../public/images/users/' . $data['image']))
                        $data['image'] = "default.png"; // Si jms il devait avoir une erreur d'upload, aucun fichier n'est envoyé
                    else
                        removeFile($data['current']['image'], '../public/images/users/');    
                }

                if($this->userModel->updateByAdmin($data))
                { 
                    flash('success', 'Le compte a bien été mis à jour.');                                        
                    redirect('admin_users');
                } 
                else{
                    die('Une erreur DB est survenue... :(');
                } 

            }
            else $this->view('dashoard/users/update', $data);
        }
        else
        {
            $data = [
                'id'                    => $id,
                'current'               => $currentUser,
                'username'              => $currentUser['username'],
                'email'                 => $currentUser['email'],
                "level_id"              => $currentUser['REF_level'],
                'levels'                => $levels,
                'password'              => '',
                'confirm_password'      => '',
                'image'                 => $currentUser['image'],
                'errors'                => '',
                'error_upload'          => ''
            ];
            $this->view('dashoard/users/update', $data);
        }        
    }

    public function lock($id = null)
    {
        if(is_null($id) || !intval($id)) redirect('admin_users');

        if($_SESSION['user_id'] == $id)
        {
            flash("fail", "Vous ne pouvez pas vous auto bloquer.", "alert alert-danger alert-dismissible");
            redirect('admin_users');
            return;
        }

        if($this->userModel->isBlocked($id))
        {
            flash("fail", "Cet utilisateur est déjà bloqué.", "alert alert-danger alert-dismissible");
            redirect('admin_users');
            return;
        }

        if($this->userModel->lock($id))
        {
            $currentUser = $this->userModel->getUserById($id);
            sendMail($currentUser['username'], $currentUser['email'], 'register_lock');
            flash("success", "L'utilisateur a correctement été bloqué.");
            redirect('admin_users');
            return;
        }
    else
        {
            flash("fail", "Une erreur est survenue, veuillez réessayer.", "alert alert-danger alert-dismissible");
            redirect('admin_users');
            return;
        }
    }

    public function unlock($id = null)
    {
        if(is_null($id) || !intval($id)) redirect('admin_users');

        if($_SESSION['user_id'] == $id)
        {
            flash("fail", "Vous ne pouvez pas vous auto débloquer.", "alert alert-danger alert-dismissible");
            redirect('admin_users');
            return;
        }

        if(!$this->userModel->isBlocked($id))
        {
            flash("fail", "Cet utilisateur est déjà débloqué.", "alert alert-danger alert-dismissible");
            redirect('admin_users');
            return;
        }

        if($this->userModel->unlock($id))
        {
            $currentUser = $this->userModel->getUserById($id);
            sendMail($currentUser['username'], $currentUser['email'], 'register_unlock');
            flash("success", "L'utilisateur a correctement été débloqué.");
            redirect('admin_users');
            return;
        }
        else
        {
            flash("fail", "Une erreur est survenue, veuillez réessayer.", "alert alert-danger alert-dismissible");
            redirect('admin_users');
            return;
        }
    }

    public function delete($id = null)
    {    
        if(is_null($id) || !intval($id)) redirect('admin_users');
        if(!$this->userModel->findUserById($id)){
            flash("fail", "L'utilisateur sélectionné n'existe pas ou a déjà été supprimé.", "alert alert-danger alert-dismissible");
            redirect('admin_users');
            return; 
        } 

        $currentUser = $this->userModel->getUserById($id);

        if($currentUser['activate'] == 1)
        {
            if($id == $_SESSION['user_id']){
                flash("fail", "Vous ne pouvez pas vous supprimer.", "alert alert-danger alert-dismissible");
                redirect('admin_users');
                exit;
            }
    
            if($this->userModel->delete($id)){
                $filename = $currentUser['image'];
                removeFile($filename, '../public/images/users/');
                flash("success", "L'utilisateur <b>{$currentUser['username']}</b> a été supprimé.");
            } 
            else
            {
                flash("fail", "La suppression de l'utilisateur a échoué. Veuillez réessayer.", "alert alert-danger alert-dismissible");
            }
            redirect('admin_users');
            return;
        }
        else
        {
            if($this->userModel->delete($id)){
                if($this->userModel->findActivity($id)) $this->userModel->deleteActivity($id);
                sendMail($currentUser['username'], $currentUser['email'], 'register_decline');
                flash("success", "L'utilisateur a été refusé et supprimé."); 
            } 
            else flash("fail", "La suppression de l'utilisateur a échoué.", "alert alert-danger alert-dismissible");
            redirect('admin_users/request');
            return;
        }
        
    }

    public function delete_attempt($id = null) 
    {
        if(is_null($id) || !intval($id)) redirect('admin_users/attempts');
        if(!$this->userModel->findAttempt($id, false)){
            flash("fail", "L'IP sélectionnée n'existe pas ou a déjà été supprimée.", "alert alert-danger alert-dismissible");
            redirect('admin_users/attempts');
            return; 
        } 

        $currentUser = $this->userModel->getUserById($id);

        if($this->userModel->deleteAttempt($id, false)){
            flash("success", "L'IP a été débloquée."); 
        } 
        else flash("fail", "La suppression de l'IP a échoué.", "alert alert-danger alert-dismissible");
        redirect('admin_users/attempts');
    }

    public function validate($id = null)
    {
        if(is_null($id) || !intval($id)) redirect('admin_users/request');
        if(!$this->userModel->findUserById($id, false)){
            flash("fail", "L'utilisateur sélectionné n'existe pas ou a déjà été validé.", "alert alert-danger alert-dismissible");
            redirect('admin_users/request');
            return; 
        } 

        $currentUser = $this->userModel->getUserById($id);

        if($this->userModel->Validate($id)){
            sendMail($currentUser['username'], $currentUser['email'], 'register_validate');
            flash("success", "L'utilisateur a correctement été ajouté à la liste d'actif."); 
            redirect('admin_users/request');
        }  
        else flash("fail", "La validation de l'utilisateur a échoué.", "alert alert-danger alert-dismissible");
    }
}
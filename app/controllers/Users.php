<?php
class Users extends Controller
{
    // Attribut sécurisant l'accès de la méthode createUserSession
    private $_isConnected = false;

    /**
     * le constructeur permettant l'initiation du modele user 
     */
    public function __construct()
    {
        $this->userModel = $this->model('User');                       
    }

    /**
     * L'index n'étant pas exploité, je détourne vers ma page erreur 404
     */
    public function index()
    {
        e404();
    }

    /**
     * Permet d'afficher la vue de la page d'inscription
     */
    public function register()
    { 
        if($this->userModel->countAll()[0] == 0) redirect('users/new');
        if(isLoggedIn()) redirect('index');  

        $attempt = $this->userModel->findAttempt($_SERVER['REMOTE_ADDR']);
        if($attempt)
        {  
            if($attempt['attempts'] > 3){
                redirect('users/fail');
                return;
            }      
        }

        $errors = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $errors = [];
            $validator = new UsersValidator();
            $errors = $validator->validates($_POST);

            $data = [
                "username" => $_POST['username'],
                "email" => $_POST['email'],
                "password" => $_POST['password'],
                "confirm_password" => $_POST['confirm_password'],
                "errors" => $errors
            ];

            if(empty($errors))
            {                
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                if($this->userModel->register($data))
                {
                    sendMail($data['username'], $data['email'], 'register_confirm'); 
                    flash('register_success', 'Vous êtes enregistré ! Votre compte sera activé dès qu\'un administrateur aura validé votre demande. Vérifiez votre boite email ainsi que les indésirables.');
                    redirect('users/login');
                } 
                else die('Une erreur de DB est survenue... :(');
            }
            else $this->view('users/register', $data);
        }
        else // Si l'utilisateur n'a pas encore envoyé de données on initialise tout nos champs à rien
        {
            $data = [
                "username" => "",
                "email" => "",
                "password" => "",
                "confirm_password" => "",
                "errors" => ""      
            ];

            $this->view('users/register', $data);
        }
    }

    /**
     * Permet d'afficher la vue de la page de connexion
     */
    public function login()
    {  
        if($this->userModel->countAll()[0] == 0) redirect('users/new');
        if(isLoggedIn()) redirect('index');

        $attempt = $this->userModel->findAttempt($_SERVER['REMOTE_ADDR']);
        if($attempt)
        {  
            flash('fail_attempts', "Attention ! Après 4 tentatives de connexion non réussie, vous serez automatiquement bloqué !", 'alert alert-danger alert-dismissible');         
            if($attempt['attempts'] > 3){
                redirect('users/fail');
                return;
            }      
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'username'      => trim($_POST['username']),
                'password'      => trim($_POST['password']),
                'username_err'  => '',
                'password_err'  => '',
                'attempts'      => $this->userModel->findAttempt($_SERVER['REMOTE_ADDR'])
            ];

            if(empty($data['username'])) $data['username_err'] = "Veuillez entrer un login !";
            if(empty($data['password'])) $data['password_err'] = "Veuillez entrer un MDP !";    
            
            if(empty($data['username_err']) && empty($data['password_err']))
            {
                $loggedInUser = $this->userModel->login($data['username'], $data['password']);                

                if($loggedInUser)
                {
                    if($loggedInUser['activate'] == 0)
                    {
                        flash("fail", "Ce compte n'a pas encore été validé par un administrateur.", "alert alert-danger alert-dismissible");
                        $this->view('users/login', $data);
                        return;
                    }                
                    
                    if($this->userModel->isBlocked($loggedInUser['id']))
                    {
                        flash("fail", "Ce compte a été bloqué.", "alert alert-danger alert-dismissible");
                        $this->view('users/login', $data);
                        return;
                    }
                    
                    if($this->userModel->findAttempt($_SERVER['REMOTE_ADDR'])) $this->userModel->deleteAttempt($_SERVER['REMOTE_ADDR']);  
                    $this->_isConnected = true;  
                    $this->userModel->recovMod($loggedInUser['email'], 0);    
                    if($this->userModel->findActivity($loggedInUser['id'])) $this->userModel->updateActivity($loggedInUser['id']);
                    else $this->userModel->insertActivity($loggedInUser['id']);                
                    $this->createUserSession($loggedInUser);
                } 
                else
                {
                    if($this->userModel->findAttempt($_SERVER['REMOTE_ADDR']))
                    {
                        $this->userModel->updateAttemptByIp($_SERVER['REMOTE_ADDR']);
                        $data['attempts'] = $this->userModel->findAttempt($_SERVER['REMOTE_ADDR']);
                        $data['password_err'] = "Login et/ou mot de passe incorrect ! (Tentative {$data['attempts']['attempts']}/4)";
                    }
                    else
                    {
                        $this->userModel->insertAttemptByIp($_SERVER['REMOTE_ADDR']);
                        $data['attempts']['attempts'] = 1;
                        $data['password_err'] = "Login et/ou mot de passe incorrect ! (Tentative 1/4)";
                    }
                    $this->view('users/login', $data);
                }
            }
            else
            {
                $this->view('users/login', $data);
            }
        }
        else
        {
            $data = [
                'username'      => '',
                'password'      => '',
                'username_err'  => '',
                'password_err'  => '',
                'attempts' => $this->userModel->findAttempt($_SERVER['REMOTE_ADDR'])
            ];

            $this->view('users/login', $data);
        }  
    }

    public function new()
    {
        if($this->userModel->countAll()[0] > 0) redirect('users/register');

        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $errors = [];
            $validator = new UsersValidator();
            $errors = $validator->validates($_POST);

            $data = [
                "username" => $_POST['username'],
                "email" => $_POST['email'],
                "password" => $_POST['password'],
                "confirm_password" => $_POST['confirm_password'],
                "errors" => $errors
            ];

            if(empty($errors))
            {                
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                if($this->userModel->registerAdmin($data))
                {
                    $this->userModel->insertActivity(1);
                    flash('register_success', 'Vous êtes enregistré ! Vous pouvez désormais vous connecter en tant qu\'administrateur');
                    redirect('users/login');
                } 
                else die('Une erreur de DB est survenue... :(');
            }
            else $this->view('users/new', $data);
        }
        else // Si l'utilisateur n'a pas encore envoyé de données on initialise tout nos champs à rien
        {
            $data = [
                "username" => "",
                "email" => "",
                "password" => "",
                "confirm_password" => "",
                "errors" => ""      
            ];

            $this->view('users/new', $data);
        }
    }

    /**
     * Mode récupération MDP
     */
    public function recovery()
    {
        if(!$this->userModel->countAll()) redirect('users/new');
        
        $attempt = $this->userModel->findAttempt($_SERVER['REMOTE_ADDR']);
        if($attempt['attempts'] > 3){
            redirect('users/fail');  
            return;  
        } 
                 


        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'email'      => $_POST['email'],
                'email_err'  => ''
            ];

            if(empty($data['email'])) $data['email_err'] = "Veuillez indiquer une adresse email !";
            if(!$this->userModel->recovFindEmail($data['email'])) $data['email_err'] = "L'email mentionné n'existe pas !";

            if(empty($data['email_err']))
            {   
                $password = randPass();
                $password_hash = password_hash($password, PASSWORD_DEFAULT); 

                // changement du mot de passe
                if($this->userModel->recovMod($data['email'], 1) && $this->userModel->recovPass($data['email'], $password_hash)){
                    sendMail(null, $data['email'], 'account_recover', $password); 
                    flash('register_success', 'Un email vous a été envoyé avec votre nouveau mot de passe.');
                    redirect('users/login');
                }
                else die('Une erreur de BDD est survenue...');                
            }
            else $this->view('users/recovery', $data);
        }
        else
        {
            $data = [
                'email'      => '',
                'email_err'  => ''
            ];

            $this->view('users/recovery', $data);
        }         
    }

    /**
     * Page de gestion du profil utilisateur
     */
    public function profil($id = null)
    {
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

        if(is_null($id) || !intval($id)) redirect('index');
        if(!$this->userModel->findUserById($id)) redirect('index');

        $currentUser = $this->userModel->getUserById($_SESSION['user_id']); 

        if($id != $_SESSION['user_id'])  redirect('index');
        if($this->userModel->isBlocked($currentUser['member_id'])) redirect('users/logout');

        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            if(isset($_FILES['image'])) $_FILES['image']['name'] = h($_FILES['image']['name']);
            $errors = [];
            $error_upload = [];
            $validator = new UsersValidator();
            $errors = $validator->validates($_POST);
            $error_upload = $validator->validates($_FILES);

            $data = [
                'current'               => $currentUser,
                'username'              => $_POST['username'],
                'email'                 => $_POST['email'],
                'current_password'      => $_POST['current_password'],
                'level_name'            => $currentUser['level_name'],
                'password'              => $_POST['password'],
                'confirm_password'      => $_POST['confirm_password'],
                'image'                 => $_FILES['image']['name'],
                'img_tmp'               => $_FILES['image']['tmp_name'],
                'errors'                => $errors,
                'error_upload'          => $error_upload
            ];

            if( $data['username'] == $data['current']['username'] &&
                $data['email'] == $data['current']['email'] &&
                empty($data['image']) &&
                empty($data['password']) && empty($data['confirm_password']) && empty($data['current_password'])){
                    flash('update_success', 'Aucune modification effectuée !', 'alert alert-info alert-dismissible');
                    redirect('start');
                }

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

                if($this->userModel->update($data))
                {       
                    flash('update_success', 'Votre compte a bien été mis à jour.');
                    redirect('start');
                } 
                else{
                    die('Une erreur DB est survenue... :(');
                } 

            }
            else $this->view('users/profil', $data);
        }
        else
        {
            $data = [
                'current'               => $currentUser,
                'username'              => $currentUser['username'],
                'email'                 => $currentUser['email'],
                'level_name'            => $currentUser['level_name'],
                'current_password'      => '',
                'password'              => '',
                'confirm_password'      => '',
                'image'                 => $currentUser['image'],
                'errors'                => '',
                'error_upload'          => ''
            ];
    
            $this->view('users/profil', $data);  
        }                     
    }

    /**
     * Page injectée en cas d'un nombre de tentatives trop important
     */
    public function fail()
    {
        if(isLoggedIn()) redirect('index');

        if($this->userModel->findAttempt($_SERVER['REMOTE_ADDR']))
        {
            $attempt = $this->userModel->findAttempt($_SERVER['REMOTE_ADDR']);
            if($attempt['attempts'] <= 3){
                redirect('users/register');  
                return; 
            }       
        }        
        else{
            redirect('users/login');
            return;
        } 
        $this->view('users/fail');        
    }

    /**
     * Création de la session utilisateur
     * @param array $user
     * @return void
     */
    public function createUserSession($user): void   
    {
        if(!$this->_isConnected){
            e404();
            exit;
        } 
        $this->userModel->refreshIp($user['id'], $_SERVER['REMOTE_ADDR']);        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_level'] = $this->userModel->getUserLevel($user['REF_level']);
        $_SESSION['user_username'] = $user['username'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_image'] = $user['image'];
        $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];        
        // ($_SESSION['user_level'] == 1) ? redirect('admin') : redirect('index');
        redirect('admin');
    }

    /**
     * Déconnexion de l'utilisateur
     * @return void
     */
    public function logout(): void
    {
        $this->userModel->deleteActivity($_SESSION['user_id']);

        unset($_SESSION['user_id']);
        unset($_SESSION['user_level']); 
        unset($_SESSION['user_username']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_image']);
        unset($_SESSION['user_ip']);
        session_destroy();
        redirect('users/login');
    }
}
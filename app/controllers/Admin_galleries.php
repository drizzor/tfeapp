<?php

class Admin_galleries extends Controller
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

        // mise à jour de la session du statut utilisateur
        $_SESSION['user_level']['name'] = $currentUser['level_name'];
        $_SESSION['user_level']['id'] = $currentUser['level_id'];
        $this->userModel->updateActivity($_SESSION['user_id']);

        if($currentUser['level_name'] !== 'Admin'){
            redirect('index');
            return;
        } 
        if($this->userModel->isBlocked($currentUser['member_id'])) redirect('users/logout');

        $this->galleryModel = $this->model('Gallery');
        $this->constructionModel = $this->model('Construction');
    }

    public function index()
    {       
        $galleries = $this->galleryModel->getAll();

        if(count($galleries) === 0){
            flash("no_gallery", "<i class='fas fa-exclamation-circle'></i> Avant de consulter les galeries veuillez au préalable enregistrer au moins une image.", "alert alert-danger alert-dismissible");
            redirect('admin_galleries/insert');
        } 
        
        $data = [
            'galleries' => $galleries,            
        ];
        
        $this->view('dashoard/galleries/list', $data);
    }

    public function insert()
    {
        $constructions = $this->constructionModel->getAll();   

        if(count($constructions) === 0){
            flash("no_construction", "<i class='fas fa-exclamation-circle'></i> Afin de pouvoir uploader une image dans une galerie, veuillez créer votre premier chantier.", "alert alert-danger alert-dismissible");
            redirect('admin_constructions/insert');
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            unset($_SESSION['construction_id']);
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            if(isset($_FILES['image'])) $_FILES['image']['name'] = h($_FILES['image']['name']);
            $errors = $error_upload = [];
            $validator = new GalleriesValidator();
            $errors = $validator->validates($_POST);
            $error_upload = $validator->validates($_FILES);

            $data = [
                'construction_id' => $_POST['construction'],
                'constructions'   => $constructions,
                'image'           => $_FILES['image']['name'], 
                'img_tmp'         => $_FILES['image']['tmp_name'],
                'type'            => $_FILES['image']['type'],
                'size'            => $_FILES['image']['size'],
                'title'           => $_POST['title'],  
                'description'     => $_POST['description'],
                'isCheck'         => (isset($_POST['inProgress']) && !empty($_POST['inProgress'])? 'checked' : ''),  
                'checkMode'       => (isset($_POST['inProgress']) && !empty($_POST['inProgress']))? 1 : 0,
                'errors'          => $errors,
                'error_upload'    => $error_upload
            ];

            if(!empty($data['image']))
            {
                $data['image'] = renameFile($data['image']);
                if(fileExist($data['image'], 'images/constructions')) 
                    $data['error_upload']['image'] = "Cette image existe déjà !";
                if(!empty($data['error_upload'])) $data['image'] = "";     
            }  

            if(empty($errors) && empty($error_upload))
            {
                if(!move_uploaded_file($data['img_tmp'], '../public/images/gallery/' . $data['image']))
                    die('Il y a eu un problème lors de l\'envoir de l\'image...');

                if($this->galleryModel->insert($data))
                {
                    $_SESSION['construction_id'] = $data['construction_id'];
                    unset($data);  
                    flash('success', '<i class="far fa-check-circle"></i> L\'image a été enregistrée dans la galerie, vous pouvez continuer l\'upload.');
                    redirect("admin_galleries/insert"); 
                }
                else{
                    removeFile($data['image'], '../public/images/gallery/'); 
                    die('Une erreur DB est survenue... :(');
                } 
            }
            else $this->view('dashoard/galleries/insert', $data);
        }
        else
        {
            $data = [
                'construction_id' => (isset($_SESSION['construction_id']) && !empty($_SESSION['construction_id']))? $_SESSION['construction_id'] : '',
                'constructions'   => $constructions,
                'isCheck'         => 'checked',  
                'image'           => '', 
                'img_tmp'         => '',
                'title'           => '',  
                'description'     => ''
            ]; 
        
            $this->view('dashoard/galleries/insert', $data);
        }        
    }

    public function show($id = null)
    {
        if(is_null($id) || !intval($id)) redirect('admin_galleries');        
        if(!$this->galleryModel->findGById($id)) redirect('admin_galleries');

        $_SESSION['construction_id'] = $id;        

        $data = [
            'galleries' => $this->galleryModel->getById($id),
        ];
        
        $this->view('dashoard/galleries/show', $data);
    }  

    public function update($id_i = null, $id_c = null)
    {
        if(is_null($id_i) || !intval($id_i)) redirect('admin_galleries');
        
        if(!$this->galleryModel->findIById($id_i)) redirect('admin_galleries/show/'.$id_c);

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $errors = [];
        $validator = new GalleriesValidator();
        $errors = $validator->validates($_POST);        

        $data = [
            'id_i'          => $id_i,
            'title'         => $_POST['title'],
            'description'   => $_POST['description'],
            'checkMode'     => (isset($_POST['inProgress']) && !empty($_POST['inProgress']))? 1 : 0,            
        ];

        if(empty($errors))
        {
            if($this->galleryModel->update($data)){
                flash("success", "<i class='fas fa-check-circle'></i> Données correctement mise  à jour."); 
            }
            else{
                die('Une erreur DB est survenue... :(');
            }
        }
        else
        {
            (!empty($errors['title'] && isset($errors['title'])))? flash("title_fail", "<i class='fas fa-exclamation-circle'></i> {$errors['title']}", "alert alert-danger alert-dismissible") : '';
            (!empty($errors['description'] && isset($errors['description'])))? flash("description_fail", "<i class='fas fa-exclamation-circle'></i> {$errors['description']}", "alert alert-danger alert-dismissible") : '';
        }
        
        redirect('admin_galleries/show/'.$id_c);        
    }    

    /**
     * Suppressionn d'une galeries complète
     */
    public function deleteG($id = null)
    {
        if(is_null($id) || !intval($id)) redirect('admin_galleries');

        if(!$this->galleryModel->findGById($id)){
            flash("fail", "<i class='fas fa-exclamation-circle'></i> L'élément sélectionné n'existe pas ou a déjà été supprimé.", "alert alert-danger alert-dismissible");
            redirect('admin_galleries');
            return; 
        } 
        
        $currentGallery = $this->galleryModel->getById($id);
            
        if($this->galleryModel->deleteG($id)){
            for ($i=0; $i < count($currentGallery); $i++) { 
                $filename = $currentGallery[$i]['image'];
                removeFile($filename, '../public/images/gallery/');
            }            
            flash("success", "<i class='fas fa-check-circle'></i> La galerie a été entièrement supprimée.");            
        }
        else
        {
            flash("fail", "<i class='fas fa-exclamation-circle'></i> La suppression a échoué.", "alert alert-danger alert-dismissible");
        }

        redirect('admin_galleries');
    }

    /**
     * Suppressionn d'une image de la galerie
     */
    public function deleteI($id = null, $id_c = null)
    {
        if(is_null($id) || !intval($id)) redirect('admin_galleries');

        if(!$this->galleryModel->findIById($id)){
            flash("fail", "<i class='fas fa-exclamation-circle'></i> L'image sélectionnée n'existe pas ou a déjà été supprimée.", "alert alert-danger alert-dismissible");
            redirect('admin_galleries/show/'.$id_c);
            return; 
        } 
        
        $currentImage = $this->galleryModel->getByIdI($id);
            
        if($this->galleryModel->deleteI($id)){
            $filename = $currentImage[0]['image'];
            removeFile($filename, '../public/images/gallery/');
            flash("success", "<i class='fas fa-check-circle'></i> L'image {$filename} a été supprimée.");            
        }
        else
        {
            flash("fail", "<i class='fas fa-exclamation-circle'></i> La suppression a échoué.", "alert alert-danger alert-dismissible");
        }

        redirect('admin_galleries/show/'.$id_c);
    }
}
<?php

class Admin_constructions extends Controller
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
        
        $this->constructionModel = $this->model('Construction');
        $this->cityModel = $this->model('City');
        $this->purchaseModel = $this->model('Purchase');
        $this->planningModel = $this->model('Planning');
        $this->galleryModel = $this->model('Gallery');
    }

    public function index()
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if($currentUser['level_name'] === 'Visiteur') redirect('index');
        $data = [
            'constructions' => $this->constructionModel->getConstructions(),
            'level_name' => $currentUser['level_name'],
        ];

        $this->view('dashoard/constructions/list', $data);
    }

    public function sold_list()
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if($currentUser['level_name'] === 'Visiteur') redirect('index');
        $data = [
            'constructions' => $this->constructionModel->getSolds(),
            'level_name' => $currentUser['level_name'],            
        ];

        $this->view('dashoard/constructions/sold-list', $data);
    }

    public function show($id = null)
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if($currentUser['level_name'] === 'Visiteur') redirect('index');
        if(is_null($id) || !intval($id)) redirect('admin_constructions');
        if(!$this->constructionModel->findConstructionById($id)) redirect('admin_constructions');

        $currentConstruction = $this->constructionModel->currentConstruction($id);

        $data = [
            'id'                => $id,
            'name'              => $currentConstruction['construction_name'],
            'city'              => $currentConstruction['city_name'] . ' (' . $currentConstruction['zipcode'] . ')',
            'address'           => $currentConstruction['address'],
            'buyingPrice'       => $currentConstruction['buyingPrice'],
            'surface'           => $currentConstruction['surface'],
            'taxes'             => $currentConstruction['taxes'],  
            'estimatePrice'     => $currentConstruction['estimatePrice'],
            'buyingDate'        => $currentConstruction['buyingDate'],
            'createDate'        => $currentConstruction['createDate'],
            'image'             => $currentConstruction['imageCover'], 
            'comment'           => $currentConstruction['comment'],
            'purchases'         => $this->purchaseModel->getByConstructionId($id),
            'soldPrice'         => $this->constructionModel->getSoldByid($id),
            'sumRowPlanning'    => $this->planningModel->sumPresta($id),
            'sumPlanning'       => $this->planningModel->sumAllPresta($id),
            'countPlanning'     => $this->planningModel->countPresta($id),
            'sumPurchases'      => $this->purchaseModel->sumByConstruct($id),
            'plannings'         => $this->planningModel->getById($id),
            'purchasesPerMonth' => $this->purchaseModel->SumByConstructAndMonth($id)
        ];
        
        $this->view('dashoard/constructions/show', $data);        
    }

    public function insert()
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if($currentUser['level_name'] !== 'Admin') redirect('index');
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            if(isset($_FILES['image'])) $_FILES['image']['name'] = h($_FILES['image']['name']);
            $errors = [];
            $error_upload = [];
            $validator = new ConstructionsValidator();
            $errors = $validator->validates($_POST);
            $error_upload = $validator->validates($_FILES);

            $data = [
                'REF_city'      => '',
                'name'          => h($_POST['name']),
                'city'          => h($_POST['city']),
                'address'       => h($_POST['address']),
                'buyingPrice'   => h($_POST['buyingPrice']),
                'surface'       => h($_POST['surface']),
                'taxes'         => h($_POST['taxes']),  
                'estimatePrice' => h($_POST['estimatePrice']),
                'buyingDate'    => h($_POST['buyingDate']),
                'image'         => $_FILES['image']['name'], 
                'img_tmp'       => $_FILES['image']['tmp_name'],
                'comment'       => h($_POST['comment']),
                'errors'        => $errors,
                'error_upload'  => $error_upload
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
                $temp = explode('(', $data['city']);
                $city = $this->cityModel->getCityByName($temp[0]);
                $data['REF_city'] = $city['id'];

                if(!empty($data['image'])){
                    if(!move_uploaded_file($data['img_tmp'], '../public/images/constructions/' . $data['image']))
                        $data['image'] = "default.png"; // Si jms il devait avoir une erreur d'upload, je garde l'image par défaut  
                }

                if($this->constructionModel->insert($data))
                {
                    flash('insert_success', 'Le chantier a été enregistré.');
                    redirect('admin_constructions');
                }
                else{
                    removeFile($data['image'], '../public/images/constructions/'); 
                    die('Une erreur DB est survenue... :(');
                } 
            }
            else $this->view('dashoard/constructions/insert', $data);
        }
        else
        {
            $data = [
                'REF_city'      => '',
                'name'          => '',
                'city'          => '',
                'address'       => '',
                'buyingPrice'   => '',
                'surface'       => '',
                'taxes'         => '',
                'estimatePrice' => '',
                'buyingDate'    => '',
                'image'         => '',
                'comment'       => '',
                'errors'        => '',
                'error_upload'  => ''
            ];

            $this->view('dashoard/constructions/insert', $data);
        }
    }

    public function update($id = null)
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if($currentUser['level_name'] !== 'Admin') redirect('index');
        if(is_null($id) || !intval($id)) redirect('admin_constructions');
        if(!$this->constructionModel->findConstructionById($id)) redirect('admin_constructions');

        $currentConstruction = $this->constructionModel->currentConstruction($id);

        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            if(isset($_FILES['image'])) $_FILES['image']['name'] = h($_FILES['image']['name']);
            $errors = [];
            $error_upload = [];
            $validator = new ConstructionsValidator();
            $errors = $validator->validates($_POST);
            $error_upload = $validator->validates($_FILES);

            $data = [
                'id'            => $id,
                'current_img'    => $currentConstruction['imageCover'],
                'REF_city'      => '',
                'name'          => h($_POST['name']),
                'city'          => h($_POST['city']),
                'address'       => h($_POST['address']),
                'buyingPrice'   => h($_POST['buyingPrice']),
                'surface'       => h($_POST['surface']),
                'taxes'         => h($_POST['taxes']),  
                'estimatePrice' => h($_POST['estimatePrice']),
                'buyingDate'    => h($_POST['buyingDate']),
                'image'         => $_FILES['image']['name'], 
                'img_tmp'       => $_FILES['image']['tmp_name'],
                'comment'       => h($_POST['comment']),
                'errors'        => $errors,
                'error_upload'  => $error_upload
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
                $temp = explode('(', $data['city']);
                $city = $this->cityModel->getCityByName($temp[0]);
                $data['REF_city'] = $city['id'];

                if(!empty($data['image'])){
                    if(!move_uploaded_file($data['img_tmp'], '../public/images/constructions/' . $data['image']))
                        $data['image'] = "default.png"; // Si jms il devait avoir une erreur d'upload, je garde l'image par défaut  
                    else
                    removeFile($data['current_img'], '../public/images/constructions/');
                }

                if($this->constructionModel->update($data))
                {
                    flash('update_success', 'Le chantier a été modifié.');
                    redirect('admin_constructions');
                }
                else{
                    die('Une erreur DB est survenue... :(');
                } 
            }
            else $this->view('dashoard/constructions/update', $data);
        }
        else
        {            
            $data = [
                'id'            => $id,
                'name'          => $currentConstruction['construction_name'],
                'city'          => $currentConstruction['city_name'] . ' (' . $currentConstruction['zipcode'] . ')',
                'address'       => $currentConstruction['address'],
                'buyingPrice'   => $currentConstruction['buyingPrice'],
                'surface'       => $currentConstruction['surface'],
                'taxes'         => $currentConstruction['taxes'],  
                'estimatePrice' => $currentConstruction['estimatePrice'],
                'buyingDate'    => $currentConstruction['buyingDate'],
                'image'         => $currentConstruction['imageCover'], 
                'comment'       => $currentConstruction['comment']
            ];

            $this->view('dashoard/constructions/update', $data);
        }
    }

    public function delete($id = null)
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if($currentUser['level_name'] !== 'Admin'){
            redirect('index');
            return;
        } 
        if(is_null($id) || !intval($id)) redirect('admin_constructions');
        if(!$this->constructionModel->findConstructionById($id)){
            flash("delete_fail", "L'élément sélectionné n'existe pas ou a déjà été supprimé.", "alert alert-danger alert-dismissible");
            redirect('admin_constructions');
            return; // Afin de ne pas récupérer les autres messages
        } 
        
        $currentConstruction = $this->constructionModel->currentConstruction($id);

        if($this->constructionModel->isSold($id)){
            // Suppresion des références dans la table "vendu"
            $this->constructionModel->cancelSold($id);

            $refGalleries = $this->constructionModel->refGalleries($id);
            $refPurchases = $this->constructionModel->refPurchases($id);

            if($refGalleries > 0){
                $currentGallery = $this->galleryModel->getById($id);
                
                for ($i = 0; $i < $refGalleries; $i++) { 
                    $filename = $currentGallery[$i]['image'];
                    removeFile($filename, '../public/images/gallery/');
                } 

                $this->galleryModel->deleteG($id);              
            }

            if($refPurchases > 0){
                $currentPurchases = $this->purchaseModel->getByConstructionId($id);
                
                for ($i = 0; $i < $refPurchases; $i++) { 
                    $filename = $currentPurchases[$i]['invoicePDF'];
                    removeFile($filename, '../public/purchases/');
                } 

                $this->purchaseModel->deleteAllFromConstruct($id);              
            }

            $this->planningModel->deleteAllFromConstruct($id);
            
            if($this->constructionModel->delete($id)){
                $filename = $currentConstruction['imageCover'];
                removeFile($filename, '../public/images/constructions/');
                flash("delete_success", "L'élément <b>{$currentConstruction['construction_name']}</b> a été entièrement supprimé.");            
            }
            else
            {
                flash("delete_fail", "La suppression de <b>{$currentConstruction['construction_name']}</b> a échoué.", "alert alert-danger alert-dismissible");
            }
            redirect('admin_constructions/sold_list');
            return;
        }

        if($this->constructionModel->refPurchases($id) > 0){
            flash("delete_fail", "Le chantier <b>{$currentConstruction['construction_name']}</b> n'est pas supprimable. Des achats sont liés.", "alert alert-danger alert-dismissible");
        }
        else if($this->constructionModel->refGalleries($id) > 0){
            flash("delete_fail", "L'élément <b>{$currentConstruction['construction_name']}</b> n'est pas supprimable. Des images sont liées dans la galerie.", "alert alert-danger alert-dismissible");
        }
        else if($this->constructionModel->delete($id)){
            $filename = $currentConstruction['imageCover'];
            removeFile($filename, '../public/images/constructions/');
            $this->planningModel->deleteAllFromConstruct($id);
            flash("delete_success", "L'élément <b>{$currentConstruction['construction_name']}</b> a été supprimé.");            
        }
        else
        {
            flash("delete_fail", "La suppression de <b>{$currentConstruction['construction_name']}</b> a échoué.", "alert alert-danger alert-dismissible");
        }
        redirect('admin_constructions');
    }

    /**
     * Effectue l'annulation d'un bâtiment déclaré vendu
     */
    public function cancelSold($id = null)
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if($currentUser['level_name'] !== 'Admin'){
            redirect('index');
            return;
        } 
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if($currentUser['level_name'] !== 'Admin') redirect('index');
        if(is_null($id) || !intval($id)) redirect('admin_constructions/sold_list');
        if(!$this->constructionModel->findConstructionById($id)){
            flash("sold_fail", "L'élément sélectionné n'existe pas ou a déjà été supprimé.", "alert alert-danger alert-dismissible");
            redirect('admin_constructions/sold_list');
            return; 
        }

        $currentConstruction = $this->constructionModel->currentConstruction($id);

        if($this->constructionModel->cancelSold($id)){
            flash("delete_success", "L'élément <b>{$currentConstruction['construction_name']}</b> a été correctement annulé.");
            redirect('admin_constructions');
            return;
        }
        else
        {
            flash("delete_fail", "L'annulation de <b>{$currentConstruction['construction_name']}</b> a échoué.", "alert alert-danger alert-dismissible");
            redirect('admin_constructions/sold_list');
            return;
        }        
    }
    
    /**
     * Effectue la déclaration d'un bâtiment vendu
     */
    public function sold($id = null)
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if($currentUser['level_name'] !== 'Admin'){
            redirect('index');
            return;
        } 
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if($currentUser['level_name'] !== 'Admin') redirect('index');
        if(is_null($id) || !intval($id)) redirect('admin_constructions');
        if(!$this->constructionModel->findConstructionById($id)){
            flash("sold_fail", "L'élément sélectionné n'existe pas ou a déjà été supprimé.", "alert alert-danger alert-dismissible");
            redirect('admin_constructions');
            return; // Afin de ne pas récupérer les autres messages
        }
        if(!isset($_POST['price'])){
            flash("sold_fail", "Veuilez indique un prix de vente", "alert alert-danger alert-dismissible");
            redirect('admin_constructions');
            return;
        }
        
        $_POST['price'] = h($_POST['price']);
        $_POST['date'] = h($_POST['date']);
        $errors = [];
        $validator = new ConstructionsValidator();
        $errors = $validator->validates($_POST);

        if(empty($errors))
        {
            $currentConstruction = $this->constructionModel->currentConstruction($id);

            if($this->constructionModel->sold($id, $_POST['price'], $_POST['date'])){
                flash("sold_success", "L'élément <b>{$currentConstruction['construction_name']}</b> a été été déclaré vendu.");
                redirect('admin_constructions/sold_list');
                return;
            }
            else
            {
                flash("sold_fail", "La mise en vente de <b>{$currentConstruction['construction_name']}</b> a échoué.", "alert alert-danger alert-dismissible");
            }
            redirect('admin_constructions');
            return;
        }
        else
        {
            flash("sold_fail", "Le chantier n'a pas été déclaré vendu. Veuillez compléter correctement les champs.", "alert alert-danger alert-dismissible");
            redirect('admin_constructions');
            return;
        }
        
    }   
}
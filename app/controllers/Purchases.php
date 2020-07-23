<?php

class Purchases extends Controller
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

        $this->purchaseModel = $this->model('Purchase');
        $this->constructionModel = $this->model('Construction');
        $this->supplierModel = $this->model('Supplier');
        $this->categoryModel = $this->model('Category');        
    }

    public function index()
    {
        e404();
    }

    public function insert()
    {
        if (!$this->categoryModel->findCategory() || 
            !$this->supplierModel->findSupplier() || 
            !$this->constructionModel->findConstruction()){
                redirect('purchases/nodata');
            }
        
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        
        if($currentUser['level'] < 2){
            redirect('index'); 
            return;
        } 
        
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $_FILES['invoiceFile']['name'] = h($_FILES['invoiceFile']['name']);
            $errors = $error_upload = [];
            $validator = new purchasesValidator();
            $errors = $validator->validates($_POST);
            $error_upload = $validator->validates($_FILES);

            $lastId = $this->purchaseModel->checkLastId();

            $data = [
                'user_session' => $currentUser,
                'construction_id' => $_POST['construction'],
                'id_purchase' => ($this->purchaseModel->findOne()) ? $lastId['id_purchase'] + 1 : 1,
                'constructions' => $this->constructionModel->getConstructions(),
                'supplier_id' => $_POST['supplier'],
                'suppliers' => $this->supplierModel->getSuppliers(),
                'category_id' => $_POST['category'],
                'categories' => $this->categoryModel->getCategories(),
                'invoiceNo' => $_POST['invoiceNo'],
                'invoiceDate' => $_POST['invoiceDate'],
                'invoiceFile' => $_FILES['invoiceFile']['name'], 
                'invoiceFile_tmp' => $_FILES['invoiceFile']['tmp_name'],
                'product' => $_POST['product'],
                'qty' => $_POST['qty'],
                'price' => $_POST['price'],
                'tax' => $_POST['tax'],
                'errors' => $errors,
                'error_upload' => $error_upload,
                'rowCount' => (isset($_POST['product'])) ? count($_POST['product']) : 1
            ];

            if(!empty($data['invoiceFile'])){
                $data['invoiceFile'] = renameFile($data['invoiceFile']);
                if(fileExist($data['invoiceFile'], 'purchases')) 
                    $data['error_upload'] = "Doublon du fichier PDF, recommencez !";
                if(!empty($data['error_upload'])) $data['invoiceFile'] = "";   
            }

            if(empty($errors) && empty($error_upload))
            {
                if(!move_uploaded_file($data['invoiceFile_tmp'], '../public/purchases/' . $data['invoiceFile'])){
                    $data['invoiceFile'] = "error.pdf";
                }

                for($i = 0; $i < $data['rowCount']; $i++)
                {
                    if(!$this->purchaseModel->insert($data, $data['category_id'][$i], $data['product'][$i], $data['qty'][$i], $data['price'][$i], $data['tax'][$i]))
                    {                        
                        removeFile($data['invoiceFile'], '../public/purchases/', false); 
                        die('Une erreur DB est survenue... :(');
                    }                
                } 
                unset($data);  
                flash('success', 'L\'achat a été enregistré, vous pouvez continuer l\'encodage.');
                redirect("purchases/insert");           
            }
            else $this->view('purchases/insert', $data);
        }
        else
        {
            $data = [
                'user_session' => $currentUser,
                'construction_id' => "",
                'constructions' => $this->constructionModel->getConstructions(),
                'supplier_id' => "",
                'suppliers' => $this->supplierModel->getSuppliers(),
                'category_id' => "",
                'categories' => $this->categoryModel->getCategories(),
                'invoiceNo' => "",
                'invoiceDate' => date("Y-m-d"),
                'invoiceFile' => "", 
                'invoiceFile_tmp' => "",
                'errors' => "",
                'error_upload' => "",
                'rowCount' => 1
            ];
    
            $this->view('purchases/insert', $data);
        }        
    }

    public function list()
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);               

        $data = [
            'currentUser' => $currentUser,
            'purchases' => $this->purchaseModel->getAll(),
            'level_name' => $currentUser['level_name']
        ];

        $this->view('purchases/list', $data);
    }

    public function show($id = null)
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']); 
        // if($currentUser['level_name'] === 'Visiteur') redirect('index');
        if(is_null($id) || !intval($id)) redirect('purchases/list');
        if(!$this->purchaseModel->findById($id)) redirect('purchases/list');

        $currentPurchase = $this->purchaseModel->getById($id);
        $currentConstruction = $this->constructionModel->currentConstruction($currentPurchase[0]['REF_constructions']);
        $currentSupplier = $this->supplierModel->currentSupplier($currentPurchase[0]['REF_suppliers']);

        // Chargement des différentes catégories
        $currentCategory = [];
        for($i = 0; $i < count($currentPurchase); $i++){
            $currentCategory[$i] = $this->categoryModel->getById($currentPurchase[$i]['REF_categories']);
        }

        $data = [
            'user_session' => $currentUser,
            'invoicePDF' => $currentPurchase[0]['invoicePDF'],
            'construction_name' => $currentConstruction['construction_name'],
            'supplier_name' => $currentSupplier['supplier_name'],
            'category_name' => $currentCategory,
            'invoiceNo' => $currentPurchase[0]['invoiceNumber'],
            'dateInvoice' => $currentPurchase[0]['dateInvoice'],
            'rowCount' => count($currentPurchase),
            'currentPurchase' => $currentPurchase
        ];

        $this->view('purchases/show', $data);
    }

    public function nodata()
    {
        if ($this->categoryModel->findCategory() && 
            $this->supplierModel->findSupplier() && 
            $this->constructionModel->findConstruction()){
            redirect('purchases/insert');
        }
        
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);               

        $data = [
            'currentUser' => $currentUser,
            'missingDatas' => [
                (!$this->categoryModel->findCategory())? 'Catégories' : '',
                (!$this->supplierModel->findSupplier())? 'Fournisseurs' : '',
                (!$this->constructionModel->findConstruction())? 'Chantiers' : ''
            ]
        ];

        $this->view('purchases/nodata', $data);
    }

    public function delete($id = null)
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if($currentUser['level_name'] == 'Visiteur'){
            flash("fail", "Vous ne pouvez pas supprimer cet élément.", "alert alert-danger alert-dismissible");
            redirect('purchases/list');
            return;
        }         

        if(is_null($id) || !intval($id)) redirect('purchases/list');        
        
        if(!$this->purchaseModel->findById($id)){
            flash("fail", "L'élément sélectionné n'existe pas ou a déjà été supprimé.", "alert alert-danger alert-dismissible");
            redirect('invoices');
            return; // Afin de ne pas récupérer les autres messages
        } 
        
        $purchaseInfo = $this->purchaseModel->getById($id);
        // dd($currentUser['level_id']); die();

        if($currentUser['level_name'] == 'Membre'){
            if($purchaseInfo[0]['REF_members'] != $currentUser['member_id']){
                flash("fail", "Vous pouvez supprimer uniquement les achats que vous avez encodé.", "alert alert-danger alert-dismissible");
                redirect('purchases/list');
                return;
            }            
        }

        if($this->purchaseModel->delete($id)){
            
            $filename = $purchaseInfo[0]['invoicePDF'];
            removeFile($filename, '../public/purchases/', false);
            flash("success", "L'élément <b>{$purchaseInfo[0]['invoiceNumber']}</b> a été supprimé.");
        }
        else
        {
            flash("fail", "La suppression de <b>{$purchaseInfo[0]['invoiceNumber']}</b> a échoué.", "alert alert-danger alert-dismissible");
        }
        redirect('purchases/list');
    }
}
<?php

class Invoices extends Controller
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

        $this->invoiceModel = $this->model('Invoice');
        $this->customerModel = $this->model('Customer');
        $this->cityModel = $this->model('City');
    }

    public function index()
    {
        $invoices = $this->invoiceModel->getAll();

        // Condition obligatoire pour éviter msg erreur en cas de liste vierge
        $year = date('Y');
        if(count($invoices) > 0){
            $temp = explode("/", $invoices[0]['invoice_createdAt']); 
            $year = $temp[2];  
        } 

        $data = [
            'invoices' => $invoices,
            'year' => $year,
            'lastInv' => (count($invoices) > 0)? $invoices[count($invoices) - 1]['id_invoice'] : 1,
            'currentUser' => $this->userModel->getUserById($_SESSION['user_id']),
        ];

        $this->view('invoices/list', $data);
    }

    public function show($id = null)
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if(is_null($id) || !intval($id)) redirect('invoices');
        if(!$this->invoiceModel->findById($id)) redirect('invoices');

        $currentInvoice = $this->invoiceModel->getInvoiceInfo($id);

        $year = explode("/", $currentInvoice[0]['invoice_date']);

        $data = [
            'currentUser' => $currentUser,
            "customer" => $currentInvoice[0]['customer_name'],
            "customer_tva_number" => $currentInvoice[0]['customer_tva'],
            "customer_zipcode" => $currentInvoice[0]['customer_zipcode'],
            "customer_city" => $currentInvoice[0]['customer_city'],
            "customer_address" => $currentInvoice[0]['customer_address'],
            "customer_country" => $currentInvoice[0]['customer_country'],
            "invoice_date" => $currentInvoice[0]['invoice_date'],
            "invoice_number" => $currentInvoice[0]['invoice_number'],     
            "inv_year" => $year[0],       
            "comment" => $currentInvoice[0]['invoice_comment'],
            "inv" => $currentInvoice,
            "rowCount"=> count($currentInvoice)
        ];

        $this->view('invoices/show', $data);
    }

    public function insert()
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if($currentUser['level'] < 2){
            redirect('index');
            return;
        } 

        if(!$this->customerModel->countAll()){
            flash("no_customers", "<i class='fas fa-exclamation-circle'></i> Avant d'encoder votre première facture, veillez créer un premier client !", "alert alert-danger alert-dismissible");
            redirect('customers/insert');
        }

        $taxes = $this->invoiceModel->getTaxes();

        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            // $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $errors = [];
            $validator = new InvoicesValidator();
            $errors = $validator->validates($_POST);

            $lastId = $this->invoiceModel->getLastInvoiceId();

            $data = [
                'currentUser' => $currentUser,
                "customer_id" => "",
                "id" => ($this->invoiceModel->findSomething()) ? $lastId['id'] : 0,
                "invoice_id" => ($this->invoiceModel->findSomething()) ? $lastId['id_invoice'] + 1 : 1,
                "customer" => $_POST['customer'],
                "description" => $_POST['description'],
                "tax_id" => "",
                "tax" => $_POST['tax'],
                "invoice_number" => "",
                "qty" => $_POST['qty'],
                "price" => $_POST["price"],
                "comment" => $_POST['comment'],
                "errors" => $errors,
                'rowCount' => (isset($_POST['description'])) ? count($_POST['description']) : 1
            ];

            if(empty($errors))
            {  
                /* Récup client par ID   */  
                $temp = explode(']', $data["customer"]);
                $temp = str_replace("[", "", $temp);
                $customer = $this->customerModel->getById($temp[0]);
                $data['customer_id'] = $customer['customer_id'];        
                
                // Récup ID TVA
                $taxesId = [];
                for($i = 0; $i < $data['rowCount']; $i++){
                    $taxesId[$i] = $this->invoiceModel->getTaxeIdByAmount($data['tax'][$i]);
                }
                $data['tax_id'] = $taxesId;

                // Création du numéro de facture
                $todayDate = date('Y/m/d');
                $todayDate = explode("/", $todayDate);
                

                if($this->invoiceModel->findSomething()){
                    $lastInvoiceNo = $this->invoiceModel->getLastInvoiceNo();
                    $lastInvoiceDate = $this->invoiceModel->getLastInvoiceDate();
                    $lastInvoiceDate = explode("/", $lastInvoiceDate['invoice_date']);

                    if(($todayDate[0] - $lastInvoiceDate[0]) > 0) $data['invoice_number'] = 1;                      
                    else $data['invoice_number'] = $lastInvoiceNo['invoice_number'] + 1;
                }
                else $data['invoice_number'] = 1;

                // Insert dans invoices
                for($i = 0; $i <  $data['rowCount']; $i++)
                {
                    if( !$this->invoiceModel->insert($data, $data['price'][$i], $data['qty'][$i], $data['description'][$i]))
                        die('Une erreur de DB est survenue... :(');
                }

                // Insert dans invoices_taxes
                $currentInvoice = $this->invoiceModel->getById($data['invoice_id']);

                for($i = 0; $i <  $data['rowCount']; $i++)
                {
                    if( !$this->invoiceModel->insertKey($currentInvoice[$i]['id'], $data['tax_id'][$i]['tax_id']))
                        die('Une erreur de DB est survenue... :(');
                }

                flash('success', 'Enregistré avec succès !');
                redirect('invoices');
            }
            else $this->view('invoices/insert', $data);
        }
        else 
        {
            $data = [
                'currentUser' => $currentUser,
                "customer_id" => '',
                "customer" => "",
                "description" => "",
                "invoice_number" => "",
                "price" => "",
                "comment" => "",
                "errors" => "",
                'rowCount' => 1
            ];

            $this->view('invoices/insert', $data);
        }        
    }

    public function print($id = null)
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if(is_null($id) || !intval($id)) redirect('invoices');
        if(!$this->invoiceModel->findById($id)) redirect('invoices');
        
        $currentInvoice = $this->invoiceModel->getInvoiceInfo($id);
        $year = explode("/", $currentInvoice[0]['invoice_date']);

        $data = [
            'currentUser' => $currentUser,
            "customer" => $currentInvoice[0]['customer_name'],
            "customer_tva_number" => $currentInvoice[0]['customer_tva'],
            "customer_zipcode" => $currentInvoice[0]['customer_zipcode'],
            "customer_city" => $currentInvoice[0]['customer_city'],
            "customer_address" => $currentInvoice[0]['customer_address'],
            "customer_country" => $currentInvoice[0]['customer_country'],
            "invoice_date" => explode('/', $currentInvoice[0]['invoice_date']),
            "invoice_number" => $currentInvoice[0]['invoice_number'],     
            "inv_year" => $year[0],       
            "comment" => $currentInvoice[0]['invoice_comment'],
            "inv" => $currentInvoice,
            "tax_amount_0" => 0,
            "tax_amount_6" => 0,
            "tax_amount_12" => 0,
            "tax_amount_21" => 0,
            "rowCount"=> count($currentInvoice)
        ];
        
        // Total TVAC en fonction du %
        for($i = 0; $i < $data['rowCount']; $i++){
            if($data['inv'][$i]['tax'] == 0)
                $data['tax_amount_0'] += ($data['inv'][$i]['notax_amount'] * $data['inv'][$i]['quantity']) * (1 + ($data['inv'][$i]['tax'] / 100)); 
                
            if($data['inv'][$i]['tax'] == 6)
                $data['tax_amount_6'] += ($data['inv'][$i]['notax_amount'] * $data['inv'][$i]['quantity']) * (1 + ($data['inv'][$i]['tax'] / 100));        
    
            if($data['inv'][$i]['tax'] == 12)
                $data['tax_amount_12'] += ($data['inv'][$i]['notax_amount'] * $data['inv'][$i]['quantity']) * (1 + ($data['inv'][$i]['tax'] / 100));
        
            if($data['inv'][$i]['tax'] == 21)
                $data['tax_amount_21'] += ($data['inv'][$i]['notax_amount'] * $data['inv'][$i]['quantity']) * (1 + ($data['inv'][$i]['tax'] / 100));
        }

        $this->view('invoices/print', $data);
    }

    public function delete($id = null)
    {   
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if($currentUser['level'] < 2){
            redirect('invoices');
            return;
        } 

        if(is_null($id) || !intval($id)) redirect('invoices');

        $checkId = $this->invoiceModel->getAll();
        if($checkId[count($checkId) - 1]['id_invoice'] > $id){
            flash("fail", "Vous devez d'abord supprimer les factures en amont.", "alert alert-danger alert-dismissible");
            redirect('invoices');
            return; 
        }

        $data = $this->invoiceModel->getById($id);
        
        if($currentUser['level'] == 2){            
            if(timeLimitReached(strtotime($data[0]['invoice_date']), 30, 'minute')){
                flash("fail", "Cette facture a été créée il y a plus de 30 minutes et ne peut pas être supprimée.", "alert alert-danger alert-dismissible");
                redirect('invoices');
                return; // Afin de ne pas récupérer les autres messages
            }
        }
        
        if(!$this->invoiceModel->findById($id)){
            flash("fail", "L'élément sélectionné n'existe pas ou a déjà été supprimé.", "alert alert-danger alert-dismissible");
            redirect('invoices');
            return; // Afin de ne pas récupérer les autres messages
        } 
        
        $invoiceInfo = $this->invoiceModel->getInvoiceInfo($id);
        $idList = [];
        $year = explode('/', $invoiceInfo[0]['invoice_date']);       
        
        for($i = 0; $i < count($invoiceInfo); $i++){
            $idList[$i] = $invoiceInfo[$i]['invoice_id'];
        } 

        for($i = 0; $i < count($invoiceInfo); $i++){
           $this->invoiceModel->deleteKey($idList[$i]);
        } 

        if($this->invoiceModel->delete($id)){
            
            $filename = $year[0] .'_MYCOMPANY_invoice_'.$data[0]['invoice_number'].'.pdf';
            removeFile($filename, '../public/inv/', false);
            flash("success", "La facture a été supprimée.");
        }
        else
        {
            flash("fail", "La suppression a échoué.", "alert alert-danger alert-dismissible");
        }
        redirect('invoices');
    }    
}
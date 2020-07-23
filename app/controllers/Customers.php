<?php

class Customers extends Controller
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

        if($currentUser['level'] < 2){
            redirect('index');
            return;
        } 
        if($this->userModel->isBlocked($currentUser['member_id'])) redirect('users/logout');

        // mise à jour de la session du statut utilisateur
        $_SESSION['user_level']['name'] = $currentUser['level_name'];
        $_SESSION['user_level']['id'] = $currentUser['level_id'];
        $this->userModel->updateActivity($_SESSION['user_id']);

        $this->customerModel = $this->model('Customer');
        $this->invoiceModel = $this->model('Invoice');
    }

    public function index()
    {
        $data = [
            'customers' => $this->customerModel->getAll(),
            'currentUser' => $this->userModel->getUserById($_SESSION['user_id'])
        ];

        $this->view('customers/list', $data);
    }

    public function show($id = null)
    {
        if(is_null($id) || !intval($id)) redirect('customers');
        if(!$this->customerModel->findById($id)) redirect('customers');

        $currentCustomer = $this->customerModel->getById($id);
        $invoices = $this->invoiceModel->getCustomerInvoices($id);

        $data = [
            'id' => $id,
            'currentUser' => $this->userModel->getUserById($_SESSION['user_id']),
            "name" => $currentCustomer['customer_name'],
            "country" => $currentCustomer['country'],
            "city" => $currentCustomer['city'],
            "address" => $currentCustomer['customer_address'],
            "zipcode" => $currentCustomer['zipcode'],
            "tva_number" => $currentCustomer['tva_number'],
            "member" => (!is_null($currentCustomer['username']))? $currentCustomer['username'] : 'Utilisateur supprimé',
            "email" => $currentCustomer['customer_email'],
            "invoices" => $invoices
        ];
        $this->view('customers/show', $data);
    }

    public function insert()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $errors = [];
            $validator = new CustomersValidator();
            $errors = $validator->validates($_POST);

            $data = [
                'currentUser' => $this->userModel->getUserById($_SESSION['user_id']),
                "name" => h($_POST['name']),
                "country" => h($_POST['country']),
                "city" => h($_POST['city']),
                "address" => h($_POST['address']),
                "zipcode" => h($_POST['zipcode']),
                "tva_number" => h($_POST['tva_number']),
                "email" => h($_POST['email']),
                "errors" => $errors
            ];

            if(empty($errors))
            {             
                if($this->customerModel->insert($data))
                {
                    flash('success', 'Enregistré avec succès !');
                    redirect('customers');
                } 
                else die('Une erreur de DB est survenue... :(');
            }
            else $this->view('customers/insert', $data);
        }
        else 
        {
            $data = [
                'currentUser' => $this->userModel->getUserById($_SESSION['user_id']),
                "name" => '',
                "country" => 'Belgique',
                "city" => '',
                "address" => '',
                "zipcode" => '',
                "tva_number" => '',
                "email" => "",
                "errors" => ''
            ];

            $this->view('customers/insert', $data);
        }        
    }

    public function update($id = null)
    {
        if(is_null($id) || !intval($id)) redirect('customers');
        if(!$this->customerModel->findById($id)) redirect('customers');

        $currentCustomer = $this->customerModel->getById($id);
        
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $errors = [];
            $validator = new CustomersValidator();
            $errors = $validator->validates($_POST);

            $data = [
                "id" => $id,
                'currentUser' => $this->userModel->getUserById($_SESSION['user_id']),
                "name" => h($_POST['name']),
                "country" => h($_POST['country']),
                "city" => h($_POST['city']),
                "address" => h($_POST['address']),
                "zipcode" => h($_POST['zipcode']),
                "tva_number" => h($_POST['tva_number']),
                "email" => h($_POST['email']),
                "errors" => $errors
            ];

            if(empty($errors))
            {  
                if($this->customerModel->update($data))
                {
                    flash("success", "Donnée correctement modifiée.");
                    redirect('customers');
                }
                else die('Une erreur de DB est survenue... :(');
            }
            else $this->view('customers/update', $data);
        }
        else
        {
            $currentCustomer= $this->customerModel->getById($id);

            $data = [
                'id' => $id,
                'currentUser' => $this->userModel->getUserById($_SESSION['user_id']),
                "name" => $currentCustomer['customer_name'],
                "country" => $currentCustomer['country'],
                "city" => $currentCustomer['city'],
                "address" => $currentCustomer['customer_address'],
                "zipcode" => $currentCustomer['zipcode'],
                "tva_number" => $currentCustomer['tva_number'],
                "email" => $currentCustomer['customer_email'],
                "errors" => ""
            ];

            $this->view('customers/update', $data);
        }
    }

    public function delete($id = null)
    {    
        if(is_null($id) || !intval($id)) redirect('customers');
        
        if(!$this->customerModel->findById($id)){
            flash("fail", "L'élément sélectionné n'existe pas ou a déjà été supprimé.", "alert alert-danger alert-dismissible");
            redirect('customers');
            return; // Afin de ne pas récupérer les autres messages
        } 
        
        $currentCustomer = $this->customerModel->getById($id);

        if($this->customerModel->refInvoice($id) > 0){
            flash("fail", "L'élément <b>{$currentCustomer['customer_name']}</b> n'est pas supprimable.", "alert alert-danger alert-dismissible");
        }
        else if($this->customerModel->delete($id)){
            flash("success", "L'élément <b>{$currentCustomer['customer_name']}</b> a été supprimé.");
        }
        else
        {
            flash("fail", "La suppression de <b>{$currentCustomer['customer_name']}</b> a échoué.", "alert alert-danger alert-dismissible");
        }
        redirect('customers');
    }

    /**
     * Ajax va récupérer les données depuis cette méthode
     */
    public function autocomplete()
    {      
        if(isset($_POST['search']))
        {
            $response = "<ul class='dropdownField'>";
            $response .= "<li>Aucun client trouvé!</li>"; 
            $response .= "<li><a href='" . URLROOT . "/customers/insert'>Ajouter nouveau</a></li>";
            $response .= "</ul>";
        
            $q = h($_POST["q"]);
        
            // J'execute la requete
            $result = $this->customerModel->getLike($q);

            // dd(count($result));
            // die();

            if(count($result) > 0)
            {
                $response = "<ul class='dropdownField' style='padding-left:5px'>";

                foreach($result as $data)
                    // $data['name'] = h($data['name']); $data['zipcode'] = h($data['zipcode']);
                    $response .= "<li class='customer'>[{$data['id']}] {$data['name']} | {$data['country']} <br> {$data['address']} <br> {$data['city']} <br> {$data['tva_number']}<hr></li>";

                $response .= "<li><a href='" . URLROOT . "/customers/insert'>Ajouter nouveau</a></li>";
                $response .= "</ul>";
            }
            exit($response);
        }
    }
}
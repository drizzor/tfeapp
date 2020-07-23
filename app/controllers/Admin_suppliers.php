<?php

class Admin_suppliers extends Controller
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

        $this->supplierModel = $this->model('Supplier');
        $this->cityModel = $this->model('City');
        $this->purchaseModel = $this->model('Purchase');
    }

    public function index()
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if($currentUser['level_name'] === 'Visiteur') redirect('index');
        $data = [
            'suppliers'  => $this->supplierModel->getSuppliers(),
            'level_name' => $currentUser['level_name']
        ];

        $this->view('dashoard/suppliers/list', $data);
    }

    public function insert()
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if($currentUser['level_name'] !== 'Admin') redirect('index');
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $errors = [];
            $validator = new SuppliersValidator();
            $errors = $validator->validates($_POST);

            $data = [
                'REF_city'    => '',
                'name'        => $_POST['name'],
                'city'        => $_POST['city'],
                'address'     => $_POST['address'],
                'contactName' => $_POST['contactName'],
                'phone'       => $_POST['phone'],
                'email'       => $_POST['email'],
                'errors'      => $errors
            ];

            if(empty($errors))
            {
                $temp = explode('(', $data['city']);
                $city = $this->cityModel->getCityByName($temp[0]);
                $data['REF_city'] = $city['id'];
                if($this->supplierModel->insert($data))
                {
                    flash('insert_success', 'Le fournisseur a été enregistré.');
                    redirect('admin_suppliers');
                }
                else die('Une erreur DB est survenue... :(');
            }
            else $this->view('dashoard/suppliers/insert', $data);
        }
        else
        {
            $data = [
                'name'          => '',
                'city'          => '',
                'address'       => '',
                'contactName'   => '',
                'phone'         => '',
                'email'         => ''
            ];

            $this->view('dashoard/suppliers/insert', $data);
        }        
    }

    public function update($id = null)
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if($currentUser['level_name'] !== 'Admin') redirect('index');
        if(is_null($id) || !intval($id)) redirect('admin_suppliers');
        if(!$this->supplierModel->findSupplierById($id)) redirect('admin_suppliers');

        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $errors = [];
            $validator = new SuppliersValidator();
            $errors = $validator->validates($_POST);

            $data = [
                'id'            => $id,
                'REF_city'      => '',
                'name'          => $_POST['name'],
                'city'          => $_POST['city'],
                'address'       => $_POST['address'],
                'contactName'   => $_POST['contactName'],
                'phone'         => $_POST['phone'],
                'email'         => $_POST['email'],
                'errors'        => $errors
            ];

            if(empty($errors))
            {
                $temp = explode('(', $data['city']);
                $city = $this->cityModel->getCityByName($temp[0]);
                $data['REF_city'] = $city['id'];
                
                if($this->supplierModel->update($data))
                {
                    flash("update_success", "Le fournisseur <b>{$data['name']}</b> a bien été modifiée.");
                    redirect('admin_suppliers');
                }
                else die('Une erreur de DB est survenue... :(');
            }
            else $this->view('dashoard/suppliers/update', $data);
        }
        else
        {
            $currentSupplier = $this->supplierModel->currentSupplier($id);
            $data = [
                'id'            => $id,
                'name'          => $currentSupplier['supplier_name'],
                'city'          => $currentSupplier['city_name'] . ' (' . $currentSupplier['zipcode'] . ')',
                'address'       => $currentSupplier['address'],
                'contactName'   => $currentSupplier['contactName'],
                'phone'         => $currentSupplier['phone'],
                'email'         => $currentSupplier['email']
            ];
            $this->view('dashoard/suppliers/update', $data);
        }
        
    }

    public function show($id = null)
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if($currentUser['level_name'] === 'Visiteur') redirect('index');
        if(is_null($id) || !intval($id)) redirect('admin_suppliers');
        if(!$this->supplierModel->findSupplierById($id)) redirect('admin_suppliers');

        $currentSupplier = $this->supplierModel->currentSupplier($id);
            $data = [
                'id'            => $id,
                'name'          => $currentSupplier['supplier_name'],
                'city'          => $currentSupplier['city_name'] . ' (' . $currentSupplier['zipcode'] . ')',
                'address'       => $currentSupplier['address'],
                'contactName'   => $currentSupplier['contactName'],
                'phone'         => $currentSupplier['phone'],
                'email'         => $currentSupplier['email'],
                'purchases'     => $this->purchaseModel->getBySupplierId($id)
            ];

            $this->view('dashoard/suppliers/show', $data);
    }

    public function delete($id = null)
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if($currentUser['level_name'] !== 'Admin'){
            redirect('index');
            return;
        } 
        if(is_null($id) || !intval($id)) redirect('admin_suppliers');
        if(!$this->supplierModel->findSupplierById($id)){
            flash("delete_fail", "L'élément sélectionné n'existe pas ou a déjà été supprimé.", "alert alert-danger alert-dismissible");
            redirect('admin_suppliers');
            return; // Afin de ne pas récupérer les autres messages
        } 
        
        $currentSupplier = $this->supplierModel->currentSupplier($id);

        if($this->supplierModel->references($id) > 0){
            flash("delete_fail", "L'élément <b>{$currentSupplier['supplier_name']}</b> n'est pas supprimable.", "alert alert-danger alert-dismissible");
        }
        else if($this->supplierModel->delete($id)){
            flash("delete_success", "L'élément <b>{$currentSupplier['supplier_name']}</b> a été supprimé.");
        }
        else
        {
            flash("delete_fail", "La suppression de <b>{$currentSupplier['supplier_name']}</b> a échoué.", "alert alert-danger alert-dismissible");
        }
        redirect('admin_suppliers');
    }
}
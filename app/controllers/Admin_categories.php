<?php

class Admin_categories extends Controller
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

        $this->categoryModel = $this->model('Category');
    }

    public function index()
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if($currentUser['level_name'] === 'Visiteur') redirect('index');
        $categoriesList = $this->categoryModel->getCategories();

        $data = [
            'categories' => $categoriesList,
            'level_name' => $currentUser['level_name']
        ];

        $this->view('dashoard/categories/list', $data);
    }

    public function insert()
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if($currentUser['level_name'] !== 'Admin') redirect('index');
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $errors = [];
            $validator = new CategoriesValidator();
            $errors = $validator->validates($_POST);

            $data = [
                'name'        => $_POST['name'],
                'description' => $_POST['description'],
                'errors'      => $errors  
            ];

            if(empty($errors))
            {
                if($this->categoryModel->insert($data))
                {
                    flash('insert_success', 'La catégorie a été ajoutée.');
                    redirect('admin_categories');
                }
                else die('Une erreur de DB est survenue... :(');
            }
            else $this->view('dashoard/categories/insert', $data);
        }
        else
        {
            $data = [
                'name'        => '',
                'description' => '',
                'errors'      => '' 
            ];

            $this->view('dashoard/categories/insert', $data);
        }        
    }

    public function update($id = null)
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if($currentUser['level_name'] !== 'Admin') redirect('index');
        if(is_null($id) || !intval($id)) redirect('admin_categories');
        if(!$this->categoryModel->findCategoryById($id)) redirect('admin_categories');

        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $errors = [];
            $validator = new CategoriesValidator();
            $errors = $validator->validates($_POST);

            $data = [
                'id'          => $id,  
                'name'        => $_POST['name'],
                'description' => $_POST['description'],
                'errors'      => $errors  
            ];

            if(empty($errors))
            {
                if($this->categoryModel->update($data))
                {
                    flash("update_success", "La catégorie <b>{$data['name']}</b> a bien été modifiée.");
                    redirect('admin_categories');
                }
                else die('Une erreur de DB est survenue... :(');
            }
            else $this->view('dashoard/categories/update', $data);
        }
        else
        {
            $currentCategory = $this->categoryModel->currentCategory($id);
            $data = [
                'id'          => $id,  
                'name'        => $currentCategory['name'],
                'description' => $currentCategory['description'],
                'errors'      => ''
            ];

            $this->view('dashoard/categories/update', $data);
        }        
    }

    public function show($id)
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if($currentUser['level_name'] === 'Visiteur') redirect('index');
        if(is_null($id) || !intval($id)) redirect('admin_categories');
        if(!$this->categoryModel->findCategoryById($id)) redirect('admin_categories');

        $currentCategory = $this->categoryModel->currentCategory($id);
        
        $data = [
            'id'          => $id,  
            'name'        => $currentCategory['name'],
            'description' => $currentCategory['description'],
            'errors'      => ''
        ];

        $this->view('dashoard/categories/show', $data);
    }

    public function delete($id = null)
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if($currentUser['level_name'] !== 'Admin'){
            redirect('index');
            return;
        } 
        if(is_null($id) || !intval($id)) redirect('admin_categories');
        if(!$this->categoryModel->findCategoryById($id)){
            flash("delete_fail", "L'élément sélectionné n'existe pas ou a déjà été supprimé.", "alert alert-danger alert-dismissible");
            redirect('admin_categories');
            return; // Afin de ne pas récupérer les autres messages
        } 
        
        $currentCategory = $this->categoryModel->currentCategory($id);

        if($this->categoryModel->references($id) > 0){
            flash("delete_fail", "L'élément <b>{$currentCategory['name']}</b> n'est pas supprimable.", "alert alert-danger alert-dismissible");
        }

        if($this->categoryModel->delete($id)){
            flash("delete_success", "L'élément <b>{$currentCategory['name']}</b> a été supprimé.");
        }
        else
        {
            flash("delete_fail", "La suppression de <b>{$currentCategory['name']}</b> a échoué.", "alert alert-danger alert-dismissible");
        }
        redirect('admin_categories');
    }
}
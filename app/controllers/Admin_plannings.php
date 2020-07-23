<?php

class Admin_plannings extends Controller
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

        $this->planningModel = $this->model('Planning');
        $this->constructionModel = $this->model('Construction');
        $this->workerModel = $this->model('Worker');
    }

    public function index()
    {
        $constructions = $this->constructionModel->getConstructions();
        if(count($constructions) == 0){
            flash("no_construction", "Avant de pouvoir encoder des presations, enregistrez votre premier chantier.", "alert alert-danger alert-dismissible");
            redirect('admin_constructions/insert');
            return;
        }
        
        if($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $id = h($_POST['construction']);

            if(is_null($id) || !intval($id)) redirect('admin_plannings');
            if(!$this->constructionModel->findNoSoldById($id)) redirect('admin_plannings');

            redirect('admin_plannings/show/' . $id);
        }
        else
        {
            $data = [
                'constructions_id' => $constructions[0]['constructions_id'],
                'constructions' => $constructions,
            ];

            $this->view('dashoard/plannings/search', $data);
        }        
    }

    public function show($id = null)
    {

        if(is_null($id) || !intval($id)) redirect('admin_plannings');
        if(!$this->constructionModel->findNoSoldById($id)) redirect('admin_plannings');
        if(!$this->workerModel->findSomething()){
            flash("fail", "Avant de pouvoir encoder des presations, enregistrez votre premier ouvrier.", "alert alert-danger alert-dismissible");
            redirect('admin_workers/insert');
            return;
        }

        $data = [
            'construction' => $this->constructionModel->getById($id),
            'plannings' =>  $this->planningModel->getById($id),
            'sum' => $this->planningModel->sumPresta($id),
            'sumAll' => $this->planningModel->sumAllPresta($id),
            'count' => $this->planningModel->countPresta($id)
        ];

        $this->view('dashoard/plannings/show', $data);
    }  

    public function update($id_c = null, $id_w = null)
    {
        if(is_null($id_c) || !intval($id_c)) redirect('admin_plannings');
        if(is_null($id_w) || !intval($id_w)) redirect('admin_plannings');
        
        if(!$this->constructionModel->findNoSoldById($id_c)) redirect('admin_plannings');
        if(!$this->workerModel->findById($id_w) && !$this->workerModel->findById($id_w, 1)) 
            redirect('admin_plannings/show/'.$id_c);

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $_POST['salary'] = h($_POST['salary']);
        $_POST['hours'] = h($_POST['hours']);
        if($_POST['hours'] == 0 || empty($_POST['hours']) || !isset($_POST['hours'])) $_POST['hours'] = 0.00;
        $errors = false;

        if($_POST['hours'] < 0 || $_POST['hours'] > 1000) $errors = true;
        if(!preg_match("/^\d+(\.\d{1,2})?$/", $_POST['hours'])) $errors = true;

        if($_POST['salary'] < 1 || $_POST['salary'] > 500) $errors = true;
        if(!preg_match("/^\d+(\.\d{1,2})?$/", $_POST['salary'])) $errors = true;

        // Vérif des heures et correction en fonction de la décimal
        $time = explode('.', $_POST['hours']);
        $hour = $time[0]; $minute = $time[1];
        (mb_strlen($minute) === 1)? $minute *= 10 : '';
        if($minute >= 60) {
            $hour += 1;
            $minute -= 60;
            $_POST['hours'] = $hour .'.'. $minute;
        }

        if($this->workerModel->isOut($id_w)[0]){
            flash("fail", "Impossible de modifier un ouvrier déclaré sorti.", "alert alert-danger alert-dismissible");
            redirect('admin_plannings/show/'.$id_c);
            return;
        }

        if($errors === false){

            $data = [
                'id_p' => -1,
                'id_c' => $id_c,
                'id_w' => $id_w,
                'salary' => $_POST['salary'],
                'hour' => $_POST['hours']
            ];            

            if($this->planningModel->findById($data)){
                $data['id_p'] = $this->planningModel->getPlanningId($data);
                if($data['hour'] == 0){
                    $this->planningModel->delete($data['id_p'][0]);
                    flash('success', 'Données correctement mises à jour.');
                    redirect('admin_plannings/show/'.$id_c);
                    return;
                }
                if(!$this->planningModel->update($data)) die('Une erreur DB est survenue... :(');
            }
            else{
                if(!$this->planningModel->insert($data)) die('Une erreur DB est survenue... :(');
            }

            flash('success', "<i class='fas fa-check-circle'></i> Données correctement mises à jour.");
            redirect('admin_plannings/show/'.$id_c);
        }
        else{
            flash("fail", "La modification n'a pas été effectuée. Veuillez remplir correctement les champs.", "alert alert-danger alert-dismissible");
            redirect('admin_plannings/show/'.$id_c);
            return;
        }
    }    
}
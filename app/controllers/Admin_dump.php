<?php

use Ifsnop\Mysqldump as IMysqldump;

/**
 * Cette classe n'est utile que pour l'autocompletion
 */
class Admin_dump extends Controller
{
    /**
     * INIT du modele 
     */
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
            return; // empeche tout processus suivant
        } 
        if($this->userModel->isBlocked($currentUser['member_id'])) redirect('users/logout');

        // mise à jour de la session du statut utilisateur
        $_SESSION['user_level']['name'] = $currentUser['level_name'];
        $_SESSION['user_level']['id'] = $currentUser['level_id'];
        $this->userModel->updateActivity($_SESSION['user_id']);        

        $this->dumpModel = $this->model('Dump');
    }


    public function index()
    {
        $data = [
            'saves' => $this->dumpModel->getAll()
        ];

        $this->view('dashoard/dbsave/index', $data);
    }

    /**
     * Gérer la mise en place d'une sauvegarde de DB
     */
    public function save()
    {   
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);   
        if($currentUser['level_name'] !== 'Admin'){
            redirect('index');
            return; 
        } 

        $isSuccess = true;
        try {
            $filename = date('d-m-Y_H\hi\ms\s_').'DBSAVE_'.uniqid().'.sql';
            $dump = new IMysqldump\Mysqldump('mysql:host='.DB_HOST.';dbname='.DB_NAME.'', ''.DB_USER.'', ''.DB_PASS.'');
            $dump->start('DB_SAVE/'.$filename);
        } 
        catch (\Exception $e) {
            echo 'mysqldump-php error: ' . $e->getMessage();
            $isSuccess = false;
        }
        if($isSuccess){
            $this->dumpModel->insert($filename);
            flash('success', '<i class="far fa-check-circle"></i> Sauvegarde correctement effectuée.');
        } 
        else flash("fail", "<i class='fas fa-exclamation-circle'></i> La sauvegarde a échoué.", "alert alert-danger alert-dismissible");
        redirect('admin_dump');
    }

    public function delete($id = null)
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if($currentUser['level_name'] !== 'Admin'){
            redirect('admin');
            return;
        } 
        if(is_null($id) || !intval($id)) redirect('admin_dump');
        if(!$this->dumpModel->findById($id)){
            flash("fail", "<i class='fas fa-exclamation-circle'></i> L'élément sélectionné n'existe pas ou a déjà été supprimé.", "alert alert-danger alert-dismissible");
            redirect('admin_dump');
            return; 
        } 

        $dumpInfo = $this->dumpModel->getById($id);
        // dd($dumpInfo['filename']); die();

        if($this->dumpModel->delete($id)){
            if(file_exists('../public/DB_SAVE/'.$dumpInfo['filename'])){
                unlink('../public/DB_SAVE/'.$dumpInfo['filename']);
            }
            flash("success", "<i class='far fa-check-circle'></i> L'élément a été supprimé.");
        }
        else
        {
            flash("fail", "<i class='fas fa-exclamation-circle'></i> La suppression a échoué.", "alert alert-danger alert-dismissible");
        }
        redirect('admin_dump');
    }
}
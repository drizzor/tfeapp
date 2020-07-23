<?php

class Admin extends Controller
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
        
        $this->statisticModel = $this->model('Statistic');
        $this->purchaseModel = $this->model('Purchase');
        $this->invoiceModel = $this->model('Invoice');
    }

    public function index()
    {
        $currentUser = $this->userModel->getUserById($_SESSION['user_id']);
        if($currentUser['level_name'] === 'Visiteur') redirect('index');

        // caclul du bénéfice sur chantier vendu
        $total_building = $this->statisticModel->sumConstructions();
        $total_sold = $this->statisticModel->sumSoldConstructions();
        $total_purchases = $this->statisticModel->sumPurchasesSold();
        $total_planning = $this->statisticModel->sumPlanningSold();

        // Calcul achats/mois 
        $months = [];
        for ($i=0; $i < 12; $i++) { 
            $months[$i] = explode('-', date('d-m-Y', mktime(0, 0, 0, date("m")-$i  , date("d"), date("Y")))); 
        }

        $purchasesPerMonth = [];
        for ($i=0; $i < count($months); $i++) { 
            $purchasesPerMonth[$i] = $this->statisticModel->purchasesPerMonth($months[$i][1], $months[$i][2]);
        }        

        $data = [
            'T_users' => $this->statisticModel->countActiveUsers(),
            'T_sold_construct' => $this->statisticModel->countSoldConstructions(),
            'T_construct' => $this->statisticModel->countConstructions(),
            'T_purchases' => $this->statisticModel->purchasesThisYear(),
            'T_sales' => $this->statisticModel->salesThisYear(),
            'T_profits' =>  $total_sold['total'] - $total_building['total'] - $total_purchases['total'] - $total_planning['total'],
            'T_purchasesPerMonth' => array_reverse($purchasesPerMonth),
            'months' => array_reverse($months),
            'purchases' => $this->purchaseModel->getLastOne(),
            'invoices' => $this->invoiceModel->getLastOne()
        ];

        // dd($data['T_purchasesPerMonth']); die();

        $this->view('dashoard/index', $data);
    }
}
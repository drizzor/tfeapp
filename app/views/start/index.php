<?php render('header', [
      'title' => 'Accueil',
      'session' => $data['currentUser']['username'],
      'image' => $data['currentUser']['image'],
      'nav_container' => 'container'
      ]); 
?>   
<div class="container mt-5">
    <div class="card card-body">
        <?= flash('update_success') ?>  
        <?= flash('success') ?>         
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h2>Que souhaitez-vous faire <b><?= ucfirst($data['currentUser']['username']) ?></b>?</h2>														            
        </div>
         <div class="row">
            <div class="col-lg-12 mb-2">    
                <div class="text-center pb-2 mb-3">
                    <h3>Achats</h3>														            
                </div>        
                <p class="text-center">                    
                    <!-- <?php if($data['currentUser']['level'] >= 2): ?>
                    <a href="<?= URLROOT; ?>/admin" title="Voir stats et autres" class="btn btn-squared-default-plain btn-menu">
                        <i class="fa fa-tachometer-alt fa-5x"></i>
                        <br />
                        <br />
                        Stats
                    </a>
                    <?php endif; ?> -->

                    <a href="<?= URLROOT; ?>/purchases/list" title="Voir les différents achats" class="btn btn-squared-default-plain btn-menu">
                        <i class="fa fa-search-plus fa-5x"></i>
                        <br />
                        <br />
                        Consulter 
                    </a>    
                    
                    <?php if($data['currentUser']['level'] >= 2): ?>
                    <a href="<?= URLROOT; ?>/purchases/insert" title="Encoder les achats liés aux chantiers" class="btn btn-squared-default-plain btn-menu">
                        <i class="fa fa-pencil-alt fa-5x"></i>
                        <br />
                        <br />
                        Encoder 
                    </a>
                    <a href="<?= URLROOT; ?>/admin_suppliers" title="Voir les fournisseurs" class="btn btn-squared-default-plain btn-menu">
                        <i class="fa fa-truck fa-5x"></i>
                        <br />
                        <br />
                        Fournisseurs
                    </a>
                    <a href="<?= URLROOT; ?>/admin_categories" title="Voir les différentes catégories" class="btn btn-squared-default-plain btn-menu">
                        <i class="fa fa-list-alt fa-5x"></i>
                        <br />
                        <br />
                        Catégories
                    </a>                    
                    <?php endif; ?>
                </p>
            </div>

            <div class="col-lg-12 mb-2"> 
                <div class="text-center pb-2 mb-3">
                    <h3>Chantiers</h3>														            
                </div> 
                <p class="text-center">
                    <?php if($data['currentUser']['level'] >= 2): ?>
                        <a href="<?= URLROOT; ?>/admin_constructions" title="Voir les chantiers en cours" class="btn btn-squared-default-plain btn-menu">
                            <i class="fa fa-building fa-5x"></i>
                            <br />
                            <br />
                            Biens actifs
                        </a>                    
                        <a href="<?= URLROOT; ?>/admin_constructions/sold_list" title="Voir les maisons vendues" class="btn btn-squared-default-plain btn-menu">
                            <i class="fa fa-wallet fa-5x"></i>
                            <br />
                            <br />
                            Biens vendus
                        </a>
                    <?php endif; ?>
                    <a href="<?= URLROOT; ?>/galleries" title="Voir les galeries d'images des chantiers" class="btn btn-squared-default-plain btn-menu">
                        <i class="fa fa-image fa-5x"></i>
                        <br />
                        <br />
                        Galeries
                    </a>                
                </p>
            </div>

            <div class="col-lg-12 mb-2"> 
                <div class="text-center pb-2 mb-3">
                    <h3>Factures</h3>														            
                </div> 
                <p class="text-center">                                        
                    <a href="<?= URLROOT; ?>/invoices" title="Créer et consulter les devis" class="btn btn-squared-default-plain btn-menu">
                        <i class="fas fa-clipboard-list fa-5x"></i>
                        <br />
                        <br />
                        Consulter
                    </a>
                    <?php if($data['currentUser']['level'] >= 2): ?>
                        <a href="<?= URLROOT; ?>/invoices/insert" title="Créer et consulter les factures" class="btn btn-squared-default-plain btn-menu"> 
                            <i class="fas fa-file-invoice-dollar fa-5x"></i>
                            <br />
                            <br />
                            Facturer
                        </a>
                        <a href="<?= URLROOT; ?>/customers" title="Créer et consulter les clients et leurs factures" class="btn btn-squared-default-plain btn-menu">
                            <i class="fa fa-address-book fa-5x"></i>
                            <br />
                            <br />
                            Clients
                        </a>
                    <?php endif; ?>                     
                </p>    
            </div>
        </div>

        <div class="bottom-menu mt-5">
            <?php if($data['currentUser']['level'] >= 2): ?>
            <p class="bottomright dashboard"><a href="<?= URLROOT; ?>/admin" title="Tableau de bord"> <span class="fa fa-tachometer-alt fa-2x"></span></a></p>
            <?php endif; ?>
            <p class="bottomleft disconnect"><a href="<?= URLROOT; ?>/users/logout" title="Bye bye ! ;)"> <span class="fa fa-power-off fa-2x"></span></a></p>
        </div>
    

    <?php require APPROOT . '/views/inc/footer.php'; ?>
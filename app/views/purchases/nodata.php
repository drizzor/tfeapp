<?php render('header', ['title' => 'Données manquantes', 
                        'session' => $data['currentUser']['username'],
                        'image' => $data['currentUser']['image'],
                        'nav_container' => 'container'
                      ]); 
?>  
      <div class="row clearfix">
        
      </div>
  <div class="container mt-5">
    <div class="card card-body">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Impossible pour le moment</h1>														
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
            <?php if($data['currentUser']['level_name'] !== 'Visiteur'): ?>
            <?= anchor(URLROOT, '', 'Retour à l\'accueil', ['.btn btn-back btn-lg', '.maClass'], 'fa fa-arrow-left') ?>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div class="col-md-12 text-center">
        <i style="color: #c54442;" class="fas fa-exclamation-triangle fa-5x mb-3"></i>
        <p>
          <?php if($data['currentUser']['level_name'] !== 'Admin'): ?>
          Afin d'avoir accès à la création d'une facture, l'administrateur doit avoir créé au préalable 
          au moins une catégorie, un fournisseur et un chantier en cours de rénovation. A l'heure actuelle une 
          ou plusieurs de ses données sont manquantes. 
          
          <p>
            Vous pouvez vérifier cela directement depuis votre <a href="<?= URLROOT ?>/admin">Dasboard</a>. <br>
            Si vous pensez qu'il s'agit d'une erreur veuillez <a href="mailto:<?= MAINMAIL ?>" target="_top">contacter</a> l'administrateur principal.
          </p>
          <?php else: ?>
          Il semble manquer les informations suivantes avant de pouvoir réaliser une facture:

          <?php foreach($data['missingDatas'] as $missingData): ?>
            <i style="color: #c54442;"><?= $missingData ?></i>
          <?php endforeach; ?>

          <br><br> Etant donné que vous êtes administrateur, vous pouvez ajouter ses informations directement depuis votre <a href="<?= URLROOT ?>/admin">Dasboard</a>
          <?php endif; ?>
        </p>
      </div>          
    <?php require APPROOT . '/views/inc/footer.php'; ?>
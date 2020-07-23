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
      <h1 class="h2">Galeries inaccessibles</h1>														
      <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
          <?php if($data['currentUser']['level_name'] !== 'Visiteur'): ?>
          <?= anchor(URLROOT, '', 'Retour à l\'accueil', ['.btn btn-back btn-lg', '.maClass'], 'fa fa-arrow-left') ?>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="col-md-12 text-center">
      <i style="color: #22cab2;" class="fas fa-camera fa-5x mb-3"></i>
      <p>
        <?php if($data['currentUser']['level_name'] !== 'Admin'): ?>
        Il n'y a pour l'instant aucune image dans nos galeries, il n'y a donc pour l'instant rien à voir ici.          
        <p>
          Seul un utilisateur ayant le statut <b>Administrateur</b> a le droit d'ajouter des images dans une galerie. Vous avez le statut de : <b><?= $data['currentUser']['level_name'] ?></b>.
        </p>
        <?php else: ?>
          Avant que la galerie s'affiche, vous devez ajouter des images sur un chantier. 

        <br><br> Etant donné que vous êtes administrateur, vous pouvez ajouter ses informations directement depuis <a href="<?= URLROOT ?>/admin_galleries/insert">la page d'upload</a>.
        <?php endif; ?>
      </p>
    </div>          
  <?php require APPROOT . '/views/inc/footer.php'; ?>
<?php render('header', ['title' => 'Factures', 
                        'session' => $data['currentUser']['username'],
                        'image' => $data['currentUser']['image'],
                        'nav_container' => 'container-fluid'
                      ]); 
?>  
      <div class="row clearfix">
        
      </div>
  <div class="container-fluid mt-5">
    <div class="card card-body">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Liste des achats</h1>														
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
          <?php if($data['currentUser']['level_name'] !== 'Visiteur'): ?>
            <a href="<?= URLROOT ?>/purchases/insert" class="btn btn-info btn-lg"><span class="fas fa-plus"></span> Ajouter</a> 
          <?php endif; ?>  
          <?= anchor(URLROOT, '', 'Retour à l\'accueil', ['.btn btn-back btn-lg', '.maClass'], 'fa fa-arrow-left') ?>
            
          </div>
        </div>
      </div>
      <?= flash('success'); ?>
      <?= flash('fail'); ?>
      <div class="col-md-12">
        <table id="myTable" class="table table-striped table-hover dataTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Chantier</th>
              <th>Date d'ajout</th>
              <th>Facture n°</th>
              <th>Fournisseur</th>
              <th>Montant HT</th>
              <th>Montant TTC</th>
              <th class="noSort">Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($data['purchases'] as $purchase): ?>
            <tr>
              <td><?= h($purchase['suppliers_purchases_id']) ?></td>
              <td><?= h($purchase['constructions_name']) ?></td>
              <td><?= h($purchase['dateInsert']) ?></td>
              <td><?= h($purchase['invoiceNumber']) ?></td>
              <td><?= h($purchase['suppliers_name']) ?></td>
              <td><?= h(number_format($purchase['total_notax'], 2, ',', '.')) ?> €</td>
              <td><?= h(number_format($purchase['total_tax'], 2, ',', '.')) ?> €</td>
              <td>
                <a href="<?= URLROOT ?>/purchases/show/<?= h($purchase['id_purchase']) ?>"  title="Voir <?= h($purchase['invoiceNumber']) ?>" class="btn btn-primary"><span class="fa fa-eye"></span></a>
                <!-- <a href="<?= URLROOT ?>/purchases/update/<?= h($purchase['id_purchase']) ?>"  title="Modifier <?= h($purchase['invoiceNumber']) ?>" class="btn btn-secondary"><span class="fas fa-edit"></span></a> -->
                <a href="<?= URLROOT ?>/purchases/<?= h($purchase['invoicePDF']) ?>" target="blank" class="btn btn-warning" title="Cosulter  document <?= h($purchase['invoiceNumber']) ?>"><span class="fa fa-file"></span></a>
                <?php if($data['currentUser']['level_name'] !== 'Visiteur'): ?>
                <a href="#" class="btn btn-danger" title="Supprimer facture N° <?= h($purchase['invoiceNumber']) ?>" data-toggle="modal" data-target="#modal-delete-<?= $purchase['id_purchase'] ?>"><span class="fa fa-trash"></span></a> 
                <?php endif; ?>
              </td>
            </tr> 
          <?php endforeach; ?>          
          </tbody>
          <tfoot>

          </tfoot>
      </table>          
    </div>       
    
    <?php 
    //MODAL BOX SYSTEM
    foreach($data['purchases'] as $purchase): 
      render('modalbox', [
            'modalId' => 'modal-delete-'. $purchase['id_purchase'],
            'actionLink' => URLROOT . '/purchases/delete/'. $purchase['id_purchase'],						
            'actionButton' => 'Supprimer',
            'title' => "Supprimer {$purchase['invoiceNumber']}",
            'message' => " L'action de suppression est <b>irrévocable</b>! Valider l'action?"
      ]); 
    endforeach;					
    ?>	

    <?php require APPROOT . '/views/inc/footer.php'; ?>
<?php render('header', ['title' => 'Factures', 
                        'session' => $data['currentUser']['username'],
                        'image' => $data['currentUser']['image'],
                        'nav_container' => 'container-fluid'
                      ]); 
?>  
  <div class="container-fluid mt-5">
    <div class="card card-body">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Liste des factures</h1>														
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
            <?php if($data['currentUser']['level_name'] !== 'Visiteur'): ?>
              <a href="<?= URLROOT ?>/invoices/insert" class="btn btn-success btn-lg mr-1"><span class="fa fa-plus"></span> Ajouter</a>
            <?php endif; ?> 
            <a href="<?= URLROOT ?>" class="btn btn-back btn-lg"><span class="fa fa-arrow-left"></span></a>            
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
              <th>Facture n°</th>
              <th>Client</th>
              <th>Ajouté le</th>
              <th>Montant HT</th>
              <th>Montant TCC</th>
              <th class="noSort">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($data['invoices'] as $invoice): ?>
              <tr>
                <td><?= h($invoice['invoice_id']) ?></td>
                <td><?= h($data['year']).' / Nr. '.h($invoice['invoice_number']) ?></td>
                <td><?= h($invoice['customer_name']) ?></td>
                <td><?= h($invoice['invoice_createdAt']) ?></td> 
                <td><?= h(number_format($invoice['total_notax'], 2, ',', '.')) ?> €</td> 
                <td><?= h(number_format($invoice['total_tax'], 2, ',', '.')) ?> €</td>
                <td>
                  <a href="<?= URLROOT ?>/invoices/show/<?= $invoice['id_invoice'] ?>" title="Voir <?= $data['year'] .' / Nr. '. $invoice['invoice_number'] ?>" class="btn btn-primary"><span class="fa fa-eye"></span></a>
                  <a href="<?= URLROOT ?>/invoices/print/<?= $invoice['id_invoice'] ?>" target="_blank" title="Imprimer <?= $data['year'] .' / Nr. '. $invoice['invoice_number'] ?>" class="btn btn-secondary"><span class="fa fa-print"></span></a>
                  <?php if($data['currentUser']['level_name'] !== 'Visiteur'): ?>
                    <?php if(($data['currentUser']['level_name'] === 'Admin' || $data['currentUser']['level_name'] === 'Membre') && ($data['lastInv'] == $invoice['id_invoice'])): ?>
                      <a href="#" title="Supprimer <?= $data['year'] .' / Nr. '. $invoice['invoice_number'] ?>" class="btn btn-danger" data-toggle="modal" data-target="#modal-delete-<?= $invoice['id_invoice'] ?>"><span class="fa fa-trash"></span></a>
                    <?php else: ?>
                      <a href="#" class="btn btn-danger disabled"><span class="fa fa-trash"></span></a>
                    <?php endif; ?>
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
    foreach($data['invoices'] as $invoice): 
    render('modalbox', [
          'modalId' => 'modal-delete-'. $invoice['id_invoice'],
          'actionLink' => URLROOT . '/invoices/delete/'. $invoice['id_invoice'],						
          'actionButton' => 'Supprimer',
          'title' => "Supprimer {$invoice['invoice_number']}",
          'message' => " L'action de suppression est <b>irrévocable</b>! Valider l'action?"
    ]); 
    endforeach;					
    ?>	

    <?php require APPROOT . '/views/inc/footer.php'; ?>
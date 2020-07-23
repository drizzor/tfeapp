<?php render('header', ['title' => 'Clients', 
                        'session' => $data['currentUser']['username'],
                        'image' => $data['currentUser']['image'],
                        'nav_container' => 'container'
                      ]); 
?>  
      
  <div class="container mt-5">
    <div class="card card-body">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Listing clients</h1>														
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
            <?php if($data['currentUser']['level_name'] !== 'Visiteur'): ?>
						  <a href="<?= URLROOT ?>/customers/insert" class="btn btn-success btn-lg mr-1"><span class="fa fa-plus"></span> Ajouter</a>
            <?php endif; ?>
            <a href="<?= URLROOT ?>" class="btn btn-back btn-lg"><span class="fa fa-arrow-left"></span></a>            
          </div>
        </div>
      </div>
      <?= flash('success') ?>
      <?= flash('fail') ?>
      <div class="col-md-12">
        <table id="myTable" class="table table-striped table-hover dataTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nom</th>
              <th>Pays</th>
              <th>TVA</th>
              <th>Email</th>
              <th class="noSort">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($data['customers'] as $customer): ?>
              <tr>
                <td><?= h($customer['customer_id']) ?></td>
                <td><?= h($customer['customer_name']) ?></td> 
                <td><?= h($customer['country']) ?></td> 
                <td><?= h($customer['tva_number']) ?></td>
                <td><?= h($customer['customer_email']) ?></td>
                <td>
                  <a href="<?= URLROOT ?>/customers/show/<?= $customer['customer_id'] ?>" title="Voir <?= $customer['customer_name'] ?>" class="btn btn-primary"><span class="fa fa-eye"></span></a>
                  <?php if($data['currentUser']['level_name'] === 'Admin' || $data['currentUser']['level_name'] === 'Membre'): ?>
                      <a href="<?= URLROOT ?>/customers/update/<?= $customer['customer_id'] ?>" title="Modifier <?= $customer['customer_name'] ?>" class="btn btn-secondary"><span class="fa fa-edit"></span></a>
                    <?php if($this->customerModel->refInvoice($customer['customer_id']) == 0): ?>
                      <a href="#" title="Supprimer <?= $customer['customer_name'] ?>" class="btn btn-danger" data-toggle="modal" data-target="#modal-delete-<?= $customer['customer_id'] ?>"><span class="fa fa-trash"></span></a>
                    <?php else: ?>
                      <a href="#" class="btn btn-danger disabled"><span class="fa fa-trash"></span></a>
                    <?php endif; ?>
                  <?php endif; ?>	
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
      </table>          
    </div>          

    <?php 
    //MODAL BOX SYSTEM
    foreach($data['customers'] as $customer): 
    render('modalbox', [
          'modalId' => 'modal-delete-'. $customer['customer_id'],
          'actionLink' => URLROOT . '/customers/delete/'. $customer['customer_id'],						
          'actionButton' => 'Supprimer',
          'title' => "Supprimer {$customer['customer_name']}",
          'message' => " L'action de suppression est <b>irrévocable</b>! Valider l'action?"
    ]); 
    endforeach;					
    ?>	

    <?php require APPROOT . '/views/inc/footer.php'; ?>
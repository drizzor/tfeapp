<?php render('header', ['title' => 'Client', 
                        'session' => $data['currentUser']['username'],
                        'image' => $data['currentUser']['image'],
                        'nav_container' => 'container'
                      ]); 
?>  
      
  <div class="container mt-5">
    <div class="card card-body mb-3">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Consulter client</h1>														
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
            <a href="<?= URLROOT ?>/customers" class="btn btn-back btn-lg"><span class="fa fa-arrow-left"></span></a>
          </div>
        </div>
      </div>
    
    <div class="col-md-12">        
        <form class="form" action="" role="form" method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input type="text" class="form-control form-control-lg" id="name" name="name"  value="<?= $data['name'] ?>" readonly>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tva_number">N° TVA</label>
                        <input type="text" class="form-control form-control-lg" id="tva_number" name="tva_number"  value="<?= $data['tva_number'] ?>" readonly>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="zipcode">Code postal</label>
                        <input type="text" class="form-control form-control-lg" id="zipcode" name="zipcode" value="<?= $data['zipcode'] ?>" readonly>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="city">Ville</label>
                        <input type="text" class="form-control form-control-lg" id="city" name="city" value="<?= $data['city'] ?>" readonly>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="address">Adresse</label>
                        <input type="text" class="form-control form-control-lg" id="address" name="address" value="<?= $data['address'] ?>" readonly>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="country">Pays</label>
                        <input type="text" class="form-control form-control-lg" id="country" name="country" value="<?= $data['country'] ?>" readonly>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control form-control-lg" id="email" name="email"  value="<?= $data['email'] ?>" readonly>
                    </div>
                </div>                
            </div>
            <br><br>
            <p>Créé par: <span style="color:#02c9fa"><?= $data['member'] ?></span></p>            
      </div> 
   </div>   

    <div class="card card-body">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Factures attribuées</h1>														        
      </div>
      <table id="myTable" class="table table-striped table-hover dataTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Numéro</th>
              <th>Ajouté le</th>
              <th>Montant HT</th>
              <th>Montant TCC</th>
              <th class="noSort">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($data['invoices'] as $invoice): ?>
              <tr>
                <td><?= h($invoice['id_invoice']) ?></td>
                <td><?= h($invoice['invoice_number']) ?></td>
                <td><?= h($invoice['invoice_createdAt']) ?></td> 
                <td><?= h(number_format($invoice['total_notax'], 2, ',', '.')) ?> €</td> 
                <td><?= h(number_format($invoice['total_tax'], 2, ',', '.')) ?> €</td>
                <td><a href="<?= URLROOT ?>/invoices/print/<?= $invoice['id_invoice'] ?>" target="_blank" title="Imprimer <?= $invoice['invoice_number'] ?>" class="btn btn-secondary"><span class="fa fa-print"></span></a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
          </tfoot>
      </table>
    </div>         

    <?php require APPROOT . '/views/inc/footer.php'; ?>
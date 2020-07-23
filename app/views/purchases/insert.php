<?php render('header', ['title' => 'Achats',
                        'session' => $data['user_session']['username'],
                        'image' => $data['user_session']['image'],
                        'nav_container' => 'container-fluid'
                        ]); 
?>   
  <div class="container-fluid mt-5">
    <div class="card card-body">
    <?= flash('success') ?> 
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Encoder les achats</h1>														
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
            <a href="<?= URLROOT ?>/purchases/list" class="btn btn-info btn-lg"><span class="fas fa-eye"></span> Listing</a> 
            <a href="<?= URLROOT ?>" class="btn btn-back btn-lg"><span class="fa fa-arrow-left"></span></a>
          </div>
        </div>
      </div>    

      <form class="form" action="<?= URLROOT ?>/purchases/insert" method="post" enctype="multipart/form-data">
        <div class="row clearfix">
          <div class="col-md-12">
            <div class="form-group float-left mr-2">
              <label for="construction">Chantiers</label>
                <select class="form-control" id="construction" name="construction">
                    <?php foreach($data['constructions'] as $construction): ?>
                      <?php if($data['construction_id'] == $construction['constructions_id']): ?>
                      <option selected="selected" value="<?= $construction['constructions_id'] ?>"><?= $construction['constructions_name'] ?></option>
                      <?php else: ?>
                      <option value="<?= $construction['constructions_id'] ?>"><?= $construction['constructions_name'] ?></option>
                      <?php endif; ?>
                    <?php endforeach; ?>                   
                </select>
                <?php if(!empty($data['errors']['construction'])): ?> 
                  <span style="color: #d2595a;"><?= $data['errors']['construction'] ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group float-left">
              <label for="supplier">Fournisseurs</label>
              <select class="form-control" id="supplier" name="supplier">
                  <?php foreach($data['suppliers'] as $supplier): ?>
                    <?php if($data['supplier_id'] == $supplier['id']): ?>
                    <option selected="selected" value="<?= $supplier['id'] ?>"><?= $supplier['name'] ?></option>
                    <?php else: ?>
                    <option value="<?= $supplier['id'] ?>"><?= $supplier['name'] ?></option>
                    <?php endif; ?>
                  <?php endforeach; ?>                  
              </select>
              <?php if(!empty($data['errors']['supplier'])): ?> 
                <span style="color: #d2595a;"><?= $data['errors']['supplier'] ?></span>
              <?php endif; ?>
            </div>
                  
            <div class="input-group float-right mb-3 col-md-3">
              <div class="input-group-prepend">
                <span class="input-group-text fa fa-calendar"></span>
              </div>
              <input type="date" class="form-control <?= (isset($data['errors']['invoiceDate']) && !empty($data['errors']['invoiceDate'])) ? 'is-invalid' : '' ?>" id="invoiceDate" name="invoiceDate" value="<?= $data['invoiceDate'] ?>">
              <span class="invalid-feedback"><?= isset($data['errors']['invoiceDate'])? $data['errors']['invoiceDate'] : '' ?></span>
            </div>

            <div class="input-group float-right mb-3 col-md-3">
              <input type="text" class="form-control <?= (isset($data['errors']['invoiceNo']) && !empty($data['errors']['invoiceNo'])) ? 'is-invalid' : '' ?>" name="invoiceNo" placeholder="Facture N°" value="<?= $data['invoiceNo'] ?>">
              <span class="invalid-feedback"><?= isset($data['errors']['invoiceNo'])? $data['errors']['invoiceNo'] : '' ?></span>
            </div>

            <div class="input-group float-right mb-3 col-md-12 mb-5">
              <label class="control-label btn btn-info" for="invoiceFile"><i class="fas fa-upload"></i> UPLOAD FACTURE (PDF) <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
              <input type="file" id="invoiceFile" name="invoiceFile" class="form-control-file"> <br>
              <?php if(!empty($data['error_upload']['invoiceFile'])): ?> 
                  <span style="color: #d2595a;"><?= $data['error_upload']['invoiceFile'] ?></span>
              <?php endif; ?> 
            </div>

            <table class="table table-hover myTable mt-5" id="tab_logic">
              <thead>
                <tr>
                  <th class="text-center"> # </th>
                  <th class="text-center"> Produit </th>
                  <th class="text-center"> Catégorie </th>
                  <th class="text-center"> Qte </th>
                  <th class="text-center"> Prix / u (€)</th>
                  <th class="text-center"> Taux TVA</th>
                  <th class="text-center"> Total HT</th>
                  <th class="text-center"> Total TTC</th>
                </tr>
              </thead>
              <tbody>
                <?php for($i = 0; $i < $data['rowCount']; $i++): ?>
                <tr id='addr<?= $i ?>'>
                  <td>
                    <?= $i + 1 ?>
                  </td>

                  <td>
                    <input type="text" class="form-control <?= (isset($data['errors']['product'][$i]) && !empty($data['errors']['product'][$i])) ? 'is-invalid' : '' ?>" name='product[]'  placeholder="Le nom de l'article" value="<?= (!empty($data['product']))? $data['product'][$i] : '' ?>"/>
                    <span class="invalid-feedback"><?= isset($data['errors']['product'][$i])? $data['errors']['product'][$i] : '' ?></span>
                  </td>

                  <td>
                    <select class="form-control" name="category[]">
                        <?php foreach($data['categories'] as $category): ?>
                          <?php if($data['category_id'][$i] == $category['id']): ?>
                            <option selected="selected" value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                          <?php else: ?>
                            <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                          <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                    <?php if(!empty($data['errors']['category'])): ?> 
                      <span style="color: #d2595a;"><?= $data['errors']['category'] ?></span>
                    <?php endif; ?>
                  </td>

                  <td>
                    <input type="text" class="form-control qty <?= (isset($data['errors']['qty'][$i]) && !empty($data['errors']['qty'][$i])) ? 'is-invalid' : '' ?>" name='qty[]' placeholder='0' step="0" min="0" value="<?= (!empty($data['qty']))? $data['qty'][$i] : 1 ?>"/>
                    <span class="invalid-feedback"><?= isset($data['errors']['qty'][$i])? $data['errors']['qty'][$i] : '' ?></span>
                  </td>
                  
                  <td>
                    <input type="text" class="form-control price <?= (isset($data['errors']['price'][$i]) && !empty($data['errors']['price'][$i])) ? 'is-invalid' : '' ?>" name='price[]' placeholder='Le prix unitaire' step="0.00" min="0" value="<?= (!empty($data['price']))? $data['price'][$i] : '' ?>"/>
                    <span class="invalid-feedback"><?= isset($data['errors']['price'][$i])? $data['errors']['price'][$i] : '' ?></span>
                  </td>

                  <td>
                    <input type="text" class="form-control tax <?= (isset($data['errors']['tax'][$i]) && !empty($data['errors']['tax'][$i])) ? 'is-invalid' : '' ?>" name='tax[]' placeholder='21, 12, 6, ...' value="<?= (!empty($data['tax']))? $data['tax'][$i] : 21 ?>"/>
                    <span class="invalid-feedback"><?= isset($data['errors']['tax'][$i])? $data['errors']['tax'][$i] : '' ?></span>
                  </td>  

                  <td>
                    <input type="text" class="form-control total_notax" name='total_notax[]' placeholder='0.00' readonly/>
                  </td>  

                  <td>
                    <input type="text" class="form-control total" name='total[]' placeholder='0.00' readonly/>
                  </td>
                </tr>

                <tr id='addr<?= $i + 1 ?>'></tr>
                <?php endfor; ?>
              </tbody>
            </table>
          </div>
        </div>

        <?php if(!isset($_POST['send_purchases'])): ?>
          <div class="row clearfix">
            <div class="col-md-12">
              <a id="add_row" class="btn btn-activate pull-left"><i class="fas fa-plus"></i></a>
              <a id='delete_row' class="pull-right btn btn-danger"><i class="fas fa-minus"></i></a>
            </div>
          </div>
        <?php endif; ?>

        <div class="row clearfix" style="margin-top:20px">
          <div class="pull-right col-md-4">
            <table class="table table-hover myTable" id="tab_logic_total">
              <tbody>
                <tr>
                  <th class="text-center">Total hors tva</th>
                  <td class="text-center"><input type="text" name='sub_total' placeholder='0.00' class="form-control" id="sub_total" readonly/></td>
                </tr>
                <tr>
                  <th class="text-center">Montant tva</th>
                  <td class="text-center"><input type="text" name='tax_amount' id="tax_amount" placeholder='0.00' class="form-control" readonly/></td>
                </tr>
                <tr>
                  <th class="text-center">Total général</th>
                  <td class="text-center"><input type="text" name='total_amount' id="total_amount" placeholder='0.00' class="form-control" readonly/></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
          <div class="form-actions">
            <button type="submit" name="send_purchases" class="btn btn-success btn-lg"><span class="fa fa-check"></span> Enregistrer</button>                          
        </div>
      </form>
 <script> let rowCount = <?= $data['rowCount'] ?>; console.log(rowCount); </script>    
  <?php require APPROOT . '/views/inc/footer.php'; ?>

<?php render('header', ['title' => 'Facture',
                        'session' => $data['currentUser']['username'],
                        'image' => $data['currentUser']['image'],
                        'nav_container' => 'container-fluid'
                        ]); 
?>  

<div class="container-fluid mt-5">
    <div class="card card-body">
      <?= flash('success') ?> 
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Nouvelle facture</h1>														
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
            <a href="<?= URLROOT ?>/invoices/" class="btn btn-back btn-lg"><span class="fa fa-arrow-left"></span></a>
          </div>
        </div>
      </div>    

      <form class="form" action="<?= URLROOT ?>/invoices/insert" method="post" enctype="multipart/form-data">
        <div class="row clearfix">
          <div class="col-md-12">                  
            <fieldset class="row">
                <legend>Recherche client</legend>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="customer">Client <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
                        <input type="text" class="form-control dropdownField form-control-lg <?= (isset($data['errors']['customer']) && !empty($data['errors']['customer'])) ? 'is-invalid' : '' ?>" id="searchBox" name="customer" placeholder="Effectuer la recherche via le nom du client" autocomplete="off"  value="<?= $data['customer'] ?>" required>
                        <span class="invalid-feedback"><?= isset($data['errors']['customer'])? $data['errors']['customer'] : '' ?></span>
                        <div id="response"></div>
                    </div>
                </div>
            </fieldset>

            <table class="table table-hover myTable mt-5" id="tab_logic">
              <thead>
                <tr>
                  <th class="text-center"> # </th>
                  <th class="text-center"> Description </th>
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
                    <input type="text" class="form-control <?= (isset($data['errors']['description'][$i]) && !empty($data['errors']['description'][$i])) ? 'is-invalid' : '' ?>" name='description[]'  placeholder="Description" value="<?= (!empty($data['description']))? $data['description'][$i] : '' ?>"/>
                    <span class="invalid-feedback"><?= isset($data['errors']['description'][$i])? $data['errors']['description'][$i] : '' ?></span>
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

        <br>
        <div class="form-group">
            <label for="comment">Commentaire</label>
            <textarea id="textarea_count"  maxlength="255" rows="5" class="form-control <?= (isset($data['errors']['comment']) && !empty($data['errors']['comment'])) ? 'is-invalid' : '' ?>" id="comment" name="comment" placeholder="<?= $data['comment'] ?>"></textarea>
            <div id="textarea_feedback" style="color:#21cab5"></div>   
            <span class="invalid-feedback"><?= isset($data['errors']['comment'])? $data['errors']['comment'] : '' ?></span>    
        </div>
        <br>

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

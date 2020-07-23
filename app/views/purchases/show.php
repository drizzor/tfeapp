<?php render('header', ['title' => 'Ajouter une facture',
                        'session' => $data['user_session']['username'],
                        'image' => $data['user_session']['image'],
                        'nav_container' => 'container-fluid'
                        ]); 
?>   
  <div class="container-fluid mt-5">
    <div class="card card-body">
    <?= flash('success') ?> 
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Consulter achat</h1>														
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
            <a href="<?= URLROOT ?>/purchases/<?= $data['invoicePDF'] ?>" target="_blank" class="btn btn-info btn-lg mr-1"><span class="fa fa-print"></span></a>
            <a href="<?= URLROOT ?>/purchases/list" class="btn btn-back btn-lg"><span class="fa fa-arrow-left"></span></a>
          </div>
        </div>
      </div>    

      <div class="row clearfix">
        <div class="col-md-12">
          <div class="form-group float-left mr-2">
            <label for="construction">Chantier</label>
            <input type="text" class="form-control" name='construction' value="<?= $data['construction_name'] ?>" readonly />  
          </div>

          <div class="form-group float-left">
            <label for="supplier">Fournisseur</label>
            <input type="text" class="form-control" name='supplier' value="<?= $data['supplier_name'] ?>" readonly />
          </div>
                
          <div class="input-group float-right mb-3 col-md-3">
            <div class="input-group-prepend">
              <span class="input-group-text fa fa-calendar"></span>
            </div>
            <input type="text" class="form-control" name="dateInvoice" value="<?= $data['dateInvoice'] ?>">
          </div>

          <div class="input-group float-right mb-3 col-md-3">
            <input type="text" class="form-control" name="invoiceNo" value="<?= $data['invoiceNo'] ?>">
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
                  <input type="text" class="form-control" name='product' value="<?= $data['currentPurchase'][$i]['product'] ?>" readonly/>
                </td>

                <td>
                  <input type="text" class="form-control" name='category' value="<?= $data['category_name'][$i][0]['name'] ?>" readonly/>  
                </td>

                <td>
                  <input type="text" class="form-control" name='quantity' value="<?= $data['currentPurchase'][$i]['quantity'] ?>" readonly/>
                </td>
                
                <td>
                  <input type="text" class="form-control" name='price' value="<?= number_format($data['currentPurchase'][$i]['price'], 2, ',', '.') . '€' ?>" readonly/>
                </td>

                <td>
                  <input type="text" class="form-control" name='tax' value="<?= $data['currentPurchase'][$i]['tva'] ?>" readonly/>
                </td>  

                <td>
                  <input type="text" class="form-control"  placeholder='0.00' value="<?= number_format($data['currentPurchase'][$i]['quantity'] * $data['currentPurchase'][$i]['price'], 2, ',', '.') . '€' ?>" readonly/>
                </td>  

                <td>
                  <input type="text" class="form-control"  placeholder='0.00' value="<?= number_format(($data['currentPurchase'][$i]['quantity'] * $data['currentPurchase'][$i]['price']) * (1 + ($data['currentPurchase'][$i]['tva'] / 100)), 2, ',', '.') . '€' ?>" readonly />
                </td>
              </tr>

              <tr id='addr<?= $i + 1 ?>'></tr>
              <?php endfor; ?>
            </tbody>
          </table>
        </div>
      </div>

      <?php 

        $total_notax = $total_tax = 0;
        for($i = 0; $i < $data['rowCount']; $i++)
        {
          $total_notax += $data['currentPurchase'][$i]['quantity'] * $data['currentPurchase'][$i]['price']; 
          $total_tax += ($data['currentPurchase'][$i]['quantity'] * $data['currentPurchase'][$i]['price']) * (1 + ($data['currentPurchase'][$i]['tva'] / 100));
            
        }
        
      ?>


      <div class="row clearfix" style="margin-top:20px">
        <div class="pull-right col-md-4">
          <table class="table table-hover myTable" id="tab_logic_total">
            <tbody>
              <tr>
                <th class="text-center">Total hors tva</th>
                <td class="text-center"><input type="text"  class="form-control"  value="<?= number_format($total_notax, 2, ',', '.') . ' €' ?>" readonly/></td>
              </tr>
              <tr>
                <th class="text-center">Montant tva</th>
                <td class="text-center"><input type="text"  class="form-control" value="<?= number_format($total_tax - $total_notax, 2, ',', '.') . ' €' ?>" readonly/></td>
              </tr>
              <tr>
                <th class="text-center">Total général</th>
                <td class="text-center"><input type="text" class="form-control" value="<?= number_format($total_tax, 2, ',', '.') . ' €' ?>" readonly/></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

 <script> let rowCount = <?= $data['rowCount'] ?>; console.log(rowCount); </script>    
  <?php require APPROOT . '/views/inc/footer.php'; ?>

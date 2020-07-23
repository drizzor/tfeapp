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
        <h1 class="h2">Consulter <?=  $data['inv_year'] . ' / Nr. ' . $data['invoice_number'] ?></h1>														
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
            <a href="<?= URLROOT ?>/invoices/print/<?= $data['inv'][0]['id_invoice'] ?>" target="_blank" class="btn btn-info btn-lg mr-1"><span class="fa fa-print"></span></a>
            <a href="<?= URLROOT ?>/invoices/" class="btn btn-back btn-lg"><span class="fa fa-arrow-left"></span></a>
          </div>
        </div>
      </div>    

    <div class="row clearfix">
        <div class="col-md-12">                  
            <fieldset class="row">
                <legend>Info client</legend>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer">Nom</label>
                        <input type="text" class="form-control dropdownField form-control-lg"  id="customer" value="<?= $data['customer'] ?>" readonly>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tva">N° TVA</label>
                        <input type="text" class="form-control dropdownField form-control-lg"  id="tva" value="<?= $data['customer_tva_number'] ?>" readonly>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="zipcode">Code postal</label>
                        <input type="text" class="form-control dropdownField form-control-lg"  id="zipcode" value="<?= $data['customer_zipcode'] ?>" readonly>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="city">Ville</label>
                        <input type="text" class="form-control dropdownField form-control-lg"  id="city" value="<?= $data['customer_city'] ?>" readonly>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="address">Adresse</label>
                        <input type="text" class="form-control dropdownField form-control-lg"  id="address" value="<?= $data['customer_address'] ?>" readonly>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="country">Adresse</label>
                        <input type="text" class="form-control dropdownField form-control-lg"  id="country" value="<?= $data['customer_country'] ?>" readonly>
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
                    <th class="text-center"> Total HTVA</th>
                    <th class="text-center"> Total TVAC</th>
                </tr>
                </thead>
                <tbody>
                <?php for($i = 0; $i < $data['rowCount']; $i++): ?>
                <tr id='addr<?= $i ?>'>
                    <td>
                    <?= $i + 1 ?>
                    </td>

                    <td>
                        <input type="text" class="form-control" value="<?= $data['inv'][$i]['invoice_description'] ?>" readonly/>
                    </td>

                    <td>
                        <input type="text" class="form-control qty" value="<?= $data['inv'][$i]['quantity'] ?>" readonly/>
                    </td>
                    
                    <td>
                        <input type="text" class="form-control price" value="<?= number_format($data['inv'][$i]['notax_amount'], 2, ',', '.') ?> €" readonly/>
                    </td>

                    <td>
                        <input type="text" class="form-control tax" value="<?= $data['inv'][$i]['tax'] ?> %" readonly/>
                    </td>  

                    <td>
                        <input type="text" class="form-control" value="<?= number_format(($data['inv'][$i]['notax_amount'] * $data['inv'][$i]['quantity']), 2, ',', '.') ?> €" readonly/>
                    </td>  

                    <td>
                    <input type="text" class="form-control" value="<?= number_format((($data['inv'][$i]['notax_amount'] * $data['inv'][$i]['quantity']) * (1 + ($data['inv'][$i]['tax'] / 100))), 2, ',', '.') ?> €" readonly/>
                    </td>
                </tr>

                <tr id='addr<?= $i + 1 ?>'></tr>
                <?php endfor; ?>
                </tbody>
            </table>
        </div>
    </div>
    <br>
    <div class="form-group">
        <label for="comment">Commentaire</label>
        <textarea type="text" class="form-control" id="comment" placeholder="<?= $data['comment'] ?>"></textarea>
    </div>
    <br>

    <?php 
        $total_notax = $total_tax = 0;
        for($i = 0; $i < $data['rowCount']; $i++)
        {
          $total_notax += $data['inv'][$i]['quantity'] * $data['inv'][$i]['notax_amount']; 
          $total_tax += ($data['inv'][$i]['quantity'] * $data['inv'][$i]['notax_amount']) * (1 + ($data['inv'][$i]['tax'] / 100));
            
        }        
    ?>

    <div class="row clearfix" style="margin-top:20px">
        <div class="pull-right col-md-4">
        <table class="table table-hover myTable" id="tab_logic_total">
            <tbody>
            <tr>
                <th class="text-center">Grand Total HTVA</th>
                <td class="text-center"><input type="text" class="form-control" id="sub_total" value="<?= number_format($total_notax, 2, ',', '.') ?> €" readonly/></td>
            </tr>
            <tr>
                <th class="text-center">Montant TVA</th>
                <td class="text-center"><input type="text"  class="form-control" value="<?= number_format(($total_tax - $total_notax), 2, ',', '.') ?> €" readonly/></td>
            </tr>
            <tr>
                <th class="text-center">Grand Total TVAC</th>
                <td class="text-center"><input type="text" class="form-control" value="<?= number_format($total_tax, 2, ',', '.') ?> €" readonly/></td>
            </tr>
            </tbody>
        </table>
    </div>
   
 <script> let rowCount = <?= $data['rowCount'] ?>; console.log(rowCount); </script>    
  <?php require APPROOT . '/views/inc/footer.php'; ?>

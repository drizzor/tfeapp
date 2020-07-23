<?php render('admin-header', ['active' => 'constructions',
								'countRequest' => $this->userModel->countRequest(),
                'countIntruder' => $this->userModel->countIntruder()
                ]); ?>	 

<div class="container-fluid">	
	<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
		<div class="card card-body">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">				
        <?php if($this->constructionModel->isSold($data['id'])): ?>
          <h1 class="h2">Consulter la vente <?= $data['name'] ?></h1>        
        <?php else: ?>
          <h1 class="h2">Consulter le chantier <?= $data['name'] ?></h1>        
        <?php endif; ?>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
            <?php if(!$this->constructionModel->isSold($data['id'])): ?>
              <a href="<?= URLROOT ?>/admin_constructions" class="btn btn-back btn-lg"><span class="fa fa-arrow-left"></span></a>
            <?php else: ?>                  
              <a href="<?= URLROOT ?>/admin_constructions/sold_list" class="btn btn-back btn-lg"><span class="fa fa-arrow-left"></span></a>
            <?php endif; ?>  
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
              <label for="ville">Ville</label>
              <input type="text" class="form-control form-control-lg" id="ville" name="ville"  value="<?= $data['city'] ?>" readonly>
          </div>

          <div class="form-group">
              <label for="adresse">Adresse</label>
              <input type="text" class="form-control form-control-lg" id="adresse" name="adresse" value="<?= $data['address'] ?>" readonly>
          </div>
          
          <div class="form-group">
          <label for="prix">Prix d'acquisition</label>
              <input type="text" class="form-control form-control-lg" id="prix" name="prix" value="<?= number_format($data['buyingPrice'], 2, ',', '.') ?> €" readonly>
          </div>

          <div class="form-group">
              <label for="surface">Surface au m²</label>
              <input type="text" step="0.01" class="form-control form-control-lg" id="surface" name="surface" value="<?= number_format($data['surface'], 2, ',', '.') ?>" readonly>
          </div>

          <div class="form-group">
              <label for="notaireTaxe">Notaire et taxes</label>
              <input type="text" step="0.01" class="form-control form-control-lg" id="notaireTaxe" name="notaireTaxe" value="<?= number_format($data['taxes'], 2, ',', '.') ?> €" readonly>
          </div>

          <div class="form-group">
              <label for="prixVenteEstime">Prix de vente estimé</label>
              <input type="text" step="0.01" class="form-control form-control-lg" id="prixVenteEstime" name="prixVenteEstime" value="<?= number_format($data['estimatePrice'], 2, ',', '.') ?> €" readonly>
          </div>

          <div class="form-group">
            <label for="buyingDate">Date d'achat</label>
            <input type="text" class="form-control form-control-lg" id="buyingDate" name="buyingDate" value="<?= $data['buyingDate'] ?>" readonly>
          </div>
          <br><br>        
        </div>
          
        <div style="text-align:center" class="col-sm-6">          
          <div  class="thumbnail">
            <h4>Date de création</h4>
            <p><span class="fa fa-calendar"></span> <?= $data['createDate'] ?></p>
          </div>
              <img width="37%" src="<?= URLROOT ?>/images/constructions/<?= $data['image'] ?>" alt="Image de couverture chantier" class="mb-3">
              <?php if($this->constructionModel->isSold($data['id'])): ?>
                  <br>
                  <h4><i style="color: #24caae" class="fas fa-hand-holding-usd"></i> Vendu: <?= number_format($data['soldPrice']['price'], 2, ',', '.') ?> €</h4>     
                  <br><br>
              <?php endif; ?>
              <div class="form-group">
                  <textarea class="form-control" id="commentaire" rows="11" name="commentaire" placeholder="" readonly><?= $data['comment'] ?></textarea>                
              </div>
          </div>
      </div>
	</main>
</div>

<!-- Aperçu des dernières dépenses pour le chantier sélectionné -->
<div class="container-fluid">
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
  <div class="card card-body">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Derniers achats liés</h1>
    </div>
    <table id="myTable" class="table table-striped table-hover dataTable">
        <thead>
          <tr>
            <th>id</th>
            <th>Numéro</th>
            <th>Date</th>
            <th>Fournisseur</th>
            <th>Total TVAC</th>
            <th class="noSort">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($data['purchases'] as $purchase): ?>
            <tr>
                <td><?= $purchase['id_purchases'] ?></td>
                <td><?= h($purchase['invoiceNumber']) ?></td>
                <td><?= h($purchase['dateInvoice']) ?></td>
                <td><?= h($purchase['supplier_name']) ?></td>
                <td><?= h(number_format($purchase['total_tax'], 2, ',', '.')) ?> €</td>
                <td>
                <a href="<?= URLROOT ?>/purchases/show/<?= $purchase['id_purchase'] ?>" target="_blank" title="Voir cet achat" class="btn btn-primary"><span class="fa fa-eye"></span></a>
                </td> 
            </tr>
          <?php endforeach; ?>							
        </tbody>
      </table>
    </div>
  </main>
</div>

<!-- Vignettes / stats -->
<div class="container-fluid">
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
      <div class="card card-body">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
          <h1 class="h2">En quelques chiffres</h1>	
        </div>

        <div class="row mb-5">     
        <?php if($this->constructionModel->isSold($data['id'])): ?>
          <div class="col-md-3">            
            <div class="card-counter success">
              <i class="fa fa-piggy-bank"></i>
              <span class="count-numbers"><?= number_format(($data['soldPrice']['price'] - $data['sumPurchases']['total'] - $data['sumPlanning']['total_presta'] - $data['buyingPrice'] - $data['taxes']), 2, ',', '.') ?> €</span>
              <span class="count-name">Bénéfice réel</span>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card-counter info">
              <i class="fa fa-balance-scale"></i>
              <span class="count-numbers"><?= number_format(($data['estimatePrice'] - $data['soldPrice']['price']), 2, ',', '.') ?> €</span>
              <span class="count-name">Ecart estimation</span>
            </div>
          </div>
        <?php endif; ?>

          <div class="col-md-3">            
            <div class="card-counter danger">
              <i class="fa fa-shopping-cart"></i>
              <span class="count-numbers"><?= number_format($data['sumPurchases']['total'], 2, ',', '.') ?> €</span>
              <span class="count-name">Total marchandises (HT)</span>
            </div>
          </div>	     

          <div class="col-md-3">            
            <div class="card-counter primary">
              <i class="fa fa-male"></i>
              <span class="count-numbers"><?= number_format($data['sumPlanning']['total_presta'], 2, ',', '.') ?> €</span>
              <span class="count-name">Prestations ouvriers</span>
            </div>
          </div>		

          <div class="col-md-3">
            <div class="card-counter warning">
              <i class="fa fa-male"></i>
              <span class="count-numbers"><?= ($data['countPlanning'] > 0)? number_format(($data['sumRowPlanning']['total_salary'] / $data['countPlanning']), 2, ',', '.') : '0,00' ?> €</span>
              <span class="count-name">Taux moyen / ouvrier</span>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card-counter laravel">
              <i class="fa fa-clock"></i>
              <span class="count-numbers"><?= ($data['countPlanning'] > 0)? number_format(($data['sumRowPlanning']['total_hours']/ $data['countPlanning']), 2, ',', '.') : '0,00' ?> h</span>
              <span class="count-name">Moyenne horaire / ouvrier</span>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card-counter mauve">
              <i class="fa fa-hourglass-start"></i>
              <span class="count-numbers"><?= number_format($data['sumRowPlanning']['total_hours'], 2, ',', '.') ?> h</span>
              <span class="count-name">Total heures prestées</span>
            </div>
          </div>            
        </div>  
        <div class="row mb-5"> 
          <div class="col-md-6">
            <canvas id="myChart" width="800" height="500"></canvas>              
          </div> 

          <div class="col-md-6">
            <canvas id="myChart2" width="800" height="500"></canvas>              
          </div>                                        
        </div> 

        <div class="row mb-5">
          <?php if(!empty($data['purchasesPerMonth'])): ?>  
            <div class="col-md-6">
                <canvas id="myChart3" width="800" height="500"></canvas>              
            </div> 
          <?php endif; ?> 
          <?php if($data['sumPurchases']['total'] > 0 || $data['sumPlanning']['total_presta'] > 0): ?>  
            <div class="col-md-6">
              <canvas id="myChart4" width="800" height="500"></canvas>              
            </div> 
          <?php endif; ?>
        </div>           
      </div> 
  </main>    
</div>

  <script>
	var ctx = document.getElementById('myChart').getContext('2d');

	var myChart = new Chart(ctx, {
	type: 'line', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
	data: {
		labels: [
			<?php foreach($data['plannings'] as $planning): ?>
				<?php if($planning['planning_total'] > 0): ?>
					'<?= $planning['firstname'] .' '. $planning['lastname'] ?>',
				<?php endif; ?>
			<?php endforeach; ?>
			],
		datasets: [{
			label: '# coût par ouvrier',
			data: [
				<?php foreach($data['plannings'] as $planning): ?>
					<?php if($planning['planning_total'] > 0): ?>
						<?= $planning['planning_total'] ?>,
					<?php endif; ?>
				<?php endforeach; ?>
			],
			backgroundColor: 'rgba(35, 202, 177, .1)',
      borderColor: '#22cab3',
			borderWidth: 1,
			hoverBorderWidth: 1,
			hoverBorderColor: '#22cab3'
		}]
	},
	options: {
		title:{
			display: true,
			text: 'Coût total par ouvrier',
			fontSize: 18,
			fontColor: '#fff'
		},
		legend:{
			display: false,
			position: 'right',
			labels:{
				fontColor:'#fff'
			}
		}
	}
	});
</script>

<script>
	var ctx = document.getElementById('myChart2').getContext('2d');

	var myChart2 = new Chart(ctx, {
	type: 'line', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
	data: {
		labels: [
			<?php foreach($data['plannings'] as $planning): ?>
				<?php if($planning['planning_hour'] > 0): ?>
					'<?= $planning['firstname'] .' '. $planning['lastname'] ?>',
				<?php endif; ?>
			<?php endforeach; ?>
			],
		datasets: [{
			label: '# heures par ouvrier',
			data: [
				<?php foreach($data['plannings'] as $planning): ?>
					<?php if($planning['planning_hour'] > 0): ?>
						<?= $planning['planning_hour'] ?>,
					<?php endif; ?>
				<?php endforeach; ?>
			],
			backgroundColor: 'rgba(245, 93, 92, .1)',
      borderColor: '#f55d5c',
			borderWidth: 1,
			hoverBorderWidth: 1,
			hoverBorderColor: '#f55d5c'
		}]    
	},
	options: {
		title:{
			display: true,
			text: 'Heures totale par ouvrier',
			fontSize: 18,
			fontColor: '#fff'
		},
		legend:{
			display: false,
			position: 'right',
			labels:{
				fontColor:'#fff'
			}
		}
	}
	});
</script>

<script>
	var ctx = document.getElementById('myChart3').getContext('2d');

	var myChart3 = new Chart(ctx, {
	type: 'line', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
	data: {
		labels: [
          <?php foreach ($data['purchasesPerMonth'] as $purchase): ?>
            <?php if($purchase['month'] == 1): ?>
              'Jan. <?= $purchase['year'] ?>',
            <?php elseif($purchase['month'] == 2): ?>
              'Fév. <?= $purchase['year'] ?>',
            <?php elseif($purchase['month'] == 3): ?>
              'Mar. <?= $purchase['year'] ?>',
            <?php elseif($purchase['month'] == 4): ?>
              'Avr. <?= $purchase['year'] ?>',
            <?php elseif($purchase['month'] == 5): ?>
              'Mai <?= $purchase['year'] ?>',
            <?php elseif($purchase['month'] == 6): ?>
              'Jun. <?= $purchase['year'] ?>',
            <?php elseif($purchase['month'] == 7): ?>
              'Jui. <?= $purchase['year'] ?>',
            <?php elseif($purchase['month'] == 8): ?>
              'Aout <?= $purchase['year'] ?>',
            <?php elseif($purchase['month'] == 9): ?>
              'Sep. <?= $purchase['year'] ?>',
            <?php elseif($purchase['month'] == 10): ?>
              'Oct. <?= $purchase['year'] ?>',
            <?php elseif($purchase['month'] == 11): ?>
              'Nov. <?= $purchase['year'] ?>',
            <?php elseif($purchase['month'] == 12): ?>
              'Déc. <?= $purchase['year'] ?>',
            <?php endif; ?>
          <?php endforeach ?>
			],
		datasets: [{
			label: '# Total achats',
			data: [
				<?php foreach($data['purchasesPerMonth'] as $purchase): ?>
						<?= $purchase['total'] ?>,
				<?php endforeach; ?>
			],
			backgroundColor: 'rgba(117, 73, 173, .1)',
      borderColor: '#7549ad',
			borderWidth: 1,
			hoverBorderWidth: 1,
			hoverBorderColor: '#7549ad'
		}]    
	},
	options: {
		title:{
			display: true,
			text: 'Total achats de marchandises par mois',
			fontSize: 18,
			fontColor: '#fff'
		},
		legend:{
			display: false,
			position: 'right',
			labels:{
				fontColor:'#fff'
			}
		}
	}
	});
</script>

<script>
	var ctx = document.getElementById('myChart4').getContext('2d');

	var myChart4 = new Chart(ctx, {
	type: 'doughnut', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
	data: {
		labels: [
          'Coût total des Prestations',
          'Coût total des Marchandises'
			],
		datasets: [{
			data: [
				<?= $data['sumPlanning']['total_presta'] ?>,
        <?= $data['sumPurchases']['total'] ?>
			],
			backgroundColor: [
        'rgba(0,123,255, .1)',
        'rgba(239, 83, 80, .1)',
      ],
      borderColor: [
        '#007bff',
        '#ef5350',
      ], 
			borderWidth: 1,
			hoverBorderWidth: 1,
			hoverBorderColor: '#7549ad'
		}]    
	},
	options: {
		title:{
			display: false,
			text: '',
			fontSize: 18,
			fontColor: '#fff'
		},
		legend:{
			display: true,
			position: 'right',
			labels:{
				fontColor:'#fff'
			}
		}
	}
	});
</script>



<?php require APPROOT . '/views/inc/admin-footer.php'; ?>      

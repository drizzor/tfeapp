<?php render('admin-header', ['subtitle' => '<span class="fas fa-tachometer-alt"></span> Général',
								'active' => 'home',
								'countRequest' => $this->userModel->countRequest(),
								'countIntruder' => $this->userModel->countIntruder()
								]); ?>
<div class="container-fluid">	
	<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 ">
		<div class="card card-body">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
				<h1 class="h2"><i style="color:#24caae;" class="fas fa-eye"></i> En un coup d'œil</h1>                
            </div>

			<?= flash('success') ?>
			
			<div class="container">				
				<div class="row">
					<div class="col-md-4">
						<div class="card-counter success">
							<i class="fa fa-file-invoice-dollar"></i>
							<span class="count-numbers"><?= number_format($data['T_purchases']['total_ht'], 2, ',', '.') ?> €</span>
							<span class="count-name">Achats en <?= date('Y') ?></span>
						</div>
					</div>
					<div class="col-md-4">
						<div class="card-counter mauve">
							<i class="fa fa-hand-holding-usd"></i>
							<span class="count-numbers"><?= number_format($data['T_sales']['total_ht'], 2, ',', '.') ?> €</span>
							<span class="count-name">Facturé en <?= date('Y') ?></span>
						</div>
					</div>

					<div class="col-md-4">
						<div class="card-counter primary">
							<i class="fas fa-building"></i>
							<span class="count-numbers"><?= $data['T_construct'] ?></span>
							<span class="count-name">Chantiers en cours</span>
						</div>
					</div>

					<div class="col-md-4">
						<div class="card-counter danger">
							<i class="far fa-building"></i>
							<span class="count-numbers"><?= $data['T_sold_construct'] ?></span>
							<span class="count-name">Chantiers vendus</span>
						</div>
					</div>
					
					<div class="col-md-4">
						<div class="card-counter orange">
							<i class="fas fa-balance-scale"></i>
							<span class="count-numbers"><?= number_format($data['T_profits'], 2, ',', '.') ?> €</span>
							<span class="count-name">Bénéfice vente bâtiment</span>
						</div>
					</div>	

					<div class="col-md-4">
						<div class="card-counter info">
							<i class="fa fa-users"></i>
							<span class="count-numbers"><?= $data['T_users'] ?></span>
							<span class="count-name">Utilisateurs</span>
						</div>
					</div>							
				</div>
				<br> <br> <canvas id="myChart" width="1000" height="400"></canvas> 			
			</div>								
		</div>
	</main>	
</div>

<div class="container-fluid">
  <main role="main" style="margin-right:1px;"  class="col-md-9 ml-sm-auto col-lg-10 pt-3 row">
      <div class="card card-body col-md-6">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
          <h1 class="h2"><i style="color:#24caae;" class="fa fa-hand-holding-usd"></i> Dernières factures ajoutées</h1>	
      </div>
			<table id="myTable" class="table table-striped table-hover">
				<?php if(count($data['invoices']) > 0): ?>
					<thead>
						<tr>
							<th>Ajouté</th>
							<th>Montant TVAC</th>
							<th>Client</th>
							<th class="noSort">Voir</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($data['invoices'] as $invoice): ?>
							<tr>
								<td><?= h($invoice['invoice_createdAt']) ?></td> 
								<td><?= number_format($invoice['total_tax'], 2, ',', '.') ?> €</td> 
								<td><?= h($invoice['customer_name']) ?></td> 
								<td>
									<a href="<?= URLROOT ?>/invoices/show/<?= $invoice['id_invoice'] ?>" target="_blank" title="Voir cette facture" class="btn btn-primary"><span class="fa fa-eye"></span></a>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				<?php else: ?>
					<h5><i class="fas fa-database"></i> Aucune facture enregistrée... Vous pouvez en encoder <a href="<?= URLROOT ?>/invoices/insert" target="_blank">ici</a></h5>
				<?php endif; ?>
			</table>
	  </div> 
	  
	  <div class="card card-body col-md-6">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
          <h1 class="h2"><i style="color:#24caae;" class="fa fa-file-pdf"></i> Derniers achats ajoutés</h1>	
      </div>
      <table id="myTable" class="table table-striped table-hover">
		<?php if(count($data['purchases']) > 0): ?>
			<thead>
				<tr>
					<th>Ajouté</th>
					<th>Montant TVAC</th>
					<th>Chantier</th>
					<th class="noSort">Voir</th>
				</tr>
			</thead>
			<tbody>			
				<?php foreach($data['purchases'] as $purchase): ?>
				<tr>
					<td><?= h($purchase['dateInsert']) ?></td> 
					<td><?= h(number_format($purchase['total_tax'], 2, ',', '.')) ?> €</td> 
					<td><?= h($purchase['constructions_name']) ?></td>
					<td>
						<a href="<?= URLROOT ?>/purchases/show/<?= $purchase['id_purchase'] ?>" target="_blank" title="Voir cet achat" class="btn btn-primary"><span class="fa fa-eye"></span></a>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
			<?php else: ?>
			<h5><i class="fas fa-database"></i> Aucun achat enregistré... Vous pouvez en encoder <a href="<?= URLROOT ?>/purchases/insert" target="_blank">ici</a></h5>
			<?php endif; ?>
		</table>
      </div> 
  </main>    
</div>

<script>
	var ctx = document.getElementById('myChart').getContext('2d');

	var myChart = new Chart(ctx, {
	type: 'line', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
	data: {
		labels: [
			<?php for($i = 0; $i < count($data['months']); $i++): ?>
				<?php if(!is_null($data['T_purchasesPerMonth'][$i]['total'])): ?>
					<?php if($data['months'][$i][1] == 1): ?>
						'Janvier <?= $data['months'][$i][2] ?>',
					<?php elseif($data['months'][$i][1] == 2): ?>
						'Février <?= $data['months'][$i][2] ?>',
					<?php elseif($data['months'][$i][1] == 3): ?>
						'Mars <?= $data['months'][$i][2] ?>',
					<?php elseif($data['months'][$i][1] == 4): ?>
						'Avril <?= $data['months'][$i][2] ?>',
					<?php elseif($data['months'][$i][1] == 5): ?>
						'Mai <?= $data['months'][$i][2] ?>',
					<?php elseif($data['months'][$i][1] == 6): ?>
						'Juin <?= $data['months'][$i][2] ?>',
					<?php elseif($data['months'][$i][1] == 7): ?>
						'Juillet <?= $data['months'][$i][2] ?>',
					<?php elseif($data['months'][$i][1] == 8): ?>
						'Aout <?= $data['months'][$i][2] ?>',
					<?php elseif($data['months'][$i][1] == 9): ?>
						'Septembre <?= $data['months'][$i][2] ?>',
					<?php elseif($data['months'][$i][1] == 10): ?>
						'Octobre <?= $data['months'][$i][2] ?>',
					<?php elseif($data['months'][$i][1] == 11): ?>
						'Novembre <?= $data['months'][$i][2] ?>',
					<?php elseif($data['months'][$i][1] == 12): ?>
						'Décembre <?= $data['months'][$i][2] ?>',
					<?php endif; ?>
				<?php endif; ?>
			<?php endfor; ?>
		],
		datasets: [{
			label: '# BSD par mois',
			data: [
				<?php foreach($data['T_purchasesPerMonth'] as $purchasePerMonth): ?>
					<?php if(!is_null($purchasePerMonth['total'])): ?>
						<?= $purchasePerMonth['total'] ?>,
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
			text: 'Achats totaux / mois',
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
		// scales: {
		// 	yAxes: [{
		// 		ticks: {
		// 			beginAtZero: true
		// 		}
		// 	}]
		// }
	}
	});
</script>

<?php require APPROOT . '/views/inc/admin-footer.php'; ?>
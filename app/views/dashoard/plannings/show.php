<?php render('admin-header', ['active' => 'planning',
								'countRequest' => $this->userModel->countRequest(),
								'countIntruder' => $this->userModel->countIntruder()
								]); ?>	   
<div class="container-fluid">	
	<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
		<div class="card card-body">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
				<h1 class="h2">Prestations sur <?= $data['construction']['name'] ?></h1>
				<div class="btn-toolbar mb-2 mb-md-0">
					<div class="btn-group mr-2">
						<a href="<?= URLROOT ?>/admin_plannings" class="btn btn-back btn-lg"><span class="fa fa-arrow-left"></span></a>
					</div>
				</div> 	     
			</div>
			<?= flash('success'); ?>
			<?= flash('fail'); ?>
			<table id="myTable" class="table table-striped table-hover dataTable">
				<thead>
					<tr>
						<th>id</th>
						<th>Ouvrier</th>
						<th>Prestation</th>
						<th>Taux horaire</th>
						<th>Total</th>
						<th class="noSort">Action</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($data['plannings'] as $planning): ?>
					<?php if($this->planningModel->refPrestaFromConstruct($data['construction']['id'], $planning['worker_id']) || !$this->workerModel->isOut($planning['worker_id'])[0]): ?>
						<tr>
							<td><?= (is_null($planning['planning_id'])) ? 0 : $planning['planning_id'] ?></td>
							<td><?= $planning['firstname'] .' '.  $planning['lastname'] ?></td>
							<td><?= (is_null($planning['planning_hour'])) ? '0,00' : number_format($planning['planning_hour'], 2, ',', '.') ?> h</td>
							<td><?= (is_null($planning['planning_salary'])) ? number_format($planning['worker_salary'], 2, ',', '.') : number_format($planning['planning_salary'], 2, ',', '.') ?> €</td>
							<td><?= number_format($planning['planning_total'], 2, ',', '.') ?> €</td>
							<td>
								<a href="#" title="Mettre à jour prestation de <?= $planning['lastname'] ?>" class="btn btn-secondary" data-toggle="modal" data-target="#modal-delete-<?= h($planning['worker_id']) ?>"><span class="fa fa-edit"></span></a>
							</td> 
						</tr>
					<?php endif; ?>		
				<?php endforeach; ?>					
				</tbody>
			</table>			
		</div>
	</main>
</div>	 

<div class="container-fluid">
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
      <div class="card card-body">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
          <h1 class="h2">En résumé</h1>	
	  	</div>
		<div class="row mb-5">
			<div class="col-md-3">
				<div class="card-counter mauve">
					<i class="fa fa-hourglass-start"></i>
					<span class="count-numbers"><?= number_format($data['sum']['total_hours'], 2, ',', '.') ?> h</span>
					<span class="count-name">Total heures prestées</span>
				</div>
			</div>

			<div class="col-md-3">
				<div class="card-counter orange">
					<i class="fa fa-euro-sign"></i>
					<span class="count-numbers"><?= number_format($data['sumAll']['total_presta'], 2, ',', '.') ?> €</span>
					<span class="count-name">Montant total prestations</span>
				</div>
			</div>		

			<div class="col-md-3">
				<div class="card-counter success">
					<i class="fa fa-euro-sign"></i>
					<span class="count-numbers"><?= ($data['count'] > 0)? number_format(($data['sum']['total_salary'] / $data['count']), 2, ',', '.'): '0,00' ?> €</span>
					<span class="count-name">Taux moyen</span>
				</div>
			</div>

			<div class="col-md-3">
				<div class="card-counter danger">
					<i class="fa fa-clock"></i>
					<span class="count-numbers"><?= ($data['count'] > 0)? number_format(($data['sum']['total_hours']/ $data['count']), 2, ',', '.'): '0,00' ?> h</span>
					<span class="count-name">Moyenne horaire</span>
				</div>
			</div>
		</div>  
		
	  	<canvas id="myChart" width="800" height="100"></canvas>
		  
      </div> 
  </main>    
</div>

<?php
//MODAL BOX DELETE
$id = explodeGET($_GET['url']);

foreach($data['plannings'] as $planning): 
render('modalbox', [
			'modalId' => 'modal-delete-'. h($planning['worker_id']),
			'actionLink' => URLROOT . '/admin_plannings/update/'.h($id[2]).'/'.h($planning['worker_id']),						
			'actionButton' => 'Modifier',
			'input_planning' => 1,
			'title' => "Prestation {$planning['lastname']} {$planning['firstname']}",
			'message' => " <i class='far fa-edit'></i> Editer la prestation",
			'alert' => 'warning',
			'btn' => 'warning',
			'hour' => is_null($planning['planning_hour'])? '0.00' : $planning['planning_hour'],
			'salary' => is_null($planning['planning_salary'])? $planning['worker_salary'] : $planning['planning_salary']
]); 
endforeach;		
?>	


<script>
	var ctx = document.getElementById('myChart').getContext('2d');

	var myChart = new Chart(ctx, {
	type: 'horizontalBar', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
	data: {
		labels: [
			<?php foreach($data['plannings'] as $planning): ?>
				<?php if($planning['planning_total'] > 0): ?>
					'<?= $planning['firstname'] .' '. $planning['lastname'] ?>',
				<?php endif; ?>
			<?php endforeach; ?>
			],
		datasets: [{
			label: '# montants par ouvrier',
			data: [
				<?php foreach($data['plannings'] as $planning): ?>
					<?php if($planning['planning_total'] > 0): ?>
						<?= $planning['planning_total'] ?>,
					<?php endif; ?>
				<?php endforeach; ?>
			],
			backgroundColor: /*'rgba(35, 202, 177, .8)',*/[
				<?php for($i = 0; $i < count($data['plannings']); $i++): ?>
					'rgba(<?= rand(0,255) ?>, <?= rand(0,255) ?>, <?= rand(0,255) ?>, .5)',
				<?php endfor; ?>
			],
			borderColor: [
				
			],
			borderWidth: 1,
			hoverBorderWidth: 1,
			hoverBorderColor: '#22252a'
		}]
	},
	options: {
		title:{
			display: true,
			text: 'Coût total sur le chantier',
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
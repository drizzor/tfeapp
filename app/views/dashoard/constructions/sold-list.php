<?php render('admin-header', ['active' => 'constructions',
								'countRequest' => $this->userModel->countRequest(),
								'countIntruder' => $this->userModel->countIntruder()
								]); ?>

<div class="container-fluid">	
	<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
		<div class="card card-body">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
				<h1 class="h2">Chantiers vendus</h1>		 
	            <div class="btn-toolbar mb-2 mb-md-0">
	              <div class="btn-group mr-2">
	              	<a href="<?= URLROOT ?>/admin_constructions" class="btn btn-info btn-lg"><span class="fas fa-wrench"></span> En cours <?= ($this->constructionModel->countOnGoingList() > 0)? '<span class="badge badge-danger ml-1">'. $this->constructionModel->countOnGoingList() . '</span>' : '' ?></a> 
	              </div>
	            </div>
			</div>
			<?= flash('insert_success'); ?>						
			<?= flash('update_success'); ?>
			<?= flash('sold_success'); ?>
			<?= flash('delete_fail') ?>
			<?= flash('delete_success') ?> 

			<table id="myTable" class="table table-striped table-hover dataTable">
				<thead>
					<tr>
						<th>ID</th>
						<th>Nom</th>
						<th>Ville</th>
						<th>Prix acquisition</th>
						<th>Prix vente</th>
						<th> Date d'achat</th>
						<th class="noSort">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($data['constructions'] as $construction): ?>	
						<tr>
							<td><?= h($construction['constructions_id']) ?></td>
							<td><?= h($construction['constructions_name']) ?></td>
							<td><?= h($construction['cities_name']) ?></td>
							<td><?= h(number_format($construction['buyingPrice'], 2, ',', '.')) ?> €</td>
							<td><?= h(number_format($construction['sold_price'], 2, ',', '.')) ?> €</td>
							<td><?= h($construction['buyingDate']) ?></td>
							<td>
								<a href="<?= URLROOT ?>/admin_constructions/show/<?= h($construction['constructions_id']) ?>"  title="Voir <?= h($construction['constructions_name']) ?>" class="btn btn-primary"><span class="fa fa-eye"></span></a>
								<?php if($data['level_name'] === 'Admin'): ?>
									<a href="#" title="Annuler vente de <?= h($construction['constructions_name']) ?>" class="btn btn-cancel"><span class="fa fa-undo-alt" data-toggle="modal" data-target="#modal-cancelSold-<?= $construction['constructions_id'] ?>"></span></a>
									<a href="#" title="Supprimer <?= h($construction['constructions_name']) ?>" class="btn btn-danger" data-toggle="modal" data-target="#modal-delete-<?= $construction['constructions_id'] ?>"><span class="fa fa-trash"></span></a>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</main>
</div>

<?php 
//MODAL BOX SOLD
foreach($data['constructions'] as $construction): 
render('modalbox', [
			'modalId' => 'modal-cancelSold-'. h($construction['constructions_id']),
			'actionLink' => URLROOT . '/admin_constructions/cancelSold/'. h($construction['constructions_id']),						
			'actionButton' => 'Confirmer',
			'btn' => 'warning',
			'title' => "Déclarer vendu {$construction['constructions_name']}",
			'message' => " Annuler la déclaration de vente?",
			'alert' => 'warning'
]); 
endforeach;					
?>	

<?php 
//MODAL BOX DELETE
foreach($data['constructions'] as $construction): 
render('modalbox', [
			'modalId' => 'modal-delete-'. h($construction['constructions_id']),
			'actionLink' => URLROOT . '/admin_constructions/delete/'. h($construction['constructions_id']),						
			'actionButton' => 'Supprimer',
			'title' => "Supprimer {$construction['constructions_name']}",
			'message' => " Cette suppression entrainera la perte <b>totale</b> de toutes les informations liées au chantier ciblé. Confirmer?"
]); 
endforeach;					
?>	
<?php require APPROOT . '/views/inc/admin-footer.php'; ?>
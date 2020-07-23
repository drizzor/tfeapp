<?php render('admin-header', ['active' => 'constructions',
								'countRequest' => $this->userModel->countRequest(),
								'countIntruder' => $this->userModel->countIntruder()
								]); ?>	 
<div class="container-fluid">	
		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
			<div class="card card-body">
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
					<h1 class="h2">Chantiers en cours</h1>	
	            <div class="btn-toolbar mb-2 mb-md-0">
	              <div class="btn-group mr-2">
						<?php if($data['level_name'] === 'Admin'): ?>
	              			<a href="<?= URLROOT ?>/admin_constructions/insert" class="btn btn-success btn-lg"><span class="fa fa-plus"></span> Ajouter</a>
						<?php endif; ?>
						<a href="<?= URLROOT ?>/admin_constructions/sold_list" class="btn btn-info btn-lg"><span class="fas fa-wallet"></span> Vendus <?= ($this->constructionModel->countSoldList() > 0)? '<span class="badge badge-danger ml-1">'. $this->constructionModel->countSoldList() . '</span>' : '' ?></a> 
	              </div>
	            </div>
			</div>
			<?= flash('insert_success'); ?>
			<?= flash('update_success'); ?>
			<?= flash('sold_success') ?>
			<?= flash('delete_fail') ?>
			<?= flash('sold_fail') ?>
			<?= flash('delete_success') ?> 
			<table id="myTable" class="table table-striped table-hover dataTable_responsive">
				<thead>
					<tr>
						<th>ID</th>
						<th>Nom</th>
						<th>Ville</th>
						<th>Prix acquisition</th>
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
							<td><?= h($construction['buyingDate']) ?></td>
							<td>
								<a href="<?= URLROOT ?>/admin_constructions/show/<?= h($construction['constructions_id']) ?>"  title="Voir <?= h($construction['constructions_name']) ?>" class="btn btn-primary"><span class="fa fa-eye"></span></a>
								<?php if($data['level_name'] === 'Admin'): ?>
									<a href="<?= URLROOT ?>/admin_constructions/update/<?= h($construction['constructions_id']) ?>"  title="Modifier <?= h($construction['constructions_name']) ?>" class="btn btn-secondary"><span class="fas fa-edit"></span></a>
									<a href="#"  title="Vendre <?= h($construction['constructions_name']) ?>" class="btn btn-warning" data-toggle="modal" data-target="#modal-sold-<?= h($construction['constructions_id']) ?>"><span class="fa fa-euro-sign"></span></a>		              				
									<?php if(($this->constructionModel->refPurchases($construction['constructions_id']) == 0) && ($this->constructionModel->refGalleries($construction['constructions_id'])) == 0): ?>
										<a href="#" title="Supprimer <?= h($construction['constructions_name']) ?>" class="btn btn-danger" data-toggle="modal" data-target="#modal-delete-<?= h($construction['constructions_id']) ?>"><span class="fa fa-trash"></span></a>														
									<?php else: ?>
										<a href="#" class="btn btn-danger disabled"><span class="fa fa-trash"></span></a>
									<?php endif; ?>
								<?php endif; ?>	
							</td>
						</tr>
					<?php	endforeach; ?>
				</tbody>
			</table>
		</div>
	</main>
</div>
<?php 
//MODAL BOX DELETE
foreach($data['constructions'] as $construction): 
render('modalbox', [
			'modalId' => 'modal-delete-'. h($construction['constructions_id']),
			'actionLink' => URLROOT . '/admin_constructions/delete/'. h($construction['constructions_id']),						
			'actionButton' => 'Supprimer',
			'title' => "Supprimer {$construction['constructions_name']}",
			'message' => " L'action de suppression est <b>irrévocable</b>! Valider l'action?"
]); 
endforeach;					
?>	

<?php 
//MODAL BOX SOLD
foreach($data['constructions'] as $construction): 
render('modalbox', [
			'modalId' => 'modal-sold-'. h($construction['constructions_id']),
			'actionLink' => URLROOT . '/admin_constructions/sold/'. h($construction['constructions_id']),						
			'actionButton' => 'Vendre',
			'btn' => 'warning',
			'title' => "Déclarer vendu {$construction['constructions_name']}",
			'input_sold' => 1,
			'message' => " Confirmer la déclaration de vente?",
			'alert' => 'warning'
]); 
endforeach;					
?>	
<?php require APPROOT . '/views/inc/admin-footer.php'; ?>

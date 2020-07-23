<?php render('admin-header', ['active' => 'workers',
								'countRequest' => $this->userModel->countRequest(),
								'countIntruder' => $this->userModel->countIntruder()
								]); ?>	   
<div class="container-fluid">	
	<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
		<div class="card card-body">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
				<h1 class="h2">Ouvriers</h1>	     
				<div class="btn-toolbar mb-2 mb-md-0">
					<div class="btn-group mr-2">
						<a href="<?= URLROOT ?>/admin_workers/insert" class="btn btn-success btn-lg"><span class="fa fa-user-plus"></span> Ajouter</a>
					</div>
				</div>
			</div>
			<?= flash('success'); ?>
			<?= flash('fail'); ?>

			<ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="in-tab" data-toggle="tab" href="#in">Actif</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="out-tab" data-toggle="tab" href="#out">Inactif</a>
                </li>
            </ul>
			<div class="tab-content" id="myTabContent">
				<div class="tab-pane fade show active" id="in">
					<table id="myTable" class="table table-striped table-hover dataTable">
						<thead>
							<tr>
								<th>id</th>
								<th>Nom</th>
								<th>Taux horaire</th>
								<th>Email</th>
								<th class="noSort">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($data['workers'] as $worker): ?>
								<?php if(!$this->workerModel->isOut($worker['id'])[0]): ?>
									<tr>
										<td><?= $worker['id'] ?></td>
										<td><?= h($worker['firstname']) . ' '. h($worker['lastname']) ?></td>
										<td><?= h(number_format($worker['salary'], 2, ',', '.')) ?> €</td>
										<td><?= h($worker['email']) ?></td>
										<td>						
											<a href="<?= URLROOT ?>/admin_workers/update/<?= $worker['id'] ?>" class="btn btn-secondary" title="Modifier <?= $worker['lastname'] .' '. $worker['firstname'] ?>"><span class="fa fa-user-md"></span></a>	
											<a href="<?= URLROOT ?>/admin_workers/out/<?= $worker['id'] ?>" class="btn btn-warning" title="Déclarer sortie <?= $worker['lastname'] .' '. $worker['firstname'] ?>"><span class="fa fa-sign-out-alt"></span></a>		
											<?php if(!$this->planningModel->refPresta($worker['id'])): ?>										
												<a href="#" class="btn btn-danger" title="Supprimer <?= $worker['lastname'] .' '. $worker['firstname'] ?>" data-toggle="modal" data-target="#modal-delete-<?= $worker['id'] ?>"><span class="fa fa-user-times"></span></a>
											<?php else: ?>
												<a href="#" class="btn btn-danger disabled"><span class="fa fa-user-times"></span></a>
											<?php endif; ?>
										</td> 
									</tr>
								<?php endif; ?>
							<?php endforeach; ?>							
						</tbody>
					</table>
				</div>
				<div class="tab-pane fade show" id="out">
					<table id="myTable" class="table table-striped table-hover dataTable" width="100%">
						<thead>
							<tr>
								<th>id</th>
								<th>Nom</th>
								<th>Taux horaire</th>
								<th>Email</th>
								<th class="noSort">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($data['workers'] as $worker): ?>
								<?php if($this->workerModel->isOut($worker['id'])[0]): ?>
									<tr>
										<td><?= $worker['id'] ?></td>
										<td><?= h($worker['firstname']) . ' '. h($worker['lastname']) ?></td>
										<td><?= h(number_format($worker['salary'], 2, ',', '.')) ?> €</td>
										<td><?= h($worker['email']) ?></td>
										<td>																	
											<a href="<?= URLROOT ?>/admin_workers/out/<?= $worker['id'] ?>" class="btn btn-warning" title="Déclarer sortie <?= $worker['lastname'] .' '. $worker['firstname'] ?>"><span class="fa fa-undo-alt"></span></a>
											<?php if(!$this->planningModel->refPresta($worker['id'])): ?>										
												<a href="#" class="btn btn-danger" title="Supprimer <?= $worker['lastname'] .' '. $worker['firstname'] ?>" data-toggle="modal" data-target="#modal-delete-<?= $worker['id'] ?>"><span class="fa fa-user-times"></span></a>
											<?php else: ?>
												<a href="#" class="btn btn-danger disabled"><span class="fa fa-user-times"></span></a>
											<?php endif; ?>
										</td> 
									</tr>
								<?php endif; ?>
							<?php endforeach; ?>							
						</tbody>
					</table>
				</div>	
			</div>	
		</div>
	</main>
</div>	        

<?php
//MODAL BOX DELETE
foreach($data['workers'] as $worker): 
render('modalbox', [
			'modalId' => 'modal-delete-'. h($worker['id']),
			'actionLink' => URLROOT . '/admin_workers/delete/'. h($worker['id']),						
			'actionButton' => 'Supprimer',
			'title' => "Supprimer {$worker['lastname']}",
			'message' => " L'action de suppression est <b>irrévocable</b>! Valider l'action?"
]); 
endforeach;					
?>	

<?php require APPROOT . '/views/inc/admin-footer.php'; ?>
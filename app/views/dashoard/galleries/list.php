
<?php render('admin-header', ['active' => 'galleries',
								'countRequest' => $this->userModel->countRequest(),
								'countIntruder' => $this->userModel->countIntruder()
								]); ?>	

<div class="container-fluid">	
	<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
		<div class="card card-body">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
				<h1 class="h2">Gérer les galeries</h1>														
				<div class="btn-toolbar mb-2 mb-md-0">
					<div class="btn-group mr-2">
						<a href="<?= URLROOT ?>/admin_galleries/insert" class="btn btn-success btn-lg" title="Uploader des images"><span class="fas fa-upload"></span></a>
					</div>
				</div>
			</div>
			<?= flash('success') ?>
			<?= flash('fail') ?>
			<table id="myTable" class="table table-striped table-hover table-responsive dataTable">
				<thead>
					<tr>
						<th>ID</th>
						<th class="noSort">Couverture</th>
						<th>Chantier</th>
						<th>Nombre d'image</th>
                        <th>Espace total</th>
						<th class="noSort">Actions</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($data['galleries'] as $gallery): ?>
					<tr>
						<td><?= h($gallery['construction_id']) ?></td>
                        <td width="5%"><img style="width:135px;" src="<?= URLROOT ?>/images/constructions/<?= $gallery['construction_cover'] ?>" alt="cover"></td> 
						<td><?= h($gallery['construction_name']) ?></td> 
                        <td><?= $this->galleryModel->references($gallery['construction_id']) ?></td>
						<td><?= number_format((($this->galleryModel->sumSize($gallery['construction_id'])[0]) / 1000000), 2, '.', ',') ?>  Mo</td>
						<td>
							<a href="<?= URLROOT ?>/admin_galleries/show/<?= $gallery['construction_id'] ?>" title="Consulter la galerie de <?= $gallery['construction_name'] ?>" class="btn btn-primary"><span class="fa fa-eye"></span></a>
                            <a href="#" title="Supprimer entièrement la galerie de <?= $gallery['construction_name'] ?>" class="btn btn-danger" data-toggle="modal" data-target="#modal-delete-<?= $gallery['construction_id'] ?>"><span class="fa fa-trash"></span></a>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>   
	</main>
</div>

<?php 
//MODAL BOX SYSTEM
foreach($data['galleries'] as $gallery): 
render('modalbox', [
			'modalId' => 'modal-delete-'. $gallery['construction_id'],
			'actionLink' => URLROOT . '/admin_galleries/deleteG/'. $gallery['construction_id'],						
			'actionButton' => 'Supprimer',
			'title' => "Supprimer {$gallery['construction_name']}",
			'message' => " L'action de suppression est <b>irrévocable</b> et supprimera <b>entièrement</b> la galerie ciblée! Valider l'action?"
]); 
endforeach;					
?>	

<?php require APPROOT . '/views/inc/admin-footer.php'; ?>
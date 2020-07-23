<?php render('admin-header', ['active' => 'categories',
								'countRequest' => $this->userModel->countRequest(),
								'countIntruder' => $this->userModel->countIntruder()
								]); ?>	

<div class="container-fluid">	
	<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
		<div class="card card-body">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
				<h1 class="h2">Catégories d'achats</h1>														
				<div class="btn-toolbar mb-2 mb-md-0">
					<div class="btn-group mr-2">
						<?php if($data['level_name'] === 'Admin'): ?>
						<a href="<?= URLROOT ?>/admin_categories/insert" class="btn btn-success btn-lg"><span class="fa fa-plus"></span> Ajouter</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?= flash('insert_success') ?>
			<?= flash('update_success') ?>
			<?= flash('delete_fail') ?>
			<?= flash('delete_success') ?>
			<table id="myTable" class="table table-striped table-hover dataTable">
				<thead>
					<tr>
						<th>ID</th>
						<th>Nom</th>
						<th>Desciptif</th>
						<th>Références</th>
						<th class="noSort">Actions</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($data['categories'] as $category): ?>
					<tr>
						<td><?= h($category['id']) ?></td>
						<td><?= h($category['name']) ?></td> 
						<td><?= substr($category['description'],0,25); if(mb_strlen($category['description']) > 25) echo " (...)"; ?></td> 
						<td><?= $this->categoryModel->references($category['id']) ?></td>
						<td>
							<a href="<?= URLROOT ?>/admin_categories/show/<?= $category['id'] ?>" title="Voir <?= $category['name'] ?>" class="btn btn-primary"><span class="fa fa-eye"></span></a>
							<?php if($data['level_name'] === 'Admin'): ?>
								<a href="<?= URLROOT ?>/admin_categories/update/<?= $category['id'] ?>" title="Modifier <?= $category['name'] ?>" class="btn btn-secondary"><span class="fa fa-edit"></span></a>
								<?php if($this->categoryModel->references($category['id']) == 0): ?>
								<a href="#" title="Supprimer <?= $category['name'] ?>" class="btn btn-danger" data-toggle="modal" data-target="#modal-delete-<?= $category['id'] ?>"><span class="fa fa-trash"></span></a>
								<?php else: ?>
								<a href="#" class="btn btn-danger disabled"><span class="fa fa-trash"></span></a>
								<?php endif; ?>
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
//MODAL BOX SYSTEM
foreach($data['categories'] as $category): 
render('modalbox', [
			'modalId' => 'modal-delete-'. $category['id'],
			'actionLink' => URLROOT . '/admin_categories/delete/'. $category['id'],						
			'actionButton' => 'Supprimer',
			'title' => "Supprimer {$category['name']}",
			'message' => " L'action de suppression est <b>irrévocable</b>! Valider l'action?"
]); 
endforeach;					
?>	

<?php require APPROOT . '/views/inc/admin-footer.php'; ?>
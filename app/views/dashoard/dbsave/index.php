<?php render('admin-header', ['active' => 'database',
								'countRequest' => $this->userModel->countRequest(),
								'countIntruder' => $this->userModel->countIntruder()
								]); ?>	   
<div class="container-fluid">	
	<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
		<?= flash('success'); ?>
		<?= flash('fail'); ?>

		<div class="card card-body">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
				<h1 class="h2">Liste des sauvegardes</h1>	     
			</div>	
			<div class="col-md-3 mb-2">
				<a href="<?= URLROOT ?>/admin_dump/save" class="btn btn-success btn-lg"><span class="fa fa-paper-plane"></span> Executer une sauvegarde</a>
			</div> <br>
			<p style="color:#d24e44"><i>Par mesure de sécurité, assurez-vous de récupérer les fichiers SQL sur un autre support.</i></p>		
			<table id="myTable" class="table table-striped table-hover dataTable">
				<thead>
					<tr>
						<th>id</th>							
						<th>Nom du fichier</th>
						<th>Date</th>
						<th class="noSort">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($data['saves'] as $save): ?>
						<tr>
							<td><?= $save['id'] ?></td>
							<td><?= $save['filename'] ?></td>
							<td><?= $save['dateInsert'] ?></td>
							<td>
								<a href="<?= URLROOT ?>/DB_SAVE/<?= $save['filename'] ?>" class="btn btn-primary" title="Récupérer fichier SQL"><span class="fa fa-download"></span></a>
								<a href="#" title="Supprimer cette sauvegarde" class="btn btn-danger" data-toggle="modal" data-target="#modal-delete-<?= $save['id'] ?>"><span class="fa fa-trash"></span></a>
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
foreach($data['saves'] as $save): 
render('modalbox', [
		'modalId' => 'modal-delete-'. $save['id'],
		'actionLink' => URLROOT . '/admin_dump/delete/'. $save['id'],						
		'actionButton' => 'Supprimer',
		'title' => "Confirmer suppression",
		'message' => " L'action de suppression est <b>irrévocable</b>! Valider l'action?"
]); 
endforeach;					
?>	


<?php require APPROOT . '/views/inc/admin-footer.php'; ?>
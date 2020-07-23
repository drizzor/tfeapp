<?php render('admin-header', ['active' => 'galleries',
								'countRequest' => $this->userModel->countRequest(),
								'countIntruder' => $this->userModel->countIntruder()
								]); ?>	

<div class="container-fluid">	
	<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
		<div class="card card-body">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
				<h1 class="h2">Gérer les images de <?= $data['galleries'][0]['construction_name'] ?></h1>														
				<div class="btn-toolbar mb-2 mb-md-0">
					<div class="btn-group mr-2">
						<a href="<?= URLROOT ?>/admin_galleries/insert" class="btn btn-success btn-lg" title="Uploader des images"><span class="fas fa-upload"></span></a>
                        <a href="<?= URLROOT ?>/admin_galleries" class="btn btn-back btn-lg"><span class="fa fa-arrow-left"></span></a>
                    </div>
				</div>
			</div>
			<?= flash('success') ?>
			<?= flash('fail') ?>
            <?= flash('title_fail') ?>
            <?= flash('description_fail') ?>
			<table id="myTable" class="table table-striped table-hover table-responsive dataTable">
				<thead>
					<tr>
						<th>ID</th>
						<th class="noSort">Image</th>
						<th>Titre</th>
						<th>Commentaire</th>
                        <th>Type</th>
                        <th>Taille</th>
						<th class="noSort">Actions</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($data['galleries'] as $gallery): ?>
					<tr>
						<td class=""><?= h($gallery['gallery_id']) ?></td>
                        <td width="5%"><img style="width:135px;" src="<?= URLROOT ?>/images/gallery/<?= $gallery['image'] ?>" alt="cover"></td> 
						<td><?= empty($gallery['title'])? 'N/A' : $gallery['title'] ?></td> 
                        <td><?= empty($gallery['gallery_comment'])? 'N/A' : substr(h($gallery['gallery_comment']),0,30); if(mb_strlen($gallery['gallery_comment']) > 30) echo " (...)"; ?></td>
						<td><?= h($gallery['type']) ?></td>
                        <td><?= number_format((($gallery['size']) / 1000000), 2, '.', ',') ?>  Mo</td>
						<td>
							<a href="#" title="Editer info image <?= $gallery['image'] ?>" class="btn btn-secondary" data-toggle="modal" data-target="#modal-update-<?= $gallery['gallery_id'] ?>"><span class="fa fa-edit"></span></a>
                            <a href="#" title="Supprimer l'image <?= $gallery['image'] ?>" class="btn btn-danger" data-toggle="modal" data-target="#modal-delete-<?= $gallery['gallery_id'] ?>"><span class="fa fa-trash"></span></a>
						</td>
					</tr>
				<?php endforeach; ?>
				</tbody>
			</table>
		</div>   
	</main>
</div>


<?php 
//MODAL BOX SYSTEM DELETE
foreach($data['galleries'] as $gallery): 
render('modalbox', [
			'modalId' => 'modal-delete-'. $gallery['gallery_id'],
			'actionLink' => URLROOT . '/admin_galleries/deleteI/'. $gallery['gallery_id'] . '/' . $gallery['construction_id'],						
			'actionButton' => 'Supprimer',
			'title' => "Supprimer {$gallery['image']}",
			'message' => " <i class='fas fa-exclamation-circle'></i> L'action de suppression est <b>irrévocable</b> ! Valider l'action?"
]); 
endforeach;					
?>	

<?php 
//MODAL BOX SYSTEM UPDATE
foreach($data['galleries'] as $gallery): 
render('modalbox', [
			'modalId' => 'modal-update-'. $gallery['gallery_id'],
			'actionLink' => URLROOT . '/admin_galleries/update/'. $gallery['gallery_id'] . '/' . $gallery['construction_id'],						
            'actionButton' => 'Modifier',
            'input_gallery' => 1,
			'title' => "Editer " . $gallery['image'],
            'message' => " <i class='far fa-edit'></i> Editer info image",
            'alert' => 'warning',
			'btn' => 'warning',
			'g_title' =>  $gallery['title'],
			'description' => $gallery['gallery_comment'],
			'isCheck' => ($gallery['inProgress'] == 0) ? '' : 'checked'
]); 
endforeach;					
?>	

<?php require APPROOT . '/views/inc/admin-footer.php'; ?>
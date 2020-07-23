<?php render('admin-header', ['active' => 'galleries_up',
								'countRequest' => $this->userModel->countRequest(),
								'countIntruder' => $this->userModel->countIntruder()
								]); ?>
								
<div class="container-fluid">	
	<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
		<div class="card card-body">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
				<h1 class="h2">Ajouter une image</h1>	
				<div class="btn-toolbar mb-2 mb-md-0">
					<div class="btn-group mr-2">
						<a href="<?= URLROOT ?>/admin_galleries" class="btn btn-success btn-lg" title="Galerie d'images"><span class="fas fa-images"></span></a>
					</div>
				</div>
			</div>
			<?= flash('success'); ?>
			<?= flash('fail'); ?>
			<?= flash('no_gallery') ?>

			<form class="form" role="form" method="post" enctype="multipart/form-data">
				<div class="row">
					<div class="col-md-2" style="text-align: center;">			
						<div class="form-group col-md-12">
							<label style="padding:18px 35px;" title="Image à uploader" class="control-label btn btn-info" for="image"><i class="fas fa-upload fa-5x"></i> <br><br> UPLOAD IMG<sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
							<input type="file" id="image" name="image" class="form-control-file"><br>
							<?php if(!empty($data['error_upload'])): ?> 
								<span style="color: #d2595a;"><?= $data['error_upload']['image'] ?></span>
							<?php endif; ?> 
						</div>
					</div>
				</div>				

				<div class="col-md-9 mt-3">
					<div class="form-group">
						<label for="construction">Sélection chantier <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
						<select class="form-control form-control-lg" id="construction" name="construction">
							<?php foreach($data['constructions'] as $construction): ?>
							<?php if($construction['id'] == $data['construction_id']): ?>
							<option selected="selected" value="<?= $construction['id'] ?>"><?= $construction['name'] ?></option>
							<?php else: ?>
							<option value="<?= $construction['id'] ?>"><?= $construction['name'] ?></option>
							<?php endif; ?>
							<?php endforeach; ?>
						</select>
						<?php if(!empty($data['errors']['construction'])): ?> 
							<span style="color: #d2595a;"><?= $data['errors']['construction'] ?></span>
						<?php endif; ?>
					</div>
				</div>

				<div class="col-md-9">
					<div class="form-group">
						<label for="title">Titre</label>
						<input type="text" class="form-control form-control-lg <?= (isset($data['errors']['title']) && !empty($data['errors']['title'])) ? 'is-invalid' : '' ?>" id="title" name="title" placeholder="Cet ajout est facultatif" value="<?= $data['title'] ?>">
						<span class="invalid-feedback"><?= isset($data['errors']['title'])? $data['errors']['title'] : '' ?></span>
					</div>
				</div>

				<div class="col-md-9">
					<div class="form-group">
						<label for="description">Description</label>
						<textarea type="text" class="form-control form-control-lg <?= (isset($data['errors']['description']) && !empty($data['errors']['description'])) ? 'is-invalid' : '' ?>" id="description" name="description" placeholder="Un petit commentaire à ajouter?"><?= $data['description'] ?></textarea>
						<span class="invalid-feedback"><?= isset($data['errors']['description'])? $data['errors']['description'] : '' ?></span>    
					</div>
				</div>

				<div class="form-group form-check">
					<label class="checkboxContainer">En cours de rénovation?
						<input type="checkbox" name="inProgress" class="form-check-input" id="inProgress" <?= $data['isCheck'] ?>>
						<span class="checkmark"></span>
					</label>
				</div>
				
				<br>

				<div class="form-actions">
					<button type="submit" class="btn btn-success btn-lg"><span class="fa fa-check"></span> Valider</button>
				</div>
				<br>
				<p><i><sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup> Champs obligatoires !</i></p>
			</form>
		</div>
	</main>
</div>
<?php require APPROOT . '/views/inc/admin-footer.php'; ?>
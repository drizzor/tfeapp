<?php render('admin-header', ['active' => 'categories',
								'countRequest' => $this->userModel->countRequest(),
								'countIntruder' => $this->userModel->countIntruder()
								]); ?>
								
<div class="container-fluid">	
	<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
		<div class="card card-body">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
				<h1 class="h2">Ajouter une catégorie</h1>	
				<div class="btn-toolbar mb-2 mb-md-0">
					<div class="btn-group mr-2">
						<a href="<?= URLROOT ?>/admin_categories" class="btn btn-back btn-lg"><span class="fa fa-arrow-left"></span></a>
					</div>
				</div>
			</div>
			<div class="col-sm-12">
				<form class="form" action="<?= URLROOT ?>/admin_categories/insert" role="form" method="post">
					<div class="col-md-6">
						<div class="form-group">
							<label for="name">Nom <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
							<input type="text" class="form-control form-control-lg <?= (isset($data['errors']['name']) && !empty($data['errors']['name'])) ? 'is-invalid' : '' ?>" id="name" name="name" placeholder="Nom de la catégorie..." value="<?= $data['name'] ?>" required autofocus>
							<span class="invalid-feedback"><?= isset($data['errors']['name'])? $data['errors']['name'] : '' ?></span>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="description">Descriptif</label>
							<input type="text"  maxlength="255" class="form-control form-control-lg <?= (isset($data['errors']['description']) && !empty($data['errors']['description'])) ? 'is-invalid' : '' ?>" id="description" name="description" placeholder="Une courte description..." value="<?= $data['description'] ?>">
							<span class="invalid-feedback"><?= isset($data['errors']['description'])? $data['errors']['description'] : '' ?></span>
						</div>
					</div>
					<br>
					<div class="form-actions">
						<button type="submit" class="btn btn-success btn-lg"><span class="fa fa-check"></span> Valider</button>
					</div>
					<br>
					<p><i><sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup> Champs obligatoires !</i></p>
				</form>
			</div>
		</div>	
	</main>
</div>
<?php require APPROOT . '/views/inc/admin-footer.php'; ?>
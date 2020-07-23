<?php render('admin-header', ['active' => 'suppliers',
								'countRequest' => $this->userModel->countRequest(),
								'countIntruder' => $this->userModel->countIntruder()
								]); ?>

<div class="container-fluid">	
	<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
		<div class="card card-body">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
				<h1 class="h2">Modifier le fournisseur</h1>	
				<div class="btn-toolbar mb-2 mb-md-0">
					<div class="btn-group mr-2">
						<a href="<?= URLROOT ?>/admin_suppliers" class="btn btn-back btn-lg"><span class="fa fa-arrow-left"></span></a>
					</div>
				</div>
			</div>
			<form class="form" action="" role="form" method="post">
				<fieldset class="cheduler-border row">
					<legend class="scheduler-border">Info fournisseur</legend>
					<div class="col-md-12">
						<div class="form-group">
							<label for="name">Nom <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
							<input type="text" class="form-control form-control-lg <?= (isset($data['errors']['name']) && !empty($data['errors']['name'])) ? 'is-invalid' : '' ?>" id="name" name="name" placeholder="Nom du fournisseur" value="<?= $data['name'] ?>" autofocus >
							<span class="invalid-feedback"><?= isset($data['errors']['name'])? $data['errors']['name'] : '' ?></span>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label for="city">Ville <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
							<input type="text" class="form-control dropdownField form-control-lg <?= (isset($data['errors']['city']) && !empty($data['errors']['city'])) ? 'is-invalid' : '' ?>" id="searchBox" name="city" placeholder="Indiquer ville ou code postal" autocomplete="off" value="<?= $data['city'] ?>" >
							<span class="invalid-feedback"><?= isset($data['errors']['city'])? $data['errors']['city'] : '' ?></span>
							<div id="response"></div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label for="address">Adresse</label>
							<input type="text" class="form-control form-control-lg <?= (isset($data['errors']['address']) && !empty($data['errors']['address'])) ? 'is-invalid' : '' ?>" id="address" name="address" placeholder="L'adresse complète" value="<?= $data['address'] ?>">
							<span class="invalid-feedback"><?= isset($data['errors']['address'])? $data['errors']['address'] : '' ?></span>
						</div>
					</div>
				</fieldset>

				<fieldset class="cheduler-border row">
					<legend class="scheduler-border">Personne de contact</legend>
					<div class="col-md-6">
						<div class="form-group">
							<label for="contactName">Nom du contact</label>
							<input type="text" class="form-control form-control-lg <?= (isset($data['errors']['contactName']) && !empty($data['errors']['contactName'])) ? 'is-invalid' : '' ?>" id="contactName" name="contactName" placeholder="Nom de la personne de contact" value="<?= $data['contactName'] ?>">
							<span class="invalid-feedback"><?= isset($data['errors']['contactName'])? $data['errors']['contactName'] : '' ?></span>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label for="phone">Téléphone</label>
							<input type="text" class="form-control form-control-lg <?= (isset($data['errors']['phone']) && !empty($data['errors']['phone'])) ? 'is-invalid' : '' ?>" id="phone" name="phone" placeholder="Téléphone ou GSM" value="<?= $data['phone'] ?>">
							<span class="invalid-feedback"><?= isset($data['errors']['phone'])? $data['errors']['phone'] : '' ?></span>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="email">Email</label>
							<input type="email" class="form-control form-control-lg <?= (isset($data['errors']['email']) && !empty($data['errors']['email'])) ? 'is-invalid' : '' ?>" id="email" name="email" placeholder="Email" value="<?= $data['email'] ?>">
							<span class="invalid-feedback"><?= isset($data['errors']['email'])? $data['errors']['email'] : '' ?></span>
						</div>
					</div>
				</fieldset>
					
				<br>

				<div class="form-actions">
					<button type="submit" class="btn btn-success btn-lg"><span class="fa fa-pencil-alt"></span> Modifier</button>
				</div>

				<br>

				<p><i><sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup> Champs obligatoires !</i></p>			
			</form> 
		</div>
	</main>
</div>
						
<?php require APPROOT . '/views/inc/admin-footer.php'; ?>
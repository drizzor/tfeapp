<?php render('admin-header', ['active' => 'suppliers',
								'countRequest' => $this->userModel->countRequest(),
								'countIntruder' => $this->userModel->countIntruder()
								]); ?>

<div class="container-fluid">	
	<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
		<div class="card card-body">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
				<h1 class="h2"><i style="color:#24caae;" class="fas fa-eye"></i> Consulter le fournisseur</h1>	
				<div class="btn-toolbar mb-2 mb-md-0">
					<div class="btn-group mr-2">
						<a href="<?= URLROOT ?>/admin_suppliers" class="btn btn-back btn-lg"><span class="fa fa-arrow-left"></span></a>
					</div>
				</div>
			</div>
			<fieldset class="cheduler-border row">
				<legend class="scheduler-border">Info fournisseur</legend>
				<div class="col-md-12">
					<div class="form-group">
						<label for="name">Nom</label>
						<input type="text" class="form-control form-control-lg <?= (isset($data['errors']['name']) && !empty($data['errors']['name'])) ? 'is-invalid' : '' ?>" id="name" name="name" placeholder="Nom du fournisseur" value="<?= $data['name'] ?>" readonly>
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label for="city">Ville</label>
						<input type="text" class="form-control form-control-lg <?= (isset($data['errors']['city']) && !empty($data['errors']['city'])) ? 'is-invalid' : '' ?>" id="searchBox" name="city" placeholder="Indiquer ville ou code postal" value="<?= $data['city'] ?>" readonly>							
					</div>
				</div>

				<div class="col-md-6">
					<div class="form-group">
						<label for="address">Adresse</label>
						<input type="text" class="form-control form-control-lg <?= (isset($data['errors']['address']) && !empty($data['errors']['address'])) ? 'is-invalid' : '' ?>" id="address" name="address" placeholder="L'adresse complète" value="<?= $data['address'] ?>" readonly>
					</div>
				</div>
			</fieldset>

				<fieldset class="cheduler-border row">
					<legend class="scheduler-border">Personne de contact</legend>
					<div class="col-md-6">
						<div class="form-group">
							<label for="contactName">Nom du contact</label>
							<input type="text" class="form-control form-control-lg <?= (isset($data['errors']['contactName']) && !empty($data['errors']['contactName'])) ? 'is-invalid' : '' ?>" id="contactName" name="contactName" placeholder="Nom de la personne de contact" value="<?= $data['contactName'] ?>" readonly>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label for="phone">Téléphone</label>
							<input type="text" class="form-control form-control-lg <?= (isset($data['errors']['phone']) && !empty($data['errors']['phone'])) ? 'is-invalid' : '' ?>" id="phone" name="phone" placeholder="Téléphone ou GSM" value="<?= $data['phone'] ?>" readonly>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="email">Email</label>
							<input type="email" class="form-control form-control-lg <?= (isset($data['errors']['email']) && !empty($data['errors']['email'])) ? 'is-invalid' : '' ?>" id="email" name="email" placeholder="Email" value="<?= $data['email'] ?>" readonly>
						</div>
					</div>
				</fieldset>
			<br>
		</div>
	</main>
</div>

<div class="container-fluid">
    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
        <div class="card card-body">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2"><i style="color:#24caae;" class="fas fa-file"></i> Achats effectués</h1>	
            </div>
			<table id="myTable" class="table table-striped table-hover dataTable">
				<thead>
					<tr>
						<th>ID</th>
						<th>Facture N°</th>
						<th>Chantier</th>										
						<th>Total TVAC</th>
						<th class="noSort">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($data['purchases'] as $purchase): ?>
						<tr>
							<td><?= h($purchase['id_purchase']) ?></td>
							<td><?= h($purchase['invoiceNumber']) ?></td>
							<td><?= h($purchase['construction_name']) ?></td>
							<td><?= h(number_format($purchase['total_TVAC'], 2, ',', '.')) ?> €</td>
							<td>
								<a href="<?= URLROOT ?>/purchases/show/<?= h($purchase['id_purchase']) ?>" target="_blank" title="Voir facture <?= h($purchase['invoiceNumber']) ?>" class="btn btn-primary"><span class="fa fa-eye"></span></a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>		
	</main>    
</div>
						
<?php require APPROOT . '/views/inc/admin-footer.php'; ?>
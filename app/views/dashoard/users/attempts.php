<?php render('admin-header', ['active' => 'attempts',
								'countRequest' => $this->userModel->countRequest(),
								'countIntruder' => $this->userModel->countIntruder()
								]); ?>	   
<div class="container-fluid">	
	<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
		<div class="card card-body mb-3">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
				<h1 class="h2">Bloquer une IP</h1>	     
			</div>
			<form class="form" action="" role="form" method="post">
				<div class="row">
					<div class="form-group col-lg-5">
						<input type="text" class="form-control form-control-lg <?= (isset($data['errors']['ip']) && !empty($data['errors']['ip'])) ? 'is-invalid' : '' ?>" name="ip" placeholder="L'IP à bloquer" value="<?= $data['ip'] ?>">
						<span class="invalid-feedback"><?= isset($data['errors']['ip'])? $data['errors']['ip'] : '' ?></span>
					</div>

					<div class="form-action col-lg-1">
						<button type="submit" class="btn btn-success btn-lg"><span class="fa fa-check"></span></button>
					</div>
				</div>
			</form>
		</div>

		<div class="card card-body">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
				<h1 class="h2">Tentatives de connexions</h1>	     
			</div>
			<?= flash('success'); ?>
			<?= flash('fail'); ?>
			<table id="myTable" class="table table-striped table-hover dataTable">
				<thead>
					<tr>
						<th>id</th>							
						<th>IP</th>
						<th>Tentatives</th>
						<th>Jours</th>
						<th>Heures</th>
						<th>Statuts</th>
						<th class="noSort">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($data['attempts'] as $attempt): ?>
						<tr>
							<td><?= $attempt['id'] ?></td>
							<td><?= $attempt['IP'] ?></td>
							<td><?= $attempt['attempts'] ?></td>
							<td><?= $attempt['day'] ?></td>
							<td><?= $attempt['hour'] ?></td>
							<td><?= ($attempt['attempts'] > 3)? 'Bloqué' : 'Non bloqué' ?></td>
							<td>
								<a href="<?= URLROOT ?>/admin_users/delete_attempt/<?= $attempt['id'] ?>" class="btn btn-danger" title="Annuler tentatives"><span class="fa fa-undo"></span></a>
							</td> 
						</tr>
					<?php endforeach; ?>							
				</tbody>
			</table>
		</div>
	</main>
</div>	        
<?php require APPROOT . '/views/inc/admin-footer.php'; ?>
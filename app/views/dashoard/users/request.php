<?php render('admin-header', ['active' => 'request',
								'countRequest' => $this->userModel->countRequest(),
								'countIntruder' => $this->userModel->countIntruder()
								]); ?>	   

<div class="container-fluid">	
	<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
		<div class="card card-body">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
				<h1 class="h2">Demandes d'inscriptions</h1>	     
			</div>
			<?= flash('success'); ?>
			<?= flash('fail'); ?>
			<table id="myTable" class="table table-striped table-hover dataTable">
				<thead>
					<tr>
						<th>id</th>
						<th>Nom utilisateur</th>
						<th>Email</th>
						<th>Inscription</th>
						<th>IP</th>
						<th class="noSort">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($data['users'] as $user): ?>
						<tr>
							<td><?= $user['member_id'] ?></td>
							<td><?= $user['username'] ?></td>
							<td><?= $user['email'] ?></td>
							<td><?= $user['dateCreate'] ?></td>
							<td><?= $user['ip'] ?></td>
							<td>
								<a href="<?= URLROOT ?>/admin_users/validate/<?= $user['member_id'] ?>" class="btn btn-activate" title="Valider <?= $user['username'] ?>"><span class="fa fa-check"></span></a>
								<a href="<?= URLROOT ?>/admin_users/delete/<?= $user['member_id'] ?>" class="btn btn-danger" title="Supprimer <?= $user['username'] ?>"><span class="fa fa-times"></span></a>									
							</td> 
						</tr>
					<?php endforeach; ?>							
				</tbody>
			</table>
		</div>
	</main>
</div>	        
<?php require APPROOT . '/views/inc/admin-footer.php'; ?>
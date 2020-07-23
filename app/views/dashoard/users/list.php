<?php render('admin-header', ['active' => 'users',
								'countRequest' => $this->userModel->countRequest(),
								'countIntruder' => $this->userModel->countIntruder()
								]); ?>	   

<div class="container-fluid">	
	<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
		<div class="card card-body">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
				<h1 class="h2">Utilisateurs</h1>	     
				<div class="btn-toolbar mb-2 mb-md-0">
					<div class="btn-group mr-2">
						<a href="<?= URLROOT ?>/admin_users/insert" class="btn btn-success btn-lg"><span class="fa fa-user-plus"></span> Ajouter</a>
					</div>
				</div>
			</div>
			<?= flash('success'); ?>
			<?= flash('fail'); ?>
			<table id="myTable" class="table table-striped table-hover dataTable">
				<thead>
					<tr>
						<th>id</th>
						<th class="col-md-1">Statut</th>
						<th>Nom utilisateur</th>
						<th>Email</th>
						<th>Niveau</th>
						<th>Adhésion</th>
						<th>Bloqué</th>
						<th class="noSort">Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($data['users'] as $user): ?>
						<tr>
							<td><?= $user['member_id'] ?></td>
							<td width="5%">
								<?php if(is_null($user['lastActivity'])): ?>
									<img src="<?= URLROOT ?>/public/images/disconnected.png" title="Déconnecté" alt="disconnected">
								<?php elseif((timeLimitReached(strtotime($user['lastActivity']), 5, 'minute')) && (!timeLimitReached(strtotime($user['lastActivity']), 20, 'minute'))): ?>
									<img src="<?= URLROOT ?>/public/images/afk.png" title="Absent" alt="afk">
								<?php elseif(!timeLimitReached(strtotime($user['lastActivity']), 20, 'minute')): ?>
									<img src="<?= URLROOT ?>/public/images/connected.png" title="Connecté" alt="connected">
								<?php else: ?>
									<img src="<?= URLROOT ?>/public/images/disconnected.png" title="Déconnecté" alt="disconnected">	
								<?php endif; ?>
							</td>
							<td><?= $user['username'] ?></td>
							<td><?= $user['email'] ?></td>
							<td><?= $user['name'] ?></td>
							<td><?= $user['dateCreate'] ?></td>
							<td><?= ($this->userModel->isBlocked($user['member_id']))? 'Oui' : 'Non' ?></td>
							<td>
								<a href="<?= URLROOT ?>/admin_users/show/<?= $user['member_id'] ?>" class="btn btn-primary" title="Voir profil de <?= $user['username'] ?>"><span class="fa fa-eye"></span></a>												
								<a href="<?= URLROOT ?>/admin_users/update/<?= $user['member_id'] ?>" class="btn btn-secondary" title="Modifier profil de <?= $user['username'] ?>"><span class="fa fa-user-md"></span></a>												
								<?php if($user['member_id'] == $_SESSION['user_id']): ?>
								<a href="#" class="btn btn-warning disabled"><span class="fa fa-lock"></span></a>
								<?php elseif($this->userModel->isBlocked($user['member_id']) && $user['member_id'] != $_SESSION['user_id']): ?>
								<a href="#" class="btn btn-warning" title="Débloquer <?= $user['username'] ?>" data-toggle="modal" data-target="#modal-unlock-<?= $user['member_id'] ?>"><span class="fa fa-unlock"></span></a>
								<?php else: ?>
								<a href="#" class="btn btn-warning" title="Bloquer <?= $user['username'] ?>" data-toggle="modal" data-target="#modal-lock-<?= $user['member_id'] ?>"><span class="fa fa-lock"></span></a>
								<?php endif; ?>
								<?php if($user['member_id'] == $_SESSION['user_id']): ?>
								<a href="#" class="btn btn-danger disabled"><span class="fa fa-user-times"></span></a>
								<?php else: ?>
								<a href="#" class="btn btn-danger" title="Supprimer <?= $user['username'] ?>" data-toggle="modal" data-target="#modal-delete-<?= $user['member_id'] ?>"><span class="fa fa-user-times"></span></a>
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
//MODAL BOX DELETE
foreach($data['users'] as $user): 
render('modalbox', [
			'modalId' => 'modal-delete-'. h($user['member_id']),
			'actionLink' => URLROOT . '/admin_users/delete/'. h($user['member_id']),						
			'actionButton' => 'Supprimer',
			'title' => "Supprimer {$user['username']}",
			'message' => " L'action de suppression est <b>irrévocable</b>! Valider l'action?"
]); 
endforeach;					
?>	

<?php 
//MODAL BOX UNLOCK
foreach($data['users'] as $user): 
render('modalbox', [
			'modalId' => 'modal-unlock-'. h($user['member_id']),
			'actionLink' => URLROOT . '/admin_users/unlock/'. h($user['member_id']),						
			'actionButton' => 'Débloquer',
			'btn' => 'warning',
			'title' => "Débloquer {$user['username']}",
			'message' => " Une fois débloqué, l'utilisateur aura à nouveau accès au site. Confirmer?",
			'alert' => 'warning'
]); 
endforeach;					
?>	

<?php 
//MODAL BOX LOCK
foreach($data['users'] as $user): 
render('modalbox', [
			'modalId' => 'modal-lock-'. h($user['member_id']),
			'actionLink' => URLROOT . '/admin_users/lock/'. h($user['member_id']),						
			'actionButton' => 'Bloquer',
			'btn' => 'warning',
			'title' => "Bloquer {$user['username']}",
			'message' => " Une fois bloqué, l'utilisateur n'aura plus accès au site. Confirmer?",
			'alert' => 'warning'
]); 
endforeach;					
?>	
<?php require APPROOT . '/views/inc/admin-footer.php'; ?>
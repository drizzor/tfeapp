<?php render('admin-header', ['active' => 'users',
								'countRequest' => $this->userModel->countRequest(),
                                'countIntruder' => $this->userModel->countIntruder()
                                ]); ?>	 

<div class="container-fluid">	
	<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
		<div class="card card-body">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
				<h1 class="h2"><?= ($_SESSION['user_id'] == $data['id'])? "Modifier mon profil" : "Modifier le profil de {$data['current']['username']}"?></h1>	 
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group mr-2">
                        <a href="<?= URLROOT ?>/admin_users" class="btn btn-back btn-lg"><span class="fa fa-arrow-left"></span></a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <form class="form" action="" role="form" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="username">Nom d'utilisateur <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
                            <input type="text" class="form-control form-control-lg <?= (!empty($data['errors']['username'])) ? 'is-invalid' : '' ?>" id="username" name="username" placeholder="Le nom de l'utilisateur" value="<?= $data['username'] ?>">
                            <span class="invalid-feedback"><?= isset($data['errors']['username'])? $data['errors']['username'] : '' ?></span>
                        </div>

                        <div class="form-group">
                            <label for="email">Email <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
                            <input type="email" class="form-control form-control-lg <?= (!empty($data['errors']['email'])) ? 'is-invalid' : '' ?>" id="email" name="email" placeholder="Un email valide" value="<?= $data['email'] ?>">
                            <span class="invalid-feedback"><?= isset($data['errors']['email'])? $data['errors']['email'] : '' ?></span>
                        </div>

                        <div class="form-group">
                            <label for="level">Niveau <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
                            <select class="form-control form-control-lg" id="level" name="level">
                                <?php foreach($data['levels'] as $level): ?>
                                    <?php if($level['id'] == $data['level_id']): ?>
                                        <option selected="selected" value="<?= $level['id'] ?>"><?= $level['name'] ?></option>
                                    <?php else: ?>
                                        <option value="<?= $level['id'] ?>"><?= $level['name'] ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                            <?php if(!empty($data['errors']['level'])): ?> 
                                <span style="color: #d2595a;"><?= $data['errors']['level'] ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="admin_set_password">Nouveau mot de passe</label>
                            <input type="password" class="form-control form-control-lg <?= (!empty($data['errors']['admin_set_password'])) ? 'is-invalid' : '' ?>" id="admin_set_password" name="admin_set_password" value="<?= $data['password'] ?>">
                            <span class="invalid-feedback"><?= isset($data['errors']['admin_set_password'])? $data['errors']['admin_set_password'] : '' ?></span>
                        </div>

                        <div class="form-group">
                                <label for="confirm_password">Confirmer le nouveau mot de passe</label>
                            <input type="password" class="form-control form-control-lg <?= (!empty($data['errors']['confirm_password'])) ? 'is-invalid' : '' ?>" id="confirm_password" name="confirm_password" value="<?= $data['confirm_password'] ?>">
                            <span class="invalid-feedback"><?= isset($data['errors']['confirm_password'])? $data['errors']['confirm_password'] : '' ?></span>
                        </div>

                        <div class="form-group">
                            <label class="control-label btn btn-info" for="image"><i class="fas fa-upload"></i> UPLOAD IMAGE </label>
                            <input type="file" id="image" name="image" class="form-control-file"> <br>
                            <?php if(!empty($data['error_upload'])): ?> 
                                <span style="color: #c54442;"><?= $data['error_upload']['image'] ?></span>
                            <?php endif; ?> 
                        </div>
                        <br>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-success btn-lg"><span class="fa fa-pencil-alt"></span> Modifier</button>
                        </div>
                        <br>
                        <p><i><sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup> Champs obligatoires !</i></p>
                    </div>

                         <div class="col-sm-6">
                            <div class="thumbnail">
                                <h4>Date d'inscription</h4>
                                <p><span class="fa fa-calendar"></span> <?= $data['current']['member_dateCreate'] ?></p>
                            </div>
                        <img width="37%" src="<?= URLROOT ?>/images/users/<?= $data['current']['image'] ?>" alt="Image de profil">
                    </form>
                </div>
            </div>
        </div>
	</main>
</div>
<?php require APPROOT . '/views/inc/admin-footer.php'; ?>
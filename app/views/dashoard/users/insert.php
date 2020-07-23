<?php render('admin-header', ['active' => 'users',
								'countRequest' => $this->userModel->countRequest(),
								'countIntruder' => $this->userModel->countIntruder()]); ?>	

<div class="container-fluid">	
	<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
		<div class="card card-body">
            <?= flash('validation_error') ?>
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">             
                <h1 class="h2">Ajouter un utilisateur</h1>	
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group mr-2">
                        <a href="<?= URLROOT ?>/admin_users" class="btn btn-back btn-lg"><span class="fa fa-arrow-left"></span></a>
                    </div>
                </div>
            </div>              
            <form class="form" action="" role="form" method="post">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="username">Nom d'utilisateur <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
                            <input type="text" class="form-control form-control-lg <?= (isset($data['errors']['username']) && !empty($data['errors']['username'])) ? 'is-invalid' : '' ?>" id="username" name="username" placeholder="Le prénom ou pseudo de l'utilisateur" value="<?= $data['username'] ?>" autofocus>
                            <span class="invalid-feedback"><?= isset($data['errors']['username'])? $data['errors']['username'] : '' ?></span>
                        </div>
                    </div>

                    <div class="col-lg-6">    
                        <div class="form-group">
                            <label for="email">Email <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
                            <input type="email" class="form-control form-control-lg <?= (isset($data['errors']['email']) && !empty($data['errors']['email'])) ? 'is-invalid' : '' ?>" id="email" name="email" placeholder="E-mail" value="<?= $data['email'] ?>" >
                            <span class="invalid-feedback"><?= isset($data['errors']['email'])? $data['errors']['email'] : '' ?></span>
                        </div>
                    </div>

                    <div class="col-lg-12">    
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
                    </div>

                    <div class="col-lg-6">                    
                        <div class="form-group">
                            <label for="password">Mot de passe <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
                            <input type="password" class="form-control form-control-lg <?= (isset($data['errors']['password']) && !empty($data['errors']['password'])) ? 'is-invalid' : '' ?>" id="password" name="password" placeholder="Mot de passe" value="<?= $data['password'] ?>">
                            <span class="invalid-feedback"><?= isset($data['errors']['password'])? $data['errors']['password'] : '' ?></span>
                        </div>
                    </div>

                    <div class="col-lg-6">    
                        <div class="form-group">
                            <label for="confirm_password">Confirmation du mot passe <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
                            <input type="password" class="form-control form-control-lg <?= (isset($data['errors']['confirm_password']) && !empty($data['errors']['confirm_password'])) ? 'is-invalid' : '' ?>" id="confirm_password" name="confirm_password" placeholder="Confirmer le mot de passe" value="<?= $data['confirm_password'] ?>">
                            <span class="invalid-feedback"><?= isset($data['errors']['confirm_password'])? $data['errors']['confirm_password'] : '' ?></span>
                        </div>
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
    </main>
</div>
<?php require APPROOT . '/views/inc/admin-footer.php'; ?>                
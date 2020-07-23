<?php render('admin-header', ['active' => 'workers',
								'countRequest' => $this->userModel->countRequest(),
                                'countIntruder' => $this->userModel->countIntruder()
                                ]); ?>	

<div class="container-fluid">	
	<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
		<div class="card card-body">
            <?= flash('validation_error') ?>
            <?= flash('fail') ?>
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">             
                <h1 class="h2">Ajouter un ouvrier</h1>	
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group mr-2">
                        <a href="<?= URLROOT ?>/admin_workers" class="btn btn-back btn-lg"><span class="fa fa-arrow-left"></span></a>
                    </div>
                </div>
            </div>              
            <form class="form" action="" role="form" method="post">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="lastname">Nom <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
                            <input type="text" class="form-control form-control-lg <?= (isset($data['errors']['lastname']) && !empty($data['errors']['lastname'])) ? 'is-invalid' : '' ?>" id="lastname" name="lastname" placeholder="Le nom de famille " value="<?= $data['lastname'] ?>" autofocus>
                            <span class="invalid-feedback"><?= isset($data['errors']['lastname'])? $data['errors']['lastname'] : '' ?></span>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="firstname">Prénom <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
                            <input type="text" class="form-control form-control-lg <?= (isset($data['errors']['firstname']) && !empty($data['errors']['firstname'])) ? 'is-invalid' : '' ?>" id="firstname" name="firstname" placeholder="Le prénom de l'ouvrier" value="<?= $data['firstname'] ?>">
                            <span class="invalid-feedback"><?= isset($data['errors']['firstname'])? $data['errors']['firstname'] : '' ?></span>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="salary">Taux horaire <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
                            <input type="text" class="form-control form-control-lg <?= (isset($data['errors']['salary']) && !empty($data['errors']['salary'])) ? 'is-invalid' : '' ?>" id="salary" name="salary" placeholder="Le salaire au taux horaire" value="<?= $data['salary'] ?>">
                            <span class="invalid-feedback"><?= isset($data['errors']['salary'])? $data['errors']['salary'] : '' ?></span>
                        </div>
                    </div>

                    <div class="col-lg-6">    
                        <div class="form-group">
                            <label for="email">Email <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
                            <input type="email" class="form-control form-control-lg <?= (isset($data['errors']['email']) && !empty($data['errors']['email'])) ? 'is-invalid' : '' ?>" id="email" name="email" placeholder="E-mail" value="<?= $data['email'] ?>">
                            <span class="invalid-feedback"><?= isset($data['errors']['email'])? $data['errors']['email'] : '' ?></span>
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
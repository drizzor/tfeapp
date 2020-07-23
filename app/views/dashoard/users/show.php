<?php render('admin-header', ['active' => 'users',
								'countRequest' => $this->userModel->countRequest(),
                                'countIntruder' => $this->userModel->countIntruder()
                                ]); ?>	  

<div class="container-fluid">	
	<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
		<div class="card card-body">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
				<h1 class="h2"><?= ($_SESSION['user_id'] == $data['id'])? "Mon profil" : "Profil de {$data['username']}" ?></h1>	
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
                            <label for="username">Nom d'utilisateur</label>
                            <input type="text" class="form-control form-control-lg" id="username" name="username" value="<?= $data['username'] ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control form-control-lg" id="email" name="email" value="<?= $data['email'] ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="level">Niveau</label>
                            <input type="text" class="form-control form-control-lg" id="level" name="level" value="<?= $data['level'] ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="ip">Adresse IP</label>
                            <input type="text" class="form-control form-control-lg" id="ip" name="ip" value="<?= $data['ip'] ?>" readonly>
                        </div>
                        <br>
                    </form>
                </div>
                <div class="col-sm-6">
                    <div class="thumbnail">
                        <h4>Date d'inscription</h4>
                        <p><span class="fa fa-calendar-alt"></span> <?= $data['dateCreate'] ?></p>
                    </div>
                    <img width="37%" src="<?= URLROOT ?>/images/users/<?= $data['image'] ?>" alt="Image de profil">
                </div>
            </div>
        </div>
	</main>
</div>
<?php require APPROOT . '/views/inc/admin-footer.php'; ?>
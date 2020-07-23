<?php 
render('header', ['title' => 'Mon profil',
                'session' => $data['current']['username'],
                'image' => $data['current']['image'],
                'nav_container' => 'container'
                ]); 
?>  
<div class="container mt-5">
    <div class="card card-body">
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2">Modifier mon profil</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                <a href="<?= URLROOT ?>" class="btn btn-back btn-lg"><span class="fa fa-arrow-left"></span></a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">                    
            <form class="form" action="<?= URLROOT ?>/users/profil/<?= $_SESSION['user_id'] ?>" role="form" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur:</label>
                    <input type="text" class="form-control <?= ($data['errors']['username']) ? 'is-invalid' : '' ?>" id="username" name="username" placeholder="username" value="<?= $data['username'] ?>" autofocus>
                    <span class="invalid-feedback"><?= $data['errors']['username'] ?></span>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control <?= ($data['errors']['email']) ? 'is-invalid' : '' ?>" id="email" name="email" placeholder="E-mail" value="<?= $data['email'] ?>">
                    <span class="invalid-feedback"><?= $data['errors']['email'] ?></span>
                </div>
                <div class="form-group">
                    <label for="levelV">Niveau:</label>
                    <input type="text" class="form-control" id="levelV" name="levelV" value="<?= $data['level_name'] ?>" readonly>
                </div>
                <div class="form-group">
                <label for="current_password">Mot de passe actuel:</label>
                    <input type="password" class="form-control <?= ($data['errors']['current_password']) ? 'is-invalid' : '' ?>" id="current_password" name="current_password" value="<?= $data['current_password'] ?>">
                    <span class="invalid-feedback"><?= $data['errors']['current_password'] ?></span>
                </div>
                <div class="form-group">
                <label for="password">Nouveau mot de passe:</label>
                    <input type="password" class="form-control <?= ($data['errors']['password']) ? 'is-invalid' : '' ?>" id="password" name="password" value="<?= $data['password'] ?>">
                    <span class="invalid-feedback"><?= $data['errors']['password'] ?></span>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirmer le nouveau mot de passe:</label>
                        <input type="password" class="form-control <?= ($data['errors']['confirm_password']) ? 'is-invalid' : '' ?>" id="confirm_password" name="confirm_password" value="<?= $data['confirm_password'] ?>">
                        <span class="invalid-feedback"><?= $data['errors']['confirm_password'] ?></span>
                    </div>
                    <br>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success btn-lg"><span class="fa fa-pencil-alt"></span> Modifier</button>
                    </div>                                             
                </div>
                <div class="col-sm-6">
                    <div class="thumbnail">
                        <h4>Date d'inscription</h4>
                        <p><span class="fa fa-calendar-alt"></span> <?= $data['current']['member_dateCreate'] ?></p>
                    </div>

                    <img width="37%" src="<?= URLROOT ?>/images/users/<?= (empty($data['image']))? $data['current']['image'] : $data['image']; ?>" alt="Image de profil">
                    <div class="form-group mb-3">
                        <br>
                        <label class="control-label btn btn-info" for="image"><i class="fas fa-upload"></i> UPLOAD </label>
                        <input type="file" id="image" name="image" class="form-control-file"> <br>
                        <?php if(!empty($data['error_upload'])): ?> 
                        <span style="color: #d2595a;"><?= $data['error_upload']['image'] ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </main>             
    <?php require APPROOT . '/views/inc/footer.php'; ?>
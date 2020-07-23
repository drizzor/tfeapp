<?php 
    render('header-login', ['title' => 'Créer compte']); 
?>   
<body> 
    <div id="particles-js"></div>    

    <div class="container">
        <div class="row">   
            <div class="col-md-6 mx-auto">
                <div class="card card-body">
                    <div class="col-12 user-img text-center">
                        <i class="fa fa-user-edit fa-5x"></i>
                    </div>
                    <div class="col-lg-12 text-center">
                        <h3 class="mb-5">Créer un compte</h3> 
                    </div>                
                    <form action="<?= URLROOT ?>/users/register" role="form" method="post" class="form">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                            </div>
                            <input type="text" class="form-control <?= ($data['errors']['username']) ? 'is-invalid' : '' ?>" name="username" id="username" placeholder="Nom d'utilisateur" value="<?= $data['username']; ?>" autofocus required>
                            <span class="valid-feedback"></span>
                            <span class="invalid-feedback"><?= $data['errors']['username'] ?></span>
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                            </div>
                            <input type="email" class="form-control  <?= ($data['errors']['email']) ? 'is-invalid' : '' ?>" name="email" id="email" placeholder="E-mail" value="<?= $data['email']; ?>" required>
                            <span class="valid-feedback"></span>
                            <span class="invalid-feedback"><?= $data['errors']['email'] ?></span>
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            </div>
                            <input type="password" id="password" name="password" class="form-control <?= ($data['errors']['password']) ? 'is-invalid' : '' ?>" placeholder="Mot de passe" value="<?= $data['password']; ?>" required>
                            <span class="valid-feedback"></span>
                            <span class="invalid-feedback"><?= $data['errors']['password'] ?></span>
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            </div>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control <?= ($data['errors']['confirm_password']) ? 'is-invalid' : '' ?>" placeholder="Confirmer le mot de passe" value="<?= $data['confirm_password']; ?>" required>
                            <span class="valid-feedback"></span>
                            <span class="invalid-feedback"><?= $data['errors']['confirm_password'] ?></span>
                        </div>

                        <!-- <input type="hidden" value="" name="g-recaptcha_response" id="g-recaptcha_response"> -->

                        <div class="row mt-5">
                            <div class="col">
                                <button class="btn btn-success btn-block" type="submit"><i class="fas fa-paper-plane"></i> S'enregistrer</button>
                            </div>
                            <div class="col">
                                <a href="<?= URLROOT; ?>/users/login" class="btn btn-danger btn-block">Déjà inscrit?</a>
                            </div>
                        </div> 
                    </form>
                </div>
            </div>
        </div> 
    </div>

    <div class="bottom-right">
        <a href="#" class="button b-green" target="_blank">Par Kevin Mary</a>
    </div>

    <script> var url = "<?= URLROOT; ?>/users/registerValidation"</script>
    <script src="<?= URLROOT; ?>/js/particles.js"></script>
    <script src="<?= URLROOT; ?>/js/particles.min.js"></script>
    <script src="<?= URLROOT; ?>/js/app.js"></script>
    <script src="<?= URLROOT; ?>/js/lib/jQuery.js"></script>
    <script src="<?= URLROOT; ?>/js/lib/popper.js"></script>
    <script src="<?= URLROOT; ?>/js/lib/bootstrap.js"></script>    

    <script>

        particlesJS.load('particles-js', 
        '<?= URLROOT ?>/js/particles.json', function() {
            console.log('callback - particles.js config loaded');
        });
    
    </script>

</body>
</html>
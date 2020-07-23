<?php render('header-login', ['title' => 'Connexion']); ?>   

<div class="mt-2">
    <?= flash('register_success'); ?>
    <?= flash('fail_attempts'); ?>
    <?= flash('fail') ?>
    <?= flash('delete_account') ?>
</div>
    
<body> 
    <div id="particles-js"></div>    
        <div class="container">
            <div class="row ">
                <div class="col-md-6 mx-auto">
                    <div class="card card-body">      
                        <div class="col-12 user-img text-center">  
                            <i class="fa fa-user-circle fa-5x"></i>
                        </div>
                        <div class="col-lg-12 text-center">
                            <h3 class="mb-5">Se connecter</h3>
                        </div>     
                        <form action="<?= URLROOT ?>/users/login" role="form" method="post" class="form" id="login-form" class="col-12">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="username"><i class="fa fa-user"></i></span>
                                </div>
                                <input type="text" class="form-control <?= ($data['username_err']) ? 'is-invalid' : '' ?>" name="username" id="username" placeholder="Nom d'utilisateur" aria-label="Username" aria-describedby="username" value="<?= $data['username'] ?>" autofocus >
                                <span class="invalid-feedback"><?= $data['username_err']; ?></span>          
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="password"><i class="fa fa-lock"></i></span>
                                </div>
                                <input type="password" class="form-control <?= ($data['password_err']) ? 'is-invalid' : '' ?>" id="password" name="password"  placeholder="Mot de passe" aria-label="Password" aria-describedby="password" value="<?= $data['password'] ?>" >
                                <span class="invalid-feedback"><?= $data['password_err']; ?></span>
                            </div>

                            <div class="row mt-5">
                                <div class="col">
                                    <button class="btn btn-success btn-block" type="submit"><i class="fas fa-sign-in-alt"></i> Connexion</button>
                                </div>

                                <div class="col">
                                    <a href="<?= URLROOT; ?>/users/register" class="btn btn-danger btn-block">Pas inscrit?</a>
                                </div>

                                <?php if($data['attempts']['attempts'] > 0): ?>
                                <div class="col-lg-12 text-center mt-4 password-recover">
                                    <a href="<?= URLROOT; ?>/users/recovery" title="Récupérer mon compte" style="color:#21cab5;">Mot de passe oublié?</a>
                                </div>
                                <?php endif; ?>
                            </div> 

                        </form>
                    </div>
                </div>        
            </div>
        </div>

        <div class="bottom-right">
            <a href="http://www.kevinmary.be" class="button b-green" target="_blank">Par Kevin Mary</a>
        </div>
    
  

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
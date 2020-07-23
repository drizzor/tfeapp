<?php render('header-login', ['title' => 'Récupération de compte']); ?>   

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
                        <i class="fa fa-user-lock fa-5x"></i>
                    </div>
                    <div class="col-lg-12 text-center">
                        <h3 class="mb-5">Mot de passe oublié?</h3>
                    </div>     
                    <p class="mb-4">Afin de renouveler votre mot de passe, veuillez indiquer l'adresse email de votre compte. Un email vous sera envoyé dans les plus brefs délais.</p>
                    <form action="" role="form" method="post" class="form" id="login-form" class="col-12">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="email"><i class="fa fa-envelope"></i></span>
                            </div>
                            <input type="email" class="form-control form-control-lg <?= ($data['email_err']) ? 'is-invalid' : '' ?>" name="email" id="email" placeholder="Indiquer email" value="<?= $data['email'] ?>" required autofocus>
                            <span class="invalid-feedback"><?= $data['email_err']; ?></span>          
                        </div>

                        <div class="row mt-5">
                            <div class="col">
                                <button class="btn btn-success btn-block" type="submit"><i class="fas fa-paper-plane"></i> &nbsp; Récupérer</button>
                            </div>

                            <div class="col">
                                <a href="<?= URLROOT; ?>/users/register" class="btn btn-danger btn-block">Pas inscrit?</a>
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
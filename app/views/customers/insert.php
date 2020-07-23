<?php render('header', ['title' => 'Client', 
                        'session' => $data['currentUser']['username'],
                        'image' => $data['currentUser']['image'],
                        'nav_container' => 'container'
                      ]); 
?>  
      
  <div class="container mt-5">
    <div class="card card-body">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1 class="h2">Nouveau client</h1>														
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group mr-2">
            <a href="<?= URLROOT ?>/customers" class="btn btn-back btn-lg"><span class="fa fa-arrow-left"></span></a>
          </div>
        </div>
      </div>
    
    <?= flash("no_customers") ?>

    <div class="col-md-12">        
        <form class="form" action="" role="form" method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Nom <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
                        <input type="text" class="form-control form-control-lg <?= (isset($data['errors']['name']) && !empty($data['errors']['name'])) ? 'is-invalid' : '' ?>" id="name" name="name" placeholder="Nom complet du client ou de l'entreprise" value="<?= $data['name'] ?>" required autofocus>
                        <span class="invalid-feedback"><?= isset($data['errors']['name'])? $data['errors']['name'] : '' ?></span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tva_number">N° TVA</label>
                        <input type="text" class="form-control form-control-lg <?= (isset($data['errors']['tva_number']) && !empty($data['errors']['tva_number'])) ? 'is-invalid' : '' ?>" id="tva_number" name="tva_number" placeholder="Indiquer seulement s'il en dispose" value="<?= $data['tva_number'] ?>">
                        <span class="invalid-feedback"><?= isset($data['errors']['tva_number'])? $data['errors']['tva_number'] : '' ?></span>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="zipcode">Code postal <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
                        <input type="text" class="form-control form-control-lg <?= (isset($data['errors']['zipcode']) && !empty($data['errors']['zipcode'])) ? 'is-invalid' : '' ?>" id="zipcode" name="zipcode" placeholder="7190, 6000, ..." value="<?= $data['zipcode'] ?>" required>
                        <span class="invalid-feedback"><?= isset($data['errors']['zipcode'])? $data['errors']['zipcode'] : '' ?></span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="city">Ville <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
                        <input type="text" class="form-control form-control-lg <?= (isset($data['errors']['city']) && !empty($data['errors']['city'])) ? 'is-invalid' : '' ?>" id="city" name="city" placeholder="Ecaussinnes, Charleroi, ..." value="<?= $data['city'] ?>" required>
                        <span class="invalid-feedback"><?= isset($data['errors']['city'])? $data['errors']['city'] : '' ?></span>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="address">Adresse <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
                        <input type="text" class="form-control form-control-lg <?= (isset($data['errors']['address']) && !empty($data['errors']['address'])) ? 'is-invalid' : '' ?>" id="address" name="address" placeholder="Adresse complète avec numéro" value="<?= $data['address'] ?>" required>
                        <span class="invalid-feedback"><?= isset($data['errors']['address'])? $data['errors']['address'] : '' ?></span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="country">Pays <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
                        <input type="text" class="form-control form-control-lg <?= (isset($data['errors']['country']) && !empty($data['errors']['country'])) ? 'is-invalid' : '' ?>" id="country" name="country" value="<?= $data['country'] ?>" required>
                        <span class="invalid-feedback"><?= isset($data['errors']['country'])? $data['errors']['country'] : '' ?></span>
                    </div>
                </div>
            </div>   
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control form-control-lg <?= (isset($data['errors']['email']) && !empty($data['errors']['email'])) ? 'is-invalid' : '' ?>" id="email" name="email" placeholder="Email valide" value="<?= $data['email'] ?>">
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

    <?php require APPROOT . '/views/inc/footer.php'; ?>
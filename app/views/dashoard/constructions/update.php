<?php render('admin-header', ['active' => 'constructions',
								'countRequest' => $this->userModel->countRequest(),
								'countIntruder' => $this->userModel->countIntruder()]); ?>

<div class="container-fluid">	
	<main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
		<div class="card card-body">
			<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
				<h1 class="h2">Modifier le chantier</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group mr-2">
                        <a href="<?= URLROOT ?>/admin_constructions" class="btn btn-back btn-lg"><span class="fa fa-arrow-left"></span> </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <form class="form" action="<?= URLROOT ?>/admin_constructions/update/<?= $data['id'] ?>" role="form" method="post" enctype="multipart/form-data">
                        
                        <div class="form-group">
                            <label for="name">Nom <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
                            <input type="text" class="form-control form-control-lg <?= (isset($data['errors']['name']) && !empty($data['errors']['name'])) ? 'is-invalid' : '' ?>" id="name" name="name" placeholder="Nom du chantier" value="<?= $data['name'] ?>" required>
                            <span class="invalid-feedback"><?= isset($data['errors']['name'])? $data['errors']['name'] : '' ?></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="city">Ville <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
                            <input type="text" class="form-control dropdownField form-control-lg form-control-lg <?= (isset($data['errors']['city']) && !empty($data['errors']['city'])) ? 'is-invalid' : '' ?>" id="searchBox" name="city" placeholder="Indiquer ville ou code postal" autocomplete="off" value="<?= $data['city'] ?>" required>
                            <span class="invalid-feedback"><?= isset($data['errors']['city'])? $data['errors']['city'] : '' ?></span>
                            <div id="response"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Adresse</label>
                            <input type="text" class="form-control form-control-lg <?= (isset($data['errors']['address']) && !empty($data['errors']['address'])) ? 'is-invalid' : '' ?>" id="address" name="address" placeholder="L'adresse complète" value="<?= $data['address'] ?>">
                            <span class="invalid-feedback"><?= isset($data['errors']['address'])? $data['errors']['address'] : '' ?></span>
                        </div>

                        <div class="form-group">
                            <label for="buyingPrice">Prix d'acquisition <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
                            <input type="text" step="0.01" class="form-control form-control-lg <?= (isset($data['errors']['buyingPrice']) && !empty($data['errors']['buyingPrice'])) ? 'is-invalid' : '' ?>" id="buyingPrice" name="buyingPrice" value="<?= $data['buyingPrice'] ?>">
                            <span class="invalid-feedback"><?= isset($data['errors']['buyingPrice'])? $data['errors']['buyingPrice'] : '' ?></span>
                        </div>

                        <div class="form-group">
                            <label for="surface">Surface au m² <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
                            <input type="text" step="0.01" class="form-control form-control-lg <?= (isset($data['errors']['surface']) && !empty($data['errors']['surface'])) ? 'is-invalid' : '' ?>" id="surface" name="surface" placeholder="La surface au mètre carré..." value="<?= $data['surface'] ?>">
                            <span class="invalid-feedback"><?= isset($data['errors']['surface'])? $data['errors']['surface'] : '' ?></span>
                        </div>

                        <div class="form-group">
                            <label for="taxes">Notaire & taxes <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
                            <input type="text" step="0.01" class="form-control form-control-lg <?= (isset($data['errors']['taxes']) && !empty($data['errors']['taxes'])) ? 'is-invalid' : '' ?>" id="taxes" name="taxes" placeholder="Cumul des différentes taxes" value="<?= $data['taxes'] ?>">
                            <span class="invalid-feedback"><?= isset($data['errors']['taxes'])? $data['errors']['taxes'] : '' ?></span>
                        </div>

                        <div class="form-group">
                            <label for="estimatePrice">Prix de vente estimé <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
                            <input type="text" step="0.01" class="form-control form-control-lg <?= (isset($data['errors']['estimatePrice']) && !empty($data['errors']['estimatePrice'])) ? 'is-invalid' : '' ?>" id="estimatePrice" name="estimatePrice" value="<?= $data['estimatePrice'] ?>">
                            <span class="invalid-feedback"><?= isset($data['errors']['estimatePrice'])? $data['errors']['estimatePrice'] : '' ?></span>
                        </div>

                        <div class="form-group">
                            <label for="buyingDate">Date de l'achat <sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup></label>
                            <input type="date" class="form-control form-control-lg <?= (isset($data['errors']['buyingDate']) && !empty($data['errors']['buyingDate'])) ? 'is-invalid' : '' ?>" id="buyingDate" name="buyingDate" value="<?= $data['buyingDate'] ?>">
                            <span class="invalid-feedback"><?= isset($data['errors']['buyingDate'])? $data['errors']['buyingDate'] : '' ?></span>
                        </div>

                        <div class="form-group">
                            <label for="comment">Commentaire</label>
                            <textarea id="textarea_count" maxlength="255" class="form-control <?= (isset($data['errors']['comment']) && !empty($data['errors']['comment'])) ? 'is-invalid' : '' ?>" id="comment" name="comment" placeholder=""><?= $data['comment'] ?></textarea>
                            <div id="textarea_feedback" style="color:#21cab5"></div> 
                            <span class="invalid-feedback"><?= isset($data['errors']['comment'])? $data['errors']['comment'] : '' ?></span>    
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
                                <br><br>
                            <p><sup class="asterisk"><i class="fas fa-asterisk ml-2"></i></sup> Champs obligatoires</p>                            
                        </div>

                    </form>
                </div>
                
                <div class="col-sm-6">
                    <div class="thumbnail">
                    <h4>Image de couverture</h4>
                    </div>
                    <img width="37%" src="<?= URLROOT ?>/images/constructions/<?= $data['image'] ?>" alt="Image de de couverture chantier">
                </div>
            </div>
		</div>
	</main>
</div>
<?php require APPROOT . '/views/inc/admin-footer.php'; ?>
<script> document.getElementById("buyingDate").valueAsDate = new Date("<?= $data['buyingDate']; ?>"); </script>
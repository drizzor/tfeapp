<?php render('header', ['title' => 'Galeries', 
                        'session' => $data['currentUser']['username'],
                        'image' => $data['currentUser']['image'],
                        'nav_container' => 'container'
                      ]); 
?>  

<div class="container mt-5">
    <div class="card card-body grid">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Galerie de <?= $data['galleries'][0]['construction_name'] ?></h1>														
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <?php if($data['currentUser']['level_name'] === "Admin"): ?>
                        <div class="dropdown show">
                            <a href="#" style="min-height:50px;" class="btn btn-success btn-lg mr-1 dropdown-toggle" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fa fa-cog"></span></a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="<?= URLROOT ?>/admin_galleries">Gestion des galeries</a>
                                <a class="dropdown-item" href="<?= URLROOT ?>/admin_galleries/insert">Upload</a>
                                <a class="dropdown-item" href="<?= URLROOT ?>/admin_galleries/show/<?= $data['galleries'][0]['construction_id'] ?>">Editer</a>
                            </div>
                        </div>
                    <?php endif; ?>
                    <a href="<?= URLROOT ?>/galleries" class="btn btn-back btn-lg"><span class="fa fa-arrow-left"></span></a>
                </div>
            </div>
        </div>        

        <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="before-tab" data-toggle="tab" href="#before">Avant</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="after-tab" data-toggle="tab" href="#after">Après</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="before">
                <section class="gallery-block compact-gallery">
                    <div class="container">            
                        <div class="row no-gutters">
                            <?php foreach($data['galleries'] as $gallery): ?>
                                <?php if($gallery['inProgress'] == 1): ?>
                                    <div class="col-md-6 col-lg-4 item zoom-on-hover">
                                        <a class="lightbox" href="<?= URLROOT ?>/images/gallery/<?= $gallery['image'] ?>">
                                            <img class="img-fluid image" src="<?= URLROOT ?>/images/gallery/<?= $gallery['image'] ?>">
                                            <span class="description">
                                                <span class="description-heading"><?= $gallery['title'] ?></span>
                                                <p class="description-body"><?= $gallery['gallery_comment'] ?></p>
                                            </span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>    
                        </div>    
                    </div>
                </section> 	
            </div>
            <div class="tab-pane fade" id="after">	
                <section class="gallery-block compact-gallery">
                    <div class="container">            
                        <div class="row no-gutters">
                            <?php foreach($data['galleries'] as $gallery): ?>
                                <?php if($gallery['inProgress'] == 0): ?>
                                    <div class="col-md-6 col-lg-4 item zoom-on-hover">
                                        <a class="lightbox" href="<?= URLROOT ?>/images/gallery/<?= $gallery['image'] ?>">
                                            <img class="img-fluid image" src="<?= URLROOT ?>/images/gallery/<?= $gallery['image'] ?>">
                                            <span class="description">
                                                <span class="description-heading"><?= $gallery['title'] ?></span>
                                                <p class="description-body"><?= $gallery['gallery_comment'] ?></p>
                                            </span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>    
                        </div>    
                    </div>
                </section> 	
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?> 
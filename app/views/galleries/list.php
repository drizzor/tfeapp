<?php render('header', ['title' => 'Galeries', 
                        'session' => $data['currentUser']['username'],
                        'image' => $data['currentUser']['image'],
                        'nav_container' => 'container'
                      ]); 
?>  
<div class="container mt-5">
    <div class="card card-body grid">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Selectionner le chantier</h1>														
            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group mr-2">
                    <?php if($data['currentUser']['level_name'] === "Admin"): ?>
                        <div class="dropdown show">
                            <a href="#" style="min-height:50px;" class="btn btn-success btn-lg mr-1 dropdown-toggle" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="fa fa-cog"></span></a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="<?= URLROOT ?>/admin_galleries">Gestion des galeries</a>
                                <a class="dropdown-item" href="<?= URLROOT ?>/admin_galleries/insert">Upload</a>
                            </div>
                        </div>
                    <?php endif; ?>
                    <a href="<?= URLROOT ?>" class="btn btn-back btn-lg"><span class="fa fa-arrow-left"></span></a>
                </div>
            </div>
        </div>
        <div class="row">
            <?php foreach($data['galleries'] as $gallery): ?>
                <div class="col-md-4">
                    <figure class="effect-ravi">
                        <img class="img-fluid image gallery_cover" src="<?= URLROOT ?>/images/constructions/<?= $gallery['construction_cover'] ?>" alt="img17" />
                        <figcaption>
                            <h2><?= $gallery['construction_name'] ?></h2>
                            <p>
                                <a href="<?= URLROOT ?>/galleries/show/<?= $gallery['construction_id'] ?>"><i class="fa fa-search"></i></a>
                            </p>
                        </figcaption>
                    </figure>
                </div>
            <?php endforeach; ?>            
        </div>
    </div>
</div>
        <?php require APPROOT . '/views/inc/footer.php'; ?> 
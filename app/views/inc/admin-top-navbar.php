<nav class="navbar navbar-dark sticky-top bg-dark navbar-expand-lg p-0">
  <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="<?= URLROOT ?>"><span class="fa fa-home mr-2"></span> ACCUEIL</a>
  <ul class="navbar-nav px-3">
  	<li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="navbar-toggler-icon"></span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <?php if($_SESSION['user_level']['name'] === "Membre" || $_SESSION['user_level']['name'] === "Admin"): ?>
            <a class="dropdown-item" href="<?= URLROOT ?>/admin">Général</a>

            <div class="dropdown-divider"></div>

            <a class="dropdown-item" href="<?= URLROOT ?>/admin_constructions">Bâtiments</a>
            <a class="dropdown-item" href="<?= URLROOT ?>/admin_categories">Catégories</a>
            <a class="dropdown-item" href="<?= URLROOT ?>/admin_suppliers">Fournisseurs</a>
          <?php endif; ?>
          <?php if($_SESSION['user_level']['name'] === "Admin"): ?>
            <div class="dropdown-divider"></div>

            <a class="dropdown-item" href="<?= URLROOT ?>/admin_workers">Ouvriers</a>   
            <a class="dropdown-item" href="<?= URLROOT ?>/admin_plannings">Planning</a>  

            <div class="dropdown-divider"></div>  

            <a class="dropdown-item" href="<?= URLROOT ?>/admin_galleries">Galeries</a>   
            <a class="dropdown-item" href="<?= URLROOT ?>/admin_galleries/insert">Upload</a> 

            <div class="dropdown-divider"></div>  

            <a class="dropdown-item" href="<?= URLROOT ?>/admin_users">Utilisateurs</a>   
            <a class="dropdown-item" href="<?= URLROOT ?>/admin_users/request">Demandes</a>   
            <a class="dropdown-item" href="<?= URLROOT ?>/admin_users/attempts">Intrusions</a> 

            <div class="dropdown-divider"></div>  
            <a class="dropdown-item" href="<?= URLROOT ?>/admin_dump">Sauvegarde</a>  

          <?php endif; ?>  
                              
          <div class="dropdown-divider"></div>

          <a class="dropdown-item" target="_blank" href="<?= URLROOT ?>/users/profil/<?= $_SESSION['user_id'] ?>">Mon profil</a>
          <a class="dropdown-item" href="<?= URLROOT ?>/users/logout">Déconnexion</a>
        </div>
      </li>
  </ul>
</nav>
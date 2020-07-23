<nav class="col-md-2 d-none d-md-block bg-light sidebar">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="sidebar-sticky">
    <?php if($_SESSION['user_level']['name'] === "Membre" || $_SESSION['user_level']['name'] === "Admin"): ?>
    <ul class="nav flex-column" style="margin-top: 10%;">
      <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
        <span>Dashboard</span>
      </h6>
      <li class="nav-item <?= (isset($active) && $active == 'home') ? 'active' : '' ?>"><a class="nav-link <?= (isset($active) && $active == 'home') ? 'active' : '' ?>" href="<?= URLROOT ?>/admin"><span class="fas fa-tachometer-alt mr-3 <?= (isset($active) && $active == 'home') ? 'tomato' : '' ?>"></span> Général</a></li>
    </ul>

    <ul class="nav flex-column">
      <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
        <span>Gestion chantiers</span>
      </h6>
      <li class="nav-item <?= (isset($active) && $active == 'constructions') ? 'active' : '' ?>"><a class="nav-link <?= (isset($active) && $active == 'constructions') ? 'active' : '' ?>" href="<?= URLROOT ?>/admin_constructions"><span class="fas fa-building mr-3 <?= (isset($active) && $active == 'constructions') ? 'tomato' : '' ?>"></span> Bâtiments</a></li>
      <li class="nav-item <?= (isset($active) && $active == 'categories') ? 'active' : '' ?>"><a class="nav-link <?= (isset($active) && $active == 'categories') ? 'active' : '' ?>" href="<?= URLROOT ?>/admin_categories"><span class="fas fa-list-alt mr-3 <?= (isset($active) && $active == 'categories') ? 'tomato' : '' ?>"></span> Catégories</a></li>
      <li class="nav-item <?= (isset($active) && $active == 'suppliers') ? 'active' : '' ?>"><a class="nav-link <?= (isset($active) && $active == 'suppliers') ? 'active' : '' ?>" href="<?= URLROOT ?>/admin_suppliers"><span class="fas fa-truck mr-3 <?= (isset($active) && $active == 'suppliers') ? 'tomato' : '' ?>"></span> Fournisseurs</a></li>
    </ul>
    <?php endif; ?>
    <?php if($_SESSION['user_level']['name'] === "Admin"): ?>
    <ul class="nav flex-column">
      <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
        <span>Gestion ouvriers</span>
      </h6>
      <li class="nav-item <?= (isset($active) && $active == 'workers') ? 'active' : '' ?>"><a class="nav-link <?= (isset($active) && $active == 'workers') ? 'active' : '' ?>" href="<?= URLROOT ?>/admin_workers"><span class="fas fa-tools mr-3 <?= (isset($active) && $active == 'workers') ? 'tomato' : '' ?>"></span> Ouvriers</a></li>
      <li class="nav-item <?= (isset($active) && $active == 'planning') ? 'active' : '' ?>"><a class="nav-link <?= (isset($active) && $active == 'planning') ? 'active' : '' ?>" href="<?= URLROOT ?>/admin_plannings"><span class="fas fa-calendar-alt mr-3 <?= (isset($active) && $active == 'planning') ? 'tomato' : '' ?>"></span> Planning</a></li>
    </ul>

    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
      <span>Galeries</span>
    </h6>
    <ul class="nav flex-column mb-2">
      <li class="nav-item <?= (isset($active) && $active == 'galleries') ? 'active' : '' ?>"><a class="nav-link <?= (isset($active) && $active == 'galleries') ? 'active' : '' ?>" href="<?= URLROOT ?>/admin_galleries"><span class="fas fa-images mr-3 <?= (isset($active) && $active == 'galleries') ? 'tomato' : '' ?>"></span> Afficher</a></li>
      <li class="nav-item <?= (isset($active) && $active == 'galleries_up') ? 'active' : '' ?>"><a class="nav-link <?= (isset($active) && $active == 'galleries_up') ? 'active' : '' ?>" href="<?= URLROOT ?>/admin_galleries/insert"><span class="fas fa-upload mr-3 <?= (isset($active) && $active == 'galleries_up') ? 'tomato' : '' ?>"></span> Upload</a></li>
    </ul>

    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
      <span>Gestion membres</span>
    </h6>
    <ul class="nav flex-column mb-2">
      <li class="nav-item <?= (isset($active) && $active == 'users') ? 'active' : '' ?>"><a class="nav-link <?= (isset($active) && $active == 'users') ? 'active' : '' ?>" href="<?= URLROOT ?>/admin_users"><span class="fas fa-users mr-3 <?= (isset($active) && $active == 'users') ? 'tomato' : '' ?>"></span> Utilisateurs</a></li>
      <li class="nav-item <?= (isset($active) && $active == 'request') ? 'active' : '' ?>"><a class="nav-link <?= (isset($active) && $active == 'request') ? 'active' : '' ?>" href="<?= URLROOT ?>/admin_users/request"><span class="fas fa-user-plus mr-3 <?= (isset($active) && $active == 'request') ? 'tomato' : '' ?>"></span> Demandes  <?= ($countRequest > 0)? '<span class="badge badge-info ml-5"> '.$countRequest.' </span>' : '' ?></a></li>
      <li class="nav-item <?= (isset($active) && $active == 'attempts') ? 'active' : '' ?>"><a class="nav-link <?= (isset($active) && $active == 'attempts') ? 'active' : '' ?>" href="<?= URLROOT ?>/admin_users/attempts"><span class="fas fa-user-shield mr-3 <?= (isset($active) && $active == 'attempts') ? 'tomato' : '' ?>"></span> Intrusions <?= ($countIntruder > 0)? '<span class="badge badge-danger ml-5"> '.$countIntruder.' </span>' : '' ?></a></li>
    </ul>    

    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
      <span>BDD</span>
    </h6>
    <ul class="nav flex-column mb-2">
      <li class="nav-item <?= (isset($active) && $active == 'database') ? 'active' : '' ?>"><a class="nav-link <?= (isset($active) && $active == 'database') ? 'active' : '' ?>" href="<?= URLROOT ?>/admin_dump"><span class="fas fa-database mr-3 <?= (isset($active) && $active == 'database') ? 'tomato' : '' ?>"></span> Sauvegarde</a></li>
    </ul>
    <?php endif; ?>
  </div>
</nav>
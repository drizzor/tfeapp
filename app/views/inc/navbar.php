<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <div class="<?= $nav_container ?> d-flex flex-column flex-md-row justify-content-between">
      <a style="background-color: transparent; border-right: solid 1px #24caae;" class="navbar-brand" href="<?= URLROOT ?>">
        <img src=<?= URLROOT . "/images/logo.png" ?> style="width:40px" class="d-inline-block align-top" alt="" style="margin-top:5px;">
        <span style="color:#24caae;font-family: 'Open Sans Condensed', sans-serif; font-size:30px;">&nbsp;<?= SITENAME ?>&nbsp;&nbsp;&nbsp;&nbsp;</span> 
      </a>
      <!-- <a class="py-2 d-none d-md-inline-block" href="#">Test</a>       -->
      <ul class="navbar-nav">
          <li style="margin-right: 85px;" class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <img style="width: 25px; border-radius: 50px;" src="<?= URLROOT ?>/images/users/<?= $image ?>" alt="">  &nbsp; <?= "$session" ?>
            </a>          
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a  class="dropdown-item" href="<?= URLROOT; ?>/users/profil/<?= $_SESSION['user_id'] ?>"><span class="fa fa-cog"></span> Modifier mon compte</a>
              <!-- <div class="dropdown-divider"></div> -->
              <a class="dropdown-item" href="<?= URLROOT; ?>/users/logout"><span class="fa fa-power-off"></span> Déconnexion</a>
            </div>
          </li>
        </ul>
    </div>
</nav>
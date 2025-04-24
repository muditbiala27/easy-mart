<?php
$current_page = basename($_SERVER['PHP_SELF']); // Get current page name
?>

<style>
.nav-link.active {
  background-color: #003366; /* Blue Background */
  color: #fff !important; /* White Text */
  font-weight: bold;
  border-radius: 5px;
}
</style>

<div class="col-lg-3 col-md-4 pro" style="background-color: #bac3cd;border-radius:10px;">
   <div class="d-flex align-items-center mb-4 justify-content-center justify-content-md-start">
      <div class="ms-3">
        <h5 class="mb-0">
            <a href="../index.php" class="text-decoration-none text-dark">User</a>
        </h5>
      </div>
   </div>
   <div class="d-md-none text-center d-grid">
      <button class="btn btn-light mb-3 d-flex align-items-center justify-content-between collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAccountMenu" aria-expanded="false" aria-controls="collapseAccountMenu">
      User Menu
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
      </svg>
      </button>
   </div>
   <div class="d-md-block collapse" id="collapseAccountMenu" style="">
      <ul class="nav flex-column nav-account side-menu">
         <li class="nav-item">
            <a class="nav-link text-dark <?php echo ($current_page == 'users_dashboard.php') ? 'active' : ''; ?>" href="users_dashboard.php">
                <span class="ms-2"><strong>Orders</strong></span>
            </a>
         </li>
         <li class="nav-item">
            <a class="nav-link text-dark <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="../index.php">
                <span class="ms-2">Home</span>
            </a>
         </li>
         <li class="nav-item">
            <a class="nav-link text-dark <?php echo ($current_page == 'login.php') ? 'active' : ''; ?>" href="../login.php">
                <span class="ms-2">Sign Out</span>
            </a>
         </li>
      </ul>
   </div>
</div>

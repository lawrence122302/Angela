<!-- <div>
  <div class="container">
    <div class="row">
    <div class="col-md-9">
        <p>A refreshing dip in the gleaming pool, a relaxing overlooking view of the Metro, a satisfying massage, Angela's Resort is ready with an answer. located in the peaceful city of Antipolo Rizal, just a few minutes away from the Cathedral</p>
      </div>
      <div class="col-md-3">
        <img src="<?= base_url('assets/images/logo-angela.jpg') ?>" class="w-25" alt="Angela's Private Pool">
      </div>
    </div>
  </div>
</div> -->

<!-- <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow sticky-top">
  <div class="container">
    <div class="logo-image">
        <img src="<?= base_url('assets/images/logo-angela.jpg') ?>" alt="Angela's Logo" />
    </div>
    <a class="navbar-brand d-block d-sm-none d-md-none" href="#"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">

      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="<?= base_url('index.php') ?>">Home</a>
        </li>
        <?php
          $navbarCategory = "SELECT * FROM categories WHERE navbar_status='0' AND status='0' ";
          $navbarCategory_run = mysqli_query($con, $navbarCategory);

          if(mysqli_num_rows($navbarCategory_run) > 0)
          {
            foreach($navbarCategory_run as $navItems)
            {
              ?>
              <li class="nav-item">
                <a class="nav-link text-white" href="<?= base_url('category/'.$navItems['slug']) ?>"><?= $navItems['name']; ?></a>
              </li>
              <?php
            }
          }
        ?>

        <?php if(isset($_SESSION['auth_user'])) : ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?= $_SESSION['auth_user']['user_name']; ?>
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">My Profile</a></li>
            <li>
              <form action="<?= base_url('allcode.php') ?>" method="POST">
                <button type="submit" name="logout_btn" class="dropdown-item">Logout</button>
              </form>
            </li>
          </ul>
        </li>
        <?php else :  ?>
        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('login.php') ?>">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('register.php') ?>">Register</a>
        </li>
        <?php endif; ?>

      </ul>
      
    </div>
  </div>
</nav> -->

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white px-lg-3 py-lg-0 shadow-sm sticky-top">
  <div class="container-fluid">
    <a class="logo-image" href="<?= base_url('index.php') ?>">
      <img src="<?= base_url('assets/images/logo-angela.jpg') ?>" alt="Angela's Logo" />
    </a>
    <a class="navbar-brand-me-5 fw-bold fs-3 h-font" href="<?= base_url('index.php') ?>">ANGELA</a>
    <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">

      <ul class="navbar-nav me-auto mb-2 mb-lg-0">

        <!-- Home navbar -->
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="<?= base_url('index.php') ?>">Home</a>
        </li>

        <!-- Navbar items from admin -->
        <?php
          $navbarCategory = "SELECT * FROM categories WHERE navbar_status='0' AND status='0' ";
          $navbarCategory_run = mysqli_query($con, $navbarCategory);

          if(mysqli_num_rows($navbarCategory_run) > 0)
          {
            foreach($navbarCategory_run as $navItems)
            {
              ?>
              <li class="nav-item">
                <a class="nav-link me-2" href="<?= base_url('category/'.$navItems['slug']) ?>"><?= $navItems['name']; ?></a>
              </li>
              <?php
            }
          }
        ?>

        <?php if(isset($_SESSION['auth_user'])) : ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?= $_SESSION['auth_user']['user_name']; ?>
          </a>
          <ul class="dropdown-menu">
            <li>
              <form action="<?= base_url('allcode.php') ?>" method="POST">
                <button type="submit" name="logout_btn" class="dropdown-item">Logout</button>
              </form>
            </li>
          </ul>
        </li>
        <?php else :  ?>
        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('login.php') ?>">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?= base_url('register.php') ?>">Register</a>
        </li>
        <?php endif; ?>

      </ul>

      <!-- Home page login and registration buttons -->
      <div class="d-flex">
        <button type="button" class="btn btn-outline-dark shadow-none me-lg-3 me-2 mb-2" data-bs-toggle="modal" data-bs-target="#loginModal">
          Login
        </button>
        <button type="button" class="btn btn-outline-dark shadow-none mb-2" data-bs-toggle="modal" data-bs-target="#registerModal">
          Register
        </button>
      </div>

    </div>
  </div>
</nav>

<!-- Login modal -->
<div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title d-flex align-items-center">
            <i class="bi bi-person-circle fs-3 me-2"></i> User Login
          </h5>
          <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" class="form-control shadow-none">
          </div>
          <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" class="form-control shadow-none">
          </div>
          <div class="d-flex align-items-center justify-content-between mb-2">
            <button type="submit" class="btn btn-dark shadow-none">Login</button>
            <a href="javascript: void(0)" class="text-secondary text-decoration-none">Forgot Password</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Registration modal -->
<div class="modal fade" id="registerModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form>
        <div class="modal-header">
          <h5 class="modal-title d-flex align-items-center">
            <i class="bi bi-person-lines-fill fs-3 me-2"></i>
            User Registration
          </h5>
          <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <span class="badge rounded-pill bg-light text-dark mb-3 text-wrap lh-base">
            Note: Your details must match those on your government-issued ID (e.g., National ID, Passport, Driving License).
          </span>
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-6 ps-0 mb-3">
                <label class="form-label">Name</label>
                <input type="text" class="form-control shadow-none">
              </div>
              <div class="col-md-6 p-0 mb-3">
                <label class="form-label">Email address</label>
                <input type="email" class="form-control shadow-none">
              </div>
              <div class="col-md-6 ps-0 mb-3">
                <label class="form-label">Phone number</label>
                <input type="number" class="form-control shadow-none">
              </div>
              <div class="col-md-6 p-0 mb-3">
                <label class="form-label">Picture</label>
                <input type="file" class="form-control shadow-none">
              </div>
              <div class="col-md-12 p-0 mb-3">
                <label class="form-label">Address</label>
                <textarea class="form-control shadow-none" rows="1"></textarea>
              </div>
              <div class="col-md-6 ps-0 mb-3">
                <label class="form-label">Pincode</label>
                <input type="number" class="form-control shadow-none">
              </div>
              <div class="col-md-6 p-0 mb-3">
                <label class="form-label">Date of birth</label>
                <input type="date" class="form-control shadow-none">
              </div>
              <div class="col-md-6 ps-0 mb-3">
                <label class="form-label">Password</label>
                <input type="password" class="form-control shadow-none">
              </div>
              <div class="col-md-6 p-0 mb-3">
                <label class="form-label">Confirm password</label>
                <input type="password" class="form-control shadow-none">
              </div>
            </div>
          </div>
          <div class="text-center my-1">
            <button type="submit" class="btn btn-dark shadow-none">Register</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
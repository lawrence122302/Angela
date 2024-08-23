<?php
include('includes/config.php');

$page_title = "Home Page";
$meta_description = "Home page description resort website";
$meta_keywords = "resort, swimming pools, serenity, luxury, retreat, wellness, relaxation, celebration";

include('includes/header.php');
include('includes/navbar.php');
?>

<!-- Carousel -->
<div class="container-fluid px-lg-4 mt-4">
  <div class="swiper swiper-container">
    <div class="swiper-wrapper">
      <div class="swiper-slide">
        <img src="assets/images/IMG_15372.png" class="w-100 d-block" />
      </div>
      <div class="swiper-slide">
        <img src="assets/images/IMG_55677.png" class="w-100 d-block" />
      </div>
      <div class="swiper-slide">
        <img src="assets/images/IMG_40905.png" class="w-100 d-block" />
      </div>
    </div>
  </div>
</div>

<!-- Check availability form -->
 <div class="container availability-form">
    <div class="row">
        <div class="col-lg-12 bg-white shadow p-4 rounded mb-4">
            <h5 class="mb-4">Check Booking Availability</h5>
            <form>
                <div class="row align-items-end">
                    <div class="col-lg-3 mb-3">
                        <label class="form-label" style="font-weight: 500;">Check-in</label>
                        <input type="date" class="form-control shadow-none">
                    </div>
                    <div class="col-lg-3 mb-3">
                        <label class="form-label" style="font-weight: 500;">Check-out</label>
                        <input type="date" class="form-control shadow-none">
                    </div>
                    <div class="col-lg-3 mb-3">
                        <label class="form-label" style="font-weight: 500;">Adult</label>
                        <select class="form-select shadow-none">
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                    <div class="col-lg-2 mb-3">
                        <label class="form-label" style="font-weight: 500;">Children</label>
                        <select class="form-select shadow-none">
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                    <div class="col-lg-1 mb-lg-3 mt-2">
                        <button type="submit" class="btn text-white shadow-none custom-bg">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
 </div>

<!-- Navbar items -->
<div class="py-5 bg-dark">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="text-white">Category</h3>
                <div class="underline"></div>
            </div>

            <?php
                $homeCategory = "SELECT * FROM categories WHERE navbar_status='0' AND status='0' LIMIT 12";
                $homeCategory_run = mysqli_query($con, $homeCategory);
    
                if(mysqli_num_rows($homeCategory_run) > 0)
                {
                    foreach($homeCategory_run as $homeCateItem)
                    {
                        ?>
                            <div class="col-md-3 mb-4">
                                <a class="text-decoration-none" href="category.php?title=<?= $homeCateItem['slug']; ?>">
                                    <div class="card card-body">
                                        <?= $homeCateItem['name']; ?>
                                    </div>
                                </a>
                            </div>
                        <?php
                    }
                }
            ?>
        </div>
    </div>
</div>

<div class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h3 class="text-dark">Angela's Private Pool</h3>
                <div class="underline"></div>
                <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam finibus suscipit sapien, non accumsan lacus pretium in. Sed nec ipsum quis sapien vestibulum sollicitudin sit amet id augue. Morbi hendrerit vestibulum enim in molestie. Integer risus leo, condimentum quis ex sit amet, finibus viverra magna.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="py-5 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <h3 class="text-dark">Latest Posts</h3>
                <div class="underline"></div>

                <?php
                    $homePosts = "SELECT * FROM posts WHERE status='0' ORDER BY id DESC LIMIT 12";
                    $homePosts_run = mysqli_query($con, $homePosts);
        
                    if(mysqli_num_rows($homePosts_run) > 0)
                    {
                        foreach($homePosts_run as $homePostItem)
                        {
                            ?>
                                <div class="mb-4">
                                    <a class="text-decoration-none" href="post.php?title=<?= $homePostItem['slug']; ?>">
                                        <div class="card card-body bg-light">
                                            <?= $homePostItem['name']; ?>
                                        </div>
                                    </a>
                                </div>
                            <?php
                        }
                    }
                ?>

            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h4>Reach Us</h4>
                    </div>
                    <div class="card-body">
                        info@example.com
                    </div>
                </div>
            </div>

            
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>
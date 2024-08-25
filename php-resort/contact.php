<?php
include('includes/config.php');

$page_title = "Angela's Private Pool - Home";
$meta_description = "Home page description resort website";
$meta_keywords = "resort, swimming pools, serenity, luxury, retreat, wellness, relaxation, celebration";

include('includes/header.php');
include('includes/navbar.php');
?>

<div class="my-5 px-4">
    <h2 class="fw-bold h-font text-center">Contact Us</h2>
    <div class="h-line bg-dark"></div>
    <p class="text-center mt-3">
        Lorem ipsum dolor sit amet consectetur adipisicing elit. 
        Rem dolor deleniti id est <br> doloremque incidunt 
        temporibus neque nostrum aliquam suscipit.
    </p>
</div>

<div class="container">
    <div class="row">
        <div class="col-lg-6 col-md-6 mb-5 px-4">

            <div class="bg-white rounded shadow p-4">
                <iframe class="w-100 rounded mb-4" height="320px" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3861.019837187774!2d121.15964256227016!3d14.597945503097405!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b916524f4a2d%3A0xa0f823518f211091!2sAngela&#39;s%20Resort%201!5e0!3m2!1sen!2sph!4v1724503646385!5m2!1sen!2sph" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

                <h5>Address</h5>
                <a href="https://maps.app.goo.gl/bD5JE7NdBXFnhiR99" target="_blank" class="d-inline-block text-decoration-none text-dark mb-2">
                    <i class="bi bi-geo-alt-fill"></i> Angela's Resort 1
                </a>
                
                <h5 class="mt-4">Call Us</h5>
                <a href="#tel: +639123456789" class="d-inline-block mb-2 text-decoration-none text-dark">
                    <i class="bi bi-telephone-fill"></i> +639123456789
                </a>
                <br>
                <a href="#tel: +639123456789" class="d-inline-block text-decoration-none text-dark">
                    <i class="bi bi-telephone-fill"></i> +639123456789
                </a>
                
                <h5 class="mt-4">Email</h5>
                <a href="mailto: angela_example@gmail.com" class="d-inline-block text-decoration-none text-dark">
                    <i class="bi bi-envelope-fill"></i> angela_example@gmail.com
                </a>

                <h5 class="mt-4">Follow Us</h5>
                <a href="#" class="d-inline-block text-dark fs-5 me-2">
                    <i class="bi bi-twitter me-1"></i>
                </a>
                <a href="#" class="d-inline-block text-dark fs-5 me-2">
                    <i class="bi bi-facebook me-1"></i>
                </a>
                <a href="#" class="d-inline-block text-dark fs-5">
                    <i class="bi bi-instagram me-1"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 px-4">
            <div class="bg-white rounded shadow p-4">
                <form>
                    <h5>Send a message</h5>
                    <div class="mt-3">
                        <label class="form-label" style="font-weight: 500;">Name</label>
                        <input type="text" class="form-control shadow-none">
                    </div>
                    <div class="mt-3">
                        <label class="form-label" style="font-weight: 500;">Email</label>
                        <input type="email" class="form-control shadow-none">
                    </div>
                    <div class="mt-3">
                        <label class="form-label" style="font-weight: 500;">Subject</label>
                        <input type="text" class="form-control shadow-none">
                    </div>
                    <div class="mt-3">
                        <label class="form-label" style="font-weight: 500;">Message</label>
                        <textarea class="form-control shadow-none" rows="5" style="resize: none;"></textarea>
                    </div>
                    <button type="submit" class="btn text-white custom-bg mt-3">Send</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
include('includes/footer.php');
?>
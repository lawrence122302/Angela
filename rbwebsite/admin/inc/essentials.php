<?php
    require(__DIR__ . '/db_config.php');

    // frontend purpose data
    // Check if the script is running in a local or production environment
    if ($_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) {
        // Local environment
        define('SITE_URL', 'http://127.0.0.1/Angela/rbwebsite/');
        define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'] . '/Angela/rbwebsite');
    } else {
        // Production environment
        define('SITE_URL', 'https://angelasprivatepool.com/');
        define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
    }

    define('ABOUT_IMG_PATH',SITE_URL.'images/about/');
    define('CAROUSEL_IMG_PATH',SITE_URL.'images/carousel/');
    define('FACILITIES_IMG_PATH',SITE_URL.'images/facilities/');
    define('ROOMS_IMG_PATH',SITE_URL.'images/rooms/');
    define('USERS_IMG_PATH',SITE_URL.'images/users/');
    
    define('SETTINGS_FILE_PATH',SITE_URL.'files/settings/');

    // Backup path
    define('BACKUP_PATH', DOCUMENT_ROOT . '/admin/backup/');

    // Backend upload process needs this data
    define('UPLOAD_IMAGE_PATH', DOCUMENT_ROOT . '/images/');
    define('ABOUT_FOLDER', 'about/');
    define('CAROUSEL_FOLDER', 'carousel/');
    define('FACILITIES_FOLDER', 'facilities/');
    define('ROOMS_FOLDER', 'rooms/');
    define('USERS_FOLDER', 'users/');

    // phpmail
    define('MAILHOST','smtp.gmail.com');
    define('USERNAME','angelasprivatepool@gmail.com');
    define('PASSWORD','miqd izdk uogm bczt');
    define('SEND_FROM','angelasprivatepool@gmail.com');
    define('SEND_FROM_NAME',"Angela's Private Pool");
    define('REPLY_TO','angelasprivatepool@gmail.com');
    define('REPLY_TO_NAME',"Angela's Private Pool");

    // Account status check using setInterval

    if (isset($_POST['status']) && $_POST['status'] === 'check') {
        session_name('admin_session');
        session_start();
        
        $query = "SELECT * FROM admin_cred WHERE sr_no=?";
        $values = [$_SESSION['adminId']];
        $res = select($query,$values,"i");
        $row = mysqli_fetch_assoc($res);

        $_SESSION['status'] = $row['status'];

        if (isset($_SESSION['status']) && $_SESSION['status'] == 0) {
            echo 'inactive';
        } else {
            echo 'active';
        }
        exit();
    }

    function adminLogin()
    {
        session_name('admin_session');
        session_start();
        
        if(!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin']==true))
        {
            redirect('index.php');
            exit;
        }

        $query = "SELECT * FROM admin_cred WHERE sr_no=?";
        $values = [$_SESSION['adminId']];
        $res = select($query,$values,"i");
        $row = mysqli_fetch_assoc($res);

        $_SESSION['status'] = $row['status'];

        // Check session token
        
        if ($row['session_token'] !== $_SESSION['session_token']) {
            session_destroy();
            redirect('index.php?alert=another_login');
            exit;
        }

        // Account status check for page loads

        if (isset($_SESSION['status']) && $_SESSION['status'] == 0) {
            session_destroy();
            redirect('index.php?alert=account_deactivated');
            exit();
        }
    }

    function superAdminLogin()
    {
        adminLogin();
        if($_SESSION['isSuperAdmin']!=1)
        {
            echo"<script>
                window.location.href='dashboard.php';
            </script>";
            exit;
        }
    }

    function redirect($url)
    {
        echo"<script>
            window.location.href='$url';
        </script>";
        exit;
    }

    function alert($type,$msg)
    {
        $bs_class = ($type == "success") ? "alert-success" : "alert-danger";
        echo <<<alert
            <div class="alert $bs_class alert-dismissible fade show custom-alert" role="alert">
                <strong class="me-3">$msg</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        alert;
    }

    function uploadImage($image,$folder)
    {
        $valid_mime = ['image/jpeg','image/png','image/webp','image/svg+xml'];
        $img_mime = $image['type'];

        if(!in_array($img_mime,$valid_mime))
        {
            return 'inv_img'; // invalid image mime or format
        }
        else if(($image['size']/(1024*1024))>2)
        {
            return 'inv_size'; // invalid size greater than 2mb
        }
        else
        {
            $ext = pathinfo($image['name'],PATHINFO_EXTENSION);
            $rname = 'IMG_'.random_int(11111,99999).".$ext";

            $img_path = UPLOAD_IMAGE_PATH.$folder.$rname;
            if(move_uploaded_file($image['tmp_name'],$img_path))
            {
                return $rname;
            }
            else
            {
                return 'upd_failed';
            }
        }
    }

    function deleteImage($image, $folder)
    {
        if(unlink(UPLOAD_IMAGE_PATH.$folder.$image))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function uploadSVGImage($image,$folder)
    {
        $valid_mime = ['image/svg+xml'];
        $img_mime = $image['type'];

        if(!in_array($img_mime,$valid_mime))
        {
            return 'inv_img'; // invalid image mime or format
        }
        else if(($image['size']/(1024*1024))>1)
        {
            return 'inv_size'; // invalid size greater than 1mb
        }
        else
        {
            $ext = pathinfo($image['name'],PATHINFO_EXTENSION);
            $rname = 'IMG_'.random_int(11111,99999).".$ext";

            $img_path = UPLOAD_IMAGE_PATH.$folder.$rname;
            if(move_uploaded_file($image['tmp_name'],$img_path))
            {
                return $rname;
            }
            else
            {
                return 'upd_failed';
            }
        }
    }

    function uploadUserImage($image)
    {
        $valid_mime = ['image/jpeg','image/png','image/webp'];
        $img_mime = $image['type'];

        if(!in_array($img_mime,$valid_mime))
        {
            return 'inv_img'; // invalid image mime or format
        }
        else
        {
            $ext = pathinfo($image['name'],PATHINFO_EXTENSION);
            $rname = 'IMG_'.random_int(11111,99999).".jpeg";

            $img_path = UPLOAD_IMAGE_PATH.USERS_FOLDER.$rname;

            if($ext == 'png' || $ext == 'PNG')
            {
                $img = imagecreatefrompng($image['tmp_name']);
            }
            else if($ext == 'webp' || $ext == 'WEBP')
            {
                $img = imagecreatefromwebp($image['tmp_name']);
            }
            else
            {
                $img = imagecreatefromjpeg($image['tmp_name']);
            }

            if(imagejpeg($img,$img_path,75))
            {
                return $rname;
            }
            else
            {
                return 'upd_failed';
            }
        }
    }

?>
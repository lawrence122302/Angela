<?php
    require('inc/essentials.php');
    superAdminLogin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Users</title>
    <?php require('inc/links.php'); ?>
</head>
<body class="bg-light">

    <?php
        require('inc/header.php');

        $u_exist_admin = select("SELECT * FROM admin_cred WHERE is_super_admin=? LIMIT 1",[1],'i');

        if(mysqli_num_rows($u_exist_admin)==0)
        {
            redirect('index.php');
        }

        $u_fetch_admin = mysqli_fetch_assoc($u_exist_admin);

        $u_exist_email = select("SELECT * FROM contact_details WHERE sr_no=?",[1],'i');

        if(mysqli_num_rows($u_exist_email)==0)
        {
            redirect('index.php');
        }

        $u_fetch_email = mysqli_fetch_assoc($u_exist_email);
    
    ?>

    <div class="container-fluid" id="main-content">
        <div class="row">

            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">Profile</h3>

                <div class="card border-0 shadow-sm mb-2">
                    <div class="card-body">

                        <form id="info-form">
                            <h5 class="mb-3 fw-bold">Super Admin Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Name</label>
                                    <input name="admin_name" type="text" value="<?php echo $u_fetch_admin['admin_name'] ?>" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input name="email" type="email" value="<?php echo $u_fetch_email['email'] ?>" class="form-control shadow-none" required>
                                </div>
                            </div>
                            <button type="submit" class="btn text-white custom-bg shadow-none">Save Changes</button>
                        </form>

                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <form id="pass-form">
                            <h5 class="mb-3 fw-bold">Change Password</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">New Password</label>
                                    <input name="new_pass" type="password" class="form-control shadow-none" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Confirm Password</label>
                                    <input name="confirm_pass" type="password" class="form-control shadow-none" required>
                                </div>
                            </div>
                            <button type="submit" class="btn text-white custom-bg shadow-none">Save Changes</button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>


    <?php require('inc/scripts.php'); ?>

    <script>
        let info_form = document.getElementById('info-form');

        info_form.addEventListener('submit',function(e){
            e.preventDefault();

            let data = new FormData();
            data.append('info_form','');
            data.append('admin_name',info_form.elements['admin_name'].value);
            data.append('email',info_form.elements['email'].value);

            let xhr = new XMLHttpRequest();
            xhr.open("POST","ajax/super_admin_profile.php",true);

            xhr.onload = function()
            {
                if(this.responseText == 0)
                {
                    alert('error','No Changes Made!');
                }
                else
                {
                    alert('success','Changes saved!');
                }
            }

            xhr.send(data);
        });

        let pass_form = document.getElementById('pass-form');

        pass_form.addEventListener('submit',function(e){
            e.preventDefault();

            let new_pass = pass_form.elements['new_pass'].value;
            let confirm_pass = pass_form.elements['confirm_pass'].value;

            if(new_pass!=confirm_pass)
            {
                alert('error','Password do not match!');
                return false;
            }

            let data = new FormData();
            data.append('pass_form','');
            data.append('new_pass',new_pass);
            data.append('confirm_pass',confirm_pass);

            let xhr = new XMLHttpRequest();
            xhr.open("POST","ajax/super_admin_profile.php",true);

            xhr.onload = function()
            {
                if(this.responseText == 'mismatch')
                {
                    alert('error',"Password do not match!");
                }
                else if(this.responseText == 0)
                {
                    alert('error','Update Failed!');
                }
                else
                {
                    alert('success','Changes saved!');
                    pass_form.reset();
                }
            }

            xhr.send(data);
        });
    </script>

</body>
</html>
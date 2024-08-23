<?php
include('authentication.php');

if(isset($_POST['booking_record_update']))
{
    $booking_record_id = $_POST['booking_record_id'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $status = $_POST['status'] == true ? '1':'0';

    $query = "UPDATE booking_records SET fname='$fname', mname='$mname', lname='$lname', phone='$phone', email='$email', 
                status='$status' WHERE id='$booking_record_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Record Updated Sucessfully";
        header('Location: booking-record-edit.php?id='.$booking_record_id);
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something Went Wrong";
        header('Location: booking-record-edit.php?id='.$booking_record_id);
        exit(0);
    }
}

if(isset($_POST['bookedrecord_add']))
{
    $name = $_POST['name'];
    
    $string = preg_replace('/[^A-Za-z0-9\-]/', '-', $_POST['slug']); // Remove all special characters
    $final_string = preg_replace('/-+/', '-', $string);
    $slug = $final_string;

    $description = $_POST['description'];

    $meta_title = $_POST['meta_title'];
    $meta_description = $_POST['meta_description'];
    $meta_keyword = $_POST['meta_keyword'];

    $navbar_status = $_POST['navbar_status'] == true ? '1':'0';
    $status = $_POST['status'] == true ? '1':'0';

    $query = "INSERT INTO categories(name,slug,description,meta_title,meta_description,meta_keyword,navbar_status,status) 
                VALUES('$name','$slug','$description','$meta_title','$meta_description','$meta_keyword','$navbar_status','$status')";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Category Added Sucessfully";
        header('Location: category-add.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something Went Wrong";
        header('Location: category-add.php');
        exit(0);
    }
}

if(isset($_POST['post_update']))
{
    $post_id = $_POST['post_id'];

    $category_id = $_POST['category_id'];
    $name = $_POST['name'];

    $string = preg_replace('/[^A-Za-z0-9\-]/', '-', $_POST['slug']); // Remove all special characters
    $final_string = preg_replace('/-+/', '-', $string);
    $slug = $final_string;
    
    $description = $_POST['description'];

    $meta_title = $_POST['meta_title'];
    $meta_description = $_POST['meta_description'];
    $meta_keyword = $_POST['meta_keyword'];

    $old_filename = $_POST['old_image'];
    $image = $_FILES['image']['name'];

    $update_filename = "";
    if($image != NULL)
    {
        // Rename this image
        $image_extension = pathinfo($image, PATHINFO_EXTENSION);
        $filename = time().'.'.$image_extension;

        $update_filename = $filename;
    }
    else
    {
        $update_filename = $old_filename;
    }
    
    $status = $_POST['status'] == true ? '1':'0';

    $query = "UPDATE posts SET category_id='$category_id',name='$name',slug='$slug',description='$description',
                meta_title='$meta_title',meta_description='$meta_description',meta_keyword='$meta_keyword',
                image='$update_filename',status='$status' WHERE id='$post_id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        if($image != NULL)
        {
            if(file_exists('../uploads/posts/'.$old_filename))
            {
                unlink("../uploads/posts/".$old_filename);
            }
            move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/posts/'.$update_filename);
        }
        $_SESSION['message'] = "Post Updated Sucessfully";
        header('Location: post-edit.php?id='.$post_id);
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something Went Wrong";
        header('Location: post-edit.php?id='.$post_id);
        exit(0);
    }
}

if(isset($_POST['post_add']))
{
    $category_id = $_POST['category_id'];

    $name = $_POST['name'];
    
    $string = preg_replace('/[^A-Za-z0-9\-]/', '-', $_POST['slug']); // Remove all special characters
    $final_string = preg_replace('/-+/', '-', $string);
    $slug = $final_string;

    $description = $_POST['description'];

    $meta_title = $_POST['meta_title'];
    $meta_description = $_POST['meta_description'];
    $meta_keyword = $_POST['meta_keyword'];

    $image = $_FILES['image']['name'];
    // Rename this image
    $image_extension = pathinfo($image, PATHINFO_EXTENSION);
    $filename = time().'.'.$image_extension;
    
    $status = $_POST['status'] == true ? '1':'0';

    $query = "INSERT INTO posts(category_id,name,slug,description,image,meta_title,meta_description,meta_keyword,status) 
                VALUES('$category_id','$name','$slug','$description','$filename','$meta_title','$meta_description','$meta_keyword','$status')";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/posts/'.$filename);
        $_SESSION['message'] = "Post Created Sucessfully";
        header('Location: post-add.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something Went Wrong";
        header('Location: post-add.php');
        exit(0);
    }
}

if(isset($_POST['category_update']))
{
    $category_id = $_POST['category_id'];

    $name = $_POST['name'];
    
    $string = preg_replace('/[^A-Za-z0-9\-]/', '-', $_POST['slug']); // Remove all special characters
    $final_string = preg_replace('/-+/', '-', $string);
    $slug = $final_string;

    $description = $_POST['description'];

    $meta_title = $_POST['meta_title'];
    $meta_description = $_POST['meta_description'];
    $meta_keyword = $_POST['meta_keyword'];

    $navbar_status = $_POST['navbar_status'] == true ? '1':'0';
    $status = $_POST['status'] == true ? '1':'0';

    $query = "UPDATE categories SET name='$name', slug='$slug', description='$description', 
                meta_title='$meta_title', meta_description='$meta_description', 
                meta_keyword='$meta_keyword', navbar_status='$navbar_status', status='$status' WHERE id='$category_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Category Updated Sucessfully";
        header('Location: category-edit.php?id='.$category_id);
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something Went Wrong";
        header('Location: category-edit.php?id='.$category_id);
        exit(0);
    }
}

if(isset($_POST['category_add']))
{
    $name = $_POST['name'];
    
    $string = preg_replace('/[^A-Za-z0-9\-]/', '-', $_POST['slug']); // Remove all special characters
    $final_string = preg_replace('/-+/', '-', $string);
    $slug = $final_string;

    $description = $_POST['description'];

    $meta_title = $_POST['meta_title'];
    $meta_description = $_POST['meta_description'];
    $meta_keyword = $_POST['meta_keyword'];

    $navbar_status = $_POST['navbar_status'] == true ? '1':'0';
    $status = $_POST['status'] == true ? '1':'0';

    $query = "INSERT INTO categories(name,slug,description,meta_title,meta_description,meta_keyword,navbar_status,status) 
                VALUES('$name','$slug','$description','$meta_title','$meta_description','$meta_keyword','$navbar_status','$status')";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Category Added Sucessfully";
        header('Location: category-add.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something Went Wrong";
        header('Location: category-add.php');
        exit(0);
    }
}

if(isset($_POST['add_user']))
{
    $user_id = $_POST['user_id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role_as = $_POST['role_as'];
    $status = $_POST['status'] == true ? '1':'0';

    $query = "INSERT INTO users (fname,lname,email,password,role_as,status) 
    VALUES ('$fname','$lname','$email','$password','$role_as','$status')";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Added Sucessfully";
        header('Location: view-register.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something Went Wrong";
        header('Location: view-register.php');
        exit(0);
    }
}

if(isset($_POST['update_user']))
{
    $user_id = $_POST['user_id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role_as = $_POST['role_as'];
    $status = $_POST['status'] == true ? '1':'0';

    $query = "UPDATE users SET fname='$fname', lname='$lname', email='$email', password='$password', role_as='$role_as', status='$status'
                WHERE id='$user_id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Updated Successfully";
        header('Location: view-register.php');
        exit(0);
    }

}

?>
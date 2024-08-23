<?php
include('authentication.php');
include('middleware/superadminAuth.php');

if(isset($_POST['booking_record_delete_btn']))
{
    $booking_record_id = $_POST['booking_record_delete_btn'];

    // 2 = delete
    $query = "UPDATE booking_records SET status='2' WHERE id='$booking_record_id' LIMIT 1 ";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Record Deleted Sucessfully";
        header('Location: booking-record-view.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something Went Wrong";
        header('Location: booking-record-view.php');
        exit(0);
    }
}

if(isset($_POST['post_delete_btn']))
{
    $post_id = $_POST['post_delete_btn'];

    // $check_img_query = "SELECT * FROM posts WHERE id='$post_id' LIMIT 1";
    // $img_res = mysqli_query($con, $check_img_query);
    // $res_data = mysqli_fetch_array($img_res);
    // $image = $res_data['image'];

    // $query = "DELETE FROM posts WHERE id='$post_id' LIMIT 1";
    $query = "UPDATE posts SET status='2' WHERE id='$post_id' LIMIT 1";
    $query_run = mysqli_query($con, $query);
    
    if($query_run)
    {
        // if(file_exists('../uploads/posts/'.$image))
        // {
        //     unlink("../uploads/posts/".$image);
        // }

        $_SESSION['message'] = "Post Deleted Sucessfully";
        header('Location: post-view.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something Went Wrong";
        header('Location: post-view.php');
        exit(0);
    }
}

if(isset($_POST['category_delete']))
{
    $category_id = $_POST['category_delete'];

    // 2 = delete
    $query = "UPDATE categories SET status='2' WHERE id='$category_id' LIMIT 1 ";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "Category Deleted Sucessfully";
        header('Location: category-view.php');
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "Something Went Wrong";
        header('Location: category-view.php');
        exit(0);
    }
}

if(isset($_POST['user_delete']))
{
    $user_id = $_POST['user_delete'];

    $query = "DELETE FROM users WHERE id='$user_id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "User Record Deleted Sucessfully";
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

?>
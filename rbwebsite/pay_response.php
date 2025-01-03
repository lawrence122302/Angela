<?php
    require('admin/inc/essentials.php');

    date_default_timezone_set("Asia/Manila");

    session_name('user_session');
    session_start();

    unset($_SESSION['room']);

    function regenerate_session($uid)
    {
        $user_q = select("SELECT * FROM user_cred WHERE id=? LIMIT 1",[$uid],'i');

        $user_fetch = mysqli_fetch_assoc($user_q);

        $_SESSION['login'] = true;
        $_SESSION['uId'] = $user_fetch['id'];
        $_SESSION['uName'] = $user_fetch['name'];
        $_SESSION['uPic'] = $user_fetch['profile'];
        $_SESSION['uPhone'] = $user_fetch['phonenum'];
    }

    if($condition == "TRUE")
    {
        $slct_query = "SELECT booking_id, user_id FROM booking_order 
            WHERE order_id='$_POST[ORDERID]'";

        $slct_res = mysqli_query($con, $slct_query);

        if(mysqli_num_rows($slct_res)==0)
        {
            redirect('index.php');
        }
        
        $slct_fetch = mysqli_fetch_assoc($slct_res);

        if(!(isset($_SESSION['login']) && $_SESSION['login']==true))
        {
            regenerate_session($_slct_res['user_id']);
        }

        if($_POST["STATUS"] == "TXN SUCCESS")
        {
            $upd_query = "UPDATE booking_order SET booking_status='booked', 
                trans_id='$_POST[TXNID]', trans_amt='$_POST[TXNAMOUNT]', 
                trans_status = '$_POST[STATUS]', trans_res_msg_='$_POST[RESPMSG]' 
                WHERE booking_id='$slct_fetch[booking_id]'";

            mysqli_query($con,$upd_query);
        }
        else
        {
            $upd_query = "UPDATE booking_order SET booking_status='payment failed', 
                trans_id='$_POST[TXNID]', trans_amt='$_POST[TXNAMOUNT]', 
                trans_status = '$_POST[STATUS]', trans_res_msg_='$_POST[RESPMSG]' 
                WHERE booking_id='$slct_fetch[booking_id]'";

            mysqli_query($con,$upd_query);
        }
        redirect('pay_status.php?order='.$_POST['ORDERID']);
    }
    else
    {
        redirect('index.php');
    }
?>
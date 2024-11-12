<?php
    require('../inc/essentials.php');
    superAdminLogin();

    if(isset($_POST['get_admins']))
    {
        $res = select("SELECT * FROM admin_cred WHERE is_super_admin!=?",[1],'i');
        $i=1;

        $data = "";

        while($row = mysqli_fetch_assoc($res))
        {
            $status = "<button onclick='toggle_status($row[sr_no],0)' class='btn btn-dark btn-sm shadow-none'>active</button>";

            if(!$row['status'])
            {
                $status = "<button onclick='toggle_status($row[sr_no],1)' class='btn btn-danger btn-sm shadow-none'>inactive</button>";
            }
            
            $edit = "<button type='button' onclick='openEditModal($row[sr_no])' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#editAdminModal'>
                <i class='bi bi-pencil-square'></i> Change Password
            </button>";
            
            $data.="
                <tr>
                    <td>$i</td>
                    <td>
                        $row[admin_name]
                    </td>
                    <td>$status</td>
                    <td>$edit</td>
                </tr>
            ";
            $i++;
        }

        echo $data;
    }

    if(isset($_POST['add_admin']))
    {
        $frm_data = filteration($_POST);

        $q = "INSERT INTO admin_cred(admin_name, admin_pass) VALUES (?,?)";
        $values = [$frm_data['adminName'],$frm_data['password']];
        $res = insert($q,$values,'ss');
        echo $res;
    }

    if(isset($_POST['edit_admin']))
    {
        $frm_data = filteration($_POST);

        $q = "UPDATE admin_cred SET admin_pass=? WHERE sr_no=?";
        $v = [$frm_data['password'],$frm_data['admin_id']];

        if(update($q,$v,'si'))
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }

    if(isset($_POST['toggle_status']))
    {
        $frm_data = filteration($_POST);

        $q = "UPDATE admin_cred SET status=? WHERE sr_no=?";
        $v = [$frm_data['value'],$frm_data['toggle_status']];

        if(update($q,$v,'ii'))
        {
            echo 1;
        }
        else
        {
            echo 0;
        }
    }

    if(isset($_POST['search_user']))
    {
        $frm_data = filteration($_POST);

        $query = "SELECT * FROM admin_cred WHERE name LIKE ?";

        $res = select($query,["%$frm_data[name]%"],'s');
        $i=1;

        $data = "";

        while($row = mysqli_fetch_assoc($res))
        {
            $del_btn = "<button type='button' onclick='remove_user($row[id])' class='btn btn-danger shadow-none btn-sm'>
                <i class='bi bi-trash'></i>
            </button>";

            $status = "<button onclick='toggle_status($row[sr_no],0)' class='btn btn-dark btn-sm shadow-none'>active</button>";

            if(!$row['status'])
            {
                $status = "<button onclick='toggle_status($row[sr_no],1)' class='btn btn-danger btn-sm shadow-none'>inactive</button>";
            }

            $data.="
                <tr>
                    <td>$i</td>
                    <td>
                        $row[admin_name]
                    </td>
                    <td>$status</td>
                    <td>$del_btn</td>
                </tr>
            ";
            $i++;
        }

        echo $data;
    }

?>
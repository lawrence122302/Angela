function get_admins()
{
    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/admins.php",true);
    xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

    xhr.onload = function()
    {
        document.getElementById('admins-data').innerHTML = this.responseText;
    }
    xhr.send('get_admins');
}

let addAdminForm = document.getElementById('addAdminForm');

addAdminForm.addEventListener('submit', function(e) {
    e.preventDefault();

    let data = new FormData(addAdminForm);
    data.append('add_admin', '');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/admins.php", true);

    xhr.onload = function() {
        var myModal = document.getElementById('addAdminModal');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if (xhr.status === 200 && xhr.responseText == 1) {
            alert('success', 'New admin added!');
            addAdminForm.reset();
            get_admins();
        } else if (xhr.responseText == 'admin_exists') {
            alert('error', 'Admin name already exists!');
        } else {
            alert('error', 'Server Down!');
        }
    };

    xhr.onerror = function() {
        alert('error', 'Request error!');
    };

    xhr.send(data);
});

const editAdminForm = document.getElementById('editAdminForm');

editAdminForm.addEventListener('submit', function(e) {
    e.preventDefault();
    submitEditAdmin();
});

window.openEditModal = function(id) {
    document.querySelector('input[name="admin_id"]').value = id;
};

function submitEditAdmin() {
    const data = new FormData(editAdminForm);
    data.append('edit_admin', '');
    data.append('admin_id', editAdminForm.elements['admin_id'].value);
    data.append('password', editAdminForm.elements['password'].value);

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/admins.php", true);

    xhr.onload = function() {
        const myModal = document.getElementById('editAdminModal');
        const modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if (xhr.status === 200 && xhr.responseText == 1) {
            alert('success', 'Password changed!');
            editAdminForm.reset();
            get_admins();
        } else {
            alert('error', 'Server Down!');
        }
    };

    xhr.onerror = function() {
        alert('error', 'Request error!');
    };

    xhr.send(data);
}

function toggle_status(id,val)
{
    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/admins.php",true);
    xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

    xhr.onload = function()
    {
        if(this.responseText==1)
        {
            alert('success','Status toggled!');
            get_admins();
        }
        else
        {
            alert('success','Server Down!');
        }
    }
    xhr.send('toggle_status='+id+'&value='+val);
}

function search_admin(username)
{
    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/admins.php",true);
    xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

    xhr.onload = function()
    {
        document.getElementById('admins-data').innerHTML = this.responseText;
    }
    xhr.send('search_admin&name='+username);
}

window.onload = function()
{
    get_admins();
}
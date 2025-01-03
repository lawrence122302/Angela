let general_data, contacts_data;

let general_s_form = document.getElementById('general_s_form');
let site_title_inp = document.getElementById('site_title_inp');
let site_about_inp = document.getElementById('site_about_inp');

let contacts_s_form = document.getElementById('contacts_s_form');

function get_general()
{
    let site_title = document.getElementById('site_title');
    let site_about = document.getElementById('site_about');

    let shutdown_toggle = document.getElementById('shutdown-toggle');

    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/settings_crud.php",true);
    xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

    xhr.onload = function()
    {
        general_data = JSON.parse(this.responseText);

        site_title.innerText = general_data.site_title;
        site_about.innerText = general_data.site_about;

        site_title_inp.value = general_data.site_title;
        site_about_inp.value = general_data.site_about;

        if(general_data.shutdown == 0)
        {
            shutdown_toggle.checked = false;
            shutdown_toggle.value = 0;
        }
        else
        {
            shutdown_toggle.checked = true;
            shutdown_toggle.value = 1;
        }
    }

    xhr.send('get_general');
}

general_s_form.addEventListener('submit',function(e)
{
    e.preventDefault();
    upd_general(site_title_inp.value,site_about_inp.value)
})

function upd_general(site_title_val,site_about_val)
{
    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/settings_crud.php",true);
    xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

    xhr.onload = function()
    {
        var myModal = document.getElementById('general-s');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if(this.responseText == 1)
        {
            alert('success','Changes saved!');
            get_general();
        }
        else
        {
            alert('error','No changes made!');
        }

    }

    xhr.send('site_title='+site_title_val+'&site_about='+site_about_val+'&upd_general');
}

function upd_shutdown(val)
{
    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/settings_crud.php",true);
    xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

    xhr.onload = function()
    {
        if(this.responseText == 1 && general_data.shutdown==0)
        {
            alert('danger','Site has been shutdown!');
        }
        else if(this.responseText == 1 && general_data.shutdown==1)
        {
            alert('success','Shutdown mode off!');
        }
        else if(this.responseText == 0 && general_data.shutdown==0)
        {
            alert('danger','No Super Admin Privileges!');
        }
        else if(this.responseText == 0 && general_data.shutdown==1)
        {
            alert('danger','No Super Admin Privileges!');
        }
        get_general();

    }

    xhr.send('upd_shutdown='+val); 
}

function get_contacts()
{
    let contacts_p_id = ['address','gmap','pn1','pn2','email','fb','insta','tw']
    let iframe = document.getElementById('iframe');

    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/settings_crud.php",true);
    xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

    xhr.onload = function()
    {
        contacts_data = JSON.parse(this.responseText);
        contacts_data = Object.values(contacts_data);
        
        for(i=0;i<contacts_p_id.length;i++)
        {
            document.getElementById(contacts_p_id[i]).innerText = contacts_data[i+1];
        }
        iframe.src = contacts_data[9];
        contacts_inp(contacts_data);
    }

    xhr.send('get_contacts');
}

function contacts_inp(data)
{
    let contacts_inp_id = ['address_inp','gmap_inp','pn1_inp','pn2_inp','email_inp','fb_inp','insta_inp','tw_inp', 'iframe_inp']
    
    for(i=0;i<contacts_inp_id.length;i++)
    {
        document.getElementById(contacts_inp_id[i]).value = data[i+1];
    }
}

contacts_s_form.addEventListener('submit',function(e)
{
    e.preventDefault();
    upd_contacts();
})

function upd_contacts()
{
    let index = ['address', 'gmap', 'pn1', 'pn2', 'email', 'fb', 'insta', 'tw', 'iframe'];
    let contacts_inp_id = ['address_inp', 'gmap_inp', 'pn1_inp', 'pn2_inp', 'email_inp', 'fb_inp', 'insta_inp', 'tw_inp', 'iframe_inp'];

    let data_str = "";

    for (let i = 0; i < index.length; i++) {
        let value = encodeURIComponent(document.getElementById(contacts_inp_id[i]).value); // Encode data
        data_str += index[i] + "=" + value + '&';
    }
    data_str += "upd_contacts";

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function()
    {
        var myModal = document.getElementById('contacts-s');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if (this.responseText == 1)
        {
            alert('success', 'Changes saved!');
            get_contacts();
        }
        else
        {
            alert('error', 'No changes made!');
        }
    }

    xhr.send(data_str);
}

document.getElementById('backupButton').addEventListener('click', function() {
    if(isSuperAdmin)
    {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "backup.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function () {
            let response = JSON.parse(xhr.responseText);
            if (response.status == 1) {
                alert('success', 'Backup stored in server and downloaded!');
                
                // Locally download backup file
                let link = document.createElement('a');
                link.download = response.file.split('/').pop();

                // Adjust path based on local or remote deployment
                if (response.file.startsWith("http")) {
                    link.href = response.file;
                } else {
                    link.href = response.file.replace(/\\/g, '/').replace('C:/xampp/htdocs/', '/');
                }

                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
            else if (response.status == 0) {
                alert('error', 'Backup failed!');
            }
        };
        xhr.send();
    }
    else
    {
        alert('error', 'No Super Admin Privileges!');
    }
});

window.onload = function()
{
    get_general();
    get_contacts();
}
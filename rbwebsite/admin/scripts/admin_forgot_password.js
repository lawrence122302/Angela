let forgot_form = document.getElementById('forgot-form');

forgot_form.addEventListener('submit', (e)=>{
    e.preventDefault();

    let data = new FormData();

    data.append('admin_name_account',forgot_form.elements['admin_name_account'].value);
    data.append('forgot_pass','');

    var myModal = document.getElementById('forgotModal');
    var modal = bootstrap.Modal.getInstance(myModal);
    modal.hide();

    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/admin_forgot_password.php",true);

    xhr.onload = function()
    {
        if(this.responseText == 'inv_email')
        {
            alert('error',"Invalid Email!")
        }
        else if(this.responseText == 'not_superadmin')
        {
            alert('error',"Not a Super Admin. Please Contact the Owner.");
        }
        else if(this.responseText == 'inactive')
        {
            alert('error',"Account Suspended! Please contact Admin.");
        }
        else if(this.responseText == 'mail_failed')
        {
            alert('error',"Cannot send email. Server Down!");
        }
        else if(this.responseText == 'upd_failed')
        {
            alert('error',"Password reset failed. Server Down!");
        }
        else
        {
            alert('success',"Reset link sent to email!");
            forgot_form.reset();
        }
    }
    xhr.send(data);
});
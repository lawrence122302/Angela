function get_bookings(search='')
{
    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/confirm_full_payment.php",true);
    xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

    xhr.onload = function()
    {
        document.getElementById('table-data').innerHTML = this.responseText;
    }
    xhr.send('get_bookings&search='+search);
}

function confirm_booking(id,down_payment)
{
    if(confirm("Confirm Down Payment?"))
        {
            let data = new FormData();
            data.append('booking_id',id);
            data.append('down_payment',down_payment);
            data.append('confirm_booking','');
    
            let xhr = new XMLHttpRequest();
            xhr.open("POST","ajax/confirm_full_payment.php",true);
    
            xhr.onload = function()
            {
                if(this.responseText == 1)
                {
                    alert('success','Booking Confirmed!');
                    get_bookings();
                }
                else
                {
                    alert('error','Server Down!');
                }
            }
            xhr.send(data);
        }
}

function cancel_booking(id)
{
    if(confirm("Cancel Booking?"))
        {
            let data = new FormData();
            data.append('booking_id',id);
            data.append('cancel_booking','');
    
            let xhr = new XMLHttpRequest();
            xhr.open("POST","ajax/confirm_full_payment.php",true);
    
            xhr.onload = function()
            {
                if(this.responseText == 1)
                {
                    alert('success','Booking Cancelled!');
                    get_bookings();
                }
                else
                {
                    alert('error','Server Down!');
                }
            }
            xhr.send(data);
        }
}

window.onload = function()
{
    get_bookings();
}
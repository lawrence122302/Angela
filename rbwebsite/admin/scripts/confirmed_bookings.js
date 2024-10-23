function get_bookings(search='')
{
    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/confirmed_bookings.php",true);
    xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

    xhr.onload = function()
    {
        document.getElementById('table-data').innerHTML = this.responseText;
    }
    xhr.send('get_bookings&search='+search);
}

function confirm_booking(id, full_payment) {
    let confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
    confirmModal.show();

    document.getElementById('confirmPaymentBtn').onclick = function() {
        let data = new FormData();
        data.append('booking_id', id);
        data.append('full_payment', full_payment);
        data.append('confirm_booking', '');

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/confirmed_bookings.php", true);

        xhr.onload = function() {
            if (this.responseText >= 1) {
                alert('success', 'Arrival Confirmed!');
                get_bookings();
            } else {
                alert('error', 'Server Down!');
            }
        }
        xhr.send(data);
        confirmModal.hide();
    };
}

function cancel_booking(id) {
    let cancelModal = new bootstrap.Modal(document.getElementById('cancelModal'));
    cancelModal.show();

    document.getElementById('confirmCancelBtn').onclick = function() {
        let data = new FormData();
        data.append('booking_id', id);
        data.append('cancel_booking', '');

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/confirmed_bookings.php", true);

        xhr.onload = function() {
            if (this.responseText == 1) {
                alert('success', 'Booking Cancelled!');
                get_bookings();
            } else {
                alert('error', 'Server Down!');
            }
        }
        xhr.send(data);
        cancelModal.hide();
    };
}

window.onload = function()
{
    get_bookings();
}
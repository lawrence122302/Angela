function get_bookings(search='',page=1)
{
    let xhr = new XMLHttpRequest();
    xhr.open("POST","ajax/refund_bookings.php",true);
    xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

    xhr.onload = function()
    {
        let data = JSON.parse(this.responseText);
        document.getElementById('table-data').innerHTML = data.table_data;
        document.getElementById('table-pagination').innerHTML = data.pagination;
    }
    xhr.send('get_bookings&search='+search+'&page='+page);
}

function change_page(page)
{
    get_bookings(document.getElementById('search_input').value,page);
}

function refund_booking(id) {
    let confirmModal = new bootstrap.Modal(document.getElementById('confirmRefundModal'));
    confirmModal.show();
    document.getElementById('confirmRefundButton').onclick = function() {
        let data = new FormData();
        data.append('booking_id', id);
        data.append('refund_booking', '');
        
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/refund_bookings.php", true);
        xhr.onload = function() {
            if (this.responseText == 1) {
                alert('success', 'Money Refunded!');
                get_bookings();
            } else {
                alert('error', 'Server Down!');
            }
        }
        xhr.send(data);
        confirmModal.hide();
    };
}

window.onload = function()
{
    get_bookings();
}
let booking_form = document.getElementById('booking_form');
let pay_now_form = document.getElementById('pay_now_form');
let info_loader = document.getElementById('info_loader');
let pay_info = document.getElementById('pay_info');

function check_availability()
{
    let accommodation = booking_form.elements['accommodationId'].value;

    let checkin_val = booking_form.elements['checkin'].value;
    let checkout_val = booking_form.elements['checkout'].value;

    // Needed to convert check-in and checkout date
    let checkin_date1 = new Date(checkin_val + "T00:00:00");
    let checkin_date2 = new Date(checkin_val + "T00:00:00");

    // Debug check-in dates
    console.log("Check-in Date 1: " + checkin_date1);
    console.log("Check-in Date 2: " + checkin_date2);

    // Check if weekend
    let dayOfWeek = checkin_date1.getDay();
    let isWeekend = (dayOfWeek === 0 || dayOfWeek === 5 || dayOfWeek === 6); // 0 is Sunday, 5 is Friday, 6 is Saturday
    if(isWeekend)
    {
        isWeekend = "true";
    }
    else if(!isWeekend)
    {
        isWeekend = "false";
    }

    // Debug day of the week
    console.log("Day of Week: " + dayOfWeek);
    console.log("Is Weekend: " + isWeekend);

    // Create new check-in value
    // Check if day or night
    let time_of_day = "";
    let new_checkin_val;
    if ((checkout_val % 2) == 0) {
        time_of_day = "Night Tour";
        new_checkin_val = new Date(checkin_date2.getTime() + 20 * 60 * 60 * 1000); // Add 20 hours
    }
    else
    {
        time_of_day = "Day Tour";
        new_checkin_val = new Date(checkin_date2.getTime() + 8 * 60 * 60 * 1000); // Add 8 hours
    }

    // Debug new check-in value and time of day
    console.log("Time of Day: " + time_of_day);
    console.log("New Check-in Value: " + new_checkin_val);

    // Check if 22 hours
    let is_22hrs;
    if (checkout_val == 3 || checkout_val == 4)
    {
        is_22hrs = "true";
    }
    else if (checkout_val == 1 || checkout_val == 2)
    {
        is_22hrs = "false";
    }

    // Debugging the value
    console.log("Checkout Value: " + checkout_val);
    console.log("Is 22 hours: " + is_22hrs);

    // Further Check-out Value Adjustments (if needed)
    // Creting new check-out value
    let new_checkout_val;
    if (checkout_val == 1 || checkout_val == 2) {
        new_checkout_val = new Date(new_checkin_val.getTime() + 10 * 60 * 60 * 1000); // 10
    }
    else if (checkout_val == 3 || checkout_val == 4) {
        new_checkout_val = new Date(new_checkin_val.getTime() + 22 * 60 * 60 * 1000); // 22
    }

    // Debug new check-out value
    console.log("New Check-out Value: " + new_checkout_val);

    // Formating check-in and check-out values
    let final_checkin_val = new Date(new_checkin_val.getTime() - new_checkin_val.getTimezoneOffset() * 60000);
    let isoStr1 = final_checkin_val.toISOString();
    let datetimeLocal_checkin = isoStr1.slice(0, 16);

    let final_checkout_val = new Date(new_checkout_val.getTime() - new_checkin_val.getTimezoneOffset() * 60000);
    let isoStr2 = final_checkout_val.toISOString();
    let datetimeLocal_checkout = isoStr2.slice(0, 16);

    // Debug final ISO string values
    console.log("Final Check-in ISO String: " + datetimeLocal_checkin);
    console.log("Final Check-out ISO String: " + datetimeLocal_checkout);

    booking_form.elements['pay_now'].setAttribute('disabled',true);

    if(datetimeLocal_checkin!='' && datetimeLocal_checkout!='')
    {
        pay_info.classList.add('d-none');
        pay_info.classList.replace('alert-warning','alert-success');
        info_loader.classList.remove('d-none');
        
        let data = new FormData();

        data.append('check_availability','');
        data.append('accommodationId', accommodation);

        data.append('datetimeLocal_checkin',datetimeLocal_checkin);
        data.append('datetimeLocal_checkout',datetimeLocal_checkout);

        data.append('isWeekend',isWeekend);
        data.append('time_of_day',time_of_day);
        data.append('is_22hrs',is_22hrs);

        let xhr = new XMLHttpRequest();
        xhr.open("POST","ajax/without_account_add_bookings.php",true);

        xhr.onload = function()
        {
            console.log("XHR onload triggered");
            let data = JSON.parse(this.responseText);
            console.log("Response data:", data);

            if(data.status == 'check_in_out_equal')
            {
                pay_info.classList.replace('alert-success','alert-warning');
                pay_info.innerHTML = "<strong>Notice:</strong> You cannot check-out on the same day.";
            }
            else if(data.status == 'check_out_earlier')
            {
                pay_info.classList.replace('alert-success','alert-warning');
                pay_info.innerHTML = "<strong>Notice:</strong> Check-out date is earlier than the check-in date.";
            }
            else if(data.status == 'check_in_earlier')
            {
                pay_info.classList.replace('alert-success','alert-warning');
                pay_info.innerHTML = "<strong>Notice:</strong> Check-in date is earlier than today's date.";
            }
            else if(data.status == 'unavailable')
            {
                pay_info.classList.replace('alert-success','alert-warning');
                pay_info.innerHTML = "<strong>Notice:</strong> Room unavailable for this date.";
            }
            else if(data.status == 'user_id_not_found')
            {
                pay_info.classList.replace('alert-success','alert-warning');
                pay_info.innerHTML = "<strong>Notice:</strong> Please select an account.";
            }
            else if(data.status == 'accommodation_id_not_found')
            {
                pay_info.classList.replace('alert-success','alert-warning');
                pay_info.innerHTML = "<strong>Notice:</strong> Please select an accommodation.";
            }
            else
            {
                pay_info.innerHTML = "Package Type:<br><strong>"+data.package_type+"</strong><br><br>Hours:<br><strong>"+data.hour1+" - "+data.hour2+"</strong><br><br>Total Amount to Pay:<br><strong>₱"+data.payment+"</strong>";
                pay_info.classList.replace('alert-warning','alert-success');
                booking_form.elements['pay_now'].removeAttribute('disabled');
            }

            pay_info.classList.remove('d-none');
            info_loader.classList.add('d-none');
            console.log("Completed updating UI");
        }
        console.log("Request sent with data: ", data);
        
        xhr.onerror = function () {
            console.error("XHR error occurred");
        };
        console.log("Sending request...");
        xhr.send(data);
        console.log("Request sent");
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const radioButtons = document.querySelectorAll('input[name="g_reference"]');
    const customInput = document.getElementById('customValue1');
    const customRadio = document.getElementById('customRadio1');

    radioButtons.forEach(radio => {
        radio.addEventListener('change', function() {
            console.log(`Radio changed: ${this.value}`);
            if (this.value === 'gcash') {
                customInput.disabled = false; 
                customInput.required = true; 
                console.log('Custom input enabled and required');
                customInput.focus();
            } else {
                customInput.disabled = true; 
                customInput.required = false; 
                customInput.value = '';
                console.log('Custom input disabled and required removed');
            }
            console.log(`customValue disabled: ${customInput.disabled}`);
        });
    });

    customInput.addEventListener('input', function() {
        customRadio.value = customInput.value;
        console.log(`Custom input value: ${customInput.value}`);
    });
});

booking_form.addEventListener('submit', (e)=>{
    e.preventDefault();

    openModal();
});

function openModal()
{
    let accommodation = booking_form.elements['accommodationId'].value;
    
    let checkin_val = booking_form.elements['checkin'].value;
    let checkout_val = booking_form.elements['checkout'].value;

    // Needed to convert check-in and checkout date
    let checkin_date1 = new Date(checkin_val + "T00:00:00");
    let checkin_date2 = new Date(checkin_val + "T00:00:00");

    // Debug check-in dates
    console.log("Check-in Date 1: " + checkin_date1);
    console.log("Check-in Date 2: " + checkin_date2);

    // Check if weekend
    let dayOfWeek = checkin_date1.getDay();
    let isWeekend = (dayOfWeek === 0 || dayOfWeek === 5 || dayOfWeek === 6); // 0 is Sunday, 5 is Friday, 6 is Saturday
    if(isWeekend)
    {
        isWeekend = "true";
    }
    else if(!isWeekend)
    {
        isWeekend = "false";
    }

    // Debug day of the week
    console.log("Day of Week: " + dayOfWeek);
    console.log("Is Weekend: " + isWeekend);

    // Create new check-in value
    // Check if day or night
    let time_of_day = "";
    let new_checkin_val;
    if ((checkout_val % 2) == 0) {
        time_of_day = "Night Tour";
        new_checkin_val = new Date(checkin_date2.getTime() + 20 * 60 * 60 * 1000); // Add 20 hours
    }
    else
    {
        time_of_day = "Day Tour";
        new_checkin_val = new Date(checkin_date2.getTime() + 8 * 60 * 60 * 1000); // Add 8 hours
    }

    // Debug new check-in value and time of day
    console.log("Time of Day: " + time_of_day);
    console.log("New Check-in Value: " + new_checkin_val);

    // Check if 22 hours
    let is_22hrs;
    if (checkout_val == 3 || checkout_val == 4)
    {
        is_22hrs = "true";
    }
    else if (checkout_val == 1 || checkout_val == 2)
    {
        is_22hrs = "false";
    }

    // Debugging the value
    console.log("Checkout Value: " + checkout_val);
    console.log("Is 22 hours: " + is_22hrs);

    // Further Check-out Value Adjustments (if needed)
    // Creting new check-out value
    let new_checkout_val;
    if (checkout_val == 1 || checkout_val == 2) {
        new_checkout_val = new Date(new_checkin_val.getTime() + 10 * 60 * 60 * 1000); // 10
    }
    else if (checkout_val == 3 || checkout_val == 4) {
        new_checkout_val = new Date(new_checkin_val.getTime() + 22 * 60 * 60 * 1000); // 22
    }

    // Debug new check-out value
    console.log("New Check-out Value: " + new_checkout_val);

    // Formating check-in and check-out values
    let final_checkin_val = new Date(new_checkin_val.getTime() - new_checkin_val.getTimezoneOffset() * 60000);
    let isoStr1 = final_checkin_val.toISOString();
    let datetimeLocal_checkin = isoStr1.slice(0, 16);

    let final_checkout_val = new Date(new_checkout_val.getTime() - new_checkin_val.getTimezoneOffset() * 60000);
    let isoStr2 = final_checkout_val.toISOString();
    let datetimeLocal_checkout = isoStr2.slice(0, 16);

    let modal = new bootstrap.Modal(document.getElementById('pay-now'));
    modal.show();

    document.getElementById('pay_now_form').addEventListener('submit', function(event) {
        event.preventDefault();

        let paidamount_val = pay_now_form.elements['paid_amount'].value;
        let g_reference_val = pay_now_form.elements['g_reference'].value;

        let data = new FormData();
        data.append('without_account_pay_now','');
        data.append('accommodationId', accommodation);
        data.append('datetimeLocal_checkin',datetimeLocal_checkin);
        data.append('datetimeLocal_checkout',datetimeLocal_checkout);
        data.append('isWeekend',isWeekend);
        data.append('time_of_day',time_of_day);
        data.append('is_22hrs',is_22hrs);
        data.append('paidamount',paidamount_val);
        data.append('g_reference',g_reference_val);

        data.append('name',booking_form.elements['customerName'].value);
        data.append('email',booking_form.elements['email'].value);
        data.append('phonenum',booking_form.elements['phonenum'].value);
        data.append('address',booking_form.elements['address'].value);
        data.append('pincode',booking_form.elements['pincode'].value);
        data.append('dob',booking_form.elements['dob'].value);
        data.append('profile',booking_form.elements['profile'].files[0]);

        // Date of birth validation

        let dob = new Date(booking_form.elements['dob'].value);
        let today = new Date();
        let age = today.getFullYear() - dob.getFullYear();
        let month = today.getMonth() - dob.getMonth();
        if (month < 0 || (month === 0 && today.getDate() < dob.getDate())) {
            age--;
        }
        
        if (age < 18) {
            alert('error', "You must be at least 18 years old to register.");
            return;
        }

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/pay_now.php", true);

        xhr.onload = function() {
            console.log("XHR onload triggered");
            console.log("Raw Response Text:", this.responseText);
            
            switch (this.responseText) {
                case 'email_already':
                    alert('error', "Email is already registered!");
                    break;
                case 'phone_already':
                    alert('error', "Phone number is already registered!");
                    break;
                case 'inv_img':
                    alert('error', "Only JPG, WEBP, & PNG images are allowed!");
                    break;
                case 'upd_failed':
                    alert('error', "Image upload failed!");
                    break;
                case 'mail_failed':
                    alert('error', "Cannot send confirmation email! Server down!");
                    break;
                case 'ins_failed':
                    alert('error', "Booking failed! Server down!");
                    break;
                default:
                    booking_form.reset();
                    alert('success', "Booking successful! Account creation sent to customer email!");
                    break;
            }
        };        
        
        console.log("Sending request...");
        xhr.send(data);
        console.log("Request sent");

        let modalInstance = bootstrap.Modal.getInstance(document.getElementById('pay-now'));
        modalInstance.hide();
    });
}
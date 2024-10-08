<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - Rooms</title>
</head>
<body class="bg-light">

    <?php
        require('inc/navbar.php');

        $checkin_default = "";
        $checkout_default = "";
        $adult_default = "";

        if(isset($_GET['check_availability']))
        {
            $frm_data = filteration($_GET);

            $checkin_default = $frm_data['checkin'];
            $checkout_default = $frm_data['checkout'];
            $adult_default = $frm_data['adult'];
        }
    ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">Our Accommodations</h2>
        <div class="h-line bg-dark"></div>
    </div>

    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-3 col-md-12 mb-lg-0 mb-4 ps-4">
                <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow">
                    <div class="container-fluid flex-lg-column align-items-stretch">
                        <h4 class="mt-2">Filters</h4>
                        <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#filterDropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="filterDropdown">

                            <!-- Check availability -->
                            <div class="border bg-light p-3 rounded mb-3">
                                <h5 class="d-flex align-items-center justfiy-content-between mb-3" style="font-size: 18px;">
                                    <span>Check Availability</span>
                                    <button id="chk_avail_btn" onclick="chk_avail_clear()" class="btn shadow-none btn-sm text-secondary d-none">Reset</button>
                                </h5>
                                <label class="form-label">Check-in</label>
                                <input type="date" class="form-control shadow-none mb-3" value="<?php echo $checkin_default ?>" id="checkin" onchange="chk_avail_filter()">
                                <label class="form-label">Check-out</label>
                                <select id="checkout" onchange="chk_avail_filter()" class="form-select shadow-none">
                                    <option value="">Select Package Type</option>
                                    <option value="1" <?php if ($checkout_default == 1) echo 'selected'; ?>>Day Tour (08:00am - 06:00pm)</option>
                                    <option value="2" <?php if ($checkout_default == 2) echo 'selected'; ?>>Night Tour (08:00pm - 06:00am)</option>
                                    <option value="3" <?php if ($checkout_default == 3) echo 'selected'; ?>>22 Hours Day Tour (08:00am - 06:00am)</option>
                                    <option value="4" <?php if ($checkout_default == 4) echo 'selected'; ?>>22 Hours Night Tour (08:00pm - 06:00pm)</option>
                                </select>
                            </div>

                            <!-- Facilities -->
                            <div class="border bg-light p-3 rounded mb-3">
                                <h5 class="d-flex align-items-center justfiy-content-between mb-3" style="font-size: 18px;">
                                    <span>Facilities</span>
                                    <button id="facilities_btn" onclick="facilities_clear()" class="btn shadow-none btn-sm text-secondary d-none">Reset</button>
                                </h5>
                                <?php
                                    $facilties_q = selectAll('facilities');

                                    while($row = mysqli_fetch_assoc($facilties_q))
                                    {
                                        echo<<<facilities
                                            <div class="mb-2">
                                                <input type="checkbox" onclick="fetch_rooms()" name="facilities" value="$row[id]" class="form-check-input shadow-none me-1 id="$row[id]"">
                                                <label class="form-check-label" for="$row[id]">$row[name]</label>
                                            </div>
                                        facilities;
                                    }
                                ?>
                            </div>

                            <!-- Guests -->
                            <div class="border bg-light p-3 rounded mb-3">
                                <h5 class="d-flex align-items-center justfiy-content-between mb-3" style="font-size: 18px;">
                                    <span>Guests</span>
                                    <button id="guests_btn" onclick="guests_clear()" class="btn shadow-none btn-sm text-secondary d-none">Reset</button>
                                </h5>
                                <div class="d-flex">
                                    <div class="me-3">
                                        <label class="form-label">Pax</label>
                                        <input type="number" min="1" id="adults" value="<?php echo $adult_default ?>" oninput="guests_filter()" class="form-control shadow-none">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>

            <div class="col-lg-9 col-md-12 px-4" id="rooms-data">
                <!-- Rooms -->
            </div>

        </div>
    </div>

    <script>
        let rooms_data = document.getElementById('rooms-data');

        let checkin = document.getElementById('checkin');
        let checkout = document.getElementById('checkout');
        console.log("Check-in Value:", checkin.value);
        console.log("Check-out Value:", checkout.value);
        let chk_avail_btn = document.getElementById('chk_avail_btn');

        let adults = document.getElementById('adults');
        let guests_btn = document.getElementById('guests_btn');
        
        let facilities_btn = document.getElementById('facilities_btn');

        function fetch_rooms()
        {
            let datetimeLocal_checkin = "";
            let datetimeLocal_checkout = "";

            if(checkin.value!='' && checkout.value!='')
            {
                let checkin_val = checkin.value;
                let checkout_val = checkout.value;

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
                datetimeLocal_checkin = isoStr1.slice(0, 16);

                let final_checkout_val = new Date(new_checkout_val.getTime() - new_checkin_val.getTimezoneOffset() * 60000);
                let isoStr2 = final_checkout_val.toISOString();
                datetimeLocal_checkout = isoStr2.slice(0, 16);

                // Debug final ISO string values
                console.log("Final Check-in ISO String: " + datetimeLocal_checkin);
                console.log("Final Check-out ISO String: " + datetimeLocal_checkout);
            }

            console.log("Check-in:", datetimeLocal_checkin, "Check-out:", datetimeLocal_checkout);

            let chk_avail = JSON.stringify({
                checkin: datetimeLocal_checkin,
                checkout: datetimeLocal_checkout
            });

            let guests = JSON.stringify({
                adults: adults.value,
            });

            let facility_list = {"facilities":[]};

            let get_facilities = document.querySelectorAll('[name="facilities"]:checked');

            if(get_facilities.length>0)
            {
                get_facilities.forEach((facility)=>{
                    facility_list.facilities.push(facility.value);
                });

                facilities_btn.classList.remove('d-none');
            }
            else
            {
                facilities_btn.classList.add('d-none');
            }

            facility_list = JSON.stringify(facility_list);

            let xhr = new XMLHttpRequest();
            xhr.open("GET","ajax/rooms.php?fetch_rooms&chk_avail="+chk_avail+"&guests="+guests+"&facility_list="+facility_list,true);

            xhr.onprogress = function()
            {
                rooms_data.innerHTML = `<div class="spinner-border text-info mb-3 d-block mx-auto" id="loader" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>`;
            }

            xhr.onload = function()
            {
                rooms_data.innerHTML = this.responseText;
            }

            xhr.send();
        }

        function chk_avail_filter()
        {
            if(checkin.value!='' && checkout.value!='')
            {
                fetch_rooms();
                chk_avail_btn.classList.remove('d-none');
            }
        }

        function chk_avail_clear()
        {
            checkin.value = '';
            checkout.value = '';
            chk_avail_btn.classList.add('d-none');
            fetch_rooms();
        }

        function guests_filter()
        {
            if(adults.value>0 || children.value>0)
            {
                fetch_rooms();
                guests_btn.classList.remove('d-none');
            }
        }

        function guests_clear()
        {
            adults.value = '';
            guests_btn.classList.add('d-none');
            fetch_rooms();
        }

        function facilities_clear()
        {
            let get_facilities = document.querySelectorAll('[name="facilities"]:checked');
            get_facilities.forEach((facility)=>{
                facility.checked=false;
            });

            facilities_btn.classList.add('d-none');
            fetch_rooms();
        }

        window.onload = function()
        {
            fetch_rooms();
        }
    </script>
    <?php require('inc/footer.php'); ?>
    
</body>
</html>
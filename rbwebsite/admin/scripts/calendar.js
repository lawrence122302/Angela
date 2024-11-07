const calendar = document.querySelector(".calendar"),
    date = document.querySelector(".date"),
    daysContainer = document.querySelector(".days"),
    prev = document.querySelector(".prev"),
    next = document.querySelector(".next"),
    todayBtn = document.querySelector(".today-btn"),
    gotoBtn = document.querySelector(".goto-btn"),
    dateInput = document.querySelector(".date-input"),
    eventDay = document.querySelector(".event-day"),
    eventDate = document.querySelector(".event-date"),
    eventsContainer = document.querySelector(".events"),
    addEventSubmit = document.querySelector(".add-event-btn");

let today = new Date();
let activeDay;
let month = today.getMonth();
let year = today.getFullYear();

const months = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
];

// Default events array

// const eventsArr = [
//   {
//     day: 30,
//     month: 10,
//     year: 2024,
//     events: [
//       {
//         title: "Event 1 lorem ipsun dolar sit genfa tersd dsad ",
//         time: "10:00 AM - 11:00 AM",
//       },
//       {
//         title: "Event 2",
//         time: "11:00 AM",
//       },
//     ],
//   },
//   {
//     day: 11,
//     month: 11,
//     year: 2024,
//     events: [
//       {
//         title: "Event 1 lorem ipsun dolar sit genfa tersd dsad ",
//         time: "10:00 AM - 11:00 AM",
//       },
//       {
//         title: "Event 2",
//         time: "11:00 AM",
//       },
//     ],
//   },
// ];

// Set an empty array

let eventsArr = [];

// Then call get

// getEvents();

// Function to add days

function initCalendar() {

    // To get prev month days and current month all days and rem next month days

    const firstDay = new Date(year, month, 1);      // Creates a Date object for the first day of the current month
    const lastDay = new Date(year, month + 1, 0);   // Creates a Date object for the last day of the current month by setting the date to the 0th of the next month
    const prevLastDay = new Date(year, month, 0);   // Creates a Date object for the last day of the previous month

    const prevDays =  prevLastDay.getDate();        // Gets the number of days in the previous month
    const lastDate = lastDay.getDate();             // Gets the number of days in the current month
    const day = firstDay.getDay();                  // Gets the day of the week for the first day of the month (If October 1, 2024, is a Tuesday, "day" would be 2 (assuming 0 = Sunday))
    const nextDays = 7 - lastDay.getDay() - 1;      // Calculates the number of days from the next month required to fill the last week of the current month

    // Update date at the top of calendar

    date.innerHTML = months[month] + " " + year;

    // Adding days on dom

    let days = "";

    // Adds days from the previous month (prev-date class)

    for (let x = day; x > 0; x--) {
        let event = false;
        let additionalClass = "";
        eventsArr.forEach((eventObj) => {
            if (eventObj.day == (prevDays - x + 1) && eventObj.month == month && eventObj.year == year) {
                event = true;
                additionalClass = getBookingClass(eventObj, year, month, (prevDays - x + 1));
            }
        });
        if (event) {
            days += `<div class="day prev-date event${additionalClass}">${prevDays - x + 1}</div>`;
        } else {
            days += `<div class="day prev-date">${prevDays - x + 1}</div>`;
        }
    }

    // Adds days from the current month

    for (let i = 1; i <= lastDate; i++) {

        // Check if event present on current day
        
        let event = false;

        // Create empty additionalClass class string

        let additionalClass = ""; // Initialize empty class string

        eventsArr.forEach((eventObj) => {
            if (
                eventObj.day == i &&
                eventObj.month == month + 1 &&
                eventObj.year == year
            ) {
                // If event found

                event = true;

                additionalClass = getBookingClass(eventObj, year, month + 1, i);
            }
        });

        // If day is today add class today

        if (
            i == new Date().getDate() && 
            year == new Date().getFullYear() && 
            month == new Date().getMonth()
        ) {
            activeDay = i;
            getActiveDay(i);
            updateEvents(i);

            // If event found also add event class
            // Add active on today at startup

            if (event) {
                days += `<div class="day today active event${additionalClass}">${i}</div>`
            } else {
                days += `<div class="day today active">${i}</div>`
            }
        }

        // Add remaining as it is

        else {
            if (event) {
                days += `<div class="day event${additionalClass}">${i}</div>`
            } else {
                days += `<div class="day">${i}</div>`
            }
        }
    }

    // Adds days from the next month (next-date class)

    // Next Month Days
    for (let j = 1; j <= nextDays; j++) {
        let event = false;
        let additionalClass = "";
        eventsArr.forEach((eventObj) => {
            if (eventObj.day == j && eventObj.month == month + 2 && eventObj.year == year) {
                event = true;
                additionalClass = getBookingClass(eventObj, year, month + 2, j);
            }
        });
        if (event) {
            days += `<div class="day next-date event${additionalClass}">${j}</div>`;
        } else {
            days += `<div class="day next-date">${j}</div>`;
        }
    }

    daysContainer.innerHTML = days;

    // Attaches click event listeners to each day element in the calendar
    // Allowing users to click on days to set them as active, and seamlessly navigate between months when clicking on days from previous or next months

    addListner();
}

initCalendar();

// Move to the previous month

function prevMonth() {
    month--;
    if (month < 0) {
        month = 11;
        year--;
    }
    initCalendar();
}

// Move to the next month

function nextMonth() {
    month++;
    if(month > 11) {
        month = 0;
        year++;
    }
    initCalendar();
}

// Add eventlistner on prev month button then prevMonth function is called

prev.addEventListener("click", prevMonth);

// Add eventlistner on next month button then nextMonth function is called

next.addEventListener("click", nextMonth);

// Goto today button functionality

todayBtn.addEventListener("click", () => {
    today = new Date();
    month = today.getMonth();
    year = today.getFullYear();
    initCalendar();
});

// Go to date input, input format

dateInput.addEventListener("input", (e) => {

    // Allow only numbers

    dateInput.value = dateInput.value.replace(/[^0-9/]/g, "");

    if (dateInput.value.length == 2) {

        // Add slash if 2 numbers entered

        dateInput.value += "/";
    }

    if (dateInput.value.length > 7) {

        // Don't allow more than 7 numbers

        dateInput.value = dateInput.value.slice(0, 7)
    }

    // If backspace pressed

    if (e.inputType == "deleteContentBackward") {
        if (dateInput.value.length == 3) {
            dateInput.value = dateInput.value.slice(0, 2);
        }
    }
});

// Add eventlistner on go button

gotoBtn.addEventListener("click", gotoDate);

// Function to go to entered date using mm/yyyy input

function gotoDate() {
    const dateArr = dateInput.value.split("/");

    // Ensures that the input was split into exactly two parts, which means the input format is mm/yyyy

    if (dateArr.length == 2) {

        // Checks if the month part (dateArr[0]) is a valid month (1-12).
        // Ensures the year part (dateArr[1]) is a 4-digit number

        if (dateArr[0] > 0 && dateArr[0] < 13 && dateArr[1].length == 4) {

            // Converts the user input month (which is 1-indexed, e.g., January = 1) to 0-indexed (e.g., January = 0) to match JavaScript's Date object format

            month = dateArr[0] - 1;

            // Sets the year variable to the user input year

            year = dateArr[1];

            // Calls the initCalendar function to refresh the calendar view with the updated month and year

            initCalendar();
            
            return;
        }
    }

    // If invalid date

    alert('error', 'Invalid Date!');
}

const addEventBtn = document.querySelector(".add-event"),
    addEventContainer = document.querySelector(".add-event-wrapper"),
    addEventCloseBtn = document.querySelector(".close"),
    addEventTitle = document.querySelector(".event-name"),
    addEventFrom = document.querySelector(".event-time-from"),
    addEventTo = document.querySelector(".event-time-to");

// Open add event input

document.addEventListener('DOMContentLoaded', () => {
    let addEventBtn = document.querySelector('.add-event');
    if (addEventBtn) {
        addEventBtn.addEventListener("click", () => {
            addEventContainer.classList.toggle("active");
        });
    }
});

// Close add event input

addEventCloseBtn.addEventListener("click", () => {
    addEventContainer.classList.remove("active");
});

// Close add event input when clicked outside

document.addEventListener("click", (e) => {

    // If click outside

    if (e.target != addEventBtn && !addEventContainer.contains(e.target)) {
        addEventContainer.classList.remove("active");
    }
});

// Allow only 50 characters in title

// addEventTitle.addEventListener("input", (e) => {
//     addEventTitle.value = addEventTitle.value.slice(0, 50);
// });

// Time format "from time" (Add event input)

// addEventFrom.addEventListener("input", (e) => {

//     // Remove anything else but numbers

//     addEventFrom.value = addEventFrom.value.replace(/[^0-9:]/g , "");

//     // If 2 numbers entered auto add :

//     if (addEventFrom.value.length == 2) {
//         addEventFrom.value += ":";
//     }

//     // Don't let users enter more than 5 characters

//     if (addEventFrom.value.length > 5) {
//         addEventFrom.value = addEventFrom.value.slice(0, 5);
//     }
// });

// Time format "to time" (Add event input)

// addEventTo.addEventListener("input", (e) => {

//     // Remove anything else but numbers

//     addEventTo.value = addEventTo.value.replace(/[^0-9:]/g , "");

//     // If 2 numbers entered auto add :
    
//     if (addEventTo.value.length == 2) {
//         addEventTo.value += ":";
//     }

//     // Don't let users enter more than 5 characters

//     if (addEventTo.value.length > 5) {
//         addEventTo.value = addEventTo.value.slice(0, 5);
//     }
// });

// Attaches click event listeners to each day element in the calendar
// Allowing users to click on days to set them as active, and seamlessly navigate between months when clicking on days from previous or next months
// Called in initCalendar()

function addListner() {

    // Selects all elements with the class .day

    const days = document.querySelectorAll(".day");

    // Loops through each day element and attaches a click event listener
    
    days.forEach((day) => {
        day.addEventListener("click", (e) => {

            // Set current day as active day

            activeDay = Number(e.target.innerHTML)

            // Call active day after click

            getActiveDay(e.target.innerHTML);
            updateEvents(Number(e.target.innerHTML));

            // Remove active from already active day

            days.forEach((day) => {
                day.classList.remove("active");
            });

            // If prev month day clicked goto prev month and add active

            if (e.target.classList.contains("prev-date")) {
                prevMonth();

                setTimeout(() => {

                    // Select all days of that month

                    const days = document.querySelectorAll(".day");

                    // After going to prev month add active to clicked

                    days.forEach((day) => {
                        if (!day.classList.contains("prev-date") && day.innerHTML == e.target.innerHTML) {
                            day.classList.add("active");
                            updateEvents(Number(day.innerHTML));
                        }
                    });
                }, 100);

            // If next month clicked goto next month and add active

            } else if (e.target.classList.contains("next-date")) {
                nextMonth();

                setTimeout(() => {

                    // Select all days of that month

                    const days = document.querySelectorAll(".day");

                    // After going to prev month add active to clicked

                    days.forEach((day) => {
                        if (!day.classList.contains("next-date") && day.innerHTML == e.target.innerHTML) {
                            day.classList.add("active");
                            updateEvents(Number(day.innerHTML));
                        }
                    });
                }, 100);
            } else {

                // Remaining current month days
                e.target.classList.add("active");
            }
        });
    });
}

// Show active day events and date at top

function getActiveDay(date) {
    const day = new Date(year, month, date);
    const dayName = day.toString().split(" ")[0];
    eventDay.innerHTML = dayName;
    eventDate.innerHTML = date + " " + months[month] + " " + year;
}

// Function to show events of that day

function updateEvents(date) {
    let events = "";
    eventsArr.forEach((event) => {

        // Get events of active day only
        if (
            date == event.day &&
            month + 1 == event.month &&
            year == event.year
        ) {
            // Show event on document
            event.events.forEach((event) => {
                events += `<div class="event">
                    <div class="title">
                        <i class="fas fa-circle"></i>
                        <h3 class="event-title">${event.title}</h3>
                    </div>
                    <div class="event-time">
                        <span class="event-time">${event.time}</span>
                    </div>
                </div>`;
            });
        }
    });

    // If no events found
    let addEventButton = document.querySelector('.add-event');

    if (addEventButton) {
        addEventButton.style.display = 'none';

        if (events == "") {
            events = `<div class="no-event">
                <h3>No Events</h3>
            </div>`;
            
            // Show add blocked date button
            addEventButton.style.display = 'flex';
        }
    }

    eventsContainer.innerHTML = events;

    // Save events when updated event called
    // saveEvents();
}

// Function to add events

addEventSubmit.addEventListener("click", () => {
    let selectedDate = new Date(year, month, activeDay);

    let selectedOption = document.querySelector('#accommodationDropdown').selectedOptions[0];
    let accommodationId = selectedOption.getAttribute('data-id');

    // Manually format the date string to avoid timezone issues
    let dateString = selectedDate.getFullYear() + '-' + 
        String(selectedDate.getMonth() + 1).padStart(2, '0') + '-' +
        String(selectedDate.getDate()).padStart(2, '0');

    fetch('ajax/calendar.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'block_date',
            date: dateString,
            accommodationId: accommodationId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('success', 'Date blocked successfully!');

            // Refresh the events
                    
            updateEvents(activeDay);

            // Call filterAccommodation with the selected accommodationId
            filterAccommodation(accommodationId);
        } else {
            alert('error', data.message || 'Failed to block date.');
        }
    })
    .catch(error => console.error('Error:', error));

    // const eventTitle = addEventTitle.value;
    // const eventTimeFrom = addEventFrom.value;
    // const eventTimeTo = addEventTo.value;

    // // Some validations

    // if (eventTitle == "" || eventTimeFrom == "" || eventTimeTo == "") {
    //     alert("Please fill all the fields");
    //     return;
    // }

    // const timeFromArr = eventTimeFrom.split(":");
    // const timeToArr = eventTimeFrom.split(":");

    // if (
    //     timeFromArr.length != 2 ||
    //     timeToArr.length != 2 ||
    //     timeFromArr[0] > 23 ||
    //     timeFromArr[1] > 59 ||
    //     timeToArr[0] > 23 ||
    //     timeToArr[1] > 59
    // ) {
    //     alert("Invalid Time Format");
    // }

    // const timeFrom = convertTime(eventTimeFrom);
    // const timeTo = convertTime(eventTimeTo);

    // const newEvent = {
    //     title: eventTitle,
    //     time: timeFrom + " - " + timeTo,
    // };

    // let eventAdded = false;

    // // Check if eventsarr is not empty

    // if (eventsArr.length > 0) {

    //     // Check if current day has already any event then add to that

    //     eventsArr.forEach((item) => {
    //         if (
    //             item.day == activeDay &&
    //             item.month == month + 1 &&
    //             item.year == year
    //         ) {
    //             item.events.push(newEvent);
    //             eventAdded = true;
    //         }
    //     });
    // }

    // // If event array is empty or current day has no event create new

    // if (!eventAdded) {
    //     eventsArr.push({
    //         day : activeDay,
    //         month : month + 1,
    //         year : year,
    //         events : [newEvent],
    //     });
    // }

    // // Remove active from add event form

    // addEventContainer.classList.remove("active");

    // // Clear the fields

    // addEventTitle.value = "";
    // addEventFrom.value = "";
    // addEventTo.value = "";

    // // Show current added event

    // updateEvents(activeDay);

    // // Add event class to newly added day if not already

    // const activeDayElem = document.querySelector(".day.active");
    
    // if(!activeDayElem.classList.contains("event")) {
    //     activeDayElem.classList.add("event")
    // }
});

// Convert time used for adding events

// function convertTime(time) {
//     let timeArr = time.split(":");
//     let timeHour = timeArr[0];
//     let timeMin = timeArr[1];
//     let timeFormat = timeHour >= 12 ? "PM" : "AM";
//     timeHour = timeHour % 12 || 12;
//     time = timeHour + ":" + timeMin + " " + timeFormat;
//     return time;
// }

// Function to remove events on click

eventsContainer.addEventListener("click", (e) => {

    // Check if the clicked element has the class "event"

    if (e.target.classList.contains("event")) {

        // Get the title of the clicked event

        const eventTitle = e.target.children[0].children[1].innerHTML;

        // Check if the event title is "Blocked Date"
        if (eventTitle === "Blocked Date") {
            const date = `${year}-${String(month + 1).padStart(2, '0')}-${String(activeDay).padStart(2, '0')}`;
            let selectedOption = document.querySelector('#accommodationDropdown').selectedOptions[0];
            let accommodationId = selectedOption.getAttribute('data-id');

            // Send a request to update the blocked_dates table
            fetch('ajax/calendar.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    action: 'unblock_date',
                    date: date,
                    accommodationId: accommodationId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('success', 'Date unblocked successfully!');
                    
                    // Optionally remove the event from eventsArr and update the display
                    eventsArr.forEach((event) => {
                        if (
                            event.day == activeDay &&
                            event.month == month + 1 &&
                            event.year == year
                        ) {
                            event.events = event.events.filter(item => item.title !== "Blocked Date");
                            
                            if (event.events.length == 0) {
                                eventsArr.splice(eventsArr.indexOf(event), 1);
                                const activeDayElem = document.querySelector(".day.active");
                                if (activeDayElem.classList.contains("event")) {
                                    activeDayElem.classList.remove("event");
                                }
                            }
                        }
                    });

                    // Refresh the events

                    updateEvents(activeDay);
                } else {
                    alert('error', data.message || 'Failed to unblock date.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('error', 'An error occurred while unblocking the date.');
            });
        }

        // // Iterate through the eventsArr array to find the matching event by title and delete it
        
        // eventsArr.forEach((event) => {

        //     // Check if the event matches the active day, month, and year

        //     if (
        //         event.day == activeDay &&
        //         event.month == month + 1 &&
        //         event.year == year
        //     ) {
        //         // Iterate through the events of the matched day

        //         event.events.forEach((item, index) => {

        //             // If the event title matches, remove the event from the array

        //             if (item.title == eventTitle) {
        //                 event.events.splice(index, 1);
        //             }
        //         });

        //         // If no events remain for that day, remove the entire day from eventsArr

        //         if (event.events.length == 0) {
        //             eventsArr.splice(eventsArr.indexOf(event), 1);

        //             // Also remove the "event" class from the active day element in the calendar

        //             const activeDayElem = document.querySelector(".day.active");
        //             if (activeDayElem.classList.contains("event")) {
        //                 activeDayElem.classList.remove("event");
        //             }
        //         }
        //     }
        // });

        // // After updating the eventsArr, refresh the display of events

        // updateEvents(activeDay);
    }
});

// Store events in local storage

// function saveEvents() {
//     localStorage.setItem("events", JSON.stringify(eventsArr));
// }

// Get events stored in local storage

// function getEvents() {
//     if (localStorage.getItem("events" != null)) {
//         return;
//     }
//     eventsArr.push(...JSON.parse(localStorage.getItem("events")));
// }

// Function to format date

function formatDate(dateStr) {

    // Create new Date object using dateStr

    const date = new Date(dateStr);

    // Return an object containing the day, month, and year of dateStr

    return {
        day: date.getDate(),
        month: date.getMonth() + 1,
        year: date.getFullYear()
    };
}

// Function to get all dates between two dates

function getDatesInRange(startDate, endDate) {
    let date = new Date(startDate);

    // Initializes dates array

    const dates = [];

    // Iterates through each day from startDate to endDate

    while (date <= endDate) {

        // Formats each date

        dates.push({
            day: date.getDate(),
            month: date.getMonth() + 1, // Months are zero-indexed
            year: date.getFullYear()
        });

        date.setDate(date.getDate() + 1);
        date.setHours(0, 0, 0, 0); // Reset time to the start of the next day
    }
    // Returns the array

    return dates;
}

// Function to filter accommodation bookings and blocked dates

function filterAccommodation(value) {
    fetchBookings(value).then(() => fetchBlockedDates(value)).then(() => {
        initCalendar(); // Call initCalendar to refresh the calendar view with the new events
    }).catch(error => console.error('Error:', error));
}

// Function to fetch bookings

function fetchBookings(value) {
    return fetch('ajax/calendar.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'action=filter_bookings&accommodation=' + encodeURIComponent(value)
    })
    .then(response => response.text())
    .then(data => JSON.parse(data))
    .then(data => {
        eventsArr = [];
        const formattedData = {};

        data.forEach(event => {
            const checkInDate = new Date(event.check_in);
            const checkOutDate = new Date(event.check_out);
            const datesInRange = getDatesInRange(checkInDate, checkOutDate);

            datesInRange.forEach(({ day, month, year }) => {
                const currentDate = new Date(year, month - 1, day);
                const key = `${day}-${month}-${year}`;
                
                let eventTime = `${checkInDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true })} - ${checkOutDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true })}`;
                let title = event.order_id;

                // Check if the current date is tomorrow in the range and prepend "(YESTERDAY)" to the title
                if (currentDate > checkInDate) {
                    title += " (YESTERDAY)";
                }

                if (!formattedData[key]) {
                    formattedData[key] = { day, month, year, events: [] };
                }

                formattedData[key].events.push({ title: title, time: eventTime });
            });
        });

        eventsArr = Object.values(formattedData);

        updateEvents(activeDay);
    });
}

// Function to fetch blocked dates

function fetchBlockedDates(value) {
    return fetch('ajax/calendar.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'action=filter_blocked_dates&accommodation=' + encodeURIComponent(value)
    })
    .then(response => response.text())
    .then(data => JSON.parse(data))
    .then(data => {
        const formattedData = {};

        data.forEach(blockedDate => {
            const date = new Date(blockedDate.date);
            const key = `${date.getDate()}-${date.getMonth() + 1}-${date.getFullYear()}`;
            if (!formattedData[key]) {
                formattedData[key] = { day: date.getDate(), month: date.getMonth() + 1, year: date.getFullYear(), events: [] };
            }
            formattedData[key].events.push({ title: 'Blocked Date', time: 'All Day' });
        });

        // Merge blocked dates into eventsArr
        eventsArr = [...eventsArr, ...Object.values(formattedData)];
    });
}

// Function to convert a time string like "08:00 AM" to an object with hours and minutes as 24-hour format integers

function parseTime(timeString) {

    // Breaks the input "08:00 AM" into "08:00" and "AM"

    const [time, modifier] = timeString.split(' ');
    
    // Further splits "08:00" into hours and minutes

    let [hours, minutes] = time.split(':');

    // Converts "12" to "00" if it's "AM"

    if (hours === '12') {
        hours = '00';
    }

    // Adds 12 to hours if it's "PM"

    if (modifier.toUpperCase() === 'PM') {
        hours = parseInt(hours, 10) + 12;
    }

    // Returns an object with { hours: 8, minutes: 0 } for "08:00 AM"

    return { hours: parseInt(hours, 10), minutes: parseInt(minutes, 10) };
}

// Function to determine the class based on the booking times for a given day

function getBookingClass(eventObj, year, month, day) {
    let additionalClass = "";
    let hasDayBooking = false;
    let hasNightBooking = false;
    let hasFullDayBooking = false;

    const currentDate = new Date(year, month, day);

    function convertTo24Hour(time) {
        const [timeStr, modifier] = time.split(' ');
        let [hours, minutes] = timeStr.split(':');
        hours = parseInt(hours, 10);

        if (hours === 12) {
            hours = 0;
        }
        if (modifier === 'PM') {
            hours += 12;
        }

        return `${hours.toString().padStart(2, '0')}:${minutes}`;
    }

    eventObj.events.forEach((booking, index) => {
        const date = `${eventObj.year}-${String(eventObj.month).padStart(2, '0')}-${String(eventObj.day).padStart(2, '0')}`;
        booking.date = date;

        if (!booking.time || booking.time === 'All Day') {
            if (booking.title === "Blocked Date") {
                additionalClass = " event-red";
            } else {
                additionalClass = " event-green";
            }
            return;
        }

        let timeStr = booking.time.replace(' (YESTERDAY)', '');
        const [startTimeStr, endTimeStr] = timeStr.split(' - ');

        const start24Hour = convertTo24Hour(startTimeStr);
        const end24Hour = convertTo24Hour(endTimeStr);

        const startDateTimeStr = `${booking.date}T${start24Hour}:00`;
        const endDateTimeStr = `${booking.date}T${end24Hour}:00`;

        const startDateTime = new Date(startDateTimeStr);
        let endDateTime = new Date(endDateTimeStr);
        
        if (endDateTime <= startDateTime) {
            endDateTime.setDate(endDateTime.getDate() + 1);
        }

        if (booking.title.includes("(YESTERDAY)")) {
            if (startDateTime.getHours() === 20 && endDateTime.getHours() === 18) {
                hasDayBooking = true;
                additionalClass = " event-yellow";
            }
            return;
        }

        if (startDateTime.getHours() === 8 && endDateTime.getHours() === 18 && startDateTime.getDate() === currentDate.getDate()) {
            hasDayBooking = true;
        } else if (startDateTime.getHours() === 20 && endDateTime.getHours() === 6 && startDateTime.getDate() === currentDate.getDate()) {
            hasNightBooking = true;
            additionalClass = " event-blue";
        } else if (startDateTime.getHours() === 8 && endDateTime.getHours() === 6 && startDateTime.getDate() === currentDate.getDate()) {
            hasFullDayBooking = true;
            additionalClass = " event-red";
        } else if (startDateTime.getHours() === 20 && endDateTime.getHours() === 18 && startDateTime.getDate() === currentDate.getDate()) {
            hasNightBooking = true;
            additionalClass = " event-blue";
            
            const nextDay = new Date(year, month, day + 1);

            eventObj.events.forEach((nextBooking, nextIndex) => {
                if (!nextBooking.time) {
                    return;
                }

                const nextStartDateTime = new Date(`${nextBooking.date}T${convertTo24Hour(nextBooking.time.split(' - ')[0])}:00`);
                if (nextStartDateTime.getHours() === 8 && nextStartDateTime.getDate() === nextDay.getDate()) {
                    additionalClass = " event-yellow";
                }
            });
        }

        if (booking.title === "Blocked Date") {
            additionalClass = " event-red";
        }
    });

    if (!hasFullDayBooking) {
        if (hasDayBooking && hasNightBooking) {
            additionalClass = " event-red";
        } else if (hasDayBooking) {
            additionalClass = " event-yellow";
        } else if (hasNightBooking) {
            additionalClass = " event-blue";
        }
    }

    return additionalClass;
}

// Automatically trigger filterAccomodation for the first option

document.addEventListener('DOMContentLoaded', (event) => {
    const dropdown = document.getElementById('accommodationDropdown');
    if (dropdown.value) {
        filterAccommodation(dropdown.value);
    }
});
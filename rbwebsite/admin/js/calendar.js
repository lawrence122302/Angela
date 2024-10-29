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
//         time: "10:00 AM",
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

getEvents();

// Function to add days

function initCalendar() {

    // To get prev month days and current month all days and rem next month days

    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const prevLastDay = new Date(year, month, 0);
    const prevDays =  prevLastDay.getDate();
    const lastDate = lastDay.getDate();
    const day = firstDay.getDay();
    const nextDays = 7 - lastDay.getDay() - 1;

    // Update date at the top of calendar

    date.innerHTML = months[month] + " " + year;

    // Adding days on dom

    let days = "";

    // Prev month days

    for (let x = day; x > 0; x--) {
        days += `<div class="day prev-date">${prevDays -x + 1}</div>`
    }

    //Current month days
    for (let i = 1; i <= lastDate; i++) {

        // Check if event present on current day
        
        let event = false;
        eventsArr.forEach((eventObj) => {
            if (
                eventObj.day == i &&
                eventObj.month == month + 1 &&
                eventObj.year == year
            ) {
                // If event found

                event = true;
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
                days += `<div class="day today active event">${i}</div>`
            } else {
                days += `<div class="day today active">${i}</div>`
            }
        }

        // Add remaining as it is

        else {
            if (event) {
                days += `<div class="day event">${i}</div>`
            } else {
                days += `<div class="day">${i}</div>`
            }
        }
    }

    // Next month days

    for (let j = 1;  j <= nextDays; j++) {
        days += `<div class="day next-date">${j}</div>`
    }

    daysContainer.innerHTML = days;

    // Add listner after calendar initialized
    addListner();
}

initCalendar();

// Prev month

function prevMonth() {
    month--;
    if (month < 0) {
        month = 11;
        year--;
    }
    initCalendar();
}

// Next month

function nextMonth() {
    month++;
    if(month > 11) {
        month = 0;
        year++;
    }
    initCalendar();
}

// Add eventlistner on prev and next

prev.addEventListener("click", prevMonth);
next.addEventListener("click", nextMonth);

// Goto today button functionality

todayBtn.addEventListener("click", () => {
    today = new Date();
    month = today.getMonth();
    year = today.getFullYear();
    initCalendar();
});

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

gotoBtn.addEventListener("click", gotoDate);

// Goto entered date functionality

function gotoDate() {
    const dateArr = dateInput.value.split("/");

    // Some date validation

    if (dateArr.length == 2) {
        if (dateArr[0] > 0 && dateArr[0] < 13 && dateArr[1].length == 4) {
            month = dateArr[0] - 1;
            year = dateArr[1];
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

addEventBtn.addEventListener("click", () => {
    addEventContainer.classList.toggle("active");
});

addEventCloseBtn.addEventListener("click", () => {
    addEventContainer.classList.remove("active");
});

document.addEventListener("click", (e) => {

    // If click outside

    if (e.target != addEventBtn && !addEventContainer.contains(e.target)) {
        addEventContainer.classList.remove("active");
    }
});

// Allow only 50 characters in title

addEventTitle.addEventListener("input", (e) => {
    addEventTitle.value = addEventTitle.value.slice(0, 50);
});

// Time format from time

addEventFrom.addEventListener("input", (e) => {

    // Remove anything else but numbers

    addEventFrom.value = addEventFrom.value.replace(/[^0-9:]/g , "");

    // If 2 numbers entered auto add :

    if (addEventFrom.value.length == 2) {
        addEventFrom.value += ":";
    }

    // Don't let users enter more than 5 characters

    if (addEventFrom.value.length > 5) {
        addEventFrom.value = addEventFrom.value.slice(0, 5);
    }
});

// Time format to time

addEventTo.addEventListener("input", (e) => {

    // Remove anything else but numbers

    addEventTo.value = addEventTo.value.replace(/[^0-9:]/g , "");

    // If 2 numbers entered auto add :
    
    if (addEventTo.value.length == 2) {
        addEventTo.value += ":";
    }

    // Don't let users enter more than 5 characters

    if (addEventTo.value.length > 5) {
        addEventTo.value = addEventTo.value.slice(0, 5);
    }
});

// Function to add listener on days after rendered

function addListner() {
    const days = document.querySelectorAll(".day");
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

    // If nothing found

    if (events == "") {
        events = `<div class="no-event">
            <h3>No Events</h3>
        </div>`;
    }

    eventsContainer.innerHTML = events;
    
    // Save events when updated event called

    saveEvents();
}

// Function to add events

addEventSubmit.addEventListener("click", () => {
    const eventTitle = addEventTitle.value;
    const eventTimeFrom = addEventFrom.value;
    const eventTimeTo = addEventTo.value;

    // Some validations

    if (eventTitle == "" || eventTimeFrom == "" || eventTimeTo == "") {
        alert("Please fill all the fields");
        return;
    }

    const timeFromArr = eventTimeFrom.split(":");
    const timeToArr = eventTimeFrom.split(":");

    if (
        timeFromArr.length != 2 ||
        timeToArr.length != 2 ||
        timeFromArr[0] > 23 ||
        timeFromArr[1] > 59 ||
        timeToArr[0] > 23 ||
        timeToArr[1] > 59
    ) {
        alert("Invalid Time Format");
    }

    const timeFrom = convertTime(eventTimeFrom);
    const timeTo = convertTime(eventTimeTo);

    const newEvent = {
        title: eventTitle,
        time: timeFrom + " - " + timeTo,
    };

    let eventAdded = false;

    // Check if eventsarr is not empty

    if (eventsArr.length > 0) {

        // Check if current day has already any event then add to that

        eventsArr.forEach((item) => {
            if (
                item.day == activeDay &&
                item.month == month + 1 &&
                item.year == year
            ) {
                item.events.push(newEvent);
                eventAdded = true;
            }
        });
    }

    // If event array is empty or current day has no event create new

    if (!eventAdded) {
        eventsArr.push({
            day : activeDay,
            month : month + 1,
            year : year,
            events : [newEvent],
        });
    }

    // Remove active from add event form

    addEventContainer.classList.remove("active");

    // Clear the fields

    addEventTitle.value = "";
    addEventFrom.value = "";
    addEventTo.value = "";

    // Show current added event

    updateEvents(activeDay);

    // Add event class to newly added day if not already

    const activeDayElem = document.querySelector(".day.active");
    
    if(!activeDayElem.classList.contains("event")) {
        activeDayElem.classList.add("event")
    }
});

function convertTime(time) {
    let timeArr = time.split(":");
    let timeHour = timeArr[0];
    let timeMin = timeArr[1];
    let timeFormat = timeHour >= 12 ? "PM" : "AM";
    timeHour = timeHour % 12 || 12;
    time = timeHour + ":" + timeMin + " " + timeFormat;
    return time;
}

// Function to remove events on click

eventsContainer.addEventListener("click", (e) => {
    if (e.target.classList.contains("event")) {
        const eventTitle = e.target.children[0].children[1].innerHTML;

        // Get the title of event than search in array by title and delete
        eventsArr.forEach((event) => {
            if (
                event.day == activeDay &&
                event.month == month + 1 &&
                event.year == year
            ) {
                event.events.forEach((item, index) => {
                    if (item.title == eventTitle) {
                        event.events.splice(index, 1)
                    }
                });

                // If no event remaining on that date remove complete day

                if (event.events.length == 0) {
                    eventsArr.splice(eventsArr.indexOf(event), 1);

                    // After removing complete day also remove active class of that day

                    const activeDayElem = document.querySelector(".day.active");

                    if (activeDayElem.classList.contains("event")) {
                        activeDayElem.classList.remove("event");
                    }
                }
            }
        });

        // After removing from array update event

        updateEvents(activeDay);
    }
});

// Store events in local storage get from there

function saveEvents() {
    localStorage.setItem("events", JSON.stringify(eventsArr));
}

function getEvents() {
    if (localStorage.getItem("events" != null)) {
        return;
    }
    eventsArr.push(...JSON.parse(localStorage.getItem("events")));
}
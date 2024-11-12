<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - Virtual Tour</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }

        .faq-container {
            width: 60%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #444;
            margin-bottom: 20px;
        }

        .faq-header {
            background-color: #fff;
            color: #333;
            padding: 20px;
            cursor: pointer;
            border: none;
            border-radius: 15px;
            margin-top: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .faq-header:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .faq-header::after {
            content: '\25BC'; /* Down arrow */
            font-size: 1.2em;
            color: #777;
            transition: transform 0.3s ease;
        }

        .faq-header.active::after {
            transform: rotate(180deg);
        }

        .faq-content {
            display: none;
            padding: 20px;
            border: none;
            border-radius: 15px;
            background-color: #f9f9f9;
            margin-top: -5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .faq-header.active + .faq-content {
            display: block;
        }

        .faq-content p {
            margin: 0;
        }
    </style>
</head>
<body class="bg-light">

    <?php require('inc/navbar.php'); ?>

    <div class="mt-5 mb-2 px-4">
        <h2 class="fw-bold h-font text-center">Frequently Asked Questions (FAQs)</h2>
        <div class="h-line bg-dark"></div>
    </div>

    <div class="faq-container">
        <div class="faq-item">
            <div class="faq-header">1. How do I make a reservation?</div>
            <div class="faq-content">
                <p>To make a reservation, create an account and log in. Once verified, you can access the booking page to select your preferred accommodation and available dates.</p>
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-header">2. What are the available payment methods?</div>
            <div class="faq-content">
                <p>Payments can be made via GCash using the QR code provided or through cash payment via walk-in.</p>
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-header">3. Can I cancel my booking?</div>
            <div class="faq-content">
                <p>Yes, you can cancel your booking, but we employ a strict NO REFUND policy. Any form of payment will be forfeited.</p>
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-header">4. What should I do if I don’t receive an email verification?</div>
            <div class="faq-content">
                <p>Check your spam or junk folder first. If you still don’t see the email, try resending it from your account page or contact our support team for assistance.</p>
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-header">5. Are there different rates for weekdays and weekends?</div>
            <div class="faq-content">
                <p>Yes, the rates differ between weekdays and weekends. Check the booking page for detailed pricing information.</p>
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-header">6. Is there a maximum number of guests allowed per booking?</div>
            <div class="faq-content">
                <p>Yes, each accommodation has a set limit for the number of guests. Please refer to the specific details of each resort type for more information.</p>
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-header">7. How can I contact customer support?</div>
            <div class="faq-content">
                <p>You can reach us through the “Contact Us” page, where you’ll find our phone number, email address, and a form to send direct messages to our team.</p>
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-header">8. Are pets allowed at the resort?</div>
            <div class="faq-content">
                <p>Any kind of Pet (s) is not permitted in any Resort premises. If a pet owner is found in breach of the Pet Policy, he/she will be charged accordingly and will be asked to vacate immediately. If any evidence of a pet (s) is found on the premises, a penalty of PHP25,000, will be charged to your account. The above amount is for the sanitation of pool water and the entire infected areal.</p>
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-header">9. What amenities are included with my booking?</div>
            <div class="faq-content">
                <p>Each booking includes a variety of amenities, which may vary by accommodation type. Check the amenities section on our website for full details.</p>
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-header">10. How do I check the status of my booking?</div>
            <div class="faq-content">
                <p>Log in to your account and go to “My Bookings” to view the status of your reservation.</p>
            </div>
        </div>
    </div>

    <?php require('inc/footer.php'); ?>

    <script>
        document.querySelectorAll('.faq-header').forEach(item => {
            item.addEventListener('click', event => {
                item.classList.toggle('active');
                const content = item.nextElementSibling;
                if (content.style.display === "block") {
                    content.style.display = "none";
                } else {
                    content.style.display = "block";
                }
            })
        })
    </script>
    
</body>
</html>
<?php include "header.php"; ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
<style>
    .contact-container {
        padding: 40px;
        margin: 50px auto;
        max-width: 755px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        text-align: center;
        color: black;
        margin-top: 200px;
    }

    .contact-heading {
        font-size: 2.5rem;
        margin-bottom: 20px;
        color: white;
    }

    .contact-details h4 {
        margin: 15px 0 10px;
        font-size: 1.25rem;
        color: white;
    }

    .contact-details p {
        margin: 5px 0 20px;
        font-size: 1rem;
        color: black;
    }

    .contact-details a {
        color: black;
        text-decoration: none;
        font-weight: bold;
    }

    .contact-details a:hover {
        text-decoration: underline;
    }

    .map-container iframe{
        width: 100%;
        height: 400px;
        border: none;
        border-radius: 10px;
        margin-top: 20px;
        color: white;
    }

    .map-container h4{
        color: white;
    }
</style>
</head>

<div class="contact-container">
    <h2 class="contact-heading">Contact Us</h2>

    <div class="contact-details">
        <h4>Our Office Address:</h4>
        <p>United International University, United City, Madani Avenue<br>Vatara 100 feet, Badda, Dhaka-1212</p>
        
        <h4>Phone Number:</h4>
        <p>📞 <a href="tel:+8801601701444">+880 1601-701444</a></p>

        <h4>Email:</h4>
        <p>📧 <a href="mailto:support@empowerhub.com">support@empowerhub.com</a></p>
    </div>

    <div class="map-container">
        <h4>Find Us on the Map:</h4>
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3649.614918437131!2d90.4471351!3d23.7978829!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c7d8042caf2d%3A0x686fa3e360361ddf!2sUnited%20International%20University!5e0!3m2!1sen!2sbd!4v1672615821560!5m2!1sen!2sbd"
            allowfullscreen=""
            loading="lazy">
        </iframe>
    </div>
</div>

<?php include "footer.php"; ?>

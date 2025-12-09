<?php include "header.php"; ?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
<style>
    .about-container {
        padding: 40px;
        margin: 50px auto;
        max-width: 950px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: black;
        text-align: center;
        margin-top: 190px;
    }

    .about-heading {
        font-size: 2.5rem;
        margin-bottom: 20px;
        color: black;
    }

    .about-section {
        display: flex;
        align-items: center;
        margin-top: 30px;
        gap: 20px;
    }

    .about-section:nth-child(even) {
        flex-direction: row-reverse;
    }

    .about-text {
        flex: 1;
        text-align: justify;
        font-size: 1rem;
        color: black;
    }

    .about-text h3 {
        font-size: 1.8rem;
        margin-bottom: 10px;
        color: #89ccc5;
    }

    .about-image {
        flex: 1;
    }

    .about-image img {
        max-width: 100%;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    @media screen and (max-width: 768px) {
        .about-section {
            flex-direction: column;
        }
    }
</style>
</head>

<div class="about-container">
    <h2 class="about-heading">About Us</h2>

    <div class="about-section">
        <div class="about-text">
            <h3>Who We Are?</h3>
            <p>
                EmpowerHub is a dynamic platform dedicated to fostering personal and professional growth by connecting learners, educators, and businesses.
                Our platform serves as a bridge for skill exchange, collaboration, and empowerment, ensuring everyone has access to opportunities that help 
                them thrive in their respective fields.
            </p>
        </div>
        <div class="about-image">
            <img src="https://st3.depositphotos.com/1092019/15323/i/450/depositphotos_153233456-stock-photo-who-we-are-concept-on.jpg" alt="Who We Are">
        </div>
    </div>

    <div class="about-section">
        <div class="about-text">
            <h3>Our Mission</h3>
            <p>
                Our mission is to create a supportive ecosystem that enables individuals to learn, teach, and grow. We strive to make quality education 
                and resources accessible to everyone while encouraging innovation and collaboration within our community.
            </p>
        </div>
        <div class="about-image">
            <img src="https://cdn.builtin.com/cdn-cgi/image/f=auto,fit=cover,w=1200,h=635,q=80/https://builtin.com/sites/www.builtin.com/files/2022-05/mission-statement-examples.png" alt="Our Mission">
        </div>
    </div>

    <div class="about-section">
        <div class="about-text">
            <h3>Our Vision</h3>
            <p>
                Our vision is to become a global leader in empowering individuals by building a platform that integrates skill exchange, professional growth, 
                and AI-driven support. We aim to inspire and equip people to achieve their goals and positively impact the world.
            </p>
        </div>
        <div class="about-image">
            <img src="https://thumbs.dreamstime.com/b/our-vision-drawn-white-brick-wall-d-inscription-modern-illustation-blue-arrow-hand-icons-around-brickwall-89018617.jpg" alt="Our Vision">
        </div>
    </div>
</div>

<?php include "footer.php"; ?>

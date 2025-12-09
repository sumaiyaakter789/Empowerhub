<style>
  /* Footer Styles */
.footer-container {
    display: flex;
    flex-wrap: wrap;
    background-color: rgba(50, 50, 54, 0.8);
    color: white;
    padding: 20px;
    text-align: justify;
    gap: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
}

.footer-section {
    flex: 1 1 20%;
    min-width: 200px;
    margin-left: 55px;
}

.footer-logo {
    width: 150px;
}

.footer-section ul {
    list-style: none;
    padding: 0;
}

.footer-section ul li {
    margin-bottom: 8px;
}

.footer-section ul li a {
    color: #89ccc5;
    text-decoration: none;
}

.footer-section ul li a:hover {
    text-decoration: underline;
}

.social-links img {
    width: 30px;
    margin-right: 10px;
    vertical-align: middle;
}

/* Newsletter Styles */
.newsletter-container {
    position: relative;
    background-color: rgba(50, 50, 54, 0.9);
    border: 1px solid rgba(255, 255, 255, 0.3);
    padding: 20px;
    color: white;
    text-align: center;
    border-radius: 10px;
    margin: 0 auto;
    width: 50%;
    margin-top: 50px;
}

.newsletter-container form {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.newsletter-container input {
    padding: 10px;
    width: 80%;
    border-radius: 5px;
    font-size: 14px;
    background-color: rgba(255, 255, 255, 0.1);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.subscribe-btn {
    padding: 10px 20px;
    background-color: #89ccc5;
    color: black;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.subscribe-btn:hover {
    background-color: #2f5955;
    color: white;
}

</style>

<!-- Newsletter Subscription Form -->
<div class="newsletter-container">
    <form id="newsletterForm">
        <h3>Subscribe to Our Newsletter</h3>
        <p>Get the latest updates and exclusive content delivered straight to your inbox.</p>
        <p id="newsletterMessage" style="color: yellow; font-weight: bold;"></p>
        
        <input type="email" name="email" id="email" placeholder="Enter your email address" required>
        <button type="submit" class="subscribe-btn">Subscribe</button>
    </form>
</div>

<!-- Footer Section -->
<footer class="footer-container">
    <div class="footer-section">
        <img src="CLogo1-Photoroom.png" alt="EmpowerHub Logo" class="footer-logo">
        <p>EmpowerHub is a platform designed to help individuals to achieve their learning, teaching and business goals efficiently. <a href="signup.php">Join us</a> today to explore the glorious professional growth opportunities.</p>
    </div>
    <div class="footer-section">
        <h4>Additional Links</h4>
        <ul>
            <li><a href="about_us.php">About Us</a></li>
            <li><a href="blog.php">Blog & Article</a></li>
            <li><a href="contact_us.php">Contact Us</a></li>
            <li><a href="faq.php">FAQ</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="privacy_policy.php">Privacy Policy</a></li>
            <li><a href="signup.php">Register</a></li>
            <li><a href="terms_and_conditions.php">Terms & Conditions</a></li>
        </ul>
    </div>
    <div class="footer-section">
        <h4>Similar Platforms</h4>
        <ul>
            <li><a href="https://www.udemy.com" target="_blank">Udemy</a></li>
            <li><a href="https://www.coursera.org" target="_blank">Coursera</a></li>
            <li><a href="https://www.linkedin.com/learning" target="_blank">LinkedIn Learning</a></li>
            <li><a href="https://www.skillshare.com" target="_blank">Skillshare</a></li>
            <li><a href="https://www.edx.org" target="_blank">edX</a></li>
            <li><a href="https://www.khanacademy.org" target="_blank">Khan Academy</a></li>
            <li><a href="https://www.codecademy.com" target="_blank">Codecademy</a></li>
            <li><a href="https://www.w3schools.com" target="_blank">W3Schools</a></li>
        </ul>
    </div>
    <div class="footer-section">
        <h4>Follow Us</h4>
        <ul class="social-links">
            <li><a href="https://www.facebook.com" target="_blank"><img src="https://img.icons8.com/fluency/48/facebook-new.png" alt="Facebook">Facebook</a></li>
            <li><a href="https://www.instagram.com" target="_blank"><img src="https://img.icons8.com/fluency/48/instagram-new.png" alt="Instagram">Instagram</a></li>
            <li><a href="https://www.twitter.com" target="_blank"><img src="https://img.icons8.com/fluency/48/twitter.png" alt="Twitter">Twitter</a></li>
            <li><a href="https://www.linkedin.com" target="_blank"><img src="https://img.icons8.com/fluency/48/linkedin.png" alt="LinkedIn">LinkedIn</a></li>
            <li><a href="https://www.youtube.com" target="_blank"><img src="https://img.icons8.com/fluency/48/youtube-play.png" alt="YouTube">YouTube</a></li>
        </ul>
    </div>
</footer>

<script>
    document.getElementById("newsletterForm").addEventListener("submit", function(event) {
        event.preventDefault();

        const email = document.getElementById("email").value;
        const messageContainer = document.getElementById("newsletterMessage");

        // Send AJAX request
        fetch("newsletter_subscribe.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `email=${encodeURIComponent(email)}`,
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                messageContainer.style.color = "green";
            } else if (data.status === "exists") {
                messageContainer.style.color = "orange";
            } else {
                messageContainer.style.color = "red";
            }
            messageContainer.textContent = data.message;
        })
        .catch(error => {
            messageContainer.style.color = "red";
            messageContainer.textContent = "An error occurred. Please try again.";
        });
    });
</script>
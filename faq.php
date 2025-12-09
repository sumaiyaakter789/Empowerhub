<?php include "header.php"; ?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Frequently Asked Questions</title>
<style>
    .faq-container {
        max-width: 1000px;
        margin: 50px auto;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 40px;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: #fff;
        text-align: center;
        margin-top: 180px;
    }

    .faq-heading {
        font-size: 2.5rem;
        color: white;
        margin-bottom: 20px;
    }

    .faq-list {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .faq-item {
        margin-bottom: 20px;
    }

    .faq-question {
        cursor: pointer;
        padding: 15px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        font-size: 1.2rem;
        color: black;
        text-align: left;
        transition: background-color 0.3s;
    }

    .faq-question:hover {
        background-color: rgba(137, 204, 197, 0.2);
    }

    .faq-answer {
        display: none;
        padding: 15px;
        margin-top: 5px;
        background: rgba(50, 50, 54, 0.9);
        border-radius: 10px;
        text-align: left;
        font-size: 1rem;
        color: #d0d0d0;
    }

    .faq-question.active + .faq-answer {
        display: block;
    }
</style>
</head>

<div class="faq-container">
    <h2 class="faq-heading">Frequently Asked Questions</h2>
    <ul class="faq-list">
        <li class="faq-item">
            <div class="faq-question">1. What is EmpowerHub?</div>
            <div class="faq-answer">EmpowerHub is a platform that connects learners, educators, and businesses to foster skill exchange and professional growth.</div>
        </li>
        <li class="faq-item">
            <div class="faq-question">2. How can I sign up?</div>
            <div class="faq-answer">You can sign up by clicking on the "Sign Up" button in the top navigation bar and filling out the registration form.</div>
        </li>
        <li class="faq-item">
            <div class="faq-question">3. Is EmpowerHub free to use?</div>
            <div class="faq-answer">Yes, EmpowerHub offers free membership for basic features. Some advanced tools may require a premium subscription.</div>
        </li>
        <li class="faq-item">
            <div class="faq-question">4. How can I access the skill exchange feature?</div>
            <div class="faq-answer">Once you log in, navigate to the "Skill Exchange" section from the dashboard to connect with others.</div>
        </li>
        <li class="faq-item">
            <div class="faq-question">5. Can I collaborate with other users?</div>
            <div class="faq-answer">Yes, EmpowerHub provides tools for collaboration, including messaging and project management features.</div>
        </li>
        <li class="faq-item">
            <div class="faq-question">6. What types of resources are available on EmpowerHub?</div>
            <div class="faq-answer">EmpowerHub offers courses, articles, templates, and community-driven resources to help you grow professionally.</div>
        </li>
        <li class="faq-item">
            <div class="faq-question">7. How secure is my data?</div>
            <div class="faq-answer">We prioritize your privacy and employ state-of-the-art security measures to protect your data.</div>
        </li>
        <li class="faq-item">
            <div class="faq-question">8. Can businesses use EmpowerHub?</div>
            <div class="faq-answer">Yes, businesses can use EmpowerHub to recruit talent, upskill employees, and promote their services.</div>
        </li>
        <li class="faq-item">
            <div class="faq-question">9. How do I reset my password?</div>
            <div class="faq-answer">Click on "Forgot Password" on the login page and follow the instructions to reset your password.</div>
        </li>
        <li class="faq-item">
            <div class="faq-question">10. How can I contact customer support?</div>
            <div class="faq-answer">You can reach our customer support team via the "Contact Us" page or email us at support@empowerhub.com.</div>
        </li>
    </ul>
</div>

<script>
    document.querySelectorAll('.faq-question').forEach((question) => {
        question.addEventListener('click', () => {
            question.classList.toggle('active');
            const answer = question.nextElementSibling;
            answer.style.display = answer.style.display === 'block' ? 'none' : 'block';
        });
    });
</script>

<?php include "footer.php"; ?>

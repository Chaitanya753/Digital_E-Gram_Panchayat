<?php
// index.php – Digital E-Gram Panchayat Home Page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Digital E-Gram Panchayat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- INTERNAL CSS -->
    <style>
        /* Body & Background */
        body {
            margin: 0;
            font size : 1000px
            font-family: Arial, sans-serif;
            color: #050303;
            background-image: url('images/G P.png'); 
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        /* Overlay for readability */
        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
            background: rgba(9, 6, 6, 0.2);
            z-index: -1;
        }

        /* Header */
        header {
            background-color: rgba(10, 77, 104, 0.9);
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        nav {
            position: relative;
        }

        nav a, nav button {
            color: white;
            margin-left: 20px;
            text-decoration: none;
            font-weight: bold;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        /* Dropdown */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 160px;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.2);
            border-radius: 5px;
            z-index: 1;
        }

        .dropdown-content a {
            color: #060312;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f12d;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Hero Section */
        .hero {
            text-align: center;
            padding: 80px 20px;
            position: relative;
            color: #ebf1e8;
        }

        .hero h2 {
            font-size: 40px;
            margin-top: 20px;
        }

        .hero p {
            max-width: 700px;
            margin: 15px auto;
            font-size: 18px;
        }

        /* Logo */
        .hero .logo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: auto;
            display: block;
            object-fit: cover;
            border: 4px solid #0a24ea;
        }

        /* Sections */
        .section {
            padding: 70px 50px;
            text-align: center;
            background-color: rgba(220, 208, 208, 0.19);
            margin: 2px;
            border-radius: 10px;
        }

        .section h2 {
            color: #f5f8f9;
            margin-bottom: 2px;

        }

        .gray {
            background-color: rgba(227, 230, 233, 0.21);
        }

        .services {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            max-width: 900px;
            margin: auto;
        }

        .service-box {
            background: white;
            padding: 20px;
            border-radius: 6px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        footer {
            background-color: rgba(10,77,104,0.9);
            color: white;
            text-align: center;
            padding: 15px;
        }

    </style>
</head>
<body>

<!-- HEADER -->
<header>
    <h1>🏛️ Digital E-Gram Panchayat</h1>
    <nav>
        <a href="#Home">Home</a>
        <a href="#about">About</a>
        <a href="#services">Services</a>
        <a href="#contact">Contact</a>

        <!-- Dropdown Login -->
        <div class="dropdown">
            <button>Login ▼</button>
            <div class="dropdown-content">
                <a href="officer_login.php">Officer Login</a>
                <a href="staff_login.php">Staff Login</a>
                <a href="user_login.php">User Login</a>
            </div>
        </div>
    </nav>
</header>

<!-- HERO -->
<section class="hero">
    <img src="images/logo.png" alt="Gram Panchayat Logo" class="logo">
    <h2>Welcome to Digital E-Gram Panchayat</h2>
    <p>
        Digital E-Gram Panchayat is an online platform that enables citizens
        to access Gram Panchayat services digitally, ensuring transparency,
        efficiency, and ease of use.
    </p>
</section>

<!-- ABOUT -->
<section id="about" class="section">
    <h2>About Us</h2>
    <p>
        The Digital E-Gram Panchayat system reduces paperwork and improves
        service delivery by allowing villagers to apply for services online,
        track application status, and receive timely updates.
    </p>
</section>

<!-- SERVICES -->
<section id="services" class="section gray">
    <h2>Our Services</h2>
    <div class="services">
        <div class="service-box">Birth Certificate</div>
        <div class="service-box">Death Certificate</div>
        <div class="service-box">Income Certificate</div>
        <div class="service-box">Residence Certificate</div>
        <div class="service-box">Government Scheme Applications</div>
        <div class="service-box">Online Application Tracking</div>
    </div>
</section>

<!-- CONTACT -->
<section id="contact" class="section">
    <h2>Contact Us</h2>
    <p><strong>Gram Panchayat Office</strong></p>
    <p>Email: egrampanchayat@gmail.com</p>
    <p>Phone: +91 98765 43210</p>
    <p>Office Hours: 9:00 AM – 5:00 PM</p>
</section>

<!-- FOOTER -->
<footer>
    <p>© <?php echo date("Y"); ?> Digital E-Gram Panchayat | All Rights Reserved</p>
</footer>

</body>
</html>

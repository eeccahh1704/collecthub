<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Collect Hub</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Elegant Font -->
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            background: linear-gradient(to right, #d6e3ea, #7aa6bc); /* Blue gradient */
            font-family: 'Merriweather', serif; /* Elegant font */
            color: black;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        /* Header */
        header {
            padding: 15px 30px;
            background: linear-gradient(to right, #16222a, #3a6073); /* Darker gradient */
            display: flex;
            align-items: center;
            width: 100%;
            box-sizing: border-box;
            color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        /* Logo */
        header img {
            width: 280px;
            height: auto;
            margin-right: 30px;
        }
 header nav {
            display: flex;
            justify-content: flex-end;
            flex-grow: 1;
        }

        header nav a {
            color: #ffffff;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            margin-left: 25px;
            transition: all 0.3s;
        }

        header nav a:hover {
            color: #2ecc71;
        }
       

        /* Container */
        .container {
            margin-top: 40px;
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
            width: 95%;
            max-width: 1100px;
            text-align: center;
        }

        h2 {
            font-size: 30px;
            font-weight: bold;
            color: #333;
        }

        p {
            color: #555;
            font-size: 16px;
            margin-top: 20px;
            line-height: 1.8;
            text-align: justify;
            max-width: 800px;
            margin: 20px auto;
        }

        /* Organizational Chart */
        .org-chart {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: row;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .org-member {
            text-align: center;
            margin: 20px;
            background: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            width: 180px;
        }

        .org-member img {
            width: 120px;
            height: 160px;
            border-radius: 50%;
            margin-bottom: 20px;
            border: 3px solid #7aa6bc;
            transition: transform 0.3s ease;
        }

        .org-member img:hover {
            transform: scale(1.1);
        }

        .org-member h4 {
            color: #333;
            font-size: 20px;
            font-weight: bold;
            margin: 10px 0;
        }

        .org-member p {
            color: #666;
            font-size: 16px;
            margin: 5px 0;
        }

        .org-member:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        /* Arrow styles */
        .arrow {
            font-size: 40px;
            color: #16222a;
            margin: 20px 0;
        }

        /* Footer */
        footer {
            margin-top: auto;
            background: linear-gradient(to right, #16222a, #3a6073);
            color: #ffffff;
            text-align: center; /* Ensures text is centered */
            padding: 20px;
            width: 100%;
            box-sizing: border-box;
            font-size: 16px;
            box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.3);
        }

        footer a {
            color: #ffffff;
            text-decoration: none;
            font-weight: bold;
        }

        footer a:hover {
            color: #2ecc71;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <img src="collecthub-removebg-preview.png" alt="COLLECT HUB Logo">
        <nav>
            <a href="index.php">Home</a>
            <a href="login.php">Log In</a>
            <a href="aboutus.php">About Us</a>
        </nav>
    </header>

    <!-- Content -->
    <div class="container">
        <h2>About Us</h2>
        <p>Welcome to Collect Hub! We are a platform dedicated to making item collection easy and efficient. CollectHub is designed to provide a reliable delivery management system for three key stakeholders: students or beneficiaries, auxiliary police, and delivery staff. The system ensures transparency and accountability, allowing each stakeholder to play a crucial role in the process. Below is our team structure:</p>

        <!-- Organizational Chart -->
        <div class="org-chart">
            <div class="org-member">
                <img src="image/ecahcollecthub.png" alt="System Administration">
                <h4>Nurul Aishah Binti Mustafa</h4>
                <p>System Administration</p>
            </div>
            <div class="arrow">&#8594;</div>
            <div class="org-member">
                <img src="image/hedzelina.png" alt="Quality Assurance Engineer">
                <h4>Nur Diana Hedzellina Binti Shaqir</h4>
                <p>Quality Assurance Engineer</p>
            </div>
            <div class="arrow">&#8594;</div>
            <div class="org-member">
                <img src="image/fira.png" alt="Backend Developer">
                <h4>Nur Aleiya Syafira Binti Nordin</h4>
                <p>Backend Developer</p>
            </div>
            <div class="arrow">&#8594;</div>
            <div class="org-member">
                <img src="image/yana.png" alt="Frontend Developer">
                <h4>Yana Binti Norazmi</h4>
                <p>Frontend Developer</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>Contact us: <a href="mailto:uitm@collecthub.com">uitm@collecthub.com</a> | Phone: +036273628</p>
    </footer>

</body>
</html>
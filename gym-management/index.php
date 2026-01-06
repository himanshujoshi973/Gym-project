<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  
  <!-- Font Awesome CSS -->
  <link rel="stylesheet" href="css/all.min.css">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Professional Custom CSS -->
  <link rel="stylesheet" href="css/professional-styles.css">

  <title>Fitness Point - Professional Fitness Center</title>
</head>

<body>
  <!-- Page Loader -->
  <div class="page-loader" id="pageLoader">
    <div class="loader-spinner"></div>
  </div>

  <!-- Enhanced Navigation -->
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNavbar">
    <div class="container">
      <a href="index.php" class="navbar-brand">Fitness Point</a>
      <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a href="#home" class="nav-link active">Home</a>
          </li>
          <li class="nav-item">
            <a href="#about" class="nav-link">About</a>
          </li>
          <li class="nav-item">
            <a href="#services" class="nav-link">Services</a>
          </li>
          <li class="nav-item">
            <a href="#trainers" class="nav-link">Trainers</a>
          </li>
          <li class="nav-item">
            <a href="#contact" class="nav-link">Contact</a>
          </li>
          <li class="nav-item">
            <a href="UserRegistration.php" class="nav-link">Sign Up</a>
          </li>
          <li class="nav-item">
            <a href="member/memberLogin.php" class="nav-link">Login</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Enhanced Hero Section -->
  <section class="hero-section" id="home">
    <div class="hero-content">
      <h1 class="hero-title">Welcome to Fitness Point</h1>
      <p class="hero-subtitle">Transform Your Body, Transform Your Life<br>Join the Professional Fitness Revolution</p>
      <div class="hero-buttons">
        <a href="member/memberLogin.php" class="btn btn-primary-custom">Get Started</a>
        <a href="userRegistration.php" class="btn btn-outline-custom">Join Now</a>
      </div>
    </div>
  </section>

  <!-- Enhanced Services Section -->
  <section class="services-section" id="services">
    <div class="container">
      <h2 class="section-title fade-in">Our Premium Services</h2>
      
      <div class="row">
        <div class="col-lg-4 col-md-6 fade-in">
          <div class="service-card">
            <img src="images/courseimg/06.jpg" alt="Yoga Classes"/>
            <div class="card-body">
              <h5 class="card-title">YOGA CLASSES</h5>
              <p class="card-text">Experience inner peace and flexibility with our expert yoga instructors in a serene environment.</p>
              <div class="price-section">
                <div>
                  <span class="price-old">NPR 2000</span>
                  <span class="price-new">NPR 1500</span>
                </div>
                <a class="btn btn-primary-custom btn-sm" href="userRegistration.php">Book Now</a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 fade-in">
          <div class="service-card">
            <img src="images/courseimg/03.jpg" alt="Weight Training"/>
            <div class="card-body">
              <h5 class="card-title">WEIGHT TRAINING</h5>
              <p class="card-text">Build strength and muscle with our comprehensive weight training programs and professional guidance.</p>
              <div class="price-section">
                <div>
                  <span class="price-old">NPR 2000</span>
                  <span class="price-new">NPR 1200</span>
                </div>
                <a class="btn btn-primary-custom btn-sm" href="userRegistration.php">Book Now</a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 fade-in">
          <div class="service-card">
            <img src="images/courseimg/04.jpg" alt="Zumba"/>
            <div class="card-body">
              <h5 class="card-title">ZUMBA</h5>
              <p class="card-text">Dance your way to fitness with high-energy Zumba classes that make working out fun and exciting.</p>
              <div class="price-section">
                <div>
                  <span class="price-old">NPR 1000</span>
                  <span class="price-new">NPR 700</span>
                </div>
                <a class="btn btn-primary-custom btn-sm" href="userRegistration.php">Book Now</a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 fade-in">
          <div class="service-card">
            <img src="images/courseimg/09.jpg" alt="Cardio Classes"/>
            <div class="card-body">
              <h5 class="card-title">CARDIO CLASSES</h5>
              <p class="card-text">Strengthen your heart and improve endurance with our dynamic cardio training programs.</p>
              <div class="price-section">
                <div>
                  <span class="price-old">NPR 9000</span>
                  <span class="price-new">NPR 6500</span>
                </div>
                <a class="btn btn-primary-custom btn-sm" href="userRegistration.php">Book Now</a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 fade-in">
          <div class="service-card">
            <img src="images/courseimg/05.jpg" alt="Group Training"/>
            <div class="card-body">
              <h5 class="card-title">GROUP TRAINING</h5>
              <p class="card-text">Stay motivated and achieve your goals together with our energetic group training sessions.</p>
              <div class="price-section">
                <div>
                  <span class="price-old">NPR 6000</span>
                  <span class="price-new">NPR 5700</span>
                </div>
                <a class="btn btn-primary-custom btn-sm" href="userRegistration.php">Book Now</a>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 fade-in">
          <div class="service-card">
            <img src="images/courseimg/08.jpg" alt="Endurance Training"/>
            <div class="card-body">
              <h5 class="card-title">ENDURANCE TRAINING</h5>
              <p class="card-text">Push your limits and build lasting stamina with our specialized endurance training programs.</p>
              <div class="price-section">
                <div>
                  <span class="price-old">NPR 4000</span>
                  <span class="price-new">NPR 2200</span>
                </div>
                <a class="btn btn-primary-custom btn-sm" href="userRegistration.php">Book Now</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Enhanced About Section -->
  <section class="about-section" id="about">
    <div class="container">
      <div class="about-content fade-in">
        <h3>About Fitness Point</h3>
        <p>Welcome to Fitness Point — where fitness meets excellence. Founded with the vision of promoting a healthier, stronger community, Fitness Point is more than just a gym; it's a lifestyle destination. We are committed to providing a supportive and motivating environment for individuals of all fitness levels.</p>
        
        <p>Our facility features state-of-the-art equipment, expert trainers, and a variety of fitness programs designed to meet your unique goals — whether you're aiming for weight loss, muscle gain, improved endurance, or overall well-being. We believe fitness should be accessible and enjoyable, which is why we offer flexible membership options, personalized training sessions, and group classes.</p>
        
        <p>At Fitness Point, your journey is our priority. Join us today and take the first step toward becoming the best version of yourself!</p>
      </div>
    </div>
  </section>

  <!-- Enhanced Trainers Section -->
  <section class="trainers-section" id="trainers">
    <div class="container">
      <h2 class="section-title text-white fade-in">Meet Our Expert Trainers</h2>
      
      <div class="row">
        <div class="col-lg-3 col-md-6 fade-in">
          <div class="trainer-card">
            <img src="images/avtar1.jpeg" class="trainer-image" alt="Aashish Thapa">
            <h4 class="card-title">Aashish Thapa</h4>
            <p class="card-text">Martial arts expert with years of experience. Join our martial arts classes and start your journey to discipline and strength.</p>
            <a class="btn btn-primary-custom btn-sm" href="/Gym management system/trainerprofile/aashishthapa.php">View Profile</a>
          </div>
        </div>

        <div class="col-lg-3 col-md-6 fade-in">
          <div class="trainer-card">
            <img src="images/avtar2.jpeg" class="trainer-image" alt="Anupama">
            <h4 class="card-title">Anupama</h4>
            <p class="card-text">Cardio specialist who will help strengthen your body and mind through dynamic cardiovascular training programs.</p>
            <a class="btn btn-primary-custom btn-sm" href="/Gym management system/trainerprofile/anupama.php">View Profile</a>
          </div>
        </div>

        <div class="col-lg-3 col-md-6 fade-in">
          <div class="trainer-card">
            <img src="images/avtar3.jpeg" class="trainer-image" alt="Bikash Shrestha">
            <h4 class="card-title">Bikash Shrestha</h4>
            <p class="card-text">Professional trainer with 7 years at Fitness Point, specializing in weight lifting and strength training programs.</p>
            <a class="btn btn-primary-custom btn-sm" href="/Gym management system/trainerprofile/bikashshrestha.php">View Profile</a>
          </div>
        </div>

        <div class="col-lg-3 col-md-6 fade-in">
          <div class="trainer-card">
            <img src="images/avtar4.jpeg" class="trainer-image" alt="Santoshi">
            <h4 class="card-title">Santoshi</h4>
            <p class="card-text">Certified yoga expert who will guide you through transformative yoga practices for mind, body, and soul wellness.</p>
            <a class="btn btn-primary-custom btn-sm" href="/Gym management system/trainerprofile/santoshi.php">View Profile</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Enhanced Contact Section -->
  <section class="contact-section" id="contact">
    <div class="container">
      <h2 class="section-title fade-in">Get In Touch</h2>
      <div class="row justify-content-center">
        <div class="col-lg-8 fade-in">
          <?php include('contactform.php'); ?>
        </div>
      </div>
    </div>
  </section>

  <!-- Enhanced Footer -->
  <footer class="footer">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6">
          <div class="social-links">
            <span class="pr-3">Follow Us:</span>
            <a href="https://www.facebook.com/fitnesspointnepal" target="_blank"><i class="fab fa-facebook-f"></i></a>
            <a href="https://x.com/home" target="_blank"><i class="fab fa-twitter"></i></a>
            <a href="https://www.youtube.com/" target="_blank"><i class="fab fa-youtube"></i></a>
          </div>
        </div>
        <div class="col-md-6 text-right">
          <small>&copy; 2025 Fitness Point. All rights reserved.</small>
          <small class="ml-3"><a href="Admin/login.php" class="text-light">Admin Login</a></small>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="js/jquery.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/all.min.js"></script>
  <script src="js/professional-animations.js"></script>
</body>

</html>

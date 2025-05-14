<?php
session_start();
include('db_connect.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Healing Haven Hospital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .about-header {
            background: linear-gradient(rgba(44, 73, 100, 0.9), rgba(25, 119, 204, 0.9));
            color: white;
            padding: 80px 0;
        }

        .feature-box {
            padding: 30px;
            border-radius: 15px;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            margin-bottom: 30px;
        }

        .feature-box:hover {
            transform: translateY(-10px);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: #1977cc;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 20px;
        }
    </style>
</head>
<body>
   <!-- Navigation -->
   <nav class="navbar navbar-expand-lg navbar-dark py-3 shadow-sm" style="background-color: #1977cc;">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3 text-white" href="#">
                <i class="fas fa-hospital-alt me-2"></i>Healing Haven
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white px-3 fw-semibold" href="index.php">
                           Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white px-3 fw-semibold" href="about.php">
                             About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white px-3 fw-semibold" href="login.php">
                           Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white px-3 fw-semibold" href="register.php">
                            <i class="fas fa-user-plus me-1"></i> Register
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <!-- About Header -->
    <section class="about-header">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="mb-4">About Healing Haven</h1>
                    <p class="lead mb-0">Setting New Standards in Healthcare Excellence</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2>Our Mission</h2>
                <p class="lead text-primary">Dedicated to Excellence in Healthcare</p>
            </div>
            <div class="row justify-content-center g-4">
                <div class="col-lg-4">
                    <div class="feature-box text-center h-100">
                        <div class="feature-icon">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <h4 class="mb-3">Expert Care</h4>
                        <p class="text-muted">Providing exceptional healthcare through our team of highly qualified medical professionals committed to your well-being.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-box text-center h-100">
                        <div class="feature-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h4 class="mb-3">Compassionate Service</h4>
                        <p class="text-muted">Treating every patient with dignity, respect, and understanding while delivering personalized medical care.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-box text-center h-100">
                        <div class="feature-icon">
                            <i class="fas fa-hospital-user"></i>
                        </div>
                        <h4 class="mb-3">Patient Focus</h4>
                        <p class="text-muted">Creating a healing environment where patient comfort and recovery are our top priorities.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2>Our Vision</h2>
                <p class="lead text-primary">Shaping the Future of Healthcare</p>
            </div>
            <div class="row justify-content-center g-4">
                <div class="col-lg-4">
                    <div class="feature-box text-center h-100">
                        <div class="feature-icon">
                            <i class="fas fa-hand-holding-medical"></i>
                        </div>
                        <h4 class="mb-3">Patient First</h4>
                        <p class="text-muted">Delivering personalized care that puts patients at the heart of everything we do, ensuring their comfort, dignity, and well-being.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-box text-center h-100">
                        <div class="feature-icon">
                            <i class="fas fa-microscope"></i>
                        </div>
                        <h4 class="mb-3">Innovation</h4>
                        <p class="text-muted">Embracing cutting-edge medical technologies and techniques to provide the most advanced healthcare solutions.</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-box text-center h-100">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4 class="mb-3">Community Health</h4>
                        <p class="text-muted">Building a healthier community through accessible, comprehensive healthcare services and preventive care education.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

   


    
    <!-- Footer -->
    <footer class="py-4 text-white" style="background-color: #1977cc;">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="fw-bold">
                        <i class="fas fa-hospital-alt me-2"></i>Healing Haven
                    </h5>
                    <p>Your trusted healthcare partner</p>
                </div>
                <div class="col-md-4">
                    <h5 class="fw-bold">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="index.php" class="text-white text-decoration-none">
                                <i class="fas fa-chevron-right me-2"></i>Home
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="about.php" class="text-white text-decoration-none">
                                <i class="fas fa-chevron-right me-2"></i>About
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-white text-decoration-none">
                                <i class="fas fa-chevron-right me-2"></i>Services
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="#" class="text-white text-decoration-none">
                                <i class="fas fa-chevron-right me-2"></i>Contact
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="fw-bold">Contact Us</h5>
                    <p>
                        <i class="fas fa-envelope me-2"></i>info@healinghaven.com<br>
                        <i class="fas fa-phone me-2"></i>(123) 456-7890<br>
                        <i class="fas fa-map-marker-alt me-2"></i>123 Medical Center Dr.
                    </p>
                </div>
            </div>
            <div class="text-center mt-4 pt-3 border-top border-light">
                <p class="mb-0">&copy; 2024 Healing Haven. All rights reserved.</p>
            </div>
        </div>
    </footer>
 

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
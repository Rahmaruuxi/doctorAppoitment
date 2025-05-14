
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MedCare - Doctor Appointment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex: 1 0 auto;
        }
        .carousel-item {
            height: 400px;
        }
        .carousel-item img {
            object-fit: cover;
            height: 100%;
            width: 100%;
        }
        .doctor-card img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 50%;
        }
        footer {
            flex-shrink: 0;
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
        .feature-box {
            padding: 30px;
            border-radius: 15px;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            margin-bottom: 30px;
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

    <!-- Main Content -->
    <main>
        <!-- Hero Section -->
        <section class="bg-light py-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <h1 class="display-4 fw-bold mb-4" style="color: #1977cc;">
                            Welcome to <br>Healing Haven
                        </h1>
                        <p class="lead mb-4 text-muted">
                            Where compassionate care meets medical excellence. 
                            Book your appointment today and experience healthcare at its finest.
                        </p>
                        <div class="mb-5">
                            <a href="login.php" class="btn btn-primary btn-lg rounded-pill px-5 py-3">
                                <i class="fas fa-calendar-check me-2"></i>Book Appointment
                            </a>
                        </div>
                        <div class="d-flex gap-4">
                            <div>
                                <h3 class="fw-bold" style="color: #1977cc;">
                                    <i class="fas fa-user-md me-2"></i>50+
                                </h3>
                                <p class="text-muted">Expert Doctors</p>
                            </div>
                            <div>
                                <h3 class="fw-bold" style="color: #1977cc;">
                                    <i class="fas fa-hospital me-2"></i>30+
                                </h3>
                                <p class="text-muted">Departments</p>
                            </div>
                            <div>
                                <h3 class="fw-bold" style="color: #1977cc;">
                                    <i class="fas fa-smile me-2"></i>10k+
                                </h3>
                                <p class="text-muted">Happy Patients</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <img src="https://img.freepik.com/free-photo/team-young-specialist-doctors-standing-corridor-hospital_1303-21199.jpg" 
                             alt="Medical Team" 
                             class="img-fluid rounded-4 shadow-lg">
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section class="py-5">
            <div class="container">
                <h2 class="text-center display-5 fw-bold mb-5">Our Services</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                            <div class="card-body text-center p-5">
                                <i class="fas fa-user-md fa-3x text-primary mb-4"></i>
                                <h4 class="fw-bold mb-3">Expert Doctors</h4>
                                <p class="text-muted">Experienced healthcare professionals at your service.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-5">
                                <i class="fas fa-calendar-check fa-3x text-primary mb-4"></i>
                                <h4 class="fw-bold mb-3">Easy Scheduling</h4>
                                <p class="text-muted">Book appointments online at your convenience.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-5">
                                <i class="fas fa-heartbeat fa-3x text-primary mb-4"></i>
                                <h4 class="fw-bold mb-3">Emergency Care</h4>
                                <p class="text-muted">24/7 emergency medical services available.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Doctors Section -->
        <section class="py-5">
            <div class="container">
                <h2 class="text-center display-5 fw-bold mb-5" style="color: #1977cc;">Our Doctors</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm text-center">
                            <div class="p-4">
                                <div class="mx-auto mb-4" style="width: 150px; height: 150px;">
                                    <img src="https://i.pinimg.com/736x/2c/98/06/2c98064f1c5585a648e6d6321b4b65b6.jpg" 
                                         alt="Doctor" 
                                         class="rounded-circle img-fluid shadow"
                                         style="width: 150px; height: 150px; object-fit: cover;">
                                </div>
                                <h5 class="fw-bold mb-1">Dr. John Doe</h5>
                                <p class="text-muted small mb-3">Cardiologist</p>
                                <a href="login.php" class="btn btn-primary rounded-pill px-4">
                                    <i class="fas fa-calendar-check me-2"></i>Book Appointment
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm text-center">
                            <div class="p-4">
                                <div class="mx-auto mb-4" style="width: 150px; height: 150px;">
                                    <img src="https://i.pinimg.com/736x/6c/59/95/6c599523460f54ddeba81f3cd689ae04.jpg" 
                                         alt="Doctor" 
                                         class="rounded-circle img-fluid shadow"
                                         style="width: 150px; height: 150px; object-fit: cover;">
                                </div>
                                <h5 class="fw-bold mb-1">Dr. Jane Smith</h5>
                                <p class="text-muted small mb-3">Neurologist</p>
                                <a href="login.php" class="btn btn-primary rounded-pill px-4">
                                    <i class="fas fa-calendar-check me-2"></i>Book Appointment
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm text-center">
                            <div class="p-4">
                                <div class="mx-auto mb-4" style="width: 150px; height: 150px;">
                                    <img src="https://i.pinimg.com/736x/e6/21/c2/e621c2c9381c059cc61f17f76647de20.jpg" 
                                         alt="Doctor" 
                                         class="rounded-circle img-fluid shadow"
                                         style="width: 150px; height: 150px; object-fit: cover;">
                                </div>
                                <h5 class="fw-bold mb-1">Dr. Mike Johnson</h5>
                                <p class="text-muted small mb-3">Pediatrician</p>
                                <a href="login.php" class="btn btn-primary rounded-pill px-4">
                                    <i class="fas fa-calendar-check me-2"></i>Book Appointment
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

          <!-- Values Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2>Our Core Values</h2>
                <p class="lead text-muted">The principles that guide our service</p>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-box text-center">
                        <div class="feature-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h4>Compassion</h4>
                        <p class="text-muted">We treat every patient with kindness, empathy, and respect</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box text-center">
                        <div class="feature-icon">
                            <i class="fas fa-award"></i>
                        </div>
                        <h4>Excellence</h4>
                        <p class="text-muted">We strive for the highest standards in healthcare delivery</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box text-center">
                        <div class="feature-icon">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <h4>Integrity</h4>
                        <p class="text-muted">We maintain honesty and transparency in all our services</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </main>


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
</body>
</html>


<?php
// Start PHP session to manage user login state
session_start();
// Include the database connection file
include('db_connect.php');

// Initialize variable to store error/success messages
$info = '';

// Check if the login form was submitted
if (isset($_POST['submit'])) {
    // Sanitize user input for email and password
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // SQL query to check user credentials and get their role
    // Joins users table with roles table to get role information
    $query = "SELECT u.*, r.role_name 
              FROM users u 
              JOIN roles r ON u.role_id = r.id 
              WHERE u.email = '$email' AND u.password = '$password'";
    
    // Execute the query
    $result = $conn->query($query);

    // Check if user exists and credentials are correct
    if ($result->num_rows > 0) {
        // Fetch user data from result
        $user = $result->fetch_assoc();
        // Store user information in session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role_name'];
        $_SESSION['email'] = $user['email'];

        // Redirect user to appropriate dashboard based on their role
        if ($user['role_name'] == 'admin') {
            header("Location: adminDashboard.php");
        } else if ($user['role_name'] == 'doctor') {
            header("Location: doctor/dashboard.php");
        } else if ($user['role_name'] == 'patient') {
            header("Location: patient/dashboard.php");
        }
        exit();
    } else {
        // Set error message if login fails
        $info = "<div class='alert alert-danger'>Invalid email or password</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Define character set and viewport for responsive design -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Page title -->
    <title>Login - Healing Haven</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
    <!-- Main container -->
    <div class="container py-5">
        <!-- Center the login form -->
        <div class="row justify-content-center">
            <div class="col-md-5">
                <!-- Login card with shadow effect -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-5">
                        <!-- Login form header -->
                        <div class="text-center mb-4">
                            <h2 style="color: #1977cc;">Welcome Back</h2>
                            <p class="text-muted">Please login to your account</p>
                        </div>

                        <!-- Display any error/success messages -->
                        <?php echo $info; ?>

                        <!-- Login form -->
                        <form action="" method="POST">
                            <!-- Email input group -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <!-- Email icon -->
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <!-- Email input field -->
                                    <input type="email" 
                                           class="form-control" 
                                           id="email" 
                                           name="email" 
                                           required>
                                </div>
                            </div>

                            <!-- Password input group -->
                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <!-- Password icon -->
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <!-- Password input field -->
                                    <input type="password" 
                                           class="form-control" 
                                           id="password" 
                                           name="password" 
                                           required>
                                    <!-- Toggle password visibility button -->
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            onclick="togglePassword()">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Login button -->
                            <button type="submit" name="submit" class="btn btn-primary w-100 rounded-pill py-2 mb-3">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>

                            <!-- Registration link -->
                            <p class="text-center mb-0">
                                Don't have an account? 
                                <a href="register.php" style="color: #1977cc;">Register here</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Function to toggle password visibility
    function togglePassword() {
        // Get password input element
        const input = document.getElementById('password');
        // Get the eye icon element
        const icon = event.currentTarget.querySelector('i');
        
        // Toggle between password and text type
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
    </script>
</body>
</html>

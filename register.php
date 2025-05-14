<?php
// Start the PHP session
session_start();
// Include database connection file
include('db_connect.php');

// Initialize info variable for status messages
$info = '';

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Sanitize and get form inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $contact_phone = mysqli_real_escape_string($conn, $_POST['contact_phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    // SQL query to check if email already exists in both users and patients tables
    $check_email = "SELECT u.* FROM users u 
                    LEFT JOIN patients p ON u.id = p.user_id 
                    WHERE u.email = '$email' AND p.id IS NOT NULL";
    $result = $conn->query($check_email);
    
    // Check if email exists
    if ($result->num_rows > 0) {
        $info = "<div class='alert alert-danger'>Email already exists</div>";
    } 
    // Check if passwords match
    else if ($password !== $confirm_password) {
        $info = "<div class='alert alert-danger'>Passwords do not match</div>";
    } 
    // Proceed with registration
    else {
        // Insert new user into users table
        $insert_user = "INSERT INTO users (email, password, role_id) 
                       VALUES ('$email', '$password', 
                       (SELECT id FROM roles WHERE role_name = 'patient'))";

        // If user creation successful
        if ($conn->query($insert_user)) {
            // Get the newly created user's ID
            $user_id = $conn->insert_id;
            
            // Insert into patients table with user details
            $insert_patient = "INSERT INTO patients (user_id, name, email, contact_phone, address) 
                             VALUES ('$user_id', '$name', '$email', '$contact_phone', '$address')";
            
            // If patient creation successful
            if ($conn->query($insert_patient)) {
                $info = "<div class='alert alert-success'>Registration successful! Please login.</div>";
                header("refresh:2;url=login.php");
            } else {
                $info = "<div class='alert alert-danger'>Error registering patient: " . $conn->error . "</div>";
            }
        } else {
            $info = "<div class='alert alert-danger'>Error creating user: " . $conn->error . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Healing Haven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4" style="color: #1977cc;">Create Account</h2>
                        
                        <?php echo $info; ?>

                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="name" 
                                       name="name" 
                                       pattern="[A-Za-z ]{2,50}"
                                       title="Only letters and spaces are allowed (2-50 characters)"
                                       placeholder="Enter your full name"
                                       required>
                                <small class="text-muted">Only letters and spaces are allowed</small>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                       title="Please enter a valid email address (e.g., name@example.com)"
                                       placeholder="name@example.com"
                                       required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Enter your password"
                                           required>
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            onclick="togglePassword('password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control" 
                                           id="confirm_password" 
                                           name="confirm_password" 
                                           placeholder="Re-enter your password"
                                           required>
                                    <button class="btn btn-outline-secondary" 
                                            type="button" 
                                            onclick="togglePassword('confirm_password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="contact_phone" class="form-label">Contact Phone</label>
                                <input type="tel" 
                                       class="form-control" 
                                       id="contact_phone" 
                                       name="contact_phone" 
                                       pattern="[0-9+\-\s]+"
                                       title="Please enter a valid phone number (numbers, +, -, and spaces allowed)"
                                       placeholder="Enter your phone number"
                                       required>
                                <small class="text-muted">Enter your phone number with  country code</small>
                            </div>

                            <div class="mb-4">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" 
                                          id="address" 
                                          name="address" 
                                          rows="3" 
                                          minlength="5"
                                          maxlength="200"
                                          title="Address must be between 5 and 200 characters"
                                          placeholder="Enter your full address"
                                          required></textarea>
                            </div>

                            <button type="submit" name="submit" class="btn btn-primary w-100 rounded-pill py-2">
                                <i class="fas fa-user-plus me-2"></i>Register
                            </button>

                            <p class="text-center mt-4 mb-0">
                                Already have an account? <a href="login.php" style="color: #1977cc;">Login here</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Function to toggle password visibility
    function togglePassword(inputId) {
        // Get the password input element
        const input = document.getElementById(inputId);
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

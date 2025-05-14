<?php
// Start a new or resume existing session
session_start();
// Include the database connection file
include('../db_connect.php');

// Security check: Verify if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    // If not logged in or not a patient, redirect to login page
    header("Location: ../login.php");
    exit();
}

// Get the current logged-in user's ID from session
$user_id = $_SESSION['user_id'];
// SQL query to get patient details from database
$query = "SELECT * FROM patients WHERE user_id = '$user_id'";
// Execute the query
$result = $conn->query($query);
// Fetch patient data as associative array
$patient = $result->fetch_assoc();

// SQL query to get list of all doctors
$doctor_query = "SELECT * FROM doctors";
// Execute the query to get doctors
$doctors = $conn->query($doctor_query);

// Initialize variable for status messages
$info = '';
// Check if appointment booking form was submitted
if (isset($_POST['book_appointment'])) {
    // Sanitize form inputs
    $doctor_id = mysqli_real_escape_string($conn, $_POST['doctor_id']);
    $appointment_date = mysqli_real_escape_string($conn, $_POST['appointment_date']);
    $appointment_time = mysqli_real_escape_string($conn, $_POST['appointment_time']);

    // SQL query to insert new appointment
    $insert_query = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, status) 
                     VALUES ('{$patient['id']}', '$doctor_id', '$appointment_date', '$appointment_time', 'pending')";

    // Execute insert query and check if successful
    if ($conn->query($insert_query)) {
        // Show success message
        $info = "<div class='alert alert-success'>Appointment booked successfully!</div>";
    } else {
        // Show error message if booking fails
        $info = "<div class='alert alert-danger'>Error booking appointment: " . $conn->error . "</div>";
    }
}

// Add this new code block after your existing appointment booking code
if (isset($_POST['delete_appointment'])) {
    // Get and sanitize the appointment ID
    $appointment_id = mysqli_real_escape_string($conn, $_POST['appointment_id']);
    
    // SQL query to delete appointment (includes patient_id check for security)
    $delete_query = "DELETE FROM appointments 
                    WHERE id = '$appointment_id' 
                    AND patient_id = '{$patient['id']}'
                    AND status = 'pending'";
    
    // Execute the delete query
    if ($conn->query($delete_query)) {
        // Show success message if deletion was successful
        $info = "<div class='alert alert-success'>Appointment cancelled successfully!</div>";
    } else {
        // Show error message if deletion failed
        $info = "<div class='alert alert-danger'>Error cancelling appointment: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Define character set and viewport settings -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Page title -->
    <title>Patient Dashboard - Healing Haven</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #1977cc;">
        <div class="container">
            <!-- Website logo/name -->
            <a class="navbar-brand" href="#">
                <i class="fas fa-hospital-alt me-2"></i>Healing Haven
            </a>
            <!-- Mobile menu button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Navigation menu items -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Display patient name -->
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">
                            <i class="fas fa-user me-1"></i>
                            <?php echo $patient['name']; ?>
                        </a>
                    </li>
                    <!-- Logout link -->
                    <li class="nav-item">
                        <a class="nav-link text-white" href="../logout.php">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main content container -->
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <!-- Appointment booking card -->
                <div class="card shadow-sm border-0">
                    <div class="card-body p-5">
                        <!-- Card title -->
                        <h3 class="text-center mb-4" style="color: #1977cc;">
                            <i class="fas fa-calendar-plus me-2"></i>Book an Appointment
                        </h3>

                        <!-- Display status messages -->
                        <?php echo $info; ?>

                        <!-- Appointment booking form -->
                        <form action="" method="POST">
                            <!-- Doctor selection dropdown -->
                            <div class="mb-3">
                                <label for="doctor_id" class="form-label">Select Doctor</label>
                                <select class="form-select" name="doctor_id" required>
                                    <option value="">Choose a doctor...</option>
                                    <?php while($doctor = $doctors->fetch_assoc()): ?>
                                        <option value="<?php echo $doctor['id']; ?>">
                                            Dr. <?php echo $doctor['name']; ?> - <?php echo $doctor['specialty']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- Date selection input -->
                            <div class="mb-3">
                                <label for="appointment_date" class="form-label">Appointment Date</label>
                                <input type="date" 
                                       class="form-control" 
                                       name="appointment_date" 
                                       min="<?php echo date('Y-m-d'); ?>"
                                       required>
                            </div>

                            <!-- Time selection dropdown -->
                            <div class="mb-4">
                                <label for="appointment_time" class="form-label">Preferred Time</label>
                                <select class="form-select" name="appointment_time" required>
                                    <option value="">Choose time...</option>
                                    <option value="09:00:00">09:00 AM</option>
                                    <option value="10:00:00">10:00 AM</option>
                                    <option value="11:00:00">11:00 AM</option>
                                    <option value="14:00:00">02:00 PM</option>
                                    <option value="15:00:00">03:00 PM</option>
                                    <option value="16:00:00">04:00 PM</option>
                                </select>
                            </div>

                            <!-- Submit button -->
                            <button type="submit" name="book_appointment" class="btn btn-primary w-100 rounded-pill py-2">
                                <i class="fas fa-calendar-check me-2"></i>Book Appointment
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Appointments list card -->
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-body p-4">
                        <h4 class="mb-4" style="color: #1977cc;">Your Appointments</h4>
                        <?php
                        // SQL query to get all appointments for current patient
                        $appointments_query = "SELECT a.*, d.name as doctor_name, d.specialty 
                                             FROM appointments a 
                                             JOIN doctors d ON a.doctor_id = d.id 
                                             WHERE a.patient_id = '{$patient['id']}'
                                             ORDER BY a.appointment_date DESC";
                        $appointments = $conn->query($appointments_query);
                        
                        // Check if patient has any appointments
                        if ($appointments->num_rows > 0):
                        ?>
                            <!-- Appointments table -->
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Doctor</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($appointment = $appointments->fetch_assoc()): ?>
                                            <tr>
                                                <td>Dr. <?php echo $appointment['doctor_name']; ?></td>
                                                <td><?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?></td>
                                                <td><?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?></td>
                                                <td>
                                                    <!-- Display status with appropriate color -->
                                                    <span class="badge bg-<?php echo $appointment['status'] == 'confirmed' ? 'success' : ($appointment['status'] == 'pending' ? 'warning' : 'danger'); ?>">
                                                        <?php echo ucfirst($appointment['status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if($appointment['status'] == 'pending'): ?>
                                                        <form method="POST">
                                                            <input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
                                                            <button type="submit" 
                                                                    name="delete_appointment" 
                                                                    class="btn btn-danger btn-sm"
                                                                    onclick="return confirm('Are you sure you want to cancel this appointment?');">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <!-- Message when no appointments found -->
                            <p class="text-muted text-center mb-0">No appointments found</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

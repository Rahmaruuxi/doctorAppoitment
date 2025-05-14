<?php
session_start();
include('db_connect.php');

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Get counts for dashboard
$doctors_count = $conn->query("SELECT COUNT(*) as count FROM doctors")->fetch_assoc()['count'];
$patients_count = $conn->query("SELECT COUNT(*) as count FROM patients")->fetch_assoc()['count'];
$appointments_count = $conn->query("SELECT COUNT(*) as count FROM appointments")->fetch_assoc()['count'];

// Add this after your existing update handler
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete_query = "DELETE FROM appointments WHERE id = '$delete_id'";
    
    if ($conn->query($delete_query)) {
        $info = "<div class='alert alert-success'>Appointment deleted successfully!</div>";
    } else {
        $info = "<div class='alert alert-danger'>Error deleting appointment: " . $conn->error . "</div>";
    }
}

// At the top of your file, update your query to include all necessary fields
$query = "SELECT a.*, p.name as patient_name, d.name as doctor_name 
          FROM appointments a 
          JOIN patients p ON a.patient_id = p.id 
          JOIN doctors d ON a.doctor_id = d.id 
          ORDER BY a.appointment_date DESC, a.appointment_time DESC 
          LIMIT 5";  // for recent appointments
$result = $conn->query($query);

// Update your update handler to include error reporting
if (isset($_POST['update'])) {
    $appointment_id = mysqli_real_escape_string($conn, $_POST['appointment_id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $appointment_date = mysqli_real_escape_string($conn, $_POST['appointment_date']);
    $appointment_time = mysqli_real_escape_string($conn, $_POST['appointment_time']);

    $query = "UPDATE appointments 
             SET status = '$status', 
                 appointment_date = '$appointment_date', 
                 appointment_time = '$appointment_time' 
             WHERE id = '$appointment_id'";

    if ($conn->query($query)) {
        $info = "<div class='alert alert-success'>Appointment updated successfully!</div>";
        // Refresh the page to show updated data
        header("Location: adminDashboard.php");
        exit();
    } else {
        $info = "<div class='alert alert-danger'>Error updating appointment: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Healing Haven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .navbar { margin-bottom: 2rem; }
        .card { margin-bottom: 2rem; }
        .table td { vertical-align: middle; }
    </style>
</head>
<body class="bg-light">
    <!-- Admin Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-hospital-alt me-2"></i>Healing Haven Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="adminDashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_doctors.php">
                            <i class="fas fa-user-md me-2"></i>Doctors
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_patients.php">
                            <i class="fas fa-users me-2"></i>Patients
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_appointments.php">
                            <i class="fas fa-calendar-check me-2"></i>Appointments
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_users.php">
                            <i class="fas fa-users-cog me-2"></i>Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_roles.php">
                            <i class="fas fa-user-tag me-2"></i>Roles
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_admins.php">
                            <i class="fas fa-user-shield me-2"></i>Admins
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="container-fluid px-4">
        <h2 class="mb-4">Dashboard Overview</h2>
        
        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Total Doctors</h6>
                                <h2 class="display-4 fw-bold text-primary mb-0"><?php echo $doctors_count; ?></h2>
                            </div>
                            <i class="fas fa-user-md fa-3x text-primary opacity-25"></i>
                        </div>
                        <a href="manage_doctors.php" class="btn btn-primary mt-3">Manage Doctors</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Total Patients</h6>
                                <h2 class="display-4 fw-bold text-success mb-0"><?php echo $patients_count; ?></h2>
                            </div>
                            <i class="fas fa-users fa-3x text-success opacity-25"></i>
                        </div>
                        <a href="manage_patients.php" class="btn btn-success mt-3">Manage Patients</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-muted">Total Appointments</h6>
                                <h2 class="display-4 fw-bold text-info mb-0"><?php echo $appointments_count; ?></h2>
                            </div>
                            <i class="fas fa-calendar-check fa-3x text-info opacity-25"></i>
                        </div>
                        <a href="manage_appointments.php" class="btn btn-info mt-3">Manage Appointments</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities Table -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Appointments</h5>
                <div class="d-flex gap-2">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search...">
                    <select id="statusFilter" class="form-select form-select-sm" style="width: auto;">
                        <option value="">All Status</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="pending">Pending</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <select id="doctorFilter" class="form-select form-select-sm" style="width: auto;">
                        <option value="">All Doctors</option>
                        <?php
                        $doctors = $conn->query("SELECT id, name FROM doctors ORDER BY name");
                        while($doctor = $doctors->fetch_assoc()) {
                            echo "<option value='".$doctor['id']."'>Dr. ".$doctor['name']."</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="appointmentsTable">
                        <thead>
                            <tr>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT a.*, p.name as patient_name, d.name as doctor_name 
                                     FROM appointments a 
                                     JOIN patients p ON a.patient_id = p.id 
                                     JOIN doctors d ON a.doctor_id = d.id 
                                     ORDER BY a.appointment_date DESC, a.appointment_time DESC 
                                     LIMIT 5";  // for recent appointments
                            $result = $conn->query($query);
                            while($row = $result->fetch_assoc()):
                            ?>
                            <tr data-doctor-id="<?php echo $row['doctor_id']; ?>">
                                <td><?php echo $row['patient_name']; ?></td>
                                <td>Dr. <?php echo $row['doctor_name']; ?></td>
                                <td><?php echo date('M d, Y', strtotime($row['appointment_date'])); ?></td>
                                <td><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $row['status'] == 'confirmed' ? 'success' : 
                                            ($row['status'] == 'pending' ? 'warning' : 'danger'); 
                                    ?>">
                                        <?php echo ucfirst($row['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <button type="button" 
                                            class="btn btn-sm btn-primary" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal<?php echo $row['id']; ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="?delete_id=<?php echo $row['id']; ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Are you sure you want to delete this appointment?')">
                                        <i class="fas fa-trash"></i>
                                    </a>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Appointment</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="adminDashboard.php" method="POST">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label">Patient Name</label>
                                                            <input type="text" class="form-control" value="<?php echo $row['patient_name']; ?>" readonly>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Doctor Name</label>
                                                            <input type="text" class="form-control" value="Dr. <?php echo $row['doctor_name']; ?>" readonly>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Status</label>
                                                            <select name="status" class="form-select" required>
                                                                <option value="pending" <?php echo $row['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                                <option value="confirmed" <?php echo $row['status'] == 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                                                <option value="cancelled" <?php echo $row['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                            </select>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label">Date</label>
                                                            <input type="date" 
                                                                   name="appointment_date" 
                                                                   class="form-control" 
                                                                   value="<?php echo $row['appointment_date']; ?>" 
                                                                   required>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label">Time</label>
                                                            <input type="time" 
                                                                   name="appointment_time" 
                                                                   class="form-control" 
                                                                   value="<?php echo $row['appointment_time']; ?>" 
                                                                   required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" name="update" class="btn btn-primary">Update Appointment</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const doctorFilter = document.getElementById('doctorFilter');
        const table = document.getElementById('appointmentsTable');
        const tbody = table.getElementsByTagName('tbody')[0];
        const rows = tbody.getElementsByTagName('tr');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusTerm = statusFilter.value.toLowerCase();
            const doctorId = doctorFilter.value;

            for (let row of rows) {
                const patientName = row.cells[0].textContent.toLowerCase();
                const doctorName = row.cells[1].textContent.toLowerCase();
                const status = row.cells[4].textContent.toLowerCase();
                const rowDoctorId = row.getAttribute('data-doctor-id');

                const matchesSearch = patientName.includes(searchTerm) || 
                                    doctorName.includes(searchTerm);
                const matchesStatus = !statusTerm || status.includes(statusTerm);
                const matchesDoctor = !doctorId || rowDoctorId === doctorId;

                row.style.display = (matchesSearch && matchesStatus && matchesDoctor) ? '' : 'none';
            }
        }

        searchInput.addEventListener('input', filterTable);
        statusFilter.addEventListener('change', filterTable);
        doctorFilter.addEventListener('change', filterTable);
    });
    </script>
</body>
</html>

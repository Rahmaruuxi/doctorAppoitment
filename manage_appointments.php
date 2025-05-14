<?php
// Start PHP session for admin authentication
session_start();
// Include database connection file
include('db_connect.php');

// Check if user is logged in and has admin role
// If not, redirect to login page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Initialize variables for messages and edit data
$info = '';
$edit_data = null;

// ====== CREATE APPOINTMENT SECTION ======
if (isset($_POST['add_appointment'])) {
    // Get and sanitize all form inputs
    $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);
    $doctor_id = mysqli_real_escape_string($conn, $_POST['doctor_id']);
    $appointment_date = mysqli_real_escape_string($conn, $_POST['appointment_date']);
    $appointment_time = mysqli_real_escape_string($conn, $_POST['appointment_time']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Create SQL query to insert new appointment
    $query = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, status) 
              VALUES ('$patient_id', '$doctor_id', '$appointment_date', '$appointment_time', '$status')";

    // Execute query and check result
    if ($conn->query($query)) {
        $info = "<div class='alert alert-success'>Appointment added successfully</div>";
    } else {
        $info = "<div class='alert alert-danger'>Error creating appointment</div>";
    }
}

// ====== UPDATE APPOINTMENT SECTION ======
if (isset($_POST['update_appointment'])) {
    // Get and sanitize all form inputs for update
    $id = mysqli_real_escape_string($conn, $_POST['appointment_id']);
    $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);
    $doctor_id = mysqli_real_escape_string($conn, $_POST['doctor_id']);
    $appointment_date = mysqli_real_escape_string($conn, $_POST['appointment_date']);
    $appointment_time = mysqli_real_escape_string($conn, $_POST['appointment_time']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Create SQL query to update appointment
    $update_query = "UPDATE appointments SET 
                    patient_id = '$patient_id',
                    doctor_id = '$doctor_id',
                    appointment_date = '$appointment_date',
                    appointment_time = '$appointment_time',
                    status = '$status'
                    WHERE id = $id";

    // Execute update query and check result
    if ($conn->query($update_query)) {
        $info = "<div class='alert alert-success'>Appointment updated successfully</div>";
    } else {
        $info = "<div class='alert alert-danger'>Error updating appointment</div>";
    }
}

// ====== DELETE APPOINTMENT SECTION ======
if (isset($_GET['delete'])) {
    // Get and sanitize appointment ID to delete
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    // Create delete query
    $delete_query = "DELETE FROM appointments WHERE id = $id";
    // Execute delete query and check result
    if ($conn->query($delete_query)) {
        $info = "<div class='alert alert-success'>Appointment deleted successfully</div>";
    } else {
        $info = "<div class='alert alert-danger'>Error deleting appointment</div>";
    }
}

// ====== GET APPOINTMENT FOR EDITING ======
if (isset($_GET['edit'])) {
    // Get and sanitize appointment ID to edit
    $id = mysqli_real_escape_string($conn, $_GET['edit']);
    // Create query to get appointment data
    $edit_query = "SELECT * FROM appointments WHERE id = $id";
    // Execute query
    $edit_result = $conn->query($edit_query);
    // Store appointment data for form population
    $edit_data = $edit_result->fetch_assoc();
}

// ====== FETCH ALL APPOINTMENTS WITH RELATED DATA ======
// Complex query to get appointments with patient and doctor names
$query = "SELECT a.*, 
          p.name as patient_name, 
          d.name as doctor_name,
          d.id as doctor_id
          FROM appointments a 
          JOIN patients p ON a.patient_id = p.id 
          JOIN doctors d ON a.doctor_id = d.id 
          ORDER BY a.appointment_date DESC, a.appointment_time DESC";
// Execute query to get all appointments
$result = $conn->query($query);

// ====== FETCH PATIENTS FOR DROPDOWN ======
// Get all patients for the selection dropdown
$patients_query = "SELECT id, name FROM patients ORDER BY name";
$patients_result = $conn->query($patients_query);

// ====== FETCH DOCTORS FOR DROPDOWN ======
// Get all doctors for the selection dropdown
$doctors_query = "SELECT id, name FROM doctors ORDER BY name";
$doctors_result = $conn->query($doctors_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments - Healing Haven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                        <a class="nav-link" href="adminDashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_doctors.php">Doctors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_patients.php">Patients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="manage_appointments.php">Appointments</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4">
        <?php echo $info; ?>

        <!-- Add/Edit Appointment Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><?php echo $edit_data ? 'Edit Appointment' : 'Add New Appointment'; ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <?php if($edit_data) { ?>
                        <input type="hidden" name="appointment_id" value="<?php echo $edit_data['id']; ?>">
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Patient</label>
                            <select class="form-select" name="patient_id" required>
                                <option value="">Select Patient</option>
                                <?php while($patient = $patients_result->fetch_assoc()): ?>
                                    <option value="<?php echo $patient['id']; ?>" 
                                            <?php echo ($edit_data && $edit_data['patient_id'] == $patient['id']) ? 'selected' : ''; ?>>
                                        <?php echo $patient['name']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <small class="text-muted">Select a patient from the list</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Doctor</label>
                            <select class="form-select" name="doctor_id" required>
                                <option value="">Select Doctor</option>
                                <?php while($doctor = $doctors_result->fetch_assoc()): ?>
                                    <option value="<?php echo $doctor['id']; ?>"
                                            <?php echo ($edit_data && $edit_data['doctor_id'] == $doctor['id']) ? 'selected' : ''; ?>>
                                        Dr. <?php echo $doctor['name']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <small class="text-muted">Select a doctor from the list</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control" name="appointment_date" 
                                   min="<?php echo date('Y-m-d'); ?>"
                                   value="<?php echo $edit_data ? $edit_data['appointment_date'] : ''; ?>" required>
                            <small class="text-muted">Select a future date</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Time</label>
                            <select class="form-select" name="appointment_time" required>
                                <option value="">Select Time</option>
                                <option value="09:00:00" <?php echo ($edit_data && $edit_data['appointment_time'] == '09:00:00') ? 'selected' : ''; ?>>09:00 AM</option>
                                <option value="10:00:00" <?php echo ($edit_data && $edit_data['appointment_time'] == '10:00:00') ? 'selected' : ''; ?>>10:00 AM</option>
                                <option value="11:00:00" <?php echo ($edit_data && $edit_data['appointment_time'] == '11:00:00') ? 'selected' : ''; ?>>11:00 AM</option>
                                <option value="14:00:00" <?php echo ($edit_data && $edit_data['appointment_time'] == '14:00:00') ? 'selected' : ''; ?>>02:00 PM</option>
                                <option value="15:00:00" <?php echo ($edit_data && $edit_data['appointment_time'] == '15:00:00') ? 'selected' : ''; ?>>03:00 PM</option>
                                <option value="16:00:00" <?php echo ($edit_data && $edit_data['appointment_time'] == '16:00:00') ? 'selected' : ''; ?>>04:00 PM</option>
                            </select>
                            <small class="text-muted">Select an available time slot</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="pending" <?php echo ($edit_data && $edit_data['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="confirmed" <?php echo ($edit_data && $edit_data['status'] == 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                <option value="cancelled" <?php echo ($edit_data && $edit_data['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                            <small class="text-muted">Select appointment status</small>
                        </div>
                    </div>
                    <button type="submit" name="<?php echo $edit_data ? 'update_appointment' : 'add_appointment'; ?>" 
                            class="btn btn-primary">
                        <?php echo $edit_data ? 'Update Appointment' : 'Add Appointment'; ?>
                    </button>
                    <?php if($edit_data) { ?>
                        <a href="manage_appointments.php" class="btn btn-secondary">Cancel</a>
                    <?php } ?>
                </form>
            </div>
        </div>

        <!-- Appointments List -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">All Appointments</h5>
                <div class="d-flex gap-2">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search appointments...">
                    <select id="statusFilter" class="form-select form-select-sm" style="width: auto;">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <select id="doctorFilter" class="form-select form-select-sm" style="width: auto;">
                        <option value="">All Doctors</option>
                        <?php
                        $doctors_query = "SELECT DISTINCT d.id, d.name 
                                         FROM doctors d 
                                         INNER JOIN appointments a ON d.id = a.doctor_id 
                                         ORDER BY d.name";
                        $doctors = $conn->query($doctors_query);
                        while($doctor = $doctors->fetch_assoc()) {
                            echo "<option value='".$doctor['id']."'>Dr. ".$doctor['name']."</option>";
                        }
                        ?>
                    </select>
                    <button onclick="exportAppointmentData()" class="btn btn-success btn-sm">
                        <i class="fas fa-file-export me-1"></i> Export
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="appointmentsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr data-doctor-id="<?php echo $row['doctor_id']; ?>">
                                <td><?php echo $row['id']; ?></td>
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
                                    <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?delete=<?php echo $row['id']; ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Are you sure you want to delete this appointment?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
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
    function exportAppointmentData() {
        const table = document.getElementById('appointmentsTable');
        
        // Create CSV content
        let csv = [];
        
        // Add headers
        let headers = [];
        for(let cell of table.rows[0].cells) {
            if(cell.innerText !== 'Actions') {
                headers.push('"' + cell.innerText + '"');
            }
        }
        csv.push(headers.join(','));
        
        // Add rows
        for(let row of table.rows) {
            if(row.rowIndex === 0) continue;
            
            let rowData = [];
            for(let cell of row.cells) {
                if(cell.cellIndex < row.cells.length - 1) {
                    rowData.push('"' + cell.innerText + '"');
                }
            }
            csv.push(rowData.join(','));
        }
        
        // Create and trigger download
        const csvContent = csv.join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        
        if (navigator.msSaveBlob) {
            navigator.msSaveBlob(blob, 'appointments_data.csv');
        } else {
            link.href = window.URL.createObjectURL(blob);
            link.setAttribute('download', 'appointments_data.csv');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }

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
            const selectedDoctorId = doctorFilter.value;

            for (let row of rows) {
                const patientName = row.cells[1].textContent.toLowerCase();
                const doctorName = row.cells[2].textContent.toLowerCase();
                const status = row.cells[5].textContent.toLowerCase();
                const doctorId = row.getAttribute('data-doctor-id');

                const matchesSearch = patientName.includes(searchTerm) || 
                                    doctorName.includes(searchTerm);
                const matchesStatus = !statusTerm || status.includes(statusTerm);
                const matchesDoctor = !selectedDoctorId || doctorId === selectedDoctorId;

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

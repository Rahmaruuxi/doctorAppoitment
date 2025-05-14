
<?php
// Start PHP session for admin authentication
session_start();
// Include database connection file
include('db_connect.php');

// Check if user is logged in and has admin role
// Redirect to login if not authorized
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Initialize variables for messages and edit data
$info = '';
$edit_data = null;

// ====== CREATE DOCTOR SECTION ======
if (isset($_POST['add_doctor'])) {
    // Initialize errors array
    $errors = array();

    // Get and sanitize all form inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $specialty = mysqli_real_escape_string($conn, $_POST['specialty']);
    $contact_phone = mysqli_real_escape_string($conn, $_POST['contact_phone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if email already exists
    $email_check = "SELECT * FROM users WHERE email = '$email'";
    $email_result = $conn->query($email_check);
    
    if ($email_result->num_rows > 0) {
        $errors[] = "Email already exists. Please use a different email.";
    }

    // If no errors, proceed with insertion
    if (empty($errors)) {
        // First create user account in users table
        $user_query = "INSERT INTO users (email, password, role_id) 
                      VALUES ('$email', '$password', 
                      (SELECT id FROM roles WHERE role_name = 'doctor'))";

        if ($conn->query($user_query)) {
            $user_id = $conn->insert_id;
            
            $doctor_query = "INSERT INTO doctors (user_id, name, email, specialty, contact_phone) 
                           VALUES ('$user_id', '$name', '$email', '$specialty', '$contact_phone')";
            
            if ($conn->query($doctor_query)) {
                $info = "<div class='alert alert-success'>Doctor added successfully</div>";
            } else {
                $info = "<div class='alert alert-danger'>Error creating doctor profile</div>";
            }
        } else {
            $info = "<div class='alert alert-danger'>Error creating user account</div>";
        }
    } else {
        // Display all validation errors
        $info = "<div class='alert alert-danger'><ul>";
        foreach ($errors as $error) {
            $info .= "<li>$error</li>";
        }
        $info .= "</ul></div>";
    }
}

// ====== UPDATE DOCTOR SECTION ======
if (isset($_POST['update_doctor'])) {
    // Get and sanitize all form inputs for update
    $id = mysqli_real_escape_string($conn, $_POST['doctor_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $specialty = mysqli_real_escape_string($conn, $_POST['specialty']);
    $contact_phone = mysqli_real_escape_string($conn, $_POST['contact_phone']);

    // Create update query for doctors table
    $update_query = "UPDATE doctors SET 
                    name = '$name',
                    email = '$email',
                    specialty = '$specialty',
                    contact_phone = '$contact_phone'
                    WHERE id = $id";

    // Execute update query and check result
    if ($conn->query($update_query)) {
        $info = "<div class='alert alert-success'>Doctor updated successfully</div>";
    } else {
        $info = "<div class='alert alert-danger'>Error updating doctor</div>";
    }
}

// ====== DELETE DOCTOR SECTION ======
if (isset($_GET['delete'])) {
    // Get and sanitize doctor ID to delete
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    // Create delete query
    $delete_query = "DELETE FROM doctors WHERE id = $id";
    // Execute delete query and check result
    if ($conn->query($delete_query)) {
        $info = "<div class='alert alert-success'>Doctor deleted successfully</div>";
    } else {
        $info = "<div class='alert alert-danger'>Error deleting doctor</div>";
    }
}

// ====== GET DOCTOR FOR EDITING ======
if (isset($_GET['edit'])) {
    // Get and sanitize doctor ID to edit
    $id = mysqli_real_escape_string($conn, $_GET['edit']);
    // Create query to get doctor data
    $edit_query = "SELECT * FROM doctors WHERE id = $id";
    // Execute query
    $edit_result = $conn->query($edit_query);
    // Store doctor data for form population
    $edit_data = $edit_result->fetch_assoc();
}

// ====== FETCH ALL DOCTORS ======
// Query to get all doctors
$query = "SELECT * FROM doctors";
// Execute query
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Doctors - Healing Haven</title>
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
                        <a class="nav-link active" href="manage_doctors.php">Doctors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_patients.php">Patients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_appointments.php">Appointments</a>
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

        <!-- Add/Edit Doctor Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><?php echo $edit_data ? 'Edit Doctor' : 'Add New Doctor'; ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <?php if($edit_data) { ?>
                        <input type="hidden" name="doctor_id" value="<?php echo $edit_data['id']; ?>">
                    <?php } ?>
                    <div class="row">
                        <!-- Name Input -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="name" 
                                   pattern="[A-Za-z. ]{2,50}"
                                   title="Name should only contain letters, dots, and spaces (2-50 characters)"
                                   value="<?php echo $edit_data ? $edit_data['name'] : ''; ?>" 
                                   required>
                            <small class="text-muted">Only letters, dots, and spaces allowed (2-50 characters)</small>
                        </div>

                        <!-- Email Input -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" 
                                   class="form-control" 
                                   name="email" 
                                   pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                   title="Please enter a valid email address"
                                   value="<?php echo $edit_data ? $edit_data['email'] : ''; ?>" 
                                   required>
                            <small class="text-muted">Enter a valid email address</small>
                        </div>

                        <!-- Password Input (only for new doctors) -->
                        <?php if(!$edit_data) { ?>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" 
                                       class="form-control" 
                                       name="password"
                                       minlength="6"
                                       title="Password must be at least 6 characters long"
                                       required>
                                <small class="text-muted">Minimum 6 characters</small>
                            </div>
                        <?php } ?>

                        <!-- Specialty Input -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Specialty</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="specialty"
                                   pattern="[A-Za-z\s\-&]{2,50}"
                                   title="Specialty should only contain letters, spaces, hyphens, and & (2-50 characters)"
                                   value="<?php echo $edit_data ? $edit_data['specialty'] : ''; ?>" 
                                   required>
                            <small class="text-muted">Enter medical specialty (2-50 characters)</small>
                        </div>

                        <!-- Phone Input -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Phone</label>
                            <input type="tel" 
                                   class="form-control" 
                                   name="contact_phone" 
                                   pattern="[0-9+\-\s]{10,15}"
                                   title="Phone number should be 10-15 digits"
                                   value="<?php echo $edit_data ? $edit_data['contact_phone'] : ''; ?>" 
                                   required>
                            <small class="text-muted">Enter valid phone number (10-15 digits)</small>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            name="<?php echo $edit_data ? 'update_doctor' : 'add_doctor'; ?>" 
                            class="btn btn-primary">
                        <?php echo $edit_data ? 'Update Doctor' : 'Add Doctor'; ?>
                    </button>

                    <?php if($edit_data) { ?>
                        <a href="manage_doctors.php" class="btn btn-secondary">Cancel</a>
                    <?php } ?>
                </form>
            </div>
        </div>

        <!-- Doctors List -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">All Doctors</h5>
                <div class="d-flex gap-2">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search doctors...">
                    <select id="specialtyFilter" class="form-select form-select-sm" style="width: auto;">
                        <option value="">All Specialties</option>
                        <?php
                        $specialties_query = "SELECT DISTINCT specialty FROM doctors ORDER BY specialty";
                        $specialties = $conn->query($specialties_query);
                        while($specialty = $specialties->fetch_assoc()) {
                            if(!empty($specialty['specialty'])) {  // Only add non-empty specialties
                                echo "<option value='".$specialty['specialty']."'>".$specialty['specialty']."</option>";
                            }
                        }
                        ?>
                    </select>
                    <button onclick="exportDoctorData()" class="btn btn-success btn-sm">
                        <i class="fas fa-file-export me-1"></i> Export
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="doctorsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Specialty</th>
                                <th>Contact</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['specialty']; ?></td>
                                <td><?php echo $row['contact_phone']; ?></td>
                                <td>
                                    <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?delete=<?php echo $row['id']; ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Are you sure you want to delete this doctor?')">
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
    function exportDoctorData() {
        // Get the table
        const table = document.getElementById('doctorsTable');
        
        // Create CSV content
        let csv = [];
        
        // Add headers
        let headers = [];
        for(let cell of table.rows[0].cells) {
            if(cell.innerText !== 'Actions') { // Skip the Actions column
                headers.push('"' + cell.innerText + '"');
            }
        }
        csv.push(headers.join(','));
        
        // Add rows
        for(let row of table.rows) {
            if(row.rowIndex === 0) continue; // Skip header row
            
            let rowData = [];
            for(let cell of row.cells) {
                // Skip the Actions column
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
        
        // Create download link
        if (navigator.msSaveBlob) { // For IE
            navigator.msSaveBlob(blob, 'doctors_data.csv');
        } else {
            link.href = window.URL.createObjectURL(blob);
            link.setAttribute('download', 'doctors_data.csv');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const specialtyFilter = document.getElementById('specialtyFilter');
        const table = document.getElementById('doctorsTable');
        const tbody = table.getElementsByTagName('tbody')[0];
        const rows = tbody.getElementsByTagName('tr');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const specialtyTerm = specialtyFilter.value;

            for (let row of rows) {
                const name = row.cells[1].textContent.toLowerCase();
                const email = row.cells[2].textContent.toLowerCase();
                const specialty = row.cells[3].textContent;

                const matchesSearch = name.includes(searchTerm) || 
                                    email.includes(searchTerm);
                const matchesSpecialty = !specialtyTerm || specialty === specialtyTerm;

                row.style.display = (matchesSearch && matchesSpecialty) ? '' : 'none';
            }
        }

        searchInput.addEventListener('input', filterTable);
        specialtyFilter.addEventListener('change', filterTable);
    });
    </script>
</body>
</html>

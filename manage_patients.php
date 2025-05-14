<?php
// Start PHP session for admin authentication
session_start();
// Include database connection file
include('db_connect.php');

// Check if user is logged in and is admin
// If not, redirect to login page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Initialize variables for messages and edit data
$info = '';
$edit_data = null;

// Initialize error array
$errors = array();

// ====== CREATE PATIENT SECTION ======
if (isset($_POST['add_patient'])) {
    // Validate name (only letters and spaces allowed)
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        $errors[] = "Name can only contain letters and spaces";
    }
    if (strlen($name) < 2 || strlen($name) > 50) {
        $errors[] = "Name must be between 2 and 50 characters";
    }

    // Validate email
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    // Check if email already exists
    $email_check = "SELECT * FROM users WHERE email = '$email'";
    if ($conn->query($email_check)->num_rows > 0) {
        $errors[] = "Email already exists";
    }

    // Validate phone number (allow numbers, +, -, and spaces)
    $contact_phone = mysqli_real_escape_string($conn, $_POST['contact_phone']);
    if (!preg_match("/^[0-9+\-\s]*$/", $contact_phone)) {
        $errors[] = "Phone number can only contain numbers, +, -, and spaces";
    }
    if (strlen($contact_phone) < 10 || strlen($contact_phone) > 15) {
        $errors[] = "Phone number must be between 10 and 15 characters";
    }

    // Validate address
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    if (strlen($address) < 5 || strlen($address) > 200) {
        $errors[] = "Address must be between 5 and 200 characters";
    }

    // Validate password
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long";
    }

    // If no errors, proceed with insertion
    if (empty($errors)) {
        // First create user account in users table
        $user_query = "INSERT INTO users (email, password, role_id) 
                      VALUES ('$email', '$password', 
                      (SELECT id FROM roles WHERE role_name = 'patient'))";

        if ($conn->query($user_query)) {
            $user_id = $conn->insert_id;
            
            $patient_query = "INSERT INTO patients (user_id, name, email, contact_phone, address) 
                            VALUES ('$user_id', '$name', '$email', '$contact_phone', '$address')";
            
            if ($conn->query($patient_query)) {
                $info = "<div class='alert alert-success'>Patient added successfully</div>";
            } else {
                $info = "<div class='alert alert-danger'>Error creating patient profile</div>";
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

// ====== UPDATE PATIENT SECTION ======
if (isset($_POST['update_patient'])) {
    // Validate name
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        $errors[] = "Name can only contain letters and spaces";
    }
    if (strlen($name) < 2 || strlen($name) > 50) {
        $errors[] = "Name must be between 2 and 50 characters";
    }

    // Validate email
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    // Validate phone
    $contact_phone = mysqli_real_escape_string($conn, $_POST['contact_phone']);
    if (!preg_match("/^[0-9+\-\s]*$/", $contact_phone)) {
        $errors[] = "Phone number can only contain numbers, +, -, and spaces";
    }
    if (strlen($contact_phone) < 10 || strlen($contact_phone) > 15) {
        $errors[] = "Phone number must be between 10 and 15 characters";
    }

    // Validate address
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    if (strlen($address) < 5 || strlen($address) > 200) {
        $errors[] = "Address must be between 5 and 200 characters";
    }

    // If no errors, proceed with update
    if (empty($errors)) {
        $id = mysqli_real_escape_string($conn, $_POST['patient_id']);
        $update_query = "UPDATE patients SET 
                        name = '$name',
                        email = '$email',
                        contact_phone = '$contact_phone',
                        address = '$address'
                        WHERE id = $id";

        if ($conn->query($update_query)) {
            $info = "<div class='alert alert-success'>Patient updated successfully</div>";
        } else {
            $info = "<div class='alert alert-danger'>Error updating patient</div>";
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

// ====== DELETE PATIENT SECTION ======
if (isset($_GET['delete'])) {
    // Get and sanitize patient ID to delete
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    // Create delete query
    $delete_query = "DELETE FROM patients WHERE id = $id";
    // Execute delete query and check result
    if ($conn->query($delete_query)) {
        $info = "<div class='alert alert-success'>Patient deleted successfully</div>";
    } else {
        $info = "<div class='alert alert-danger'>Error deleting patient</div>";
    }
}

// ====== GET PATIENT FOR EDITING ======
if (isset($_GET['edit'])) {
    // Get and sanitize patient ID to edit
    $id = mysqli_real_escape_string($conn, $_GET['edit']);
    // Create query to get patient data
    $edit_query = "SELECT * FROM patients WHERE id = $id";
    // Execute query
    $edit_result = $conn->query($edit_query);
    // Store patient data for form population
    $edit_data = $edit_result->fetch_assoc();
}

// ====== FETCH ALL PATIENTS ======
// Query to get all patients
$query = "SELECT * FROM patients";
// Execute query
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Patients - Healing Haven</title>
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
                        <a class="nav-link active" href="manage_patients.php">Patients</a>
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

        <!-- Add/Edit Patient Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><?php echo $edit_data ? 'Edit Patient' : 'Add New Patient'; ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <?php if($edit_data) { ?>
                        <input type="hidden" name="patient_id" value="<?php echo $edit_data['id']; ?>">
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="name" 
                                   pattern="[A-Za-z ]{2,50}"
                                   title="Name should only contain letters and spaces (2-50 characters)"
                                   value="<?php echo $edit_data ? $edit_data['name'] : ''; ?>" 
                                   required>
                            <small class="text-muted">Only letters and spaces allowed (2-50 characters)</small>
                        </div>

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

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" 
                                      name="address" 
                                      rows="3" 
                                      minlength="5"
                                      maxlength="200"
                                      title="Address must be between 5 and 200 characters"
                                      required><?php echo $edit_data ? $edit_data['address'] : ''; ?></textarea>
                            <small class="text-muted">Enter complete address (5-200 characters)</small>
                        </div>
                    </div>
                    <button type="submit" 
                            name="<?php echo $edit_data ? 'update_patient' : 'add_patient'; ?>" 
                            class="btn btn-primary">
                        <?php echo $edit_data ? 'Update Patient' : 'Add Patient'; ?>
                    </button>
                    <?php if($edit_data) { ?>
                        <a href="manage_patients.php" class="btn btn-secondary">Cancel</a>
                    <?php } ?>
                </form>
            </div>
        </div>

        <!-- Patients List -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">All Patients</h5>
                <div class="d-flex gap-2">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search patients...">
                    <button onclick="exportPatientData()" class="btn btn-success btn-sm">
                        <i class="fas fa-file-export me-1"></i> Export
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="patientsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Address</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['contact_phone']; ?></td>
                                <td><?php echo substr($row['address'], 0, 50) . (strlen($row['address']) > 50 ? '...' : ''); ?></td>
                                <td>
                                    <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?delete=<?php echo $row['id']; ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Are you sure you want to delete this patient?')">
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
    function exportPatientData() {
        // Get the table
        const table = document.getElementById('patientsTable');
        
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
            navigator.msSaveBlob(blob, 'patients_data.csv');
        } else {
            link.href = window.URL.createObjectURL(blob);
            link.setAttribute('download', 'patients_data.csv');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('patientsTable');
        const tbody = table.getElementsByTagName('tbody')[0];
        const rows = tbody.getElementsByTagName('tr');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();

            for (let row of rows) {
                const name = row.cells[1].textContent.toLowerCase(); // Name is in column 1
                const email = row.cells[2].textContent.toLowerCase(); // Email is in column 2
                const contact = row.cells[3].textContent.toLowerCase(); // Contact is in column 3
                const address = row.cells[4].textContent.toLowerCase(); // Address is in column 4

                const matchesSearch = name.includes(searchTerm) || 
                                    email.includes(searchTerm) || 
                                    contact.includes(searchTerm) ||
                                    address.includes(searchTerm);

                row.style.display = matchesSearch ? '' : 'none';
            }
        }

        searchInput.addEventListener('input', filterTable);
    });
    </script>
</body>
</html>

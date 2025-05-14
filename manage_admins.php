<?php
session_start();
include('db_connect.php');

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$info = '';
$edit_data = null;

// Create Admin
if (isset($_POST['add_admin'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role_id = mysqli_real_escape_string($conn, $_POST['role_id']);

    // First create user account
    $user_query = "INSERT INTO users (email, password, role_id) 
                   VALUES ('$email', '$password', 
                   (SELECT id FROM roles WHERE role_name = 'admin'))";

    if ($conn->query($user_query)) {
        $user_id = $conn->insert_id;
        
        // Then create admin record
        $admin_query = "INSERT INTO admins (user_id, role_id) 
                       VALUES ('$user_id', '$role_id')";
        
        if ($conn->query($admin_query)) {
            $info = "<div class='alert alert-success'>Admin added successfully</div>";
        } else {
            $info = "<div class='alert alert-danger'>Error creating admin record</div>";
        }
    } else {
        $info = "<div class='alert alert-danger'>Error creating user account</div>";
    }
}

// Update Admin
if (isset($_POST['update_admin'])) {
    $id = mysqli_real_escape_string($conn, $_POST['admin_id']);
    $role_id = mysqli_real_escape_string($conn, $_POST['role_id']);
    
    // Update admin role
    $update_query = "UPDATE admins SET role_id = '$role_id' WHERE id = $id";

    if ($conn->query($update_query)) {
        $info = "<div class='alert alert-success'>Admin updated successfully</div>";
    } else {
        $info = "<div class='alert alert-danger'>Error updating admin</div>";
    }
}

// Delete Admin
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    
    // Get user_id before deleting admin record
    $user_query = "SELECT user_id FROM admins WHERE id = $id";
    $user_result = $conn->query($user_query);
    $user_data = $user_result->fetch_assoc();
    
    // Delete admin record
    $delete_query = "DELETE FROM admins WHERE id = $id";
    if ($conn->query($delete_query)) {
        // Delete associated user account
        $delete_user = "DELETE FROM users WHERE id = " . $user_data['user_id'];
        $conn->query($delete_user);
        $info = "<div class='alert alert-success'>Admin deleted successfully</div>";
    } else {
        $info = "<div class='alert alert-danger'>Error deleting admin</div>";
    }
}

// Get admin data for editing
if (isset($_GET['edit'])) {
    $id = mysqli_real_escape_string($conn, $_GET['edit']);
    $edit_query = "SELECT a.*, u.email 
                   FROM admins a 
                   JOIN users u ON a.user_id = u.id 
                   WHERE a.id = $id";
    $edit_result = $conn->query($edit_query);
    $edit_data = $edit_result->fetch_assoc();
}

// Fetch all admins with user details
$query = "SELECT a.*, u.email, r.role_name 
          FROM admins a 
          JOIN users u ON a.user_id = u.id 
          JOIN roles r ON a.role_id = r.id";
$result = $conn->query($query);

// Fetch all roles for dropdown
$roles_query = "SELECT * FROM roles";
$roles_result = $conn->query($roles_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admins - Healing Haven</title>
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
                        <a class="nav-link" href="manage_appointments.php">Appointments</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_users.php">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_roles.php">Roles</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="manage_admins.php">Admins</a>
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

        <!-- Add/Edit Admin Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><?php echo $edit_data ? 'Edit Admin' : 'Add New Admin'; ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <?php if($edit_data) { ?>
                        <input type="hidden" name="admin_id" value="<?php echo $edit_data['id']; ?>">
                    <?php } ?>
                    <div class="row">
                        <?php if(!$edit_data) { ?>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                        <?php } else { ?>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="<?php echo $edit_data['email']; ?>" disabled>
                            </div>
                        <?php } ?>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" name="role_id" required>
                                <option value="">Select Role</option>
                                <?php 
                                $roles_result->data_seek(0);
                                while($role = $roles_result->fetch_assoc()): 
                                ?>
                                    <option value="<?php echo $role['id']; ?>"
                                            <?php echo ($edit_data && $edit_data['role_id'] == $role['id']) ? 'selected' : ''; ?>>
                                        <?php echo ucfirst($role['role_name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="<?php echo $edit_data ? 'update_admin' : 'add_admin'; ?>" 
                            class="btn btn-primary">
                        <?php echo $edit_data ? 'Update Admin' : 'Add Admin'; ?>
                    </button>
                    <?php if($edit_data) { ?>
                        <a href="manage_admins.php" class="btn btn-secondary">Cancel</a>
                    <?php } ?>
                </form>
            </div>
        </div>

        <!-- Admins List -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">All Administrators</h5>
                <div class="d-flex gap-2">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search administrators...">
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="adminsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td>
                                    <span class="badge bg-info">
                                        <?php echo ucfirst($row['role_name']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?delete=<?php echo $row['id']; ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Are you sure you want to delete this admin?')">
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
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('adminsTable');
        const tbody = table.getElementsByTagName('tbody')[0];
        const rows = tbody.getElementsByTagName('tr');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();

            for (let row of rows) {
                const name = row.cells[1].textContent.toLowerCase();
                const email = row.cells[2].textContent.toLowerCase();

                const matchesSearch = name.includes(searchTerm) || 
                                    email.includes(searchTerm);

                row.style.display = matchesSearch ? '' : 'none';
            }
        }

        searchInput.addEventListener('input', filterTable);
    });
    </script>
</body>
</html>

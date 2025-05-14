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

// Create User
if (isset($_POST['add_user'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role_id = mysqli_real_escape_string($conn, $_POST['role_id']);

    $query = "INSERT INTO users (email, password, role_id) 
              VALUES ('$email', '$password', '$role_id')";

    if ($conn->query($query)) {
        $info = "<div class='alert alert-success'>User added successfully</div>";
    } else {
        $info = "<div class='alert alert-danger'>Error creating user</div>";
    }
}

// Update User
if (isset($_POST['update_user'])) {
    $id = mysqli_real_escape_string($conn, $_POST['user_id']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role_id = mysqli_real_escape_string($conn, $_POST['role_id']);
    
    // Only update password if a new one is provided
    $password_update = "";
    if (!empty($_POST['password'])) {
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $password_update = ", password = '$password'";
    }

    $update_query = "UPDATE users SET 
                    email = '$email',
                    role_id = '$role_id'
                    $password_update
                    WHERE id = $id";

    if ($conn->query($update_query)) {
        $info = "<div class='alert alert-success'>User updated successfully</div>";
    } else {
        $info = "<div class='alert alert-danger'>Error updating user</div>";
    }
}

// Delete User
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $delete_query = "DELETE FROM users WHERE id = $id";
    if ($conn->query($delete_query)) {
        $info = "<div class='alert alert-success'>User deleted successfully</div>";
    } else {
        $info = "<div class='alert alert-danger'>Error deleting user</div>";
    }
}

// Get user data for editing
if (isset($_GET['edit'])) {
    $id = mysqli_real_escape_string($conn, $_GET['edit']);
    $edit_query = "SELECT * FROM users WHERE id = $id";
    $edit_result = $conn->query($edit_query);
    $edit_data = $edit_result->fetch_assoc();
}

// Fetch all users with role names
$query = "SELECT u.*, r.role_name 
          FROM users u 
          JOIN roles r ON u.role_id = r.id";
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
    <title>Manage Users - Healing Haven</title>
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
                        <a class="nav-link active" href="manage_users.php">Users</a>
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

        <!-- Add/Edit User Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><?php echo $edit_data ? 'Edit User' : 'Add New User'; ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <?php if($edit_data) { ?>
                        <input type="hidden" name="user_id" value="<?php echo $edit_data['id']; ?>">
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-4 mb-3">
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
                        <div class="col-md-4 mb-3">
                            <label class="form-label">
                                Password <?php echo $edit_data ? '(Leave blank to keep current)' : ''; ?>
                            </label>
                            <input type="password" 
                                   class="form-control" 
                                   name="password" 
                                   <?php if(!$edit_data) { ?>
                                   minlength="6"
                                   title="Password must be at least 6 characters"
                                   required
                                   <?php } ?>>
                            <small class="text-muted">
                                <?php echo $edit_data ? 'Enter new password or leave blank' : 'Minimum 6 characters'; ?>
                            </small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" 
                                    name="role_id" 
                                    required>
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
                            <small class="text-muted">Select user role</small>
                        </div>
                    </div>
                    <button type="submit" 
                            name="<?php echo $edit_data ? 'update_user' : 'add_user'; ?>" 
                            class="btn btn-primary">
                        <?php echo $edit_data ? 'Update User' : 'Add User'; ?>
                    </button>
                    <?php if($edit_data) { ?>
                        <a href="manage_users.php" class="btn btn-secondary">Cancel</a>
                    <?php } ?>
                </form>
            </div>
        </div>

        <!-- Users List -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">All Users</h5>
                <div class="d-flex gap-2">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search users...">
                    <select id="roleFilter" class="form-select form-select-sm" style="width: auto;">
                        <option value="">All Roles</option>
                        <?php
                        $roles = $conn->query("SELECT * FROM roles ORDER BY role_name");
                        while($role = $roles->fetch_assoc()) {
                            echo "<option value='".$role['role_name']."'>".$role['role_name']."</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="usersTable">
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
                                       onclick="return confirm('Are you sure you want to delete this user? This will also delete associated doctor/patient data.')">
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
        const roleFilter = document.getElementById('roleFilter');
        const table = document.getElementById('usersTable');
        const tbody = table.getElementsByTagName('tbody')[0];
        const rows = tbody.getElementsByTagName('tr');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const roleTerm = roleFilter.value.toLowerCase();

            for (let row of rows) {
                const email = row.cells[1].textContent.toLowerCase();
                const role = row.cells[2].textContent.toLowerCase();

                const matchesSearch = email.includes(searchTerm);
                const matchesRole = !roleTerm || role.includes(roleTerm);

                row.style.display = (matchesSearch && matchesRole) ? '' : 'none';
            }
        }

        searchInput.addEventListener('input', filterTable);
        roleFilter.addEventListener('change', filterTable);
    });
    </script>
</body>
</html>

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

// Create Role
if (isset($_POST['add_role'])) {
    $role_name = mysqli_real_escape_string($conn, $_POST['role_name']);
    
    $query = "INSERT INTO roles (role_name) VALUES ('$role_name')";
    
    if ($conn->query($query)) {
        $info = "<div class='alert alert-success'>Role added successfully</div>";
    } else {
        $info = "<div class='alert alert-danger'>Error creating role</div>";
    }
}

// Update Role
if (isset($_POST['update_role'])) {
    $id = mysqli_real_escape_string($conn, $_POST['role_id']);
    $role_name = mysqli_real_escape_string($conn, $_POST['role_name']);

    $update_query = "UPDATE roles SET role_name = '$role_name' WHERE id = $id";

    if ($conn->query($update_query)) {
        $info = "<div class='alert alert-success'>Role updated successfully</div>";
    } else {
        $info = "<div class='alert alert-danger'>Error updating role</div>";
    }
}

// Delete Role
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    
    // Check if role is being used
    $check_query = "SELECT COUNT(*) as count FROM users WHERE role_id = $id";
    $check_result = $conn->query($check_query)->fetch_assoc();
    
    if ($check_result['count'] > 0) {
        $info = "<div class='alert alert-danger'>Cannot delete role: It is assigned to users</div>";
    } else {
        $delete_query = "DELETE FROM roles WHERE id = $id";
        if ($conn->query($delete_query)) {
            $info = "<div class='alert alert-success'>Role deleted successfully</div>";
        } else {
            $info = "<div class='alert alert-danger'>Error deleting role</div>";
        }
    }
}

// Get role data for editing
if (isset($_GET['edit'])) {
    $id = mysqli_real_escape_string($conn, $_GET['edit']);
    $edit_query = "SELECT * FROM roles WHERE id = $id";
    $edit_result = $conn->query($edit_query);
    $edit_data = $edit_result->fetch_assoc();
}

// Fetch all roles
$query = "SELECT * FROM roles ORDER BY role_name";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Roles - Healing Haven</title>
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
                        <a class="nav-link active" href="manage_roles.php">Roles</a>
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

        <!-- Add/Edit Role Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0"><?php echo $edit_data ? 'Edit Role' : 'Add New Role'; ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <?php if($edit_data) { ?>
                        <input type="hidden" name="role_id" value="<?php echo $edit_data['id']; ?>">
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role Name</label>
                            <input type="text" class="form-control" name="role_name" 
                                   value="<?php echo $edit_data ? $edit_data['role_name'] : ''; ?>" required>
                        </div>
                    </div>
                    <button type="submit" name="<?php echo $edit_data ? 'update_role' : 'add_role'; ?>" 
                            class="btn btn-primary">
                        <?php echo $edit_data ? 'Update Role' : 'Add Role'; ?>
                    </button>
                    <?php if($edit_data) { ?>
                        <a href="manage_roles.php" class="btn btn-secondary">Cancel</a>
                    <?php } ?>
                </form>
            </div>
        </div>

        <!-- Roles List -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">All Roles</h5>
                <div class="d-flex gap-2">
                    <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search roles...">
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="rolesTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Role Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo ucfirst($row['role_name']); ?></td>
                                <td>
                                    <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?delete=<?php echo $row['id']; ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Are you sure you want to delete this role?')">
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
        const table = document.getElementById('rolesTable');
        const tbody = table.getElementsByTagName('tbody')[0];
        const rows = tbody.getElementsByTagName('tr');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();

            for (let row of rows) {
                const roleName = row.cells[1].textContent.toLowerCase();
                const description = row.cells[2].textContent.toLowerCase();

                const matchesSearch = roleName.includes(searchTerm) || 
                                    description.includes(searchTerm);

                row.style.display = matchesSearch ? '' : 'none';
            }
        }

        searchInput.addEventListener('input', filterTable);
    });
    </script>
</body>
</html>

<?php
session_start();
include_once('db/connect.php');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Quản lý người dùng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <link rel="stylesheet" href="css/header.css?v=<?php echo time(); ?>" type="text/css">

</head>
<body>
<?php include 'header_admin.php'; ?>
<div class="container my-5">
  <h1>User Management</h1>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Username</th>
        <th>Role</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
        if (!isset($conn)) {
          die("Database connection failed.");
        }
        $sql = "SELECT * FROM tbl_user";
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["phone"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["user"]) . "</td>";
            echo "<td>" . ($row["role"] == 0 ? "User" : "Admin") . "</td>";
            echo "<td>" . ($row["status"] == 0 ? "Active" : "Locked") . "</td>";
            echo "<td>
              <a href='edit.php?id=" . $row["id"] . "' class='btn btn-primary btn-sm'>Edit</a>
            </td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='8'>No users found.</td></tr>";
        }

        $conn->close();
      ?>
    </tbody>
  </table>
  <a href="add.php" class="btn btn-success">Add User</a>
</div>
</body>
</html>

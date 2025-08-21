<?php
session_start();
require 'api/db_connect.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
  header("Location: index.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['loginName'] ?? '';
  $password = $_POST['loginPassword'] ?? '';

  if (empty($username) || empty($password)) {
    $login_error = "All fields are required.";
  } else {
    $stmt = $conn->prepare("SELECT u.employee_id, u.username, u.password_hash, u.role, e.full_name 
                                FROM users u JOIN employees e ON u.employee_id = e.id 
                                WHERE u.username = ? AND u.is_active = 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
      $user = $result->fetch_assoc();
      if (password_verify($password, $user['password_hash'])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['employee_id'] = $user['employee_id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['full_name'];
        header("Location: index.php");
        exit();
      }
    }
    $login_error = "Invalid username or password.";
    $stmt->close();
  }
  $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Nordlich Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/css/style.css" rel="stylesheet" />
</head>

<body>
  <div class="login-bg">
    <div class="login-card">
      <div class="logo-holder">
        <img src="assets/images/logo.png" alt="Nordlich Logo">
        <span class="login-title">Employee Login</span>
      </div>
      <?php if (isset($login_error)): ?>
        <div class="login-error"><?php echo htmlspecialchars($login_error); ?></div>
      <?php endif; ?>
      <form method="POST" action="login.php" autocomplete="off">
        <div class="mb-3">
          <label for="loginName" class="form-label login-label">Username</label>
          <input type="text" class="form-control" id="loginName" name="loginName" required>
        </div>
        <div class="mb-3">
          <label for="loginPassword" class="form-label login-label">Password</label>
          <input type="password" class="form-control" id="loginPassword" name="loginPassword" required>
        </div>
        <button type="submit" class="btn btn-login w-100 py-2">Sign In</button>
      </form>
      <div class="login-footer">© 2025 Nordlich Pharma</div>
    </div>
  </div>
</body>

</html>
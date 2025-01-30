<?php
include '../includes/header.php';
include '../includes/db.php';

session_start();

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['password'])) { 
                $_SESSION['admin_id'] = $row['id']; 
                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Invalid username or password.";
            }
        } else {
            $error = "Invalid username or password."; 
        }

    } catch(PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

?>

<div class="hero-section d-flex justify-content-center align-items-center mt-1">
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center">Admin Login</h2>
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary">Login</button>
                            <a href="../index.php" class="btn btn-info">Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include '../includes/footer.php';
?>
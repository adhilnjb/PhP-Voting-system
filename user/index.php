<?php
include '../includes/header.php';
include '../includes/db.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                header("Location: vote.php");
                exit;
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "User not found.";
        }

    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}

?>

<div class="hero-section d-flex justify-content-center align-items-center mt-1" style="min-height: 80vh;">
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card shadow user-login-card">  <div class="card-body">
                        <h2 class="card-title text-center">User Login</h2>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" name="login" class="btn btn-primary">Login</button>
                            </div>
                            <div class="mt-3 text-center">
                                Don't have an account? <a href="./register.php">Register</a><br>
                                <a href="../index.php" class="btn btn-info btn-sm mt-2">Back to Home</a>
                            </div>
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
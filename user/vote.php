<?php
include '../includes/header.php';
include '../includes/db.php';

session_start();

if (!isset($_SESSION['user_id'])) { 
    header("Location: ../user/login.php"); 
    exit;
}

if (isset($_POST['vote'])) {
    $candidate_id = $_POST['candidate_id'];

    try {
        // Check if the user has already voted
        $stmt = $pdo->prepare("SELECT * FROM votes WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $error = "You have already voted.";
        } else {
            // Insert vote into the database
            $stmt = $pdo->prepare("INSERT INTO votes (user_id, candidate_id) VALUES (:user_id, :candidate_id)");
            $stmt->bindParam(':user_id', $_SESSION['user_id']);
            $stmt->bindParam(':candidate_id', $candidate_id);
            $stmt->execute();

            // Update candidate's vote count
            $stmt = $pdo->prepare("UPDATE candidates SET votes = votes + 1 WHERE id = :candidate_id");
            $stmt->bindParam(':candidate_id', $candidate_id);
            $stmt->execute();

            $success = "You have successfully voted!";
        }

    } catch(PDOException $e) {
        $error = "Error voting: " . $e->getMessage();
    }
}

?>
<link rel="stylesheet" href="../css/style.css">
<div class="hero-section ">
<div class="container mt-5">
    <div class="row">
        <?php
        if (isset($error)) {
            echo '<div class="alert alert-danger">' . $error . '</div>';
        } elseif (isset($success)) {
            echo '<div class="alert alert-success">' . $success . '</div>';
        }
        ?>
        <div class="col-md-3 offset-md-4">
            <h2>Vote for a Candidate</h2>
            <?php
            // Fetch candidates from the database
            $stmt = $pdo->query("SELECT * FROM candidates");
            $candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($candidates)) {
                foreach ($candidates as $candidate) {
                    ?>
                    <div class="card mb-2">
                        <div class="card-body">
                            <img src="uploads/<?php echo $candidate['photo']; ?>" alt="<?php echo $candidate['name']; ?>" class="img-fluid mb-3">
                            <h3 class="card-title"><?php echo $candidate['name']; ?></h3>
                            <p class="card-text"><?php echo $candidate['description']; ?></p>
                            <form method="post">
                                <input type="hidden" name="candidate_id" value="<?php echo $candidate['id']; ?>">
                                <button type="submit" name="vote" class="btn btn-success">Vote Now</button>
                            </form>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No candidates found.</p>";
            }
            ?>
            <a href="./index.php" class="btn btn-primary">back</a>
        </div>
    </div>
</div>
</div>
<?php
include '../includes/footer.php';
?>
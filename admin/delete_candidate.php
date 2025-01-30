<?php
include '../includes/header.php';
include '../includes/db.php';

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['delete'])) {
    $candidate_id = $_GET['delete'];

    try {
        // Delete the candidate from the database
        $stmt = $pdo->prepare("DELETE FROM candidates WHERE id = :id");
        $stmt->bindParam(':id', $candidate_id);
        $stmt->execute();

        // Optionally, delete the candidate's photo if it exists
        $stmt = $pdo->prepare("SELECT photo FROM candidates WHERE id = :id");
        $stmt->bindParam(':id', $candidate_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($result['photo'])) {
            $photo_path = $result['photo'];
            if (file_exists($photo_path)) {
                unlink($photo_path); // Delete the photo file
            }
        }

        $success_message = "Candidate deleted successfully!";
    } catch (PDOException $e) {
        $error = "Error deleting candidate: " . $e->getMessage();
    }

    // Redirect back to manage_candidates.php after deletion
    header("Location: manage_candidates.php");
    exit;
} else {
    // If no candidate ID is provided, redirect to manage_candidates.php
    header("Location: manage_candidates.php");
    exit;
}

?>

<?php
include '../includes/footer.php';
?>
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
      
        $stmt = $pdo->prepare("DELETE FROM candidates WHERE id = :id");
        $stmt->bindParam(':id', $candidate_id);
        $stmt->execute();

   
        $stmt = $pdo->prepare("SELECT photo FROM candidates WHERE id = :id");
        $stmt->bindParam(':id', $candidate_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($result['photo'])) {
            $photo_path = $result['photo'];
            if (file_exists($photo_path)) {
                unlink($photo_path); 
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
  
    header("Location: manage_candidates.php");
    exit;
}

?>

<?php
include '../includes/footer.php';
?>
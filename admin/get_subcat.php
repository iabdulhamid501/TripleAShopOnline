<?php
// get_subcat.php - Fetches subcategories for a given category ID (AJAX)
include('includes/config.php');

if(!empty($_POST["cat_id"])) {
    $id = intval($_POST['cat_id']);
    
    // Use prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($con, "SELECT id, subcategoryName FROM subcategory WHERE categoryid = ? ORDER BY subcategoryName");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($result) > 0) {
        echo '<option value="">Select Subcategory</option>';
        while($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['subcategoryName']) . '</option>';
        }
    } else {
        echo '<option value="">No subcategories found</option>';
    }
    mysqli_stmt_close($stmt);
} else {
    echo '<option value="">Select Category first</option>';
}
?>
<?php
include "../config/db.php";

$result = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) as total FROM book_service
WHERE status='Pending'
"));

echo $result['total'];
?>
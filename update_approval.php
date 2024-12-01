<?php
include('db_connection.php');

// Get data from AJAX request
$input = json_decode(file_get_contents('php://input'), true);

$cropId = $input['cropId'];
$status = $input['status']; // 1 for approve, -1 for disapprove

// Validate inputs
if (is_numeric($cropId) && ($status === 1 || $status === -1)) {
    $query = "UPDATE crop SET is_approved = ? WHERE c_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $status, $cropId);

    if ($stmt->execute()) {
        $response = [
            'success' => true,
            'message' => 'Approval status updated successfully.',
            'redirect' => 'approval_page.php' // Redirect to login page
        ];
    } else {
        $response = [
            'success' => false,
            'message' => 'Failed to update approval status.'
        ];
    }

    $stmt->close();
} else {
    $response = [
        'success' => false,
        'message' => 'Invalid input.'
    ];
}

$conn->close();

// Send JSON response
echo json_encode($response);
?>

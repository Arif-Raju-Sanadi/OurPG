<?php
include 'db.php';

$district = $_POST['district'];
$taluka = $_POST['taluka'];
$city_village = $_POST['city_village'];
$sharing = $_POST['sharing'];
$min_price = $_POST['min_price'];
$max_price = $_POST['max_price'];

// Debug: Uncomment to check form data
// echo "<pre>POST Data: " . print_r($_POST, true) . "</pre>";

$sql = "SELECT * FROM pgs WHERE LOWER(district) = LOWER(?) AND LOWER(taluka) = LOWER(?) AND LOWER(city_village) = LOWER(?) AND LOWER(sharing) = LOWER(?) AND price BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssdd", $district, $taluka, $city_village, $sharing, $min_price, $max_price);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OurPG - Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="results-bg">
    <div class="overlay">
        <div class="container mt-5 mb-5">
            <h2 class="text-center mb-4 fw-bold text-white">Search Results</h2>
            <?php if ($result->num_rows > 0): ?>
                <div class="row">
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm border-0 pg-card animate__animated animate__fadeIn">
                                <div class="card-body">
                                    <h5 class="card-title text-primary"><?php echo $row['pg_name']; ?></h5>
                                    <p class="card-text">
                                        <strong>Owner:</strong> <?php echo $row['owner_name']; ?><br>
                                        <strong>Mobile:</strong> <?php echo $row['mobile']; ?><br>
                                        <strong>Sharing:</strong> <?php echo $row['sharing']; ?><br>
                                        <strong>Price:</strong> ₹<?php echo number_format($row['price'], 2); ?><br>
                                        <strong>Location:</strong> <?php echo $row['city_village']; ?>, <?php echo $row['taluka']; ?>
                                    </p>
                                    <a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode($row['address']); ?>" target="_blank" class="btn btn-info btn-sm">View on Google Maps</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-warning text-center" role="alert">
                    No PGs found matching your criteria. Try adjusting your search.
                </div>
            <?php endif; ?>
            <div class="text-center mt-4">
                <a href="search.html" class="btn btn-secondary">Back to Search</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
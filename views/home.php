<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../models/carModel.php';

$featuredCars = task1GetFeaturedCars();
$categories = task1GetCarCategories();

include('header.php');
?>

<div class="container">
    <div class="home-hero">
        <h1>Welcome to Online Car Rent</h1>
        <p>Browse cars by category and find your suitable ride easily.</p>
    </div>

    <section class="section-box">
        <h2>Featured Cars</h2>

        <div class="car-grid">
            <?php if ($featuredCars && mysqli_num_rows($featuredCars) > 0) { ?>
                <?php while ($car = mysqli_fetch_assoc($featuredCars)) { ?>
                    <?php
                        $image = !empty($car['image_path']) ? "../" . $car['image_path'] : "../assets/no-car.png";
                    ?>
                    <div class="car-card">
                        <img src="<?php echo htmlspecialchars($image); ?>" alt="Car Image">
                        <h3><?php echo htmlspecialchars($car['name']); ?></h3>
                        <p>Model: <?php echo htmlspecialchars($car['model']); ?></p>
                        <p>Type: <?php echo htmlspecialchars($car['type']); ?></p>
                        <p>Price/Day: <?php echo htmlspecialchars($car['price_per_day']); ?> BDT</p>
                        <p>Status: <?php echo htmlspecialchars($car['availability_status']); ?></p>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p>No featured cars available yet.</p>
            <?php } ?>
        </div>
    </section>

    <section class="section-box">
        <h2>Browse by Categories</h2>

        <div class="category-bar">
            <?php if ($categories && mysqli_num_rows($categories) > 0) { ?>
                <?php while ($cat = mysqli_fetch_assoc($categories)) { ?>
                    <button class="category-btn" data-type="<?php echo htmlspecialchars($cat['type']); ?>">
                        <?php echo htmlspecialchars($cat['type']); ?>
                    </button>
                <?php } ?>
            <?php } else { ?>
                <p>No categories available yet.</p>
            <?php } ?>
        </div>

        <div id="categoryResult" class="car-grid"></div>
    </section>
</div>

<script src="../assets/ajax.js"></script>

<?php include('footer.php'); ?>

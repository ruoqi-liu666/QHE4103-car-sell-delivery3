<?php
require __DIR__ . '/includes/app.php';

$vehicles = recent_vehicles($pdo, 30);
$message = page_message();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventory | Veluxe Motors</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <header class="site-header">
    <div class="container nav-shell">
      <a class="brand" href="add-car.php">
        <span class="brand-mark">V</span>
        <span>
          <strong>Veluxe Motors</strong>
          <small>Seller & Car Database</small>
        </span>
      </a>
      <nav class="site-nav" aria-label="Primary">
        <a href="register.php">Register Seller</a>
        <a href="add-car.php">Add Car</a>
        <a href="inventory.php" class="is-active">Inventory</a>
      </nav>
    </div>
  </header>

  <main class="section">
    <div class="container">
      <section class="hero hero-compact reveal">
        <div>
          <p class="eyebrow">MySQL inventory</p>
          <h1>Saved vehicle listings with seller contact details.</h1>
        </div>
        <a class="button button-primary" href="add-car.php">Add another car</a>
      </section>

      <p class="form-status <?= $message['type'] ? 'is-' . e($message['type']) : '' ?>" aria-live="polite"><?= e($message['text']) ?></p>

      <section class="inventory-grid reveal">
        <?php if ($vehicles): ?>
          <?php foreach ($vehicles as $vehicle): ?>
            <article class="vehicle-card">
              <?php if ($vehicle['image_path']): ?>
                <img src="<?= e($vehicle['image_path']) ?>" alt="<?= e($vehicle['make'] . ' ' . $vehicle['model']) ?>">
              <?php endif; ?>
              <div class="vehicle-card-body">
                <div class="vehicle-title">
                  <div>
                    <span><?= e((string) $vehicle['manufacture_year']) ?></span>
                    <h2><?= e($vehicle['make'] . ' ' . $vehicle['model']) ?></h2>
                  </div>
                  <strong>RMB <?= e(number_format((float) $vehicle['price'], 2)) ?></strong>
                </div>
                <div class="spec-grid">
                  <span><?= e(number_format((int) $vehicle['mileage'])) ?> km</span>
                  <span><?= e($vehicle['fuel_type']) ?></span>
                  <span><?= e($vehicle['transmission']) ?></span>
                  <span><?= e($vehicle['color']) ?></span>
                  <span><?= e($vehicle['location']) ?></span>
                </div>
                <p><?= e($vehicle['description']) ?></p>
                <div class="seller-box">
                  <strong>Seller: <?= e($vehicle['seller_name']) ?></strong>
                  <span><?= e($vehicle['seller_phone']) ?> / <?= e($vehicle['seller_email']) ?></span>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        <?php else: ?>
          <section class="notice">
            <strong>No vehicle records yet.</strong>
            <span>Add a vehicle to test the MySQL storage flow.</span>
            <a class="button button-secondary" href="add-car.php">Add car</a>
          </section>
        <?php endif; ?>
      </section>
    </div>
  </main>

  <script src="script.js"></script>
</body>
</html>


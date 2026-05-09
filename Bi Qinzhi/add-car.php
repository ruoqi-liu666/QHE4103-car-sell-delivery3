<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/includes/app.php';

// 当前登录用户（从 login.php 写入的 Session 读取）
$currentSellerId   = isset($_SESSION['seller_id']) ? (int) $_SESSION['seller_id'] : 0;
$currentSellerName = $_SESSION['name']     ?? '';
$currentUsername   = $_SESSION['username'] ?? '';

// 仅校验 session 中的 seller 在数据库中确实存在
$isLoggedIn = $currentSellerId > 0 && seller_exists($pdo, $currentSellerId);

$images = texture_options();
$vehicles = recent_vehicles($pdo, 3);
$message = page_message();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Car | Veluxe Motors</title>
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
        <a href="add-car.php" class="is-active">Add Car</a>
        <a href="inventory.php">Inventory</a>
      </nav>
    </div>
  </header>

  <main class="section">
    <div class="container">
      <section class="hero reveal">
        <div>
          <p class="eyebrow">Add-car module</p>
          <h1>Publish a vehicle and link it to a registered seller.</h1>
          <p>The seller information stays in the existing sellers table. Each car record stores its own vehicle details and references the seller by ID.</p>
        </div>
        <img src="../Textures/bm m5.png" alt="Vehicle preview" class="hero-image">
      </section>

      <?php if (!$isLoggedIn): ?>
        <section class="notice reveal">
          <strong>You are not signed in.</strong>
          <span>Please sign in as a seller before publishing a vehicle. The listing will be linked to your account automatically.</span>
          <a class="button button-secondary" href="../login.html">Go to sign in</a>
        </section>
      <?php endif; ?>

      <section class="workspace">
        <form class="panel form-panel reveal" id="carForm" method="post" action="actions/add-car.php" novalidate>
          <div class="panel-heading">
            <div>
              <p class="eyebrow">Vehicle details</p>
              <h2>Add a car listing</h2>
            </div>
          </div>

          <div class="form-grid">
            <label class="field field-wide">
              <span>Seller</span>
              <input type="text"
                     value="<?= $isLoggedIn ? e($currentSellerName . ' / ' . $currentUsername) : 'Not signed in' ?>"
                     readonly
                     style="background:#f5f5f5;cursor:not-allowed;">
              <input type="hidden" name="seller_id" value="<?= e((string) $currentSellerId) ?>">
              <small class="error-message">Listing will be saved under the signed-in seller account.</small>
            </label>

            <label class="field">
              <span>Brand</span>
              <input type="text" name="make" placeholder="BMW" required>
              <small class="error-message"></small>
            </label>
            <label class="field">
              <span>Model</span>
              <input type="text" name="model" placeholder="M5" required>
              <small class="error-message"></small>
            </label>
            <label class="field">
              <span>Manufacture year</span>
              <input type="number" name="manufacture_year" min="1980" max="<?= e((string) ((int) date('Y') + 1)) ?>" placeholder="2024" required>
              <small class="error-message"></small>
            </label>
            <label class="field">
              <span>Price (RMB)</span>
              <input type="number" name="price" min="1" step="0.01" placeholder="428000" required>
              <small class="error-message"></small>
            </label>
            <label class="field">
              <span>Mileage (km)</span>
              <input type="number" name="mileage" min="0" step="1" placeholder="18000" required>
              <small class="error-message"></small>
            </label>
            <label class="field">
              <span>Color</span>
              <input type="text" name="color" placeholder="Black" required>
              <small class="error-message"></small>
            </label>
            <label class="field">
              <span>Fuel type</span>
              <select name="fuel_type" required>
                <option value="">Choose fuel type</option>
                <option>Petrol</option>
                <option>Diesel</option>
                <option>Hybrid</option>
                <option>Electric</option>
              </select>
              <small class="error-message"></small>
            </label>
            <label class="field">
              <span>Transmission</span>
              <select name="transmission" required>
                <option value="">Choose transmission</option>
                <option>Automatic</option>
                <option>Manual</option>
              </select>
              <small class="error-message"></small>
            </label>
            <label class="field">
              <span>Location</span>
              <input type="text" name="location" placeholder="Shanghai" required>
              <small class="error-message"></small>
            </label>
            <label class="field">
              <span>Vehicle image</span>
              <select name="image_path" id="imagePath">
                <option value="">No image</option>
                <?php foreach ($images as $image): ?>
                  <option value="<?= e($image) ?>"><?= e(basename($image)) ?></option>
                <?php endforeach; ?>
              </select>
              <small class="error-message"></small>
            </label>
            <label class="field field-wide">
              <span>Description</span>
              <textarea name="description" placeholder="Describe condition, ownership history, maintenance, and highlights." required></textarea>
              <small class="error-message"></small>
            </label>
          </div>

          <button class="button button-primary" type="submit" <?= !$isLoggedIn ? 'disabled' : '' ?>>Save vehicle</button>
          <p class="form-status <?= $message['type'] ? 'is-' . e($message['type']) : '' ?>" id="formStatus" aria-live="polite"><?= e($message['text']) ?></p>
        </form>

        <aside class="panel preview-panel reveal">
          <p class="eyebrow">Recent records</p>
          <h2>Latest saved cars</h2>
          <div class="mini-list">
            <?php if ($vehicles): ?>
              <?php foreach ($vehicles as $vehicle): ?>
                <article class="mini-card">
                  <?php if ($vehicle['image_path']): ?>
                    <img src="<?= e($vehicle['image_path']) ?>" alt="<?= e($vehicle['make'] . ' ' . $vehicle['model']) ?>">
                  <?php endif; ?>
                  <strong><?= e($vehicle['make'] . ' ' . $vehicle['model']) ?></strong>
                  <span><?= e($vehicle['seller_name']) ?> / RMB <?= e(number_format((float) $vehicle['price'], 2)) ?></span>
                </article>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="empty-text">No vehicles have been saved yet.</p>
            <?php endif; ?>
          </div>
        </aside>
      </section>
    </div>
  </main>

  <script src="script.js"></script>
</body>
</html>


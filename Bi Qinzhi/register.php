<?php
require __DIR__ . '/includes/app.php';

$sellerCount = (int) $pdo->query('SELECT COUNT(*) FROM sellers')->fetchColumn();
$message = page_message();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Seller Registration | Veluxe Motors</title>
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
        <a href="register.php" class="is-active">Register Seller</a>
        <a href="add-car.php">Add Car</a>
        <a href="inventory.php">Inventory</a>
      </nav>
    </div>
  </header>

  <main class="section">
    <div class="container">
      <section class="hero hero-compact reveal">
        <div>
          <p class="eyebrow">Seller information</p>
          <h1>Register seller details before publishing vehicles.</h1>
        </div>
        <div class="hero-stat">
          <span>Registered sellers</span>
          <strong><?= e(str_pad((string) $sellerCount, 2, '0', STR_PAD_LEFT)) ?></strong>
        </div>
      </section>

      <form class="panel form-panel reveal" id="sellerForm" method="post" action="actions/register.php" novalidate>
        <div class="form-grid">
          <label class="field">
            <span>Name</span>
            <input type="text" name="name" placeholder="Wei Zhang" required>
            <small class="error-message"></small>
          </label>
          <label class="field">
            <span>Address</span>
            <input type="text" name="address" placeholder="88 Pudong Avenue" required>
            <small class="error-message"></small>
          </label>
          <label class="field">
            <span>Phone number</span>
            <input type="tel" name="phone" placeholder="13800138000" required>
            <small class="error-message"></small>
          </label>
          <label class="field">
            <span>Email address</span>
            <input type="email" name="email" placeholder="seller@example.com" required>
            <small class="error-message"></small>
          </label>
          <label class="field">
            <span>Username</span>
            <input type="text" name="username" placeholder="seller01" required>
            <small class="error-message"></small>
          </label>
          <label class="field">
            <span>Password</span>
            <input type="password" name="password" placeholder="At least 6 letters or numbers" required>
            <small class="error-message"></small>
          </label>
        </div>
        <button class="button button-primary" type="submit">Save seller</button>
        <p class="form-status <?= $message['type'] ? 'is-' . e($message['type']) : '' ?>" id="formStatus" aria-live="polite"><?= e($message['text']) ?></p>
      </form>
    </div>
  </main>

  <script src="script.js"></script>
</body>
</html>


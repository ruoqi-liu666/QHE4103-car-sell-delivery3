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
  <title>Seller Registration | Veluxe Motors Seller Module</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body data-page="register">
  <header class="site-header">
    <div class="container nav-shell">
      <a class="brand" href="../homepage.html">
        <span class="brand-mark">V</span>
        <span class="brand-copy">
          <strong>Veluxe Motors</strong>
          <small>Seller Module</small>
        </span>
      </a>
      <nav class="site-nav" aria-label="Primary">
        <a href="../homepage.html">Home</a>
        <a href="../search.html">Search</a>
        <a href="register.php" class="is-active">Register</a>
        <a href="../login.html">Login</a>
        <a href="../Bi Qinzhi/add-car.php">Add Car</a>
      </nav>
    </div>
  </header>

  <main class="section">
    <div class="container narrow">
      <section class="seller-hero reveal">
        <div class="seller-hero-copy">
          <p class="eyebrow">Seller onboarding</p>
          <h2>Build trust before the first car is even listed.</h2>
        </div>
        <div class="hero-visual-stack">
          <div class="hero-photo-frame">
            <img
              class="hero-photo"
              src="https://images.pexels.com/photos/7144255/pexels-photo-7144255.jpeg?auto=compress&cs=tinysrgb&w=1200"
              alt="Seller speaking with a customer at a modern car dealership"
              loading="lazy"
            >
          </div>
        </div>
      </section>

      <div class="section-heading reveal reveal-delay-1">
        <p class="eyebrow">Seller Module</p>
        <h1>Register as a vehicle seller</h1>
      </div>

      <section class="insight-strip reveal reveal-delay-1" aria-label="Platform snapshot">
        <article class="stat-card">
          <span>Registered sellers</span>
          <strong><?= e(str_pad((string) $sellerCount, 2, '0', STR_PAD_LEFT)) ?></strong>
        </article>
      </section>

      <section class="workspace-grid workspace-grid-single">
        <form class="panel form-panel reveal reveal-delay-2" id="registrationForm" method="post" action="actions/register.php" novalidate>
          <div class="form-grid">
            <label class="field">
              <span>Name</span>
              <input type="text" name="name" placeholder="Wei Zhang" required>
              <small class="error-message"></small>
            </label>
            <label class="field">
              <span>Address</span>
              <input type="text" name="address" placeholder="88 Pudong Avenue 1" required>
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
          <button class="button button-primary" type="submit">Create seller account</button>
          <p class="form-status <?= $message['type'] ? 'is-' . e($message['type']) : '' ?>" id="registrationStatus" aria-live="polite"><?= e($message['text']) ?></p>
        </form>
      </section>
    </div>
  </main>

  <footer class="site-footer">
    <div class="container footer-shell">
      <p>Veluxe Motors (c) 2026</p>
      <div class="footer-links">
        <a href="../homepage.html">Home</a>
        <a href="../search.html">Search</a>
        <a href="register.php">Register</a>
        <a href="../login.html">Login</a>
        <a href="../Bi Qinzhi/add-car.php">Add Car</a>
      </div>
    </div>
  </footer>

  <script src="script.js"></script>
</body>
</html>

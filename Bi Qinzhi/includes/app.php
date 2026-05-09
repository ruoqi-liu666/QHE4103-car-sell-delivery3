<?php

declare(strict_types=1);

require __DIR__ . '/db.php';

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect_with(string $path, string $type, string $message): never
{
    header('Location: ' . $path . '?' . http_build_query([$type => $message]));
    exit;
}

function page_message(): array
{
    if (isset($_GET['success'])) {
        return ['type' => 'success', 'text' => (string) $_GET['success']];
    }

    if (isset($_GET['error'])) {
        return ['type' => 'error', 'text' => (string) $_GET['error']];
    }

    return ['type' => '', 'text' => ''];
}

function fetch_sellers(PDO $pdo): array
{
    return $pdo
        ->query('SELECT id, name, phone, email, username FROM sellers ORDER BY created_at DESC, id DESC')
        ->fetchAll();
}

function seller_exists(PDO $pdo, int $sellerId): bool
{
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM sellers WHERE id = :id');
    $stmt->execute(['id' => $sellerId]);

    return (int) $stmt->fetchColumn() > 0;
}

function texture_options(): array
{
    $files = glob(__DIR__ . '/../../Textures/*.{png,jpg,jpeg,webp}', GLOB_BRACE) ?: [];
    sort($files, SORT_NATURAL | SORT_FLAG_CASE);

    return array_map(
        static fn (string $file): string => '../Textures/' . basename($file),
        $files
    );
}

function valid_texture_path(string $path): bool
{
    if ($path === '') {
        return true;
    }

    $allowed = texture_options();

    return in_array($path, $allowed, true);
}

function recent_vehicles(PDO $pdo, int $limit = 6): array
{
    $stmt = $pdo->prepare(
        'SELECT
            vehicles.*,
            sellers.name AS seller_name,
            sellers.phone AS seller_phone,
            sellers.email AS seller_email
         FROM vehicles
         INNER JOIN sellers ON sellers.id = vehicles.seller_id
         ORDER BY vehicles.created_at DESC, vehicles.id DESC
         LIMIT :limit'
    );
    $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}


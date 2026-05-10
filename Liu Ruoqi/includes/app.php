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

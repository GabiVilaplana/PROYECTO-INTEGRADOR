<?php
// backend/includes/json_connect.php

function json_get_data(string $filename = 'db.json'): array {
    $path = __DIR__ . '/../../data/' . $filename;
    if (!file_exists($path)) {
        error_log("❌ Fitxer no trobat: $path");
        return [];
    }
    $json = file_get_contents($path);
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

// Opcional: per actualitzar només una part (ex: afegir usuari)
function json_update_collection(string $collection, array $newData): bool {
    $path = __DIR__ . '/../../data/db.json';
    $data = json_decode(file_get_contents($path), true) ?: [];
    $data[$collection] = $newData;
    return file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
}

function json_save_data(string $filename, array $data): bool {
    $path = __DIR__ . '/../../data/' . $filename;
    return file_put_contents(
        $path,
        json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    ) !== false;
}
?>
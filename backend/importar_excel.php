<?php
// backend/importar_excel.php
require_once __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$uploadDir = __DIR__ . '/uploads';          // donde subes los Excel
$dataDir   = dirname(__DIR__) . '/data';    // donde estará db.json

if (!is_dir($dataDir) && !mkdir($dataDir, 0775, true)) {
    die("❌ Error: no se pudo crear la carpeta 'data'.\n");
}

// Inicializa array final que contendrá todos los endpoints
$db = [];

// Buscar todos los Excel en /uploads
$files = glob($uploadDir . '/*.{xlsx,xls}', GLOB_BRACE);
if (!$files) {
    die("❌ No se encontraron archivos .xlsx o .xls en 'backend/uploads/'.\n");
}

foreach ($files as $filePath) {
    $baseName = pathinfo($filePath, PATHINFO_FILENAME); // ej. "servicios"

    try {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray(null, true, true, true);

        if (empty($rows)) {
            echo "⚠️ '$baseName' está vacío, se omite.\n";
            continue;
        }

        // Primera fila = cabeceras
        $headers = array_shift($rows);
        $cleanHeaders = [];
        foreach ($headers as $i => $cell) {
            $cell = trim((string)$cell);
            $cleanHeaders[] = $cell !== '' ? $cell : 'campo_' . ($i + 1);
        }

        // Convertir filas a objetos
        $data = [];
        foreach ($rows as $row) {
            if (!array_filter($row, fn($c) => $c !== null && trim((string)$c) !== '')) continue;
            $data[] = array_combine($cleanHeaders, $row);
        }

        if (!empty($data)) {
            $db[$baseName] = $data; // Agrega al array final con nombre del endpoint
        } else {
            echo "⚠️ '$baseName' no tiene datos válidos, se omite.\n";
        }

    } catch (Exception $e) {
        echo "❌ Error con '$baseName.xlsx': " . $e->getMessage() . "\n";
    }
}

// Escribe db.json combinado en /data
$dbFile = $dataDir . '/db.json';
if (file_put_contents($dbFile, json_encode($db, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) === false) {
    die("❌ Error al escribir '$dbFile'.\n");
}

echo "✅ db.json generado correctamente con endpoints: " . implode(', ', array_keys($db)) . "\n";

<?php
// Configuración de la carpeta de destino
$uploadDir = __DIR__ . '/../../../upload/officebanking'; // Ruta en el sistema de archivos
$publicBaseUrl = 'rentdesk/upload/officebanking/'; // Ruta pública para el navegador

// Crear la carpeta si no existe
if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
    echo json_encode(['status' => 'error', 'message' => 'No se pudo crear la carpeta de destino.']);
    exit;
}

// Verificar si se recibió un archivo válido
if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $nombreArchivo = basename($_POST['nombreArchivo']);
    if (empty($nombreArchivo)) {
        echo json_encode(['status' => 'error', 'message' => 'El nombre del archivo está vacío.']);
        exit;
    }

    $rutaArchivo = rtrim($uploadDir, '/') . '/' . $nombreArchivo;

    // Mover el archivo a la carpeta de destino
    if (move_uploaded_file($_FILES['file']['tmp_name'], $rutaArchivo)) {
        // Generar la URL pública del archivo
        $rutaPublica = $publicBaseUrl . $nombreArchivo;
        echo json_encode([
            'success' => true,
            'filePath' => $rutaArchivo,
            'ruta' => $rutaPublica,
            'fileName' => $nombreArchivo,
            'message' => 'Archivo guardado correctamente.'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo guardar el archivo.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No se recibió un archivo válido.', 'error' => $_FILES['file']['error']]);
}

exit;

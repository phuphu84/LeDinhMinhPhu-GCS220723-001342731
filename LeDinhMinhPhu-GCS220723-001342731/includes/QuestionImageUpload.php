<?php
/**
 * Process question image uploads with validation and deduplication.
 * Returns the image file name to store or null on failure/no upload.
 */
function processQuestionImageUpload(?array $file, array &$errors, ?string $fallback = null): ?string
{
    if ($file === null || !isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return $fallback;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Unable to upload the selected image.';
        return $fallback;
    }

    $maxSize = 2 * 1024 * 1024; // 2MB
    if (($file['size'] ?? 0) > $maxSize) {
        $errors[] = 'Image must be 2MB or smaller.';
        return $fallback;
    }

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($mimeType, $allowedTypes, true)) {
        $errors[] = 'Unsupported image type. Please upload JPG, PNG, GIF, or WEBP.';
        return $fallback;
    }

    $uploadDir = __DIR__ . '/../assets/images/';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
        $errors[] = 'Image storage directory is unavailable.';
        return $fallback;
    }

    $fileHash = @hash_file('sha256', $file['tmp_name']);
    if ($fileHash === false) {
        $errors[] = 'Unable to process the uploaded image.';
        return $fallback;
    }

    $storedFiles = @scandir($uploadDir);
    if ($storedFiles === false) {
        $errors[] = 'Unable to read the image storage directory.';
        return $fallback;
    }

    // Reuse an existing image when the uploaded file is identical to keep storage clean.
    foreach ($storedFiles as $existingFile) {
        if ($existingFile === '.' || $existingFile === '..') {
            continue;
        }

        $existingPath = $uploadDir . $existingFile;
        if (!is_file($existingPath)) {
            continue;
        }

        $existingHash = @hash_file('sha256', $existingPath);
        if ($existingHash !== false && hash_equals($existingHash, $fileHash)) {
            return $existingFile;
        }
    }

    $originalName = basename($file['name'] ?? 'image');
    $baseName = pathinfo($originalName, PATHINFO_FILENAME);
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);

    $safeBase = preg_replace('/[^a-zA-Z0-9_-]+/', '_', $baseName);
    if ($safeBase === '') {
        $safeBase = 'image';
    }

    $safeExtension = preg_replace('/[^a-zA-Z0-9]+/', '', $extension);
    if ($safeExtension === '') {
        $safeExtension = 'jpg';
    }

    $fileName = $safeBase . '.' . $safeExtension;
    $destination = $uploadDir . $fileName;
    $counter = 1;

    while (file_exists($destination)) {
        $fileName = $safeBase . '_' . $counter . '.' . $safeExtension;
        $destination = $uploadDir . $fileName;
        $counter++;
    }

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        $errors[] = 'Failed to save the uploaded image.';
        return $fallback;
    }

    return $fileName;
}

<?php
require 'vendor/autoload.php'; // For Azure SDK

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;

// Azure Blob Storage connection string
$connectionString = "DefaultEndpointsProtocol=https;AccountName=storingfiles;AccountKey=FVDiDa/6VUYjGmTOql9+kw/oukKQFrlzx7MPQ9aSUgq4+n0THl+NAiMETW/FvK7KX7iA82paZ5Cr+ASt4qor8A==;EndpointSuffix=core.windows.net";

// Create blob client
$blobClient = BlobRestProxy::createBlobService($connectionString);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf_file'])) {
    $file = $_FILES['pdf_file'];
    
    // Check if file was uploaded without errors
    if ($file['error'] === UPLOAD_ERR_OK) {
        $containerName = "files-storing";
        $blobName = basename($file['name']); // Sanitize the filename
        $content = fopen($file['tmp_name'], 'r');
        
        try {
            // Create the container if it doesn't exist
            try {
                $blobClient->createContainer($containerName);
            } catch (ServiceException $e) {
                if ($e->getCode() !== 409) { // Ignore conflict error if container already exists
                    echo "Error creating container: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
                    exit;
                }
            }

            // Upload the file to Azure Blob Storage
            $blobClient->createBlockBlob($containerName, $blobName, $content);
            echo "File uploaded successfully.";
        } catch (ServiceException $e) {
            echo "Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
        } finally {
            fclose($content); // Ensure the file stream is closed
        }
    } else {
        echo "Error: " . htmlspecialchars($file['error'], ENT_QUOTES, 'UTF-8');
    }
}
?>

<form action="upload_pdf.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="pdf_file" />
    <input type="submit" value="Upload PDF" />
</form>

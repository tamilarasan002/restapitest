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
    $containerName = "pdf-container";
    $blobName = $file['name'];
    $content = fopen($file['tmp_name'], 'r');

    try {
        $blobClient->createBlockBlob($containerName, $blobName, $content);
        echo "File uploaded successfully.";
    } catch(ServiceException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<form action="upload_pdf.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="pdf_file" />
    <input type="submit" value="Upload PDF" />
</form>


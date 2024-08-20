<?php
require 'vendor/autoload.php'; // For Azure SDK

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;

// Azure Blob Storage connection string
$connectionString = "DefaultEndpointsProtocol=https;AccountName=storingfiles;AccountKey=FVDiDa/6VUYjGmTOql9+kw/oukKQFrlzx7MPQ9aSUgq4+n0THl+NAiMETW/FvK7KX7iA82paZ5Cr+ASt4qor8A==;EndpointSuffix=core.windows.net";

// Create blob client
$blobClient = BlobRestProxy::createBlobService($connectionString);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedPdf = $_POST['pdf_file'];

    // Here you can store the selected PDF name in the database
    // or perform other actions. For example, you could upload it back to blob storage if needed.

    echo "Selected PDF: " . htmlspecialchars($selectedPdf);
}
?>

<?php
require 'vendor/autoload.php'; // For Azure SDK

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;

$connectionString = "DefaultEndpointsProtocol=https;AccountName=storingfiles;AccountKey=FVDiDa/6VUYjGmTOql9+kw/oukKQFrlzx7MPQ9aSUgq4+n0THl+NAiMETW/FvK7KX7iA82paZ5Cr+ASt4qor8A==;EndpointSuffix=core.windows.net";

$blobClient = BlobRestProxy::createBlobService($connectionString);

try {
    $containerName = "files-storing";
    $listBlobs = $blobClient->listBlobs($containerName);
    $blobs = $listBlobs->getBlobs();

    echo "<form action='select_pdf.php' method='POST'>";
    echo "<select name='pdf_file'>";

    foreach ($blobs as $blob) {
        $blobName = htmlspecialchars($blob->getName(), ENT_QUOTES, 'UTF-8');
        echo "<option value='$blobName'>$blobName</option>";
    }

    echo "</select>";
    echo "<input type='submit' value='Select PDF'>";
    echo "</form>";
} catch(ServiceException $e) {
    echo "Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}
?>

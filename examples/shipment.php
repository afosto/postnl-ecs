<?php

/**
 * This example the retrieval of (multiple) stocklists
 */

use Afosto\Ecs\Components\App;
use Afosto\Ecs\Updates\Shipment;

//Init with config parameters (see examples/config.php)
App::init($config);

$shipmentUpdates = new Shipment();

foreach ($shipmentUpdates->getUpdates() as $model) {
    //Do something with the message
    $message = [
        'trackTrace'  => $model->trackAndTraceCode,
        'orderNumber' => $model->orderNumber,
    ];
}

//Mark the processed messages as read (they will be deleted)
$shipmentUpdates->markAsRead();

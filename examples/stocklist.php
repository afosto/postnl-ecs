<?php

/**
 * This example the retrieval of shipment updates
 */

use Afosto\Ecs\Components\App;
use Afosto\Ecs\Updates\StockList;

//Init with config parameters (see examples/config.php)
App::init($config);

$stockListUpdates = new StockList();

foreach ($stockListUpdates->getUpdates() as $model) {
    //Do something with the message
    $message = [
        'sku'   => $model->sku, //The product reference
        'count' => $model->count, //The new count
    ];
}

//Mark the processed messages as read (they will be deleted)
$stockListUpdates->markAsRead();

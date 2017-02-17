<?php

/**
 * This example shows the creation of a new product at ECS
 */

use Afosto\Ecs\Components\App;
use Afosto\Ecs\Messages\Product as ProductMessage;
use Afosto\Ecs\Models\Product;
use Afosto\Ecs\Helpers\MessageHelper;

//Init with config parameters (see examples/config.php)
App::init($config);

$product = new Product();
$product->sku = 'ART-1-TEST';
$product->shortDescription = 'Test article';

//Make sure we use a valid EAN13
$product->ean = '1000000000016';

//Insert fictional parameters
$product->height = $product->weight = $product->depth = $product->width = 1;

//Send the message, insert a message number
$message = new ProductMessage(1);
$message->addMessagePart($product);

$message->send();
//Or for debugging show
$message->show();
//or download the XML
$message->download();
//or list the current waiting messages
$messages = MessageHelper::listMessages(ProductMessage::getDirectory());
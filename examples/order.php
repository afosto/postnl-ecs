<?php
/**
 * This example shows the creation of a new order at ECS
 */

use Afosto\Ecs\Models\Address;
use Afosto\Ecs\Models\Customer;
use Afosto\Ecs\Models\Order;
use Afosto\Ecs\Messages\Order as OrderMessage;
use Afosto\Ecs\Components\App;
use Afosto\Ecs\Helpers\MessageHelper;
use Afosto\Ecs\Helpers\ShipmentMethodHelper;

//Init with config parameters (see examples/config.php)
App::init($config);

$address = new Address();
$address->city = 'Groningen';
$address->countryCode = 'NL';
$address->houseNumber = 16;
$address->houseNumberSuffix = 'a';
$address->postalCode = '9731DG';
$address->street = 'Grondzijl';

$customer = new Customer();
$customer->company = 'Afosto SaaS BV';
$customer->email = 'peter@afosto.com';
$customer->firstName = 'Peter';
$customer->lastName = 'Bakker';
$customer->title = 'Mr';
$customer->phoneNumber = '0507119519';

$order = new Order();
$order->setCustomer($customer);
$order->setAddresses($address);

//Fictional order number
$order->orderNumber = time();
$order->setDateTime(new DateTime());
$order->setShipmentOptions(ShipmentMethodHelper::METHOD_STANDARD);
$order->insertOrderLine('SP-POV-DIV-TSA', 2);
$order->insertOrderLine('SP-POV-POL-19', 1);

//Build the message, insert a message number
$message = new OrderMessage(1);
$message->addMessagePart($order);

//To send multiple orders in 1 one message repeat the previous line or insert as array:
$order2 = clone $order;
$order2->orderNumber += 1;

$order3 = clone $order2;
$order3->orderNumber += 1;

$message->addMessagePart([$order2, $order3]);

//Send the message
$message->send();

//Or for debugging show
$message->show();
//or download the XML
$message->download();
//or list the current waiting messages
$messages = MessageHelper::listMessages(OrderMessage::getDirectory());
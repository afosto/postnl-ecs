# PostNL ECS

Use this client to convieniently interact with PostNL ECS . This PHP package was developed by Afosto to make a reliable connection between Afosto (Retail Software) and PostNL ECS and provides the following functionality:

- send product information to ECS
- send order information to ECS
- receive stock information (mutations or full list)
- receive shipment updates (track&trace codes)

## Getting Started

Simply follow the installation instructions. You will need an account at PostNL ECS that is set up for you to use.

### Prerequisites

What things you need to install the software and how to install them
- PHP5.5+
- Composer (for installation)

### Installing

Installing is easy through [Composer](http://www.getcomposer.org/). 

```
composer require afosto/ecs
```


## Examples

Now, to insert a product at ECS, use the following code.

First set some configuration parameters:

```php
$config = [
    'host'       => 'sftp-postnlint-accp.xs4.mendix.net',
    'port'       => 22,
    'username'   => '',
    'privateKey' => '',
    'root'       => '/home/{username}/' 
];
```

Initialze the application with the configuration

```php
App::init($config);
```

### Send a product

Build the product object

```php
$product = new Product();
$product->sku = 'ART-1-TEST';
$product->shortDescription = 'Test article';
$product->ean = '1000000000016';
$product->height = $product->weight = $product->depth = $product->width = 1;
```
Make a message container and insert a message number (in this case 1) and add the product and send the message:
```php
$message = new ProductMessage(1);
```
Insert a single product or add multiple:
```php
$message->addMessagePart($product);
$message->addMessagePart([$product2, $product3]);
```
Now send the message:
```php
$message->send();
```
For debugging you can also download or show the XML file:
```php
$message->show();
$message->download();
```
Now the product should be available in ECS. 


### Stock updates
To parse a batch of stock messages, use the following code.

```php
$stockListUpdates = new StockList();
```
Load the messages (XML files from the SFTP server):
```php
foreach ($stockListUpdates->getUpdates() as $model) {
    //Do something with the message
    $message = [
        'sku'   => $model->sku, 
        'count' => $model->count,
    ];
}
```

Mark the processed messages as read (they will be deleted):
```php
$stockListUpdates->markAsRead();
```

### Other examples
In the examples directory you will find more examples of this project.


## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/afosto/dnl/tags). 


## License

This project is licensed under the Apache License 2.0 - see the [LICENSE.md](LICENSE.md) file for details
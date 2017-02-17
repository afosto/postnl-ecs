# PostNL ECS

Use this client to convieniently interact with PostNL ECS . This PHP package was developed by Afosto to make a reliable connection between Afosto (Retail Software) and PostNL ECS and provides all the basic functionality.

## Getting Started

Simply follow the installation instructions. You will need an account at PostNL ECS that is set up for you to use.

### Prerequisites

What things you need to install the software and how to install them
- PHP5.5+
- Composer (for installation)

### Installing

Installing is easy through [Composer](http://www.getcomposer.org/). 

```
composer require afosto/fp
```

##Examples

Now, to insert a product at ECS, use the following code.

First set some configuration

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
//Init with config parameters
App::init($config);
```

###Send a product

Build the product object

```php
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

```
Now we are ready to send, show (output in browser) or download the message (XML file)
```php
$message->send();
//Or for debugging show
$message->show();
//or download the XML
$message->download();
```

Now the product should be available in ECS. 

###Stock updates
To parse a batch of stock messages, use the following code.

```php
$stockListUpdates = new StockList();

foreach ($stockListUpdates->models as $model) {
    //Do something with the message
    $message = [
        'sku'   => $model->sku, 
        'count' => $model->count,
    ];
}

//Mark the processed messages as read (they will be deleted)
$stockListUpdates->markAsRead();

```

###Other examples
In the examples directory you will find more examples of this project.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/afosto/dnl/tags). 

## License

This project is licensed under the Apache License 2.0 - see the [LICENSE.md](LICENSE.md) file for details
# momo-php-sdk
Handles MTN momo transactions in Cameroon

## Installation
``
composer require bnlambert/momo-php-sdk:dev-master
``

## Implementation
### Receive payment online
To receive payment online, call the ``checkOut`` method. This method requires two parameter i.e. the client's number and the amount the client needs to pay.
``$mtn->checkOut(string $phone, int $amount);``

#### Example 
````
<?php

require 'vendor/autoload.php';

use BNLambert\Momo\Mtn;

$params = [
  'merchant_email' => 'merchant@domain.com'
];

$mtn = new Mtn($params);

$res = $mtn->checkOut('67000000', 500);

var_dump($res);

?>
````


## License
MIT
<?php
declare(strict_types=1);

namespace BNLambert\Momo;

use BNLambert\Momo\Interfaces\TransactionsInterface;
use GuzzleHttp\Client;
use BNLambert\Momo\Helpers\Config;


/**
*  A sample class
*
*  Use this section to define what this class is doing, the PHPDocumentator will use this
*  to automatically generate an API documentation using this information.
*
*  @author yourname
*/
class Mtn implements TransactionsInterface {
    protected $config;

    public function __construct($params)
    {
        $this->config = new Config($params);
    }

    public function checkOut($tel, int $amount)
    {
        $params = [
            'query' => [
                '_tel' => $tel,
                '_clP' => $this->config->_clP,
                '_email' => $this->config->_email,
                '_amount' => $amount,
                'idbouton'=> $this->config->idbouton,
                'typebouton' => $this->config->typebouton
            ]
        ];

        // for now
        $client = new Client(['verify' => $this->config->verify]);

        try {
            $response = $client->request('GET', $this->config->requestUrl, $params);
            return json_encode($response->getBody()->getContents(), 128);
        }catch(\Exception $e) {
            return json_encode($e->getResponse()->getBody()->getContents(), 128);
        }

    }

}
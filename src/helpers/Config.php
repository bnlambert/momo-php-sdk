<?php
/**
 * Created by IntelliJ IDEA.
 * User: HP
 * Date: 12/22/2019
 * Time: 9:58 PM
 */

namespace BNLambert\Momo\helpers;


class Config
{
    public $url = 'https://developer.mtn.cm/OnlineMomoWeb/faces/transaction/transaction.xhtml';
    public $requestUrl = 'https://developer.mtn.cm/OnlineMomoWeb/faces/transaction/transactionRequest.xhtml';
    public $idbouton = 2;
    public $typebouton = 'PAIE';
    public $_clP = '';
    public $_email = null;
    public $verify = false;

    public function __construct($params)
    {
        $this->_email = $params['merchant_email'] ?? null;
        $this->verify = $params['verify_ssl'] ?? false;
    }

}
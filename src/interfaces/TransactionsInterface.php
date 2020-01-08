<?php
namespace BNLambert\Momo\Interfaces;
/**
 * Created by IntelliJ IDEA.
 * User: HP
 * Date: 12/22/2019
 * Time: 9:04 PM
 */

interface TransactionsInterface {
    public function checkOut($tel, $amount);
}
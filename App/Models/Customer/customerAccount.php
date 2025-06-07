<?php

namespace App\Models\Customer;

use Core\Crud\Insert;
use Core\Crud\Select;

class CustomerAccount extends Select
{
    protected $table = 'customer';

    public function loginOrRegister($mobileNumber)
    {
        if (null === $customer = $this->select()->where('mobileNumber', $mobileNumber)->first()) {
            $this->signup($mobileNumber);
            return $this->loginOrRegister($mobileNumber);
        }
        return $customer;
    }

    public function signup($mobileNumber)
    {
        return Insert::getInstance()->__invoke($this->table)->insert(['mobileNumber' => $mobileNumber])->getResult();
    }
}

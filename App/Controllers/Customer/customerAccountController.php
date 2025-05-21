<?php

namespace App\Controllers\Customer;

use App\Models\Customer\CustomerAccount;
use Core\Controller;
use Core\Helper;
use Exception;

class CustomerAccountController extends Controller
{
    public function check(CustomerAccount $customerAccount)
    {
        try {
            if ($customer = $customerAccount->loginOrRegister(Helper::token()->getToken()->mobileNumber))
                Helper::token()->generate([
                    'userId' => $customer->customerId,
                    'roleId' => $customer->roleId,
                    'temporaryCode' => null,
                    'csrf' => null,
                    'mobileNumber' => null
                ]);
            Helper::response('customer')->redirect();
        } catch (Exception $exception) {
            Helper::showMessageOrRedirect($exception->getMessage(), $exception->getCode());
        }
    }
}

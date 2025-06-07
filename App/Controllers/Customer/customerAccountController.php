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
            if ($mobileNumber = Helper::token()->getToken()->mobileNumber ?? null) {
                if ($customer = $customerAccount->loginOrRegister($mobileNumber))
                    Helper::token()->generate(['iat' => .1, 'userId' => $customer->customerId, 'roleId' => $customer->roleId, 'temporaryCode' => null, 'csrf' => null, 'mobileNumber' => null]);
                Helper::response('customer')->redirect();
            }
            Helper::invalidRequest();
        } catch (Exception $exception) {
            Helper::showMessageOrRedirect($exception->getMessage(), $exception->getCode());
        }
    }
}

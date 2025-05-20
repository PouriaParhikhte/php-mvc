<?php

namespace App\Controllers\Customer;

use App\Models\Customer\CustomerAccount;
use Core\Controller;
use Exception;

class CustomerAccountController extends Controller
{
    public function check(CustomerAccount $customerAccount)
    {
        try {
            if ($customer = $customerAccount->loginOrRegister($this->token()->getToken()->mobileNumber))
                $this->token()->createToken(['userId' => $customer->customerId, 'roleId' => $customer->roleId, 'temporaryCode' => null, 'csrf' => null, 'mobileNumber' => null]);
            $this->response('customer')->redirect();
        } catch (Exception $exception) {
            $this->showMessageOrRedirect($exception->getMessage(), $exception->getCode());
        }
    }
}

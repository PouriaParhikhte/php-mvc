<?php

namespace App\Controllers\Customer;

use Core\Controller;

class CustomerLogoutController extends Controller
{
    public function logout()
    {
        $this->token()->createToken(['userId' => null, 'roleId' => null])->redirectTo(SETTINGS->HOMEPAGEURL);
    }
}

<?php

namespace App\Controllers\Customer;

use Core\Controller;
use Core\Helper;

class CustomerLogoutController extends Controller
{
    public function logout()
    {
        Helper::token()->generate(['iat' => .1, 'userId' => null, 'roleId' => null])->redirectTo(SETTINGS->HOMEPAGEURL);
    }
}

<?php

namespace App\Controllers\Customer;

use Core\Controller;
use Core\Helper;

class CustomerLogoutController extends Controller
{
    public function logout()
    {
        Helper::token()->generate(['userId' => null, 'roleId' => null])->redirectTo(SETTINGS->HOMEPAGEURL);
    }
}

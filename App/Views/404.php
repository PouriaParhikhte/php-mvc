<?php

use Core\Helpers\Token\Token;

header("$_SERVER[SERVER_PROTOCOL] 404", true, 404);
http_response_code(404);
Token::getInstance()->generate(['responseCode' => 404]);
exit('<center><h1>404</h1><h2>Not Found!</h2></center>');

<?php

namespace App;

use Illuminate\Support\Facades\Facade;

class TokenFacade extends Facade {

    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return Token::class;
    }
}
<?php

namespace App\Models\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/12/03
 * Time: 上午 11:02
 */
class Signer extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Signer';
    }
}

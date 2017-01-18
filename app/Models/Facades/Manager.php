<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/01/18
 * Time: 下午 06:22
 */

namespace App\Models\Facades;

use Illuminate\Support\Facades\Facade;

class Manager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Manager';
    }
}
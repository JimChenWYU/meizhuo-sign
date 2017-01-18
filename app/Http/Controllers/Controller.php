<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var array
     */
    protected $accept_data = [];

    /**
     * Controller constructor.
     */
    public function __construct(Request $request)
    {
        $this->accept_data = $request->all();
    }


    /**
     * function : ajax
     * description :
     * @param array $data
     * @param int $status
     * @param array $headers
     * @param int $options
     * @return mixed
     */
    protected function ajax(
        $data = [], $status = 200, array $headers = [], $options = 0)
    {
        return response()->json($data, $status, $headers, $options);
    }
}

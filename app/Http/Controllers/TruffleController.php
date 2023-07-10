<?php

namespace App\Http\Controllers;

use App\Http\Requests\Truffle\PostRegisterTruffle;
use App\Services\TruffleService;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;

class TruffleController extends BaseController
{
    use DispatchesJobs;

    /** @var TruffleService */
    private $service;

    /**
     * TruffleController constructor.
     * @param TruffleService $service
     */
    public function __construct(TruffleService $service)
    {
        $this->service = $service;
    }

    /**
     * @param PostRegisterTruffle $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerTruffle(PostRegisterTruffle $request)
    {
        $data = [];
        $data['weight'] = $request->weight;
        $data['price'] = $request->price;
        $truffle = $this->service->saveTruffle($data);

        return response()->json(['status' => 'success', 'data' => $truffle]);
    }
}

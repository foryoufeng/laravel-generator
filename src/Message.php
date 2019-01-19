<?php
/**
 * Created by PhpStorm.
 * User: wuqiang
 * Date: 12/27/18
 * Time: 3:56 PM
 */

namespace Foryoufeng\Generator;


/**
 * Trait Message
 * @package Foryoufeng\Generator
 */
trait Message
{

    /**
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data)
    {
        return response()->json(['message' => 'success', 'errcode' => 0, 'data' => $data]);
    }

    /**
     *
     * @param $message
     * @param int $errcode
     * @return \Illuminate\Http\JsonResponse
     */
    public function error($message, $errcode=1)
    {
        return response()->json(['message' => $message, 'errcode' => $errcode, 'data' => []]);
    }
}
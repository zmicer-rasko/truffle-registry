<?php

namespace App\Validation;

class CreateTruffle
{
    /**
     * @return string[]
     */
    public static function getRules()
    {
        return [
            'sku'    => 'string|unique:truffles',
            'weight' => 'required|integer|min:1',
            'price'  => 'required|numeric|min:1',
        ];
    }
}
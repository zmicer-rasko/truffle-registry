<?php

namespace App\DTO;

use App\Models\Truffle;

class TruffleCsvDTO
{
    public $sku;
    public $weight;
    public $price;
    public $expiresAt;

    /**
     * @param Truffle $model
     * @return TruffleCsvDTO
     */
    public static function createFromModel(Truffle $model)
    {
        $dto = new self();
        $dto->sku       = $model->sku;
        $dto->weight    = $model->weight;
        $dto->price     = $model->price;
        $dto->expiresAt = $model->expires_at;

        return $dto;
    }

    /**
     * @param array $csvRow
     * @return TruffleCsvDTO
     */
    public static function createFromCsvRow(array $csvRow)
    {
        $dto = new self();

        $dto->sku       = $csvRow[0];
        $dto->weight    = $csvRow[1];
        $dto->price     = $csvRow[2];
        $dto->expiresAt = $csvRow[3];

        return $dto;
    }

    /**
     * @return string
     */
    public function getCsvRow()
    {
        return implode(',', [
            $this->sku,
            $this->weight,
            $this->price,
            $this->expiresAt,
        ]);
    }

    /**
     * @return array
     */
    public function getArray()
    {
        return [
            'sku'        => $this->sku,
            'weight'     => $this->weight,
            'price'      => $this->price,
            'expires_at' => $this->expiresAt,
        ];
    }
}
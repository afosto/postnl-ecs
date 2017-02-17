<?php

namespace Afosto\Ecs\Models;

use Afosto\Ecs\Components\Model;

/**
 * Class Stock
 * @package Afosto\Ecs\Models
 *
 * @property string  $sku
 * @property integer $count
 */
class Stock extends Model {

    /**
     * Required, the model rules
     *
     * @return mixed
     */
    public function getRules() {
        return [
            ['sku', 'string', true],
            ['count', 'string', true, 'formatStock'],
        ];
    }

    /**
     * Format the stock accordingly
     */
    public function formatStock() {
        $this->count = (int)$this->count;
    }

    /**
     * Map to readable keys
     *
     * @return array
     */
    protected function getMap() {
        return [
            'SKU'               => 'sku',
            'stockdtl_itemnum'  => 'sku',
            'itemNo'            => 'sku',
            'currentStockLevel' => 'count',
            'stockdtl_fysstock' => 'count',
            'quantity'          => 'count',
        ];
    }

}
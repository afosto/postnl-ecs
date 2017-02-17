<?php

namespace Afosto\Ecs\Models;

use Afosto\Ecs\Components\Model;

/**
 * Class OrderLine
 * @package Afosto\Ecs\Models
 *
 * @property string  $sku
 * @property integer $quantity
 */
class OrderLine extends Model {
    /**
     * @return array
     */
    protected function getMap() {
        return [
            'sku' => 'itemNo',
        ];
    }

    public function getRules() {
        return [
            ['sku', 'string', true, 25],
            ['quantity', 'integer', true, 5],
        ];
    }

}
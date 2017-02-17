<?php

namespace Afosto\Ecs\Models;

use Afosto\Ecs\Components\Model;

/**
 * Class TrackTrace
 * @package Afosto\Ecs\Models
 *
 * @property string $orderNumber
 * @property string $trackAndTraceCode
 */
class TrackTrace extends Model {

    /**
     * Required, the model rules
     *
     * @return mixed
     */
    public function getRules() {
        return [
            ['orderNumber', 'string', true],
            ['trackAndTraceCode', 'string', true],
        ];
    }

    /**
     * Map to readable keys
     *
     * @return array
     */
    protected function getMap() {
        return [
            'orderNo' => 'orderNumber',
        ];
    }
}
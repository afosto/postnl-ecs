<?php

namespace Afosto\Ecs\Models;

use Afosto\Ecs\Components\Model;

/**
 * Class Address
 * @package Afosto\Ecs\Models
 *
 * @property string $street
 * @property string $houseNumber
 * @property string $postalCode
 * @property string $city
 * @property string $countryCode
 * @property string $houseNumberSuffix
 */
class Address extends Model {

    /**
     * @return array
     */
    protected function getMap() {
        return [];
    }

    public function getRules() {
        return [
            ['street', 'string', true, 35],
            ['houseNumber', 'string', true, 5],
            ['postalCode', 'string', true, 10],
            ['city', 'string', true, 30],
            ['countryCode', 'string', true, 2],
            ['houseNumberSuffix', 'string', false, 35],

        ];
    }
}
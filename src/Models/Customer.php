<?php

namespace Afosto\Ecs\Models;

use Afosto\Ecs\Components\Model;

/**
 * Class Customer
 * @package Afosto\Ecs\Models
 * @property string $firstName
 * @property string $lastName
 * @property string $company
 * @property string $phoneNumber
 * @property string $title
 * @property string $email
 */
class Customer extends Model {

    public function getMap() {
        return [
            'phoneNumber' => 'phone',
        ];
    }

    public function getRules() {
        return [
            ['firstName', 'string', false, 35],
            ['lastName', 'string', true, 35],
            ['company', 'string', false, 35],
            ['phoneNumber', 'string', false, 17],
            ['title', 'string', false, 10],
            ['email', 'string', false, 50],
        ];
    }
}
<?php

namespace Afosto\Ecs\Models;

use Afosto\Ecs\Components\Model;

/**
 * Class Product
 * @package Afosto\Ecs\Models
 *
 * @property string  $sku
 * @property string  $shortDescription
 * @property string  $description
 * @property string  $measurementUnit
 * @property integer $height
 * @property integer $width
 * @property integer $depth
 * @property integer $weight
 * @property string  $mpn
 * @property string  $ean
 * @property boolean $active
 * @property boolean $enriched
 */
class Product extends Model {

    /**
     * Map the values to the inconsistent PostNL format
     *
     * @return array
     */
    public function getMap() {
        return [
            'sku'              => 'itemNo',
            'shortDescription' => 'description',
            'description'      => 'description2',
            'measurementUnit'  => 'unitOfMeasure',
            'mpn'              => 'vendorItemNo',
            'ean'              => 'eanNo',
        ];
    }

    /**
     * @return array
     */
    public function getAttributes() {
        return [
            'item' => parent::getAttributes(),
        ];
    }

    /**
     * Do some defaults
     *
     * @return bool
     */
    public function beforeValidate() {
        if ($this->mpn == null) {
            $this->mpn = '';
        }
        $this->measurementUnit = 'ST';
        $this->active = true;
        $this->enriched = true;

        return parent::beforeValidate();
    }

    /**
     * Models rules
     *
     * @return array
     */
    public function getRules() {
        return [
            ['sku', 'string', true, 25],
            ['shortDescription', 'string', true, 50],
            ['description', 'string', false, 50],
            ['measurementUnit', 'string', false],
            ['height', 'integer', true],
            ['width', 'integer', true],
            ['depth', 'integer', true],
            ['weight', 'integer', true],
            ['mpn', 'string', true, 25],
            ['ean', 'string', true, 15],
            ['active', 'boolean', true],
            ['enriched', 'boolean', true],

        ];
    }
}
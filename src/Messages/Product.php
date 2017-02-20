<?php

namespace Afosto\Ecs\Messages;

use Afosto\Ecs\Components\Message;
use Afosto\Ecs\Models\Product as Model;

class Product extends Message {

    /**
     * @return string
     */
    protected function getType() {
        return 'item';
    }

    /**
     * @return string
     */
    public function getDirectory() {
        return 'Productdata';
    }

    /**
     * @return string
     */
    protected function getFileName() {
        return 'ART' . parent::getFileName();
    }

    /**
     * @return array
     */
    protected function toArray() {
        $array = [];
        foreach ($this->parts as $item) {
            $array['items'][] = $item->getAttributes();
        }

        return $array;
    }

}
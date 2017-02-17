<?php

namespace Afosto\Ecs\Messages;

use Afosto\Ecs\Components\Message;
use Afosto\Ecs\Models\Product as Model;

class Product extends Message {

    /**
     * Add a part to the message
     *
     * @param $part
     *
     * @return void
     */
    public function addMessagePart($part) {
        if ($part instanceof Model) {
            $this->parts[] = $part;
        }
    }

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
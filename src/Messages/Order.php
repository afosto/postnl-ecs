<?php

namespace Afosto\Ecs\Messages;

use Afosto\Ecs\Components\Message;

class Order extends Message {

    /**
     * @return string
     */
    protected function getFileName() {
        return 'ORD' . parent::getFileName();
    }

    /**
     * Returns the name of the directory where to store the xml file into
     * @return string
     */
    public function getDirectory() {
        return 'Order';
    }

    /**
     * Returns the message type
     * @return string
     */
    protected function getType() {
        return 'deliveryOrder';
    }

    /**
     * @return mixed
     */
    protected function toArray() {
        $array = [];
        foreach ($this->parts as $order) {
            $array['deliveryOrders'][] = ['deliveryOrder' => $order->getAttributes()];
        }

        return $array;
    }
}
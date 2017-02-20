<?php
namespace Afosto\Ecs\Updates;

use Afosto\Ecs\Components\UpdateMessage;
use Afosto\Ecs\Models\TrackTrace;

/**
 * Class Shipment returns shipment updates (trackTrace for orderNumber)
 *
 * @package Afosto\Ecs\Updates
 */
class Shipment extends UpdateMessage {

    /**
     * Format the message into models
     *
     * @param $data
     *
     * @return TrackTrace[]
     */
    public function processMessage($data) {
        $models = [];
        if ($data['type'] == $this->getType()) {
            $model = new TrackTrace();
            $model->setAttributes($data[$this->getType()]);
            $model->validate();
            $models[] = $model;
        }

        return $models;
    }

    /**
     * Returns trackTrace models
     *
     * @return TrackTrace[]
     */
    public function getUpdates() {
        return parent::getUpdates();
    }

    /**
     * Returns the directory for this message
     *
     * @return string
     */
    protected function getDirectory() {
        return 'Shipment';
    }

    /**
     * Returns the message type
     * @return string
     */
    protected function getType() {
        return 'orderStatus';
    }

}
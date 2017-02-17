<?php
namespace Afosto\Ecs\Updates;

use Afosto\Ecs\Models\Stock;
use Afosto\Ecs\Components\UpdateMessage;

/**
 * Class StockList returns all the stock
 *
 * @package Afosto\Ecs\Updates
 * @property Stock[] $models
 */
class StockList extends UpdateMessage {

    /**
     * Format the message into models
     *
     * @param $data
     *
     * @return Stock[]
     */
    public function processMessage($data) {
        $models = [];
        foreach ($data[$this->getType()] as $stockData) {
            $model = new Stock();
            $model->setAttributes($stockData);
            $model->validate();
            $models[] = $model;
        }

        return $models;
    }

    /**
     * Return the directory name
     * @return string
     */
    protected function getDirectory() {
        return 'Stockcount';
    }

    /**
     * Returns the message type
     * @return string
     */
    protected function getType() {
        return 'Stockupdate';
    }

}
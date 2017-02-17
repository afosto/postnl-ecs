<?php

namespace Afosto\Ecs\Updates;

use Afosto\Ecs\Models\Stock;
use Afosto\Ecs\Components\UpdateMessage;

/**
 * Class StockMutation returns a list of mutations
 *
 * @package Afosto\Ecs\Updates
 * @property \Afosto\Ecs\Models\Stock[] $models
 */
class StockMutation extends UpdateMessage {

    /**
     * Format the message into models
     *
     * @param $data
     *
     * @return Stock[]
     */
    public function processMessage($data) {
        $models = [];

        foreach ($data['stockMutations'] as $stockData) {
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
        return 'Stockmutation';
    }

    /**
     * Returns the message type
     * @return string
     */
    protected function getType() {
        return 'Stockupdate';
    }

}
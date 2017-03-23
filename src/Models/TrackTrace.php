<?php

namespace Afosto\Ecs\Models;

use Afosto\Ecs\Components\Model;

/**
 * Class TrackTrace
 *
 * Track&trace can be null: shipment fits in the mailbox
 *
 * @package Afosto\Ecs\Models
 *
 * @property string $orderNumber
 * @property string $trackAndTraceCode
 */
class TrackTrace extends Model {

    /**
     * Contains the trackTrace (multiple in case of comma-separated values)
     * @var array
     */
    private $_codes = [];

    /**
     * Required, the model rules
     *
     * @return mixed
     */
    public function getRules() {
        return [
            ['orderNumber', 'string', true],
            ['trackAndTraceCode', 'string', false, 'formatTrackTrace'],
        ];
    }

    /**
     * Map incomming data
     *
     * @param array $data
     */
    public function setAttributes($data) {
        foreach ($data as $key => $value) {
            $this->{$this->getFormattedKey($key)} = $value;
        }
    }

    /**
     * Formats the trackTrace code
     * Might contain multiple codes that are comma-separated. If so return only the first but make it possible to
     * obtain the other codes through getAllTrackTraceCodes()
     */
    public function formatTrackTrace() {
        //Handle the data in case of an empty trackTrace field
        if ((is_array($this->trackAndTraceCode) && empty($this->trackAndTraceCode)) || trim($this->trackAndTraceCode) == '') {
            $this->trackAndTraceCode = null;
        } else {
            //Handle the data in case a comma separated field data (multiple codes)
            $this->_codes = explode(';', $this->trackAndTraceCode);
            $this->trackAndTraceCode = current($this->_codes);
        }
    }

    /**
     * Returns true if multiple track&trace codes were returned
     * @return bool
     */
    public function hasMultipleCodes() {
        return (count($this->getAllTrackTraceCodes()) > 1);
    }

    /**
     * Returns all the trackTrace codes
     * @return array
     */
    public function getAllTrackTraceCodes() {
        return $this->_codes;
    }

    /**
     * Map to readable keys
     *
     * @return array
     */
    protected function getMap() {
        return [
            'orderNumber' => 'orderNo',
        ];
    }
}
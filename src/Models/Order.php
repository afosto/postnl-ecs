<?php

namespace Afosto\Ecs\Models;

use Afosto\Ecs\Components\Model;

/**
 * Class Order
 * @package Afosto\Ecs\Models
 *
 * @property string  $orderNumber
 * @property string  $webOrderNumber
 * @property string  $orderDate
 * @property string  $orderTime
 * @property boolean $onlyHomeAddress
 * @property string  $vendorNo
 * @property string  $shipToTitle
 * @property string  $shipToFirstName
 * @property string  $shipToLastName
 * @property string  $shipToCompany
 * @property string  $shipToStreet
 * @property string  $shipToHouseNumber
 * @property string  $shipToHouseNumberSuffix
 * @property string  $shipToPostalCode
 * @property string  $shipToCity
 * @property string  $shipToCountryCode
 * @property string  $shipToPhone
 * @property string  $shipToEmail
 * @property string  $invoiceToTitle
 * @property string  $invoiceToFirstName
 * @property string  $invoiceToLastName
 * @property string  $invoiceToCompany
 * @property string  $invoiceToStreet
 * @property string  $invoiceToHouseNumber
 * @property string  $invoiceToHouseNumberSuffix
 * @property string  $invoiceToPostalCode
 * @property string  $invoiceToCity
 * @property string  $invoiceToCountryCode
 * @property string  $invoiceToEmail
 * @property string  $language
 * @property string  $shippingAgentCode
 * @property string  $shipmentProductOption
 * @property string  $shipmentOption
 * @property string  $deliveryDate
 * @property string  $deliveryTime
 * @property string  $comment
 */
class Order extends Model {

    /**
     * @var string
     */
    private $_shipmentProvider;

    /**
     * @var string
     */
    private $_shipmentTiming;

    /**
     * @var OrderLine[]
     */
    private $_lines = [];

    /**
     * @var \DateTime
     */
    private $_deliveryDateTime;

    /**
     * @var \DateTime
     */
    private $_dateTime;

    /**
     * @param \DateTime $orderDate
     */
    public function setDateTime(\DateTime $orderDate) {
        $this->_dateTime = $orderDate;
    }

    /**
     *
     * @param      $method
     * @param null $timing
     */
    public function setShipmentOptions($method, $timing = null) {
        if ($this->_shipmentProvider === null) {
            $this->_shipmentProvider = $method;
            $this->_shipmentTiming = $timing;
        }
    }

    /**
     * @param Address      $shipmentAddress
     * @param Address|null $invoiceAddress
     */
    public function setAddresses(Address $shipmentAddress, Address $invoiceAddress = null) {
        $this->_setProperties($shipmentAddress, 'shipTo');
        $this->_setProperties((($invoiceAddress === null) ? $shipmentAddress : $invoiceAddress), 'invoiceTo');
    }

    /**
     * @param Customer $customer
     */
    public function setCustomer(Customer $customer) {
        $this->_setProperties($customer, 'shipTo');
        $this->_setProperties($customer, 'invoiceTo');
    }

    /**
     * Set desired delivery date (optional)
     *
     * @param \DateTime $dateTime
     */
    public function setDeliveryDateTime(\DateTime $dateTime) {
        $this->_deliveryDateTime = $dateTime;
    }

    /**
     * Update the model to ship to a pickup point
     *
     * @param PickupPoint $pickupPoint
     */
    public function setPickupPoint(PickupPoint $pickupPoint) {
        $this->_setProperties($pickupPoint, 'shipTo');
        $this->_shipmentProvider = '03533';
    }

    /**
     * @param $sku
     * @param $quantity
     */
    public function insertOrderLine($sku, $quantity) {
        $line = new OrderLine();
        $line->sku = $sku;
        $line->quantity = $quantity;
        $this->_lines[] = $line;
    }

    /**
     * Do some funky stuff in order to set some properties properly
     *
     * @return bool
     */
    public function beforeValidate() {
        $this->webOrderNumber = $this->orderNumber;
        $this->orderDate = $this->_dateTime->format('Y-m-d');
        $this->orderTime = $this->_dateTime->format('H:i:s');
        $this->onlyHomeAddress = false;
        $this->vendorNo = '';
        $this->shippingAgentCode = $this->_shipmentProvider;
        $this->language = 'NL';

        if ($this->_deliveryDateTime !== null) {
            $this->deliveryDate = $this->_deliveryDateTime->format('Y-m-d');
            $this->deliveryTime = $this->_deliveryDateTime->format('H:i:s');
        }

        if ($this->_shipmentTiming !== null) {
            list($this->shipmentProductOption, $this->shipmentOption) = explode('|', $this->_shipmentTiming);
        }

        return parent::beforeValidate();
    }

    /**
     * @return array
     */
    public function getAttributes() {
        $array = [
            parent::getAttributes(),
        ];

        foreach ($this->_lines as $line) {
            $array['deliveryOrderLines'][] = ['deliveryOrderLine' => $line->getAttributes()];
        }

        return $array;
    }

    /**
     * Return mapping
     */
    protected function getMap() {
        return [
            'orderNumber'                => 'orderNo',
            'webOrderNumber'             => 'webOrderNo',
            'deliveryDate'               => 'requestedDeliveryDate',
            'deliveryTime'               => 'requestedDeliveryTime',
            'shipToCompany'              => 'shipToCompanyName',
            'shipToHouseNumber'          => 'shipToHouseNo',
            'shipToHouseNumberSuffix'    => 'shipToAnnex',
            'invoiceToCompany'           => 'invoiceToCompanyName',
            'invoiceToHouseNumber'       => 'invoiceToHouseNo',
            'invoiceToHouseNumberSuffix' => 'invoiceToAnnex',
        ];
    }

    /**
     * Return rules
     * @return array
     */
    public function getRules() {
        return [
            ['orderNumber', 'string', true, 10],
            ['webOrderNumber', 'string', true, 10],
            ['orderDate', 'string', true],
            ['orderTime', 'string', true],
            ['onlyHomeAddress', 'boolean', true],
            ['vendorNo', 'string', false],
            ['shipToTitle', 'string', true],
            ['shipToFirstName', 'string', true],
            ['shipToLastName', 'string', true],
            ['shipToCompany', 'string', true],
            ['shipToStreet', 'string', true],
            ['shipToHouseNumber', 'string', true],
            ['shipToHouseNumberSuffix', 'string', false],
            ['shipToPostalCode', 'string', true],
            ['shipToCity', 'string', true],
            ['shipToCountryCode', 'string', true],
            ['shipToPhone', 'string', true],
            ['shipToEmail', 'string', false],
            ['invoiceToTitle', 'string', true],
            ['invoiceToFirstName', 'string', true],
            ['invoiceToLastName', 'string', true],
            ['invoiceToCompany', 'string', true],
            ['invoiceToStreet', 'string', true],
            ['invoiceToHouseNumber', 'string', true],
            ['invoiceToHouseNumberSuffix', 'string', false],
            ['invoiceToPostalCode', 'string', true],
            ['invoiceToCity', 'string', true],
            ['invoiceToCountryCode', 'string', true],
            ['invoiceToEmail', 'string', false],
            ['language', 'string', true, 2],
            ['shippingAgentCode', 'string', true],
            ['shipmentProductOption', 'string', false],
            ['shipmentOption', 'string', false],
            ['deliveryDate', 'string', false],
            ['deliveryTime', 'string', false],
            ['comment', 'string', false, 255],
        ];
    }

    /**
     * Set the properties for shipTo or invoiceTo
     * @param        $address
     * @param string $prefix
     */
    private function _setProperties(Model $address, $prefix = 'shipTo') {
        foreach ($address->getAttributes() as $key => $value) {
            $newKey = $prefix . ucfirst($key);
            $this->$newKey = $value;
        }
    }
}
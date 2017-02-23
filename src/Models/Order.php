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
     * @var Customer
     */
    private $_customer;

    /**
     * @var Address
     */
    private $_invoiceAddress;

    /**
     * @var Address
     */
    private $_shipmentAddress;

    /**
     * @var PickupPoint
     */
    private $_pickupPoint;

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
     * Define shipping options
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
     * Insert shipping and optional billing address
     * Will handle all the parameters to be set in the message
     *
     * @param Address      $shipmentAddress
     * @param Address|null $invoiceAddress
     */
    public function setAddresses(Address $shipmentAddress, Address $invoiceAddress = null) {
        $this->_shipmentAddress = $shipmentAddress;
        $this->_invoiceAddress = (($invoiceAddress === null) ? $shipmentAddress : $invoiceAddress);
    }

    /**
     * Shortcut to set the customer information into shipping and billing information
     *
     * @param Customer $customer
     */
    public function setCustomer(Customer $customer) {
        $this->_customer = $customer;
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
        $this->_pickupPoint = $pickupPoint;
    }

    /**
     * Add an orderline
     *
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
     * Prepare the model
     *
     * @return bool
     */
    public function beforeValidate() {
        $this->webOrderNumber = $this->orderNumber;
        $this->orderDate = $this->_dateTime->format('Y-m-d');
        $this->orderTime = $this->_dateTime->format('H:i:s');
        $this->onlyHomeAddress = false;
        $this->vendorNo = '';
        $this->language = 'NL';

        if ($this->_deliveryDateTime !== null) {
            $this->deliveryDate = $this->_deliveryDateTime->format('Y-m-d');
            $this->deliveryTime = $this->_deliveryDateTime->format('H:i:s');
        }

        //Set shipping and billing information based on customer and address data
        $this->_setProperties($this->_shipmentAddress, 'shipTo');
        $this->_setProperties($this->_invoiceAddress, 'invoiceTo');
        $this->_setProperties($this->_customer, 'shipTo');
        $this->_setProperties($this->_customer, 'invoiceTo');

        //Override some fields in case of a pickup point
        if ($this->_pickupPoint !== null) {
            $this->_setProperties($this->_pickupPoint, 'shipTo');
            $this->shippingAgentCode = '03533';
        } else {
            $this->shippingAgentCode = $this->_shipmentProvider;
        }

        if ($this->_shipmentTiming !== null) {
            list($this->shipmentProductOption, $this->shipmentOption) = explode('|', $this->_shipmentTiming);
        }

        /**
         * Make sure we have a phoneNumber placeholder
         */
        if ($this->shipToPhone === null) {
            $this->shipToPhone = 0;
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

        //Format the order lines
        foreach ($this->_lines as $line) {
            $array['deliveryOrderLines'][] = ['deliveryOrderLine' => $line->getAttributes()];
        }

        return $array;
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
     * Set the properties for shipTo or invoiceTo
     *
     * @param        $model
     * @param string $prefix
     */
    private function _setProperties(Model $model, $prefix = 'shipTo') {
        foreach ($model->getAttributes() as $key => $value) {
            $this->{$prefix . ucfirst($key)} = $value;
        }
    }
}
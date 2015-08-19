<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Mapper;

use jtl\Connector\Core\Utilities\Language;

class CustomerOrder extends BaseMapper
{
    protected $pull = [
        'id' => 'order_id',
        'customerId' => 'customer_id',
        'billingAddress' => 'CustomerOrderBillingAddress',
        'creationDate' => 'date_added',
        'currencyIso' => 'currency_code',
        'languageISO' => null,
        'note' => 'comment',
        'shippingAddress' => 'CustomerOrderShippingAddress',
        'shippingInfo' => 'shipping_custom_field',
        // Shipping: const in Custom Order
        //'status' => 'string',
        'totalSum' => 'total',
        // TODO: Error
        //'items' => 'CustomerOrderItem',
        // Flat Shipping Rate ?
        //'carrierName' => 'string',
        // History ?
        //'paymentDate' => 'DateTime',
        // See PaymentTypes ?
        //'paymentModuleCode' => 'string',
        //'paymentStatus' => 'string',
        //'shippingDate' => 'DateTime',
        //'shippingMethodName' => 'string',
    ];

    protected $push = [

    ];

    protected function languageISO($data)
    {
        return Language::convert($data['code']);
    }
}
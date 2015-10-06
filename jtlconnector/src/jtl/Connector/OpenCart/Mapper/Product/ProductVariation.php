<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper\Product
 */

namespace jtl\Connector\OpenCart\Mapper\Product;

use jtl\Connector\Model\ProductVariation as ProductVariationModel;
use jtl\Connector\OpenCart\Mapper\BaseMapper;

class ProductVariation extends BaseMapper
{
    protected $pull = [
        'id' => 'product_option_id',
        'productId' => 'product_id',
        'sort' => 'sort_order',
        'type' => null,
        'i18ns' => 'Product\ProductVariationI18n',
        'values' => 'Product\ProductVariationValue'
    ];

    protected $push = [
        'sort_order' => 'sort',
        'type' => null,
        'required' => null,
        'product_option_id' => null
    ];

    protected function type($data)
    {
        if ($data instanceof ProductVariationModel) {
            switch ($data->getType()) {
                case ProductVariationModel::TYPE_IMAGE_SWATCHES:
                    return 'image';
                case ProductVariationModel::TYPE_TEXTBOX:
                    return 'select';
                case ProductVariationModel::TYPE_FREE_TEXT:
                    return 'text';
                case ProductVariationModel::TYPE_FREE_TEXT_OBLIGATORY:
                    return 'text';
            }
            return $data->getType();
        } else {
            if ($data['type'] === 'select') {
                return ProductVariationModel::TYPE_SELECT;
            } elseif ($data['type'] === 'radio') {
                return ProductVariationModel::TYPE_RADIO;
            } elseif ($data['type'] === 'image') {
                return ProductVariationModel::TYPE_IMAGE_SWATCHES;
            } else {
                if ($data['required']) {
                    return ProductVariationModel::TYPE_FREE_TEXT_OBLIGATORY;
                } else {
                    return ProductVariationModel::TYPE_FREE_TEXT;
                }
            }
        }
    }

    protected function required(ProductVariationModel $data)
    {
        return $data->getType() !== ProductVariationModel::TYPE_FREE_TEXT;
    }

    protected function product_option_id()
    {
        return "";
    }
}
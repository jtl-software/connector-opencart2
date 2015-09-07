<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Linker\IdentityLinker;
use jtl\Connector\OpenCart\Controller\MainEntityController;
use jtl\Connector\OpenCart\Utility\OpenCart;

class Product extends MainEntityController
{

    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $model, $limit);
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf('
            SELECT p.*
            FROM oc_product p
            LEFT JOIN jtl_connector_link l ON p.product_id = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL
            LIMIT %d',
            IdentityLinker::TYPE_PRODUCT, $limit
        );
    }

    /**
     * @param $data \jtl\Connector\Model\Product
     */
    protected function pushData($data, $model)
    {
        $product = OpenCart::getInstance()->loadModel('catalog/product');
        $endpoint = $this->mapper->toEndpoint($data);
        if (is_null($data->getId()->getEndpoint())) {
            $id = $product->addProduct($endpoint);
            $data->getId()->setEndpoint($id);
        } else {
            $product->editProduct($data->getId()->getEndpoint(), $endpoint);
        }
        return $data;
    }

    protected function deleteData($data)
    {
        // TODO: Implement deleteData() method. Keep in mind that picture files are not deleted automatically.
    }

    protected function getStats()
    {
        return $this->database->queryOne(sprintf('
			SELECT COUNT(*)
			FROM oc_product p
			LEFT JOIN jtl_connector_link l ON p.product_id = l.endpointId AND l.type = %d
            WHERE l.hostId IS NULL',
            IdentityLinker::TYPE_PRODUCT
        ));
    }
}
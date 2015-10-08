<?php

namespace jtl\Connector\OpenCart\Controller\Product;

use jtl\Connector\Model\Product as ProductModel;
use jtl\Connector\OpenCart\Controller\BaseController;
use jtl\Connector\OpenCart\Utility\SQLs;

class ProductSpecific extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data);
    }

    protected function pullQuery($data, $limit = null)
    {
        return SQLs::productSpecificPull($data['product_id']);
    }

    public function pushData(ProductModel $data, &$model)
    {
        $model['product_filter'] = [];
        foreach ((array)$data->getSpecifics() as $specific) {
            if (!is_null($specific->getSpecificValueId()->getEndpoint())) {
                $specificValueId = $specific->getSpecificValueId()->getEndpoint();
                $model['product_filter'][] = $specificValueId;
                foreach ($data->getCategories() as $category) {
                    $categoryId = $category->getCategoryId()->getEndpoint();
                    $query = SQLs::productSpecificPush($categoryId, $specificValueId);
                    if ($this->database->queryOne($query) == 0) {
                        $this->database->query(SQLs::productSpecificFind($categoryId, $specificValueId));
                    }
                }
            }
        }
    }
}
<?php

namespace jtl\Connector\OpenCart\Controller;

class CustomerGroupI18n extends BaseController
{
    public function pullData($data, $model, $limit = null)
    {
        $return = [];
        $query = $this->pullQuery($data);
        $result = $this->database->query($query);
        foreach ($result as $row) {
            $model = $this->mapper->toHost($row);
            $return[] = $model;
        }
        return $return;
    }

    protected function pullQuery($data, $limit = null)
    {
        return sprintf('
            SELECT c.*, l.code
            FROM oc_customer_group_description c
            LEFT JOIN oc_language l ON c.language_id = l.language_id
            WHERE c.customer_group_id = %d',
            $data['customer_group_id']
        );
    }


}
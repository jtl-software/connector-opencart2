<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Controller
 */

namespace jtl\Connector\OpenCart\Controller;

use jtl\Connector\Model\FileUpload as FileUploadModel;
use jtl\Connector\OpenCart\Utility\OpenCart;
use jtl\Connector\OpenCart\Utility\OptionHelper;
use jtl\Connector\OpenCart\Utility\SQLs;

class FileUpload extends BaseController
{
    private $ocOption;
    /**
     * @var OptionHelper
     */
    private $optionHelper;

    public function __construct()
    {
        parent::__construct();
        $this->optionHelper = OptionHelper::getInstance();
        $this->ocOption = OpenCart::getInstance()->loadAdminModel('catalog/option');
    }

    public function pullData($data, $model, $limit = null)
    {
        return parent::pullDataDefault($data, $limit);
    }

    protected function pullQuery($data, $limit = null)
    {
        return SQLs::fileUploadPull($limit);
    }

    public function pushData(FileUploadModel $data, $model)
    {
        $option = ['type' => 'file', 'sort_order' => null];
        list($id, $descriptions) = $this->optionHelper->buildOptionDescriptions($data);
        $option['option_description'] = $descriptions;
        if (is_null($id)) {
            $id = $this->ocOption->addOption($option);
        } else {
            $this->ocOption->editOption($id, $option);
        }
        $query = SQLs::fileUploadPush($data->getProductId()->getHost(), $id, $data->getIsRequired());
        $this->database->query($query);
        return $data;
    }

    protected function deleteData(FileUploadModel $data)
    {
        $this->database->query(SQLs::fileUploadDelete($data->getId()->getEndpoint()));
        return $data;
    }
}

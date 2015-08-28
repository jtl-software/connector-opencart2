<?php
/**
 * @copyright 2010-2013 JTL-Software GmbH
 * @package jtl\Connector\OpenCart\Mapper
 */

namespace jtl\Connector\OpenCart\Mapper;

use jtl\Connector\Core\Utilities\Singleton;
use jtl\Connector\Model\Identity;
use jtl\Connector\OpenCart\Utility\Constants;
use jtl\Connector\OpenCart\Utility\Date;
use jtl\Connector\OpenCart\Utility\Db;

abstract class BaseMapper extends Singleton
{
    private $model = null;
    private $type = null;
    protected $database = null;
    protected $endpointModel = null;
    protected $push = [];
    protected $pull = [];

    public function __construct()
    {
        $reflect = new \ReflectionClass($this);
        $typeClass = "\\jtl\\Connector\\Type\\{$reflect->getShortName()}";
        $this->database = DB::getInstance();
        $this->model = Constants::CORE_MODEL_NAMESPACE . $reflect->getShortName();
        $this->type = new $typeClass();
    }

    public function toHost($data)
    {
        $model = new $this->model();
        foreach ($this->pull as $host => $endpoint) {
            $setter = 'set' . ucfirst($host);
            $fnName = strtolower($host);
            if (method_exists($this, $fnName)) {
                $value = $this->$fnName($data);
            } else {
                $value = (isset($data[$endpoint])) ? $data[$endpoint] : null;
                $property = $this->type->getProperty($host);
                if ($property->isNavigation()) {
                    $subControllerName = Constants::CONTROLLER_NAMESPACE . $endpoint;
                    if (class_exists($subControllerName)) {
                        $subController = new $subControllerName();
                        $value = $subController->pullData($data, $model);
                    }
                } elseif ($property->isIdentity()) {
                    $value = new Identity($value);
                } elseif ($property->getType() == 'boolean') {
                    $value = (bool)$value;
                } elseif ($property->getType() == 'integer') {
                    $value = intval($value);
                } elseif ($property->getType() == 'double') {
                    $value = floatval($value);
                } elseif ($property->getType() == 'DateTime') {
                    $value = Date::open_date($value) ? null : new \DateTime($value);
                }
            }
            if (!empty($value)) {
                $model->$setter($value);
            }
        }
        return $model;
    }

    public function toEndpoint($data, $customData = null)
    {
        $model = [];
        foreach ($this->push as $endpoint => $host) {
            $fnName = strtolower($endpoint);
            if (method_exists($this, $fnName)) {
                $value = $this->$fnName($data, $customData);
            } else {
                $getter = 'get' . ucfirst($host);
                $value = $data->$getter();
                $property = $this->type->getProperty($host);
                if ($property->isNavigation()) {
                    $subControllerName = Constants::CONTROLLER_NAMESPACE . $endpoint;
                    if (class_exists($subControllerName)) {
                        $subController = new $subControllerName();
                        $subController->pushData($data, $model);
                    }
                } elseif ($property->isIdentity()) {
                    $value = $value->getEndpoint();
                } elseif ($property->getType() == 'DateTime') {
                    $value = $value === null ? '0000-00-00 00:00:00' : $value->format('Y-m-d H:i:s');
                }
            }
            $model[$endpoint] = $value;
        }
        return $model;
    }

}
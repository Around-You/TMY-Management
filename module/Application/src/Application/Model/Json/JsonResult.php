<?php
namespace Application\Model\Json;

use Zend\Stdlib\ArraySerializableInterface;

class JsonResult implements ArraySerializableInterface
{

    const JSON_RESULT_SUCCESSFUL = 1;

    const JSON_RESULT_FAILED = 0;

    public $status;

    /**
     *
     * @var ArraySerializableInterface Array
     */
    public $data;

    /**
     *
     * @return the $data
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     *
     * @param ArraySerializableInterface|Array $data
     */
    public function setData($data)
    {
        if ($data instanceof ArraySerializableInterface) {
            $this->data = $data->getArrayCopy();
        } elseif (is_array($data)) {
            $this->data = $data;
        } else {
            throw new \Exception('输入数据类型不是ArraySerializableInterface或者Array');
        }
    }

    /**
     *
     * @param string $status
     * @param ArraySerializableInterface|Array $data
     * @return \Application\src\Application\Model\Json\JsonResult
     */
    public static function buildResult($status = self::JSON_RESULT_FAILED, $data = array())
    {
        $result = new JsonResult();
        $result->status = $status;
        $result->setData($data);
        return $result;
    }

    public function getArrayCopy()
    {
        $data = array(
            'status' => $this->status,
            'data' => $this->data
        );
        return $data;
    }

    /**
     * Exchange internal values from provided array
     *
     * @param array $array
     * @return void
     */
    public function exchangeArray(array $array)
    {
        throw new \Exception('not used');
    }
}

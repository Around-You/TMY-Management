<?php
namespace Application\Model\Json;

use Zend\Stdlib\ArraySerializableInterface;
use Zend\Db\ResultSet\ResultSet;

class JsonResult implements ArraySerializableInterface
{

    const JSON_RESULT_SUCCESSFUL = 1;

    const JSON_RESULT_FAILED = 0;

    public $status;
    public $error;

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
        } elseif ($data instanceof ResultSet){
            foreach ($data as $row){
                $rowArr = $row->getArrayCopy();
                if(isset($rowArr['id'])){
                    $rowArr['DT_RowId'] = $rowArr['id'];
                }
                $this->data[] = $rowArr;
            }
        }else {
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

    /**
     *
     * @param string $status
     * @param ArraySerializableInterface|Array $data
     * @return \Application\src\Application\Model\Json\JsonResult
     */
    public static function buildSuccessResult($data = array())
    {
        $result = new JsonResult();
        $result->status = self::JSON_RESULT_SUCCESSFUL;
        $result->setData($data);
        return $result;
    }

    /**
     *
     * @param string $status
     * @param ArraySerializableInterface|Array $data
     * @return \Application\src\Application\Model\Json\JsonResult
     */
    public static function buildFailedResult(\Exception $e)
    {
        $result = new JsonResult();
        $result->status = self::JSON_RESULT_FAILED;
        $result->error = $e->getMessage();
        return $result;
    }

    public function getArrayCopy()
    {
        $data = array(
            'status' => $this->status,
            'data' => $this->data,
            'error' => $this->error
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

<?php
namespace Application\Model\Json;

use Zend\Stdlib\ArraySerializableInterface;
use Zend\Db\ResultSet\ResultSet;

class DataTableResult implements ArraySerializableInterface
{

    const JSON_RESULT_SUCCESSFUL = 1;

    const JSON_RESULT_FAILED = 0;

    public $draw = 0;

    public $recordsTotal = 0;

    public $recordsFiltered = 0;

    public $errorMessage = '';

    /**
     *
     * @var Array
     */
    protected $data = array();

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
     * @param ResultSet|Array $data
     */
    public function setData($draw, $count, $data)
    {
        $this->draw = $draw ++;
        $this->recordsTotal = $count;
        $this->recordsFiltered = $count;

        if (is_array($data)) {
            $this->data = $data;
        } elseif ($data instanceof ResultSet) {
            foreach ($data as $row) {
                $rowArr = $row->getArrayCopy();
                if (isset($rowArr['id'])) {
                    $rowArr['DT_RowId'] = $rowArr['id'];
                }
                $this->data[] = $rowArr;
            }
        } else {
            throw new \Exception('输入数据类型不是ResultSet或者Array');
        }
    }

    /**
     *
     * @param ArraySerializableInterface|Array $data
     * @return \Application\src\Application\Model\Json\JsonResult
     */
    public static function buildResult($draw = 1, $count = 0, $data = array())
    {
        $result = new DataTableResult();
        if (! empty($data)) {
            $result->setData($draw, $count, $data);
        }
        return $result;
    }

    public function getArrayCopy()
    {
        $data = array(
            'draw' => $this->draw,
            'data' => $this->data,
            'recordsTotal' => $this->recordsTotal,
            'recordsFiltered' => $this->recordsFiltered
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

    public static function getOrderString($request)
    {
        $columns = $request['columns'];
        $order = $request['order'];
        $orderString = $columns[$order[0]['column']]['data'] . ' ' . $order[0]['dir'];
        return $orderString;
    }

    public function setErrorMessage($errorMessage){
        $this->errorMessage = $errorMessage;
    }
}

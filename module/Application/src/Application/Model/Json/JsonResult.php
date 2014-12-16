<?php
namespace Application\src\Application\Model\Json;

class JsonResult
{
    public $status;
    public $data;
    public function getArrayCopy()
    {
        $data = array(
            'status' => $this->status,
            'data' => $this->data
        );
        return $data;
    }
}

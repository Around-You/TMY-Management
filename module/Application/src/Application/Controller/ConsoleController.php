<?php
namespace Application\Controller;

use Zend\Db\Adapter\Adapter;

class ConsoleController extends AbstractActionController
{



    public function updateMemberGoodsStateAction()
    {
       $table = $this->getMemberGoodsTable();
       $now = date('Y-m-d');
       $sql = "update tmy.member_has_goods set enable = 0 where end_date < '{$now}'";
       $table->getAdapter()->query($sql, Adapter::QUERY_MODE_EXECUTE);
       echo $sql;
    }


}

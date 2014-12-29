<?php
namespace Application\Model\Report;

use SamFramework\Model\AbstractModelMapper;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Expression;

class DailyReportTable extends AbstractModelMapper
{

    public $currentStoreId = 0;

    public $currentUserId = 0;

    protected $tableName = 'daily_report';

    protected $modelClassName = 'Application\\Model\\Report\\DailyReport';

    public function buildSqlSelect(Select $select, $where = array())
    {
        $select->where($where);
    }

    public function getFetchAllCounts($where = array())
    {
        $select = $this->getTableGateway()
            ->getSql()
            ->select();
        $this->buildSqlSelect($select, $where);
        $select->columns(array(
            new Expression('count(' . $this->tableName . '.id) as rownum')
        ));
        $statement = $this->getTableGateway()
            ->getSql()
            ->prepareStatementForSqlObject($select);
        $row = $statement->execute()->current();
        return $row['rownum'];
    }

    public function fetchAll($where = array(), $offset = 0, $limit = 99999)
    {
        $offset = (int) $offset;
        $limit = (int) $limit;

        $table = $this;
        $resultSet = $this->getTableGateway()->select(function (Select $select) use($offset, $limit, $table, $where)
        {
            $table->buildSqlSelect($select, $where);
            $select->offset($offset)
                ->limit($limit);
        });
        return $resultSet;
    }

    public function save(DailyReport $item)
    {
        $tableGateway = $this->getTableGateway();
        $data = $item->getArrayCopyForSave();
        $id = (int) $item->id;
        if ($id == 0) {
            $tableGateway->insert($data);
            $item->id = $this->getTableGateway()->getLastInsertValue();
        } else {
            if ($this->getOneById($id)) {
                $tableGateway->update($data, array(
                    'id' => $id
                ));
            }
        }
        return $item;
    }

    /**
     *
     * @param unknown $id
     * @throws \Exception
     * @return DailyReport
     */
    public function getOneById($id)
    {
        $tableGateway = $this->getTableGateway();
        $id = (int) $id;
        $rowset = $tableGateway->select(array(
            'id' => $id
        ));
        $row = $rowset->current();
        if (! $row) {
            throw new \Exception("Could not find row $id");
        }

        return $row;
    }

    /**
     *
     * @param unknown $id
     * @throws \Exception
     * @return DailyReport
     */
    public function getOneByDate($date = NULL)
    {
        if ($date == NULL) {
        	$date = date('Y-m-d');
        }
        $tableGateway = $this->getTableGateway();
        $rowset = $tableGateway->select(array(
            'date' => $date
        ));
        $row = $rowset->current();
        if (! $row) {
            $report = new DailyReport();
            $report->date = $date;
            return $report;
        }

        return $row;
    }

    public function addMemberCount()
    {
        $report = $this->getOneByDate();
        $report->member_count++;
        $this->save($report);
    }

    public function addSaleCount()
    {
        $report = $this->getOneByDate();
        $report->sale_count++;
        $this->save($report);
    }
}


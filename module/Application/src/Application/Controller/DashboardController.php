<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class DashboardController extends AbstractActionController
{

    protected $dailyReportTalbe;

    public function getDailyReportTable()
    {
        if (! $this->dailyReportTalbe) {
            $this->dailyReportTalbe = $this->getServiceLocator()->get('Application\Model\Report\DailyReportTable');
        }
        return $this->dailyReportTalbe;
    }

    public function indexAction()
    {
        $today = $this->getDailyReportTable()->getOneByDate();
        $last30 = $this->getLast30Report();
        return array(
            'today' => $today,
            'last30' => json_encode($last30)
        );
    }

    protected function getLast30Report()
    {
        $resultSet = $this->getDailyReportTable()->fetchAll(array(), 0, 30, 'id DESC');
        $returnArr = array(
            'sale' => array(),
            'member' => array()
        );
        foreach ($resultSet as $day) {
            $returnArr['sale'][] = array(
                strtotime($day->date),
                $day->sale_count
            );
            $returnArr['member'][] = array(
                strtotime($day->date),
                $day->member_count
            );
        }
        for ($i = 0; $i < 30; $i ++) {
            $returnArr['x'][] = array(
                strtotime("-{$i} days"),
                date('m/d', strtotime("-{$i} days"))
            );
        }
        $returnArr['sale'] = array_reverse($returnArr['sale']);
        $returnArr['member'] = array_reverse($returnArr['member']);
        $returnArr['x'] = array_reverse($returnArr['x']);
        return $returnArr;
    }
}

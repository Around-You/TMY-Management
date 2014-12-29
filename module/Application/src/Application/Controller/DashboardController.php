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
    	return array('today'=>$today);
    }
}

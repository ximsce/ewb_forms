<?php
namespace Monitor\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class MonitorController extends AbstractActionController
{   
    // connection to forms api
    private $monitorReportClient;
    
    public function getMonitorReportClient()
    {
        if (!$this->monitorReportClient) {
            $sm = $this->getServiceLocator();
            $this->monitorReportClient = $sm->get('Monitor\Model\MonitorReportClient');
        }
        return $this->monitorReportClient;
    }
    
    public function indexAction()
    {
        return new ViewModel(array(
            'dmForms' => $this->getMonitorReportClient()->fetchList()
            ));
    }

    public function reportAction()
    {
        $id = (int) $this->params()->fromRoute('id');
        if (!$id) {
            return $this->redirect()->toRoute('monitor', array(
                'action' => ''
            ));
        }
        
        return new ViewModel(array(
            'dmForm' => $this->getMonitorReportClient()->getMonitorReport($id)
        ));
    }
}

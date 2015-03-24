<?php
namespace Monitor;

use Zend\Http\Client;
use Monitor\Model\MonitorReportClient;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Monitor\Model\MonitorReportClient' =>  function($sm) {
                    $config = $sm->get('config');
                    $dmClient = new Client();
                    $client = new MonitorReportClient($config, $dmClient);
                    return $client;
                },
            ),
        );
    }
}

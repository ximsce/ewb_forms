<?php
namespace Monitor\Model;

class MonitorReportClient {
    // ssl settings
    private $httpsConfig = array(
    		    'adapter' => 'Zend\Http\Client\Adapter\Socket',
    		    'sslverifypeer' => false
    		   );
    
    // connection settings for form api
    private $dmBaseUrl;
    private $dmFormsXml;
    private $dmFormsEndpoint;
    private $dmOrganizationId;
    private $dmApiKey;
    private $dmApiPassword;
    
    // injected http client
    private $dmClient;
    
    public function __construct($config, $client) {
        $this->dmBaseUrl = $config['dmBaseUrl'];
        $this->dmFormsXml = $config['dmFormsXml'];
        $this->dmFormsEndpoint = $config['dmFormsEndpoint'];
        $this->dmOrganizationId = $config['dmOrganizationId'];
        $this->dmApiKey = $config['dmApiKey'];
        $this->dmApiPassword = $config['dmApiPassword'];
        
        $this->dmClient = $client;
    }
    
    public function fetchList() {
        // retrieve all forms
        $this->dmClient->setUri($this->dmBaseUrl . $this->dmOrganizationId . $this->dmFormsXml);
        $this->dmClient->setOptions($this->httpsConfig);
        $this->dmClient->setAuth($this->dmApiKey, $this->dmApiPassword);
        
        $dmResponse = $this->dmClient->send();
        $dmFormsJson = \Zend\Json\Json::fromXml($dmResponse->getBody(), true);
        $dmForms = \Zend\Json\Json::decode($dmFormsJson);
        return $dmForms;
    }
    
    public function getMonitorReport($id) {
        $this->dmClient->setUri($this->dmBaseUrl . $this->dmOrganizationId . $this->dmFormsEndpoint . '/' . $id);
        $this->dmClient->setOptions($this->httpsConfig);
        $this->dmClient->setAuth($this->dmApiKey, 'x');
        
        $formResponse = $this->dmClient->send();
        $monitorReport = new MonitorReport($id);
        $monitorReport->exchangeArray($formResponse->getBody());
        return $monitorReport;
    }
}
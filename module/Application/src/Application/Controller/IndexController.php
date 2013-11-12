<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Http\Client;
use Zend\Http\Headers;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    private $dmBaseUrl = 'https://www.devicemagic.com/organizations/';
    private $dmFormsEndpoint = '/forms';
    private $dmOrganizationId = '33238';
    private $dmApiKey = 'UNkUEK3Pjyr87pzyBZcQ';
    private $dmApiPassword = 'x';
    
    public function indexAction()
    {
        // set up connection for ssl
        $httpsConfig = array(
    		    'adapter' => 'Zend\Http\Client\Adapter\Socket',
    		    'sslverifypeer' => false
    		   );
        
        // retrieve all forms
        $dmClient = new Client($this->dmBaseUrl . $this->dmOrganizationId . '/forms.xml', $httpsConfig);
        $dmClient->setAuth($this->dmApiKey, $this->dmApiPassword);
        
        $dmResponse = $dmClient->send();
        $dmFormsJson = \Zend\Json\Json::fromXml($dmResponse->getBody(), true);
        $dmForms = \Zend\Json\Json::decode($dmFormsJson);
        
        // retrieve form details
        foreach ($dmForms->forms as $form => $formProperties) {
            $formClient = new Client($this->dmBaseUrl . $this->dmOrganizationId . $this->dmFormsEndpoint . '/' . $formProperties->id, $httpsConfig);
            $formClient->setAuth($this->dmApiKey, 'x');
            
            $formResponse = $formClient->send();
            $formProperties->elements = $formResponse->getBody();
        }
            
        $vm = new ViewModel();
        $vm->setVariable('dmForms', $dmForms);
        return $vm;
    }
}

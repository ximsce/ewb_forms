<?php
namespace MonitorTest\Model;

use Monitor\Model\MonitorReportClient;
use Monitor\Model\MonitorReport;
use PHPUnit_Framework_TestCase;

class MonitorReportClientTest extends PHPUnit_Framework_TestCase
{
    private $config = array(
        'dmBaseUrl' => 'https://www.devicemagic.com/organizations/',
        'dmFormsXml' => '/forms.xml',
        'dmFormsEndpoint' => '/forms',
        'dmOrganizationId' => '1234',
        'dmApiKey' => '12343214',
        'dmApiPassword' => 'x'
        );
    
    private $responseBody = '<?xml version="1.0"?>
<h:html xmlns:h="http://www.w3.org/1999/xhtml" xmlns:dm="http://www.devicemagic.com/XMLSchemaDataTypes" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:ev="http://www.w3.org/2001/xml-events" xmlns="http://www.w3.org/2002/xforms">
  <h:head>
    <h:title>Site 41:  Upper Alcanterizado de Malingua</h:title>
    <model>
    <instance xmlns="http://www.devicemagic.com/xforms/01827500-1ce5-0131-c944-12313b0190ad">
        <inputs>
          <Are_structures_working_to_contain_erosion_/>
          <Add_image_s__to_demonstrate_structures_working_or_not_working/>
          <How_many_plants_are_surviving_/>
          <Please_add_anything_else_you_would_like_to_inform_us_about_this_site/>
          <Please_detail_future_plans__if_any__for_this_site/>
        </inputs>
      </instance>
      <bind nodeset="Are_structures_working_to_contain_erosion_" type="boolean"/>
      <bind nodeset="Add_image_s__to_demonstrate_structures_working_or_not_working" type="binary"/>
      <bind nodeset="How_many_plants_are_surviving_" type="integer"/>
    </model>
  </h:head>
  <h:body>
  </h:body>
</h:html>';
    
    public function testFetchAllReturnsAllMonitorReports()
    {
        $responseBody = '<xml></xml>';
        $resultSet        = $this->getMock('Zend\Http\Response',
                                            array('getBody'), array(), '', false);
        $resultSet->expects($this->once())
                ->method('getBody')
                ->with()
                ->will($this->returnValue($responseBody));
        $mockHttpClient = $this->getMock('Zend\Http\Client',
                                           array('send'), array(), '', false);
        $mockHttpClient->expects($this->once())
                         ->method('send')
                         ->with()
                         ->will($this->returnValue($resultSet));

        $monitorReportClient = new MonitorReportClient($this->config, $mockHttpClient);

        $this->assertEquals(\Zend\Json\Json::decode(\Zend\Json\Json::fromXml($responseBody)), $monitorReportClient->fetchList());
    }
    
    public function testCanRetrieveMonitorReportByItsId()
    { 
        $resultSet        = $this->getMock('Zend\Http\Response',
                                            array('getBody'), array(), '', false);
        $resultSet->expects($this->once())
                ->method('getBody')
                ->with()
                ->will($this->returnValue($this->responseBody));
        
        $mockHttpClient = $this->getMock('Zend\Http\Client', array('send'), array(), '', false);
        $mockHttpClient->expects($this->once())
                         ->method('send')
                         ->with()
                         ->will($this->returnValue($resultSet));

        $monitorReportClient = new MonitorReportClient($this->config, $mockHttpClient);

        $fetchedMonitorReport = $monitorReportClient->getMonitorReport(123);
        $this->assertEquals(123, $fetchedMonitorReport->id);
    }
}
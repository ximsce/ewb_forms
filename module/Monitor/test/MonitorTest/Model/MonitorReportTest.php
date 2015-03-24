<?php
namespace MonitorTest\Model;

use Monitor\Model\MonitorReport;
use PHPUnit_Framework_TestCase;

class MonitorReportTest extends PHPUnit_Framework_TestCase
{
    private $xml = '<?xml version="1.0"?>
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
  <input ref="Are_structures_working_to_contain_erosion_">
      <label>Are structures working to contain erosion?</label>
    </input>
    <upload ref="Add_image_s__to_demonstrate_structures_working_or_not_working" mediatype="image/*">
      <label>Add image(s) to demonstrate structures working or not working</label>
      <filename>@filename</filename>
      <mediatype>@mediatype</mediatype>
    </upload>
    <input ref="How_many_plants_are_surviving_">
      <label>How many plants are surviving?</label>
    </input>
    <input ref="Please_add_anything_else_you_would_like_to_inform_us_about_this_site" dm:multiline="true">
      <label>Please add anything else you would like to inform us about this site</label>
    </input>
    <input ref="Please_detail_future_plans__if_any__for_this_site">
      <label>Please detail future plans (if any) for this site</label>
    </input>
  </h:body>
</h:html>';
    
    public function testMonitorReportInitialState()
    {
        $monitorReport = new MonitorReport();

        $this->assertNull($monitorReport->id, '"id" should initially be null');
    }

    public function testExchangeArraySetsPropertiesCorrectly()
    {
        $monitorReport = new MonitorReport(123);
        $monitorReport->exchangeArray($this->xml);

        $this->assertEquals(123, $monitorReport->id, '"id" was not set correctly');
        $this->assertEquals('Site 41:  Upper Alcanterizado de Malingua', $monitorReport->title, '"title" was not set correctly');
        $this->assertEquals(5, count($monitorReport->inputs), 'Unexpected number of inputs');
        $this->assertEquals('Are_structures_working_to_contain_erosion_', $monitorReport->inputs[0]['name'], 'Incorrect name for first input');
        $this->assertEquals('Are structures working to contain erosion?', $monitorReport->inputs[0]['label'], 'Incorrect label for first input');
        $this->assertEquals('boolean', $monitorReport->inputs[0]['type'], 'Incorrect type for first input');
        $this->assertEquals('textarea', $monitorReport->inputs[3]['type'], 'Incorrect type for textarea input');
    }
}
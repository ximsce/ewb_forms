<?php
namespace Monitor\Model;

class MonitorReport
{
    public $id;
    public $title;
    public $inputs;
    
    public function __construct($id = null) {
        $this->id = $id;
    }
    
    public function exchangeArray($reportXml)
    {
        $elements = new \SimpleXMLElement($reportXml);
        $this->title = $elements->children('h', true)->head->title;
        $inputs = $elements->children('h', true)->head->children()->model->children()->instance->inputs->children();
        $this->inputs = array();
        foreach ($inputs as $input) {
            $this->inputs[] = array(
                'name' => $input->getName(),
                'type' => $this->_getInputType($input->getName(), $elements),
                'label' => $this->_getInputLabel($input->getName(), $elements),
              );
        }
    }
    
    private function _getInputType($name, $reportElements) {
        $model = $reportElements->children('h', true)->head->children()->model;
        $bindArray = $model->xpath("*[@nodeset='$name']");
        if (count($bindArray) === 1) {
            return $bindArray[0]['type'];
        } else {
            return 'textarea';
        }
    }
    
    private function _getInputLabel($name, $reportElements) {
        $body = $reportElements->children('h', true)->body;
        $inputArray = $body->xpath("*[@ref='$name']");
        if (count($inputArray) === 1) {
            return $inputArray[0]->label;
        } else {
            return "";
        }
    }
}
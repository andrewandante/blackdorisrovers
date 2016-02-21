<?php 

class Match extends CalendarEvent {
  
  private static $db = array(
    'Opposition' => 'Varchar(50)',
    'OppositionScore' => 'Int',
    'BDRScore' => 'Int',
    'Result' => 'Enum("Win, Loss, Draw")',
    'UID' => 'Varchar(20)'
  );
  
  public function getCMSFields() {
    
    $fields = parent::getCMSFields();
    $fields->removeByName("Content");
    
    $fields->addFieldToTab("Root.Main", ReadonlyField::create('UID', 'UID'), 'URLSegment');
    
    $fields->addFieldsToTab("Root.Main", array(
      TextField::create('Opposition', 'Opposition'),
      DropdownField::create('Result', 'Game result', singleton('Match')->dbObject('Result')->enumValues()
    )->setEmptyString('(TBD)')), 'Metadata');
    
      
    return $fields;
  }
}

class Match_Controller extends CalendarEvent_Controller {
  
}
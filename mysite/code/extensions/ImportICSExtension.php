<?php

class ImportICSExtension extends DataExtension {

  private static $db = array(

    'ImportURL' => 'Varchar',

  );

  public function updateCMSFields(FieldList $fields) {

    $fields->addFieldToTab("Root.Main",
      TextField::create("ImportURL", "URL to import ICS from")

    );

  }

}

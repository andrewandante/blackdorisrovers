<?php

class LogoExtension extends DataExtension {

  private static $has_one = array(
    'Logo' => 'Image',
  );

  public function updateCMSFields(FieldList $fields) {
    $fields->addFieldToTab("Root.Main",
      UploadField::create("Logo")
    );
  }
}

<?php 

class Season extends Calendar {
  
  private static $db = array(
    'LastSyncDate' => 'SS_Datetime'
  );
  
  public function getCMSFields() {
    $fields = parent::getCMSFields();
    $fields->addFieldToTab("Root.Feeds", DatetimeField_readonly::create("LastSyncDate", "Date of last sync"), "Feeds");
    
    return $fields;
  }
  
}

class Season_Controller extends Calendar_Controller {
  
  public static $allowed_actions = array(
    'SyncButton'
  ); 
  
  public function getEventList($start, $end, $filter = null, $limit = null) {

		$eventList = new ArrayList();

		foreach($this->getAllCalendars() as $calendar) {
			if($events = $calendar->getStandardEvents($start, $end, $filter)) {
				$eventList->merge($events);
			}

		}

		$eventList = $eventList->sort(array("StartDate" => "ASC", "StartTime" => "ASC"));
		$eventList = $eventList->limit($limit);

		return $eventList;
	}

  public function getLatestResults() {
    $begins = SS_Datetime::now();
    $results = $this->data()->getEventList(
      $begins->setValue(0),
      SS_Datetime::now()
    )->sort('StartDate', 'DESC');
    $latestevents = ArrayList::create();
    foreach ($results as $result) {
      $id = $result->EventID;
      $match = Match::get()->byid($id);
      if ($match->Result) {
        $latestevents->push($match);
      }
      if ($latestevents->count() == $this->DefaultPreviousResults) {
        break;
      }
    }
    return $latestevents->sort('UID');
  }
  
  public function SyncButton() {
      
      $fields = FieldList::create(
        ReadonlyField::create('LastSyncDate', 'Last Sync Date')
      );
      
			$actions = FieldList::create(
        FormAction::create("doSyncFromFeed")->setTitle("Sync Feed to Events")
      );
			
      $form = Form::create($this, 'SyncButton', $fields, $actions);
      $form->loadDataFrom($this->data());
      
			return $form;
	}
  
  public function doSyncFromFeed($data, $form) {
    
    $feeds = $this->Feeds();
    foreach( $feeds as $feed ) {
      $feedreader = new ICSReader( $feed->URL );
      $events = $feedreader->getEvents();
      foreach ( $events as $event ) {
        // translate iCal schema into Match schema
        $uid = strtok($event['UID'], '@');
        if ($match = Match::get()->filter(array("UID" => $uid))->First()) {
          $feedevent = $match;
        } else {
          $feedevent = Match::create();
          $feedevent->UID = $uid;
        }
        
        $feedevent->Title = $event['SUMMARY'];
        //Get opposition with some string fun
        $feedevent->Opposition = trim(str_replace(array(
          "-",
          "Black Doris Rovers",
          "vs"
        ), "", $event['SUMMARY']));
        
        if (preg_match('/Round/', $feedevent->Opposition)) {
          $opp = explode(" ", $feedevent->Opposition);
          $opp = array_slice($opp, 2);
          $feedevent->Opposition = trim(implode(" ", $opp));
        }
        
        if (isset($event['DESCRIPTION']) && !empty($event['DESCRIPTION']) && $event['DESCRIPTION'] != " ") {
          
          $scores = str_replace("Result\n", "", $event['DESCRIPTION']);
          $scores = explode("-", $scores);
          foreach ($scores as $score) {
            $score = trim($score);
            $bits = explode(" ", $score);
            if (preg_match('/Black Doris Rovers/', $score)) {
              $feedevent->BDRScore = end($bits);
            } else {
              $feedevent->OppositionScore = end($bits);
            }
          }
          
          if (intval($feedevent->BDRScore) > intval($feedevent->OppositionScore)) {
            $feedevent->Result = 'Win';
          } elseif (intval($feedevent->BDRScore) < intval($feedevent->OppositionScore)) {
            $feedevent->Result = 'Loss';
          } else {
            $feedevent->Result = 'Draw';
          }
        } else{
          $feedevent->BDRScore = NULL;
          $feedevent->OppositionScore = NULL;
          $feedevent->Result = NULL;
        }

        $startdatetime = $this->iCalDateToDateTime($event['DTSTART']);

        if (array_key_exists('DTEND', $event) && $event['DTEND'] != NULL) {
          $enddatetime = $this->iCalDateToDateTime($event['DTEND']);
        } elseif (array_key_exists('DURATION', $event) && $event['DURATION'] != NULL) {
          $enddatetime = $this->iCalDurationToEndTime($event['DTSTART'], $event['DURATION']);
        }
        
        $new = false;
        if($feedevent->DateTimes()->Count() == 0) {
          $cdt = CalendarDateTime::create();
          $new = true;
        } else {
          $cdt = $feedevent->DateTimes()->First();
        }
        
        $cdt->StartDate = $startdatetime->format('Y-m-d');
        $cdt->StartTime = $startdatetime->format('H:i:s');

        $cdt->EndDate = $enddatetime->format('Y-m-d');
        $cdt->EndTime = $enddatetime->format('H:i:s');
        
        if ($new == true) {
          $feedevent->DateTimes()->add($cdt);
        } else {
          $cdt->write();
        }
        
        $feedevent->ParentID = $this->ID;
        
        $feedevent->write();
        $feedevent->publish('Stage', 'Live');
        
      }
      
    }
    
    $form->sessionMessage('Sync Succeeded', 'good');

    $data = $this->data();
    $data->LastSyncDate = date("Y-m-d H:i:s");
    $data->write();
    $data->publish('Stage', 'Live');

    return $this->redirectBack();
  }
  
}
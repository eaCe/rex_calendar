<?php
//ical export
if (rex_get('ical') !== "")
{
    if(is_numeric(rex_get('ical')))
    {
        $eventArray = getEventById(rex_get('ical'));
        
        if($eventArray)
        {
            getiCal($eventArray[0]);
        }
    }
}

function getiCal($eventArray)
{
    function dateToCal($timestamp)
    {
        return date('Ymd\THis\Z', $timestamp);
    }

    function escapeString($string)
    {
        return preg_replace('/([\,;])/', '\\\$1', $string);
    }  
    
    $iCalContent = "";

    $iCalContent .= "BEGIN:VCALENDAR";
    $iCalContent .= "VERSION:2.0";
    $iCalContent .= "PRODID:-//Kalender Export//NONSGML//DE";
    $iCalContent .= "CALSCALE:GREGORIAN";
    
    //begin event
    $iCalContent .= "BEGIN:VEVENT";
    $iCalContent .= "SUMMARY:" . escapeString($eventArray["title"]);
    $iCalContent .= "DESCRIPTION:";
    $iCalContent .= "DTSTART:";
    $iCalContent .= "UID:" . uniqid();
    $iCalContent .= "DTSTAMP:";
    $iCalContent .= "DTEND:";
    $iCalContent .= "URL:" . rex::getServer();
    $iCalContent .= "END:VEVENT";

    $iCalContent .= "END:VCALENDAR";
}


function getEventsByMonth($month = false, $year = false, $limit = false)
{ 
    $setLimit = "";
    
    if (!$month)
    {
        $month = date("n");
    }

    if (!$year)
    {
        $year = date("Y");
    }

    if ($limit)
    {
        $setLimit = " LIMIT " . $limit;
    }
    
    $sql_eventsbymonth = rex_sql::factory();
    $sql_eventsbymonth->setQuery('SELECT * FROM `rex_event_calendar` WHERE `rex_event_calendar`.`month` = ' . $month . ' AND `rex_event_calendar`.`year` = ' . $year . ' ORDER BY `rex_event_calendar`.`day` ASC' . $setLimit);
    
    $eventsByMonthArray = $sql_eventsbymonth->getArray();
    $result = $eventsByMonthArray;
    
    return $result;
}

function getEventsByYear($year = false, $limit = false)
{ 
    $setLimit = "";
    
    if (!$year)
    {
        $year = date("Y");
    }

    if ($limit)
    {
        $setLimit = " LIMIT " . $limit;
    }
    
    $sql_eventsbyyear = rex_sql::factory();
    $sql_eventsbyyear->setQuery('SELECT * FROM `rex_event_calendar` WHERE `rex_event_calendar`.`year` = ' . $year . ' ORDER BY `rex_event_calendar`.`day` ASC' . $setLimit);
    
    $eventsByYearArray = $sql_eventsbyyear->getArray();
    $result = $eventsByYearArray;

    return $result;
}

function getEventsByDay($day, $month, $year)
{ 
    $sql_eventsbyday = rex_sql::factory();
    $sql_eventsbyday->setQuery('SELECT * FROM `rex_event_calendar` WHERE `rex_event_calendar`.`day` = ' . $day . ' AND `rex_event_calendar`.`month` = ' . $month . ' AND `rex_event_calendar`.`year` = ' . $year . ' ORDER BY `rex_event_calendar`.`day` ASC');
    
    return $sql_eventsbyday->getArray();
}

function getNextEvents($day = false, $month = false, $year = false, $limit = false)
{
    $setLimit = "";
    
    if(!$day)
    {
        $day = date("d");
    }
    
    if(!$month)
    {
        $month = date("n");
    }
    
    if(!$year)
    {
        $year = date("Y");
    }
    
    if($limit)
    {
        $setLimit = " LIMIT " . $limit;
    }
    
    $date = date("Y-m-d", strtotime($day . "." . $month . "." . $year));
    $sql_eventsfromnow = rex_sql::factory();
    $sql_eventsfromnow->setQuery("SELECT * FROM `rex_event_calendar` WHERE `startdate` >= '".$date."' ORDER BY `day` ASC " . $setLimit);

    return $sql_eventsfromnow->getArray();
}


//get image by id getImageById(eventid,mediapath - default media/, settitle - )
function getImageById($id, $mediaPath = false, $setTitle = false)
{
    $sql_imgebyid = rex_sql::factory();
    $sql_imgebyid->setTable('rex_event_calendar');
    $sql_imgebyid->setWhere(array('id' => $id));
    $sql_imgebyid->select('*');
    $img = $sql_imgebyid->getValue("image");
    $title = $sql_imgebyid->getValue("title");
    $src = $img;
    
    if(!$mediaPath)
    {
        $mediaPath = "media/";
    }
    
    if(!$setTitle)
    {
        return '<img src="'.$mediaPath .$src.'" alt="'.$title.'" />';
    }
    else
    {
        return '<img src="'.$mediaPath .$src.'" alt="'.$title.'" title="'.$title.'" />';    
    }

}

function getEventById($id)
{
    $sql_eventbyid = rex_sql::factory();
    $sql_eventbyid->setTable('rex_event_calendar');
    $sql_eventbyid->setWhere(array('id' => $id));
    $sql_eventbyid->select('*');
    $event = $sql_eventbyid->getArray();
    
    return $event[0];
}

function getEvenByCategory($category, $day = false, $month = false, $year = false, $limit = false)
{
    $setLimit = "";

    if (!$day)
    {
        $day = date("d");
    }

    if (!$month)
    {
        $month = date("n");
    }

    if (!$year)
    {
        $year = date("Y");
    }

    if ($limit)
    {
        $setLimit = " LIMIT " . $limit;
    }
    
    $date = date("Y-m-d", strtotime($day . "." . $month . "." . $year));
    $sql_eventsfromcategory = rex_sql::factory();
    $sql_eventsfromcategory->setQuery("SELECT * FROM `rex_event_calendar` WHERE `category` = '" . $category . "' AND `startdate` >= '".$date."' ORDER BY `day` ASC " . $setLimit);
    
    return $sql_eventsfromcategory->getArray();
}
<?php

//add new entry
if (rex_post('addevent') == "true")
{
    ob_end_clean();

    $date = date("Y-m-d", strtotime(rex_post('day') . "." . rex_post('month') . "." . rex_post('year')));

    $sql_add = rex_sql::factory();
    $sql_add->setTable('rex_event_calendar');
    $sql_add->setValue('startdate', $date);
    $sql_add->setValue('day', rex_post('day'));
    $sql_add->setValue('month', rex_post('month'));
    $sql_add->setValue('year', rex_post('year'));
    $sql_add->setValue('starttime', rex_post('starttime'));
    $sql_add->setValue('endtime', rex_post('endtime'));
    $sql_add->setValue('title', rex_post('title'));
    $sql_add->setValue('image', rex_post('image'));
    $sql_add->setValue('description', nl2br(rex_post('description')));
    $sql_add->setValue('category', rex_post('category'));

    if ($sql_add->insert())
    {
        http_response_code(200);
    }
    else
    {
        http_response_code(500);
    }

    exit;
}

//edit entry
if (rex_post('editevent') == "true")
{
    ob_end_clean();

    $date = date("Y-m-d", strtotime(rex_post('day') . "." . rex_post('month') . "." . rex_post('year')));

    $sql_edit = rex_sql::factory();
    $sql_edit->setTable('rex_event_calendar');
    $sql_edit->setValue('startdate', $date);
    $sql_edit->setValue('day', rex_post('day'));
    $sql_edit->setValue('month', rex_post('month'));
    $sql_edit->setValue('year', rex_post('year'));
    $sql_edit->setValue('starttime', rex_post('starttime'));
    $sql_edit->setValue('endtime', rex_post('endtime'));
    $sql_edit->setValue('title', rex_post('title'));
    $sql_edit->setValue('image', rex_post('image'));
    $sql_edit->setValue('description', nl2br(rex_post('description')));
    $sql_edit->setValue('category', rex_post('category'));
    $sql_edit->setWhere('id=' . rex_post('id'));

    if ($sql_edit->update())
    {
        http_response_code(200);
    }
    else
    {
        http_response_code(500);
    }

    exit;
}

//delete entry
if (rex_post('deleteevent') == "true")
{
    ob_end_clean();

    $sql_delete = rex_sql::factory();
    $sql_delete->setTable('rex_event_calendar');
    $sql_delete->setWhere('id=' . rex_post('id'));

    if ($sql_delete->delete())
    {
        http_response_code(200);
    }
    else
    {
        http_response_code(500);
    }
    exit;
}

//category-select for modals
function getCategories()
{
    $addon = rex_addon::get('rex_calendar');
    $categoriesString = $addon->getConfig('rex_calendar_settings_categories');

    if ($categoriesString !== "" && $categoriesString !== null)
    {
        $categories = explode(",", $categoriesString);
        $categoryOutput = "";
        $categoryOutput .= '<dl class="rex-form-group form-group">';
        $categoryOutput .= '<dt>';
        $categoryOutput .= '<label class = "control-label" for = "rex-calendar-add-description">' . $addon->i18n("rex_ec_category") . '</label>';
        $categoryOutput .= '</dt>';
        $categoryOutput .= '<dd>';
        $categoryOutput .= '<select name="category" class="form-control">';
        $categoryOutput .= '<option selected="selected "value=""></option>';

        for ($i = 0; $i < count($categories); $i++)
        {
            $category = $categories[$i];

            $categoryOutput .= '<option value="' . $category . '">' . $category . '</option>';
        }

        $categoryOutput .= '</select>';
        $categoryOutput .= '</dd>';
        $categoryOutput .= '</dl>';

        return $categoryOutput;
    }
    else
    {
        return;
    }
}

//get entry data
function getDataSet($eventThisDay)
{
    $content = '';
    $content .= 'data-id="' . $eventThisDay["id"] . '"';
    $content .= 'data-startdate="' . $eventThisDay["startdate"] . '"';
    $content .= 'data-enddate="' . $eventThisDay["enddate"] . '"';
    $content .= 'data-day="' . $eventThisDay["day"] . '"';
    $content .= 'data-month="' . $eventThisDay["month"] . '"';
    $content .= 'data-year="' . $eventThisDay["year"] . '"';
    $content .= 'data-title="' . $eventThisDay["title"] . '"';
    $content .= 'data-starttime="' . $eventThisDay["starttime"] . '"';
    $content .= 'data-endtime="' . $eventThisDay["endtime"] . '"';
    $content .= 'data-description="' . $eventThisDay["description"] . '"';
    $content .= 'data-image="' . $eventThisDay["image"] . '"';
    $content .= 'data-category="' . $eventThisDay["category"] . '"';

    return $content;
}

//get name of month by number
function getMonthName($monthNumber)
{
    $dateObj = DateTime::createFromFormat('!m', $monthNumber);
    return utf8_encode(strftime("%B", strtotime($dateObj->format('Y-m-d'))));
}

//get name of day by number
function getDayName($dayNumber)
{
    $sun = date('Y-m-d', strtotime('First Sunday of ' . date('Y-m-d')));
    $mon = date('Y-m-d', strtotime('First Monday of ' . date('Y-m-d')));
    $tue = date('Y-m-d', strtotime('First Tuesday of ' . date('Y-m-d')));
    $wed = date('Y-m-d', strtotime('First Wednesday of ' . date('Y-m-d')));
    $thu = date('Y-m-d', strtotime('First Thursday of ' . date('Y-m-d')));
    $fri = date('Y-m-d', strtotime('First Friday of ' . date('Y-m-d')));
    $sat = date('Y-m-d', strtotime('First Saturday of ' . date('Y-m-d')));

    switch ($dayNumber)
    {
        case 1:
            return utf8_encode(strftime("%a", strtotime($mon)));
            break;
        case 2:
            return utf8_encode(strftime("%a", strtotime($tue)));
            break;
        case 3:
            return utf8_encode(strftime("%a", strtotime($wed)));
            break;
        case 4:
            return utf8_encode(strftime("%a", strtotime($thu)));
            break;
        case 5:
            return utf8_encode(strftime("%a", strtotime($fri)));
            break;
        case 6:
            return utf8_encode(strftime("%a", strtotime($sat)));
            break;
        case 7:
            return utf8_encode(strftime("%a", strtotime($sun)));
            break;
    }
}


//show calender
function showMonth($month = null, $year = null, $daysArray)
{
    $today = date('Y-n-d');

    $calendar = '';
    if ($month == null || $year == null)
    {
        $month = date('m');
        $year = date('Y');
    }
    $date = mktime(12, 0, 0, $month, 1, $year);

    $daysInMonth = date("t", $date);
    $offset = date("w", $date) - 1;
    
    $rows = 1;
    $prev_month = $month - 1;
    $prev_year = $year;
    if ($month == 1)
    {
        $prev_month = 12;
        $prev_year = $year - 1;
    }

    $next_month = $month + 1;
    $next_year = $year;
    if ($month == 12)
    {
        $next_month = 1;
        $next_year = $year + 1;
    }
    $calendar .= "<div class='panel-heading text-center'><div class='row'><div class='col-md-3 col-xs-4'><a class='ajax-navigation btn btn-default btn-sm' href='" . rex_url::currentBackendPage() . "&month=" . $prev_month . "&year=" . $prev_year . "'><i class='fa fa-arrow-left' aria-hidden='true'></i></a></div><div class='col-md-6 col-xs-4'><strong><h3>" . strftime("%B %Y", $date) . "</h3></strong></div>";
    $calendar .= "<div class='col-md-3 col-xs-4 '><a class='ajax-navigation btn btn-default btn-sm' href='" . rex_url::currentBackendPage() . "&month=" . $next_month . "&year=" . $next_year . "'><i class='fa fa-arrow-right' aria-hidden='true'></i></a></div></div></div>";
    $calendar .= "<table class='table table-bordered'>";
    $calendar .= "<tr><th>" . getDayName(1) . "</th><th>" . getDayName(2) . "</th><th>" . getDayName(3) . "</th><th>" . getDayName(4) . "</th><th>" . getDayName(5) . "</th><th>" . getDayName(6) . "</th><th>" . getDayName(7) . "</th></tr>";
    $calendar .= "<tr>";
    for ($i = 1; $i <= $offset; $i++)
    {
        $calendar .= "<td></td>";
    }
    for ($day = 1; $day <= $daysInMonth; $day++)
    {       
        $theDay = $year . "-" . intval($month) . "-" . intval($day);
        $hasEventClass = "";
        $hasEvent = false;
        $isTodayClass = "";
        $isSatClass = (($day + $offset + 1) % 7) ? "" : " sat";
        $isSunClass = (($day + $offset) % 7) ? "" : " sun";

        if (($day + $offset - 1) % 7 == 0 && $day != 1)
        {
            $calendar .= "</tr><tr>";
            $rows++;
        }

        $eventsThisDay = 0;
        if (array_key_exists(intval($day), $daysArray))
        {
            $hasEventClass = " event";
            $hasEvent = true;

            $hasEventSql = rex_sql::factory();
            $hasEventSql->setTable(rex::getTablePrefix() . 'event_calendar');
            $hasEventSql->setWhere('day=' . $day . ' AND month=' . $month . ' AND year=' . $year);
            $hasEventSql->select('*');
            $eventArray = $hasEventSql->getArray();

            $eventsThisDay = count($eventArray);
        }

        if ($today === $theDay)
        {
            $isTodayClass = " today";
        }

        $editEventClass = "";

        if ($eventsThisDay === 1)
        {
            $editEventClass = "edit";
            $calendar .= "<td class='" . $hasEventClass . $isTodayClass . $isSunClass . $isSatClass ." edit-event' " . getDataSet($eventArray[0]) . ">";
        }
        elseif ($eventsThisDay > 1)
        {
            $editEventClass = "popover-edit";
            $popOverContent = "";
            for ($j = 0; $j < $eventsThisDay; $j++)
            {
                $eventThisDay = $eventArray[$j];
                $shortenTitle = (strlen($eventThisDay["title"]) > 50) ? substr($eventThisDay["title"], 0, 50) . '...' : $eventThisDay["title"];

                $popOverContent .= '<div class="edit-multiple"';
                $popOverContent .= getDataSet($eventThisDay);
                $popOverContent .= '><i class="fa fa-edit" aria-hidden="true"></i> ';
                $popOverContent .= $shortenTitle;
                $popOverContent .= '</div>';
            }

            $calendar .= "<td class='" . $hasEventClass . $isTodayClass . $isSunClass . $isSatClass . " pop' data-selector='form' data-toggle='popover' data-content='" . $popOverContent . "' data-html='true' data-trigger='click' data-placement='top'>";
        }
        else
        {
            $calendar .= "<td class='" . $hasEventClass . $isTodayClass . $isSunClass . $isSatClass . "'>";
        }
        $calendar .= "<form action='" . rex_url::currentBackendPage() . "&month=" . intval($month) . "&year=" . $year . "'>";
        $calendar .= $day;
        $calendar .= "<input type='hidden' name='day' value='" . intval($day) . "'>";
        $calendar .= "<input type='hidden' name='month' value='" . intval($month) . "'>";
        $calendar .= "<input type='hidden' name='year' value='" . $year . "'>";
        $calendar .= "<input type='hidden' name='full-date' value='" . utf8_encode(strftime("%A %d. %B %Y", strtotime($theDay))) . "'>";
        
        if ($hasEvent)
        {
            $calendar .= "<i class='fa fa-edit " . $editEventClass . "' aria-hidden='true'></i>";
        }
        $calendar .= "<i class='fa fa-plus-circle add' aria-hidden='true'></i>";
        $calendar .= "</form>";
        $calendar .= "</td>";
    }
    while (($day + $offset) <= $rows * 7)
    {
        $calendar .= "<td></td>";
        $day++;
    }
    $calendar .= "</tr>";
    $calendar .= "</table>";
    return $calendar;
}
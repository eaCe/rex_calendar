<?php

include_once rex_path::addon("rex_calendar", "lib/befunctions.php");

$useMarkitup = "";
$viewdYear;
$viewdMonth;

if ($this->getConfig('rex_calendar_settings_markitup') == "1")
{
    $useMarkitup = " markitupEditor-calendar";
}

if (rex_get("year"))
{
    $viewdYear = rex_get("year");
}
else
{
    $viewdYear = date('Y');
}

if (rex_get("month"))
{
    $viewdMonth = rex_get("month");
}
else
{
    $viewdMonth = date('n');
}

$eventsql = rex_sql::factory();
$eventsql->setTable(rex::getTablePrefix() . 'event_calendar');
$eventsql->setWhere('year=' . $viewdYear . ' AND month=' . $viewdMonth);
$eventsql->select('*');
$eventSqlArray = $eventsql->getArray();
$selectedDays = array_map(create_function('$ar', 'return $ar["day"];'), $eventSqlArray);
$daysArray = array_flip($selectedDays);
?>

<div id="calendar">
    <div>
        <div class="label label-primary">Schnellnavigation</div><br>
        <div class="btn-group" role="group" aria-label="">
            <?php            
            if(($viewdMonth . "." . $viewdYear) !== (date('n') . "." . date('Y')))
            {
            ?>
            <div class="btn-group" role="group">
                <a href="<?= rex_url::currentBackendPage() ?>&month=<?= date('n') ?>&year=<?= date('Y') ?>" class="btn btn-default">Gehe zu Heute</a>
            </div>
            <?php    
            }
            ?>
            <div class="btn-group" role="group">
                <button class="btn btn-default dropdown-toggle" type="button" id="monthDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Monat: <?= getMonthName($viewdMonth) ?>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="monthDropdown">
                    <li><a href="<?= rex_url::currentBackendPage() ?>&month=1&year=<?= $viewdYear ?>"><?= getMonthName(1); ?> <?= $viewdYear ?></a></li>
                    <li><a href="<?= rex_url::currentBackendPage() ?>&month=2&year=<?= $viewdYear ?>"><?= getMonthName(2); ?> <?= $viewdYear ?></a></li>
                    <li><a href="<?= rex_url::currentBackendPage() ?>&month=3&year=<?= $viewdYear ?>"><?= getMonthName(3); ?> <?= $viewdYear ?></a></li>
                    <li><a href="<?= rex_url::currentBackendPage() ?>&month=4&year=<?= $viewdYear ?>"><?= getMonthName(4); ?> <?= $viewdYear ?></a></li>
                    <li><a href="<?= rex_url::currentBackendPage() ?>&month=5&year=<?= $viewdYear ?>"><?= getMonthName(5); ?> <?= $viewdYear ?></a></li>
                    <li><a href="<?= rex_url::currentBackendPage() ?>&month=6&year=<?= $viewdYear ?>"><?= getMonthName(6); ?> <?= $viewdYear ?></a></li>
                    <li><a href="<?= rex_url::currentBackendPage() ?>&month=7&year=<?= $viewdYear ?>"><?= getMonthName(7); ?> <?= $viewdYear ?></a></li>
                    <li><a href="<?= rex_url::currentBackendPage() ?>&month=8&year=<?= $viewdYear ?>"><?= getMonthName(8); ?> <?= $viewdYear ?></a></li>
                    <li><a href="<?= rex_url::currentBackendPage() ?>&month=9&year=<?= $viewdYear ?>"><?= getMonthName(9); ?> <?= $viewdYear ?></a></li>
                    <li><a href="<?= rex_url::currentBackendPage() ?>&month=10&year=<?= $viewdYear ?>"><?= getMonthName(10); ?> <?= $viewdYear ?></a></li>
                    <li><a href="<?= rex_url::currentBackendPage() ?>&month=11&year=<?= $viewdYear ?>"><?= getMonthName(11); ?> <?= $viewdYear ?></a></li>
                    <li><a href="<?= rex_url::currentBackendPage() ?>&month=12&year=<?= $viewdYear ?>"><?= getMonthName(12); ?> <?= $viewdYear ?></a></li>
                </ul>
            </div>
            <div class="btn-group" role="group">
                <button class="btn btn-default dropdown-toggle" type="button" id="yearDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Jahr: <?= $viewdYear ?>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="yearDropdown">
                    <li><a href="<?= rex_url::currentBackendPage() ?>&month=<?= $viewdMonth ?>&year=<?= $viewdYear - 2 ?>"><?= $viewdYear - 2 ?></a></li>
                    <li><a href="<?= rex_url::currentBackendPage() ?>&month=<?= $viewdMonth ?>&year=<?= $viewdYear - 1 ?>"><?= $viewdYear - 1 ?></a></li>
                    <li class="disabled"><a href="#"><?= $viewdYear ?></a></li>
                    <li><a href="<?= rex_url::currentBackendPage() ?>&month=<?= $viewdMonth ?>&year=<?= $viewdYear + 1 ?>"><?= $viewdYear + 1 ?></a></li>
                    <li><a href="<?= rex_url::currentBackendPage() ?>&month=<?= $viewdMonth ?>&year=<?= $viewdYear + 2 ?>"><?= $viewdYear + 2 ?></a></li>
                </ul>
            </div>
        </div>
    </div>
    <hr>

    <?php    
    if (rex_get("month") && rex_get("year"))
    {
        $newMonth = rex_get("month");
        $newYear = rex_get("year");

        echo showMonth($newMonth, $newYear, $daysArray);
    }
    else
    {
        echo showMonth(date('m'), date('Y'), $daysArray);
    }
    ?>

</div>

<!--edit modal-->
<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Termin bearbeiten</h4>
                <span class="label label-success full-date"></span>
            </div>
            <div class="modal-body">
                <div id="missing-values" class="alert alert-danger" role="alert" style="display: none">
                    Bitte einen Titel angeben.
                </div>
                <form>
                    <input type="hidden" name="editevent" value="true" />
                    <input type="hidden" id="rex-calendar-edit-id" name="id" value="" />
                    <input type="hidden" id="rex-calendar-edit-day" name="day" value="" />
                    <input type="hidden" id="rex-calendar-edit-month" name="month" value="" />
                    <input type="hidden" id="rex-calendar-edit-year" name="year" value="" />
                    <fieldset>
                        <dl class="rex-form-group form-group">
                            <dt>
                                <label class="control-label" for="rex-calendar-edit-title">Titel</label>
                            </dt>
                            <dd>
                                <input id="rex-calendar-edit-title" class="form-control" name="title" value="" type="text">
                            </dd>
                        </dl>
                        <dl class="rex-form-group form-group">
                            <dt>
                                <label class="control-label" for="rex-event-calendar-edit-starttime">Zeit</label>
                            </dt>
                            <dd>         
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="small">Start</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span class="small">Ende</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input class="form-control timepicker" name="starttime" value="" id="rex-event-calendar-edit-starttime" type="text" />
                                            <span class="input-group-btn">
                                                <a href="#" class="btn btn-popup show-timepicker" data-picker="#rex-event-calendar-edit-starttime"><i class='fa fa-clock-o' aria-hidden='true'></i></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input class="form-control timepicker" name="endtime" value="" id="rex-event-calendar-edit-endtime" type="text" />
                                            <span class="input-group-btn">
                                                <a href="#" class="btn btn-popup show-timepicker" data-picker="#rex-event-calendar-edit-endtime"><i class='fa fa-clock-o' aria-hidden='true'></i></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                            </dd>
                        </dl>
                        <dl class="rex-form-group form-group">
                            <dt>
                                <label class="control-label" for="rex-calendar-edit-description">Beschreibung</label>
                            </dt>
                            <dd>
                                <textarea id="rex-calendar-edit-description" class="form-control<?= $useMarkitup ?>" rows="6" name="description"></textarea>
                            </dd>
                        </dl>
                        <dl class="rex-form-group form-group">
                            <dt>
                                <label class="control-label" for="rex_event_calendar_edit_image">Bild</label>
                            </dt>
                            <dd>
                                <div class="input-group">
                                    <input class="form-control" name="image" value="" id="rex_event_calendar_edit_image" readonly="readonly" type="text" />
                                    <span class="input-group-btn">
                                        <a href="#" class="btn btn-popup select-media" data-input="rex_event_calendar_edit_image" title="Bild auswählen"><i class='fa fa-image' aria-hidden='true'></i></a>
                                    </span>
                                </div>
                            </dd>
                        </dl>
                        <?= getCategories() ?>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-4 text-left">
                        <button type="button" class="btn btn-danger" id="delete-edit-entry"><i class='fa fa-trash' aria-hidden='true'></i> Löschen</button>
                    </div>
                    <div class="col-md-8">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban" aria-hidden="true"></i> Abbrechen</button>
                        <button type="button" class="btn btn-success" id="save-edit-entry"><i class="fa fa-floppy-o" aria-hidden="true"></i> Speichern</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--add modal-->
<div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Termin hinzufügen</h4>
                <span class="label label-success full-date"></span>
            </div>
            <div class="modal-body">
                <div id="missing-values" class="alert alert-danger" role="alert" style="display: none">
                    Bitte einen Titel angeben.
                </div>
                <form>
                    <input type="hidden" name="addevent" value="true" />
                    <input type="hidden" id="rex-calendar-add-day" name="day" value="" />
                    <input type="hidden" id="rex-calendar-add-month" name="month" value="" />
                    <input type="hidden" id="rex-calendar-add-year" name="year" value="" />
                    <fieldset>
                        <dl class="rex-form-group form-group">
                            <dt>
                                <label class="control-label" for="rex-calendar-add-title">Titel</label>
                            </dt>
                            <dd>
                                <input id="rex-calendar-add-title" class="form-control" name="title" value="" type="text">
                            </dd>
                        </dl>
                        <dl class="rex-form-group form-group">
                            <dt>
                                <label class="control-label" for="rex-event-calendar-add-starttime">Zeit</label>
                            </dt>
                            <dd>         
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="small">Start</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span class="small">Ende</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input class="form-control timepicker" name="starttime" value="" id="rex-event-calendar-add-starttime" type="text" />
                                            <span class="input-group-btn">
                                                <a href="#" class="btn btn-popup show-timepicker" data-picker="#rex-event-calendar-add-starttime"><i class='fa fa-clock-o' aria-hidden='true'></i></a>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <input class="form-control timepicker" name="endtime" value="" id="rex-event-calendar-add-endtime" type="text" />
                                            <span class="input-group-btn">
                                                <a href="#" class="btn btn-popup show-timepicker" data-picker="#rex-event-calendar-add-endtime"><i class='fa fa-clock-o' aria-hidden='true'></i></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                            </dd>
                        </dl>
                        <dl class="rex-form-group form-group">
                            <dt>
                                <label class="control-label" for="rex-calendar-add-description">Beschreibung</label>
                            </dt>
                            <dd>
                                <textarea id="rex-calendar-add-description" class="form-control<?= $useMarkitup ?>" rows="6" name="description"></textarea>
                            </dd>
                        </dl>
                        <dl class="rex-form-group form-group">
                            <dt>
                                <label class="control-label" for="rex-event-calendar-add-image">Bild</label>
                            </dt>
                            <dd>
                                <div class="input-group">
                                    <input class="form-control" name="image" value="" id="rex-event-calendar-add-image" readonly="readonly" type="text" />
                                    <span class="input-group-btn">
                                        <a href="#" class="btn btn-popup select-media" data-input="rex-event-calendar-add-image" title="Bild auswählen"><i class='fa fa-image' aria-hidden='true'></i></a>
                                    </span>
                                </div>
                            </dd>
                        </dl>
                        <?= getCategories() ?>
                    </fieldset>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban" aria-hidden="true"></i> Abbrechen</button>
                <button type="button" class="btn btn-success" id="save-add-entry"><i class="fa fa-floppy-o" aria-hidden="true"></i> Speichern</button>
            </div>
        </div>
    </div>
</div>

<!--confirm modal-->
<div id="confirm" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                Möchtest du den Eintrag <strong class="delete-title"></strong> wirklich löschen?
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-danger" id="delete-entry">Löschen</button>
                <button type="button" data-dismiss="modal" class="btn btn-default">Abbrechen</button>
            </div>
        </div>
    </div>
</div>

<script>
    var calendarAjaxUrl = "<?= rex_url::currentBackendPage() ?>";

    jQuery(document).ready(function ()
    {
        //prepend bootstrap modal mobile behaviour..
        var jBody = jQuery("body");

        var jConfirmModal = jQuery("#confirm").detach();
        jBody.append(jConfirmModal);

        var jEditModal = jQuery("#edit-modal").detach();
        jBody.append(jEditModal);

        var jAddModal = jQuery("#add-modal").detach();
        jBody.append(jAddModal);

        new redaxo.Calendar();
    });
</script>
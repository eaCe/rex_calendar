<?php
$infoBubble = "";
$addon = rex_addon::get('rex_calendar');

if (rex_post('config-submit', 'boolean'))
{
    $addon->setConfig(rex_post('config', [
        ['rex_calendar_settings_categories', 'string'],
        ['rex_calendar_settings_markitup', 'string']
    ]));

    $infoBubble .= rex_view::info($addon->i18n("rex_ec_settings_saved"));
}

//    var_dump($this->getConfig('rex_calendar_settings_categories'));
//var_dump(explode(",", $this->getConfig('rex_calendar_settings_categories')));
?>

<div id="calendar">
    <section class="rex-page-section">
        <div class="panel panel-edit">
            <header class="panel-heading">
                <div class="panel-title"><?= $addon->i18n("rex_ec_title") ?></div>
            </header>
            <div class="panel-body">
                <div id="calendar-settings">
                    <?= $infoBubble ?>
                    <div class="container">
                        <div class="row">
                            <div class="col-md-8">
                                <form action="<?= rex_url::currentBackendPage() ?>" method="post" id="rex_calendar-settings">
                                    <fieldset>
                                        <dl class="rex-form-group form-group">
                                            <dt>
                                            <label for="rex_calendar_settings_categories"><?= $addon->i18n("rex_ec_settings_categories") ?>:</label><br>
                                            </dt>
                                            <dd>
                                                <!--<span class="label label-info">Kommaseperiert</span>-->
                                                <input class="rex-form-text form-control" type="text" id="rex_calendar_settings_categories" name="config[rex_calendar_settings_categories]" value="<?= $addon->getConfig('rex_calendar_settings_categories') ?>"/>
                                            </dd>
                                        </dl>
                                        <dl class="rex-form-group form-group">
                                            <dt>
                                                MarkitUp:
                                            </dt>
                                            <dd>
                                                <?php
                                                $useMarkitup = "";

                                                if ($this->getConfig('rex_calendar_settings_markitup') == "1")
                                                {
                                                    $useMarkitup = 'checked="checked"';

                                                    if (!rex_markitup::profileExists("calendar"))
                                                    {
                                                        echo '<div class="alert alert-warning" role="alert">' . $addon->i18n("rex_ec_settings_missing_profile") . '</div>';
                                                    }
                                                }
                                                ?>
                                                <!--<span class="label label-info">Kommaseperiert</span>-->
                                                <input class="rex-form-checkbox" type="checkbox" id="rex_calendar_settings_markitup" name="config[rex_calendar_settings_markitup]" value="1" <?= $useMarkitup ?>/>
                                                <label for="rex_calendar_settings_markitup"><span></span> <?= $addon->i18n("rex_ec_settings_markitup") ?></label>
                                            </dd>
                                        </dl>
                                        <dl class="rex-form-group form-group">
                                            <dd>
                                                <button class="btn btn-save" type="submit" name="config-submit" value="1" title="<?= $addon->i18n("rex_ec_settings_save") ?>"><?= $addon->i18n("rex_ec_settings_save") ?></button>
                                            </dd>
                                        </dl>
                                    </fieldset>    
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script>
    var jSaveInfo = jQuery("#calendar-settings .alert-info");

    window.setTimeout(function ()
    {
        jSaveInfo.slideUp();
    }, 2000);
</script>
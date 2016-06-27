<?php

if (rex::isBackend())
{
    
    rex_view::addJSFile($this->getAssetsUrl('js/vendor/jquery.timepicker.min.js'));
    rex_view::addJSFile($this->getAssetsUrl('js/calendar.js'));

    rex_extension::register('PACKAGES_INCLUDED', function ()
    {
        if ($this->getProperty('compile'))
        {
            $compiler = new rex_scss_compiler();

            $scss_files = rex_extension::registerPoint(new rex_extension_point('BE_STYLE_SCSS_FILES', [$this->getPath('scss/master.scss')]));
            $compiler->setScssFile($scss_files);
            $compiler->setCssFile($this->getPath('assets/css/styles.css'));
            $compiler->compile();
            rex_file::copy($this->getPath('assets/css/styles.css'), $this->getAssetsPath('css/styles.css'));
        }
    });

    rex_view::addCssFile($this->getAssetsUrl('css/styles.css'));
}

var redaxo = redaxo || {};
redaxo.Calendar = redaxo.Calendar || (function (jQuery)
{
    var jRexLoader;

    var Calendar = function ()
    {
        var jCalendarWrapper = jQuery('#calendar');
        var jCalendar = jCalendarWrapper.find('table');
        var jAddEntryTrigger = jCalendar.find(".add");
        var jEditEntryTrigger = jCalendar.find(".edit");
        var jSelectMedia = jQuery(".select-media");
        var jShowTimepicker = jQuery(".show-timepicker");
        var jPopoverEdit = jQuery('.popover-edit');

        jQuery.extend(true, this,
        {
            jCalendarWrapper: jCalendarWrapper,
            jCalendar: jCalendar,
            jAddEntryTrigger: jAddEntryTrigger,
            jEditEntryTrigger: jEditEntryTrigger,
            jSelectMedia: jSelectMedia,
            jShowTimepicker: jShowTimepicker,
            jPopoverEdit: jPopoverEdit
        });

        initView.call(this);
    };

    var getRexLoader = function getRexLoader()
    {
        var that = this;

        var checkExist = setInterval(function ()
        {
            if (jQuery('#rex-js-ajax-loader').length)
            {
                jRexLoader = jQuery('#rex-js-ajax-loader');
                attachEventHandler.call(that);
                clearInterval(checkExist);
            }
        }, 100);
    };


    var initView = function initView()
    {
        getRexLoader.call(this);
        initCalendar.call(this);
    };


    // init calendar view
    var initCalendar = function initCalendar()
    {
    };

    var calendarAjax = function calendarAjax(postData, successCallback)
    {
        jQuery.ajax(
        {
            method: "POST",
            url: calendarAjaxUrl,
            data: postData,
            beforeSend: function (xhr)
            {
                jRexLoader.addClass('rex-visible');
            },
            success: function (data, textStatus, jqXHR)
            {
                successCallback();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                jRexLoader.removeClass('rex-visible');
                console.error("#ERROR: jqXHR, textStatus, errorThrown", jqXHR, textStatus, errorThrown);
            }
        });
    };

    var timePickerSettings = {
        "show2400": true,
        "timeFormat": "H:i",
        "scrollDefault": "15:00",
        "disableTextInput": true
    };

    // add calendar entry
    var addEntryHandler = function addEntryHandler(event)
    {
        event.preventDefault();

        var jThis = jQuery(this);
        var jThisForm = jThis.parent("form");
        var day = jThisForm.find("input[name=day]").val();
        var month = jThisForm.find("input[name=month]").val();
        var year = jThisForm.find("input[name=year]").val();
        var fullDate = jThisForm.find("input[name=full-date]").val();
        var jAddEntryModal = jQuery("#add-modal");
        var jFullDate = jAddEntryModal.find(".full-date");
        var jForm = jAddEntryModal.find("form");
        var jDay = jForm.find("#rex-calendar-add-day");
        var jMonth = jForm.find("#rex-calendar-add-month");
        var jYear = jForm.find("#rex-calendar-add-year");
        var jTitle = jForm.find("#rex-calendar-add-title");
        var jDescription = jForm.find("#rex-calendar-add-description");
        var jCategoryName = jForm.find("#rex-calendar-add-category");
        var jMedia = jForm.find("#rex-event-calendar-add-image");
        var jStartTime = jForm.find("#rex-event-calendar-add-starttime");
        var jEndTime = jForm.find("#rex-event-calendar-add-endtime");
        var jErrorContainer = jAddEntryModal.find("#missing-values");

        jQuery(".popover").popover("destroy");

        jFullDate.text(fullDate);
        jTitle.val("");
        jDescription.val("");
        jCategoryName.val("");
        jStartTime.val("");
        jEndTime.val("");
        jMedia.val("");

        jDay.val(day);
        jMonth.val(month);
        jYear.val(year);

        jErrorContainer.hide();

        jTitle.val("");
        jDescription.val("");

        var checkForm = function checkForm(event)
        {
            var formData = jForm.serialize();

            if (jTitle.val() === "")
            {
                jErrorContainer.slideDown();
                jAddEntryModal.modal().one('click', '#save-add-entry', checkForm);
            }
            else
            {
                jErrorContainer.slideUp();

                var postSuccessCallback = function postSuccessCallback()
                {
                    jAddEntryModal.modal('hide');
                    window.location.reload();
                };

                calendarAjax(formData, postSuccessCallback);
            }
        };

        jAddEntryModal.modal().one('click', '#save-add-entry', checkForm);

        jAddEntryModal.one("shown.bs.modal", function ()
        {
            jStartTime.timepicker(timePickerSettings);
            jEndTime.timepicker(timePickerSettings);
        });

        jAddEntryModal.one("hide.bs.modal", function ()
        {
            jStartTime.timepicker("remove");
            jEndTime.timepicker("remove");
        });
    };
    
    
    var deleteEntryHandler = function deleteEntryHandler(event, data)
    {
        event.preventDefault();

        var jThis = jQuery(this);
        var id = data.id;
        var title = data.title;
        var jDeleteEntryModal = jQuery('#confirm');
        var jConfirmItemTitle = jDeleteEntryModal.find(".delete-title");

        jConfirmItemTitle.text(title);

        var postSuccessCallback = function postSuccessCallback()
        {
            window.location.reload();
        };

        jDeleteEntryModal.modal().one('click', '#delete-entry', function (event)
        {
            calendarAjax({"deleteevent": "true", "id": id}, postSuccessCallback);
        });
    };


    // edit kanban entry
    var editEntryHandler = function editEntryHandler(event, multiple)
    {
        event.preventDefault();
        var jThis = jQuery(this);
        var jParent = jThis.closest("td");
        var jEditEntryModal = jQuery("#edit-modal");
        var jForm = jEditEntryModal.find("form");
        var jId = jForm.find("#rex-calendar-edit-id");
        var jTitle = jForm.find("#rex-calendar-edit-title");
        var jDescription = jForm.find("#rex-calendar-edit-description");
        var jCategory = jForm.find("select[name=category]");
        var jDay = jForm.find("#rex-calendar-edit-day");
        var jMonth = jForm.find("#rex-calendar-edit-month");
        var jYear = jForm.find("#rex-calendar-edit-year");
        var jStartTime = jForm.find("#rex-event-calendar-edit-starttime");
        var jEndTime = jForm.find("#rex-event-calendar-edit-endtime");
        var jImage = jForm.find("#rex_event_calendar_edit_image");
        var jErrorContainer = jQuery("#missing-values");
        var fullDate = jParent.find("input[name=full-date]").val();
        var jFullDate = jEditEntryModal.find(".full-date");
        var data;
        
        if(multiple)
        {
            data = jThis.data();
        }
        else
        {
            data = jParent.data();
        }
        
        jFullDate.text(fullDate);
        
        var id = data.id;
        var title = data.title;
        var description = data.description;
        var day = data.day;
        var month = data.month;
        var year = data.year;
        var startdate = data.startdate;
        var enddate = data.enddate;
        var image = data.image;
        var starttime = data.starttime;
        var endtime = data.endtime;
        var category = data.category;

        jQuery(".popover").popover("destroy");

        jErrorContainer.hide();
        jId.val(id);
        jTitle.val(title);
        jDescription.val(description);
        jCategory.val(category);
        jStartTime.val(starttime);
        jEndTime.val(endtime);
        jDay.val(day);
        jMonth.val(month);
        jYear.val(year);
        jImage.val(image);
        
        var checkForm = function checkForm(event)
        {
            var formData = jForm.serialize();

            if (jTitle.val() === "" || jCategory.val() === "")
            {
                jErrorContainer.slideDown();
                jEditEntryModal.modal().one('click', '#save-edit-entry', checkForm);
            }
            else
            {
                jErrorContainer.slideUp();

                var postSuccessCallback = function postSuccessCallback()
                {
                    jEditEntryModal.modal('hide');
                    window.location.reload();
                };

                calendarAjax(formData, postSuccessCallback);
            }
        };

        jEditEntryModal.modal().one('click', '#save-edit-entry', checkForm);
        jEditEntryModal.modal().one('click', '#delete-edit-entry', function (event)
        {
            jEditEntryModal.modal("hide");
            deleteEntryHandler.call(this, event, data);   
        });
        
        jEditEntryModal.one("shown.bs.modal", function ()
        {
            jStartTime.timepicker(timePickerSettings);
            jEndTime.timepicker(timePickerSettings);
        });

        jEditEntryModal.one("hide.bs.modal", function ()
        {
            jStartTime.timepicker("remove");
            jEndTime.timepicker("remove");
        });
    };

    var popoverEditHandler = function popoverEditHandler()
    {
        var that = this;
        var jThis = jQuery(this);
        var jParent = jThis.parents(".pop");
        var jPopOver = jParent.popover();

        jPopOver.one('inserted.bs.popover', function (event)
        {
            var jEdit = jQuery(".popover").find(".edit-multiple");
            
            jEdit.on("click", function (event)
            {
                editEntryHandler.call(this, event, 1);
            });
        });
    };
    
    var selectMediaHandler = function selectMediaHandler()
    {
        var jThis = jQuery(this);
        var input = jThis.data("input");

        openMediaPool(input);
    };

    var showTimepickerHandler = function showTimepickerHandler()
    {
        var jThis = jQuery(this);
        var input = jThis.data("picker");
        var jInput = jQuery(input);

        jInput.timepicker("show");
    };

    var attachEventHandler = function attachEventHandler()
    {
        this.jAddEntryTrigger.on("click", addEntryHandler);
        this.jSelectMedia.on("click", selectMediaHandler);
        this.jShowTimepicker.on("click", showTimepickerHandler);
        this.jPopoverEdit.on("click", popoverEditHandler);
        this.jEditEntryTrigger.on("click", function (event)
        {
            editEntryHandler.call(this, event, 0);
        });
    };

    return Calendar;

}(jQuery));
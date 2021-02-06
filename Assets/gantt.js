KB.on("dom.ready", function () {
    if (KB.exists("#gantt-chart")) {
        console.log("load gantt chart");

        var tasks = jQuery("#gantt-chart").data("records");
        console.log(tasks);
        var gantt = new Gantt("#gantt-chart", initGanttTasks(tasks), {
            view_modes: ["Day", "Week", "Month"],
            view_mode: "Week",
            date_format: "YYYY-MM-DD",
            custom_popup_html: function (task) {
                function format_date(oDate) {
                    if (oDate.getHours() === 0 && oDate.getMinutes() === 0) {
                        return oDate.toLocaleDateString();
                    } else {
                        return oDate.toLocaleString();
                    }
                }
                task.start_date = format_date(task._start);
                task.end_date = format_date(task._end);
                // TODO: add more info and translate text
                var html = jQuery('#gantt-popup-template').html();
                var matches = html.match(/\$\{([\w-_\.]*)\}/g);
                if (matches) {
                    jQuery.each(matches, function (i, taskValue) {
                        var _taskValue = taskValue.slice(2, -1).split('.');
                        if (_taskValue.length > 1 && task.hasOwnProperty(_taskValue[1])) {
                            html = html.replace(taskValue, task[_taskValue[1]]);
                        } else if (_taskValue.length == 1 && task.hasOwnProperty(_taskValue[0])) {
                            html = html.replace(taskValue, task[_taskValue[0]]);
                        } else {
                            var $div = jQuery('<div/>');
                            $div.append(html);
                            ($el = jQuery('td:contains("' + taskValue + '")', jQuery($div))) && $el.parent() && $el.parent().remove();
                            html = $div.html();
                        }
                    });
                }
                return html;
            },
            language: jQuery("html").attr("lang"),
            on_click: function (task) {
                //console.log(task);
                window.open(task.url);
            },
            on_date_change: function (task, start, end) {
                console.log(task, start, end);

                //get save url
                var sUrl = jQuery("#gantt-chart").data("save-url");

                var oValues = {
                    id: task.id,
                    start: start,
                    end: end,
                };

                $.ajax({
                    cache: false,
                    url: sUrl,
                    contentType: "application/json",
                    type: "POST",
                    processData: false,
                    data: JSON.stringify(oValues),
                });
            },
        });

        // handler to toggle task on first colum
        jQuery(document).on("change", "#hide-first-column", function (t) {
            if (typeof gantt === "undefined") {
                return;
            }

            var columIdToHide = '0'; // coz localStorage accepts strings
            if (jQuery(this).is(':checked')) {
                columIdToHide = jQuery(this).val().toString();
            }

            localStorage.setItem("gantt_hide_first_colum", columIdToHide);

            gantt.refresh(initGanttTasks(tasks, columIdToHide));
        })

        // remove resize handles for the moment
        /*var handles = document.querySelectorAll("#gantt-chart .handle-group");
            for (var i = 0; i < handles.length; i++) {
                handles[i].remove();
            }*/

        console.log("gantt chart loaded");

        KB.onClick("#gantt-mode-day", function (element) {
            gantt.change_view_mode("Day");
            KB.find("#gantt-mode-week").removeClass("active");
            KB.find("#gantt-mode-month").removeClass("active");
            KB.dom(element.srcElement).addClass("active");
        });

        KB.onClick("#gantt-mode-week", function (element) {
            gantt.change_view_mode("Week");
            KB.find("#gantt-mode-month").removeClass("active");
            KB.find("#gantt-mode-day").removeClass("active");
            KB.dom(element.srcElement).addClass("active");
        });

        KB.onClick("#gantt-mode-month", function (element) {
            gantt.change_view_mode("Month");
            KB.find("#gantt-mode-week").removeClass("active");
            KB.find("#gantt-mode-day").removeClass("active");
            KB.dom(element.srcElement).addClass("active");
        });
    }
});

function initGanttTasks(_tasks, columIdToHide) {
    var tasksData = [];
    var columnToHide = ((typeof columIdToHide !== "undefined") ? columIdToHide : (jQuery.isNumeric(localStorage.getItem("gantt_hide_first_colum")) ? localStorage.getItem("gantt_hide_first_colum") : '0'));

    if (columnToHide && parseInt(columnToHide) > 0) {
        jQuery('#hide-first-column').attr('checked', 'checked');
        jQuery.each(_tasks, function (i, t) {
            if (columnToHide != t.column_id) {
                tasksData.push(t);
            }
        });

        return tasksData;
    }

    return _tasks;
}
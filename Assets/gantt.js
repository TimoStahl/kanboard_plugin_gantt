KB.on('dom.ready', function() {
    if (KB.exists('#gantt-chart')) {

        console.log("load gantt chart");

        var tasks = jQuery("#gantt-chart").data("records");
        var gantt = new Gantt("#gantt-chart", tasks, {
            view_modes: ['Day', 'Week', 'Month'],
            view_mode: 'Week',
            date_format: 'YYYY-MM-DD',
            custom_popup_html: null, //TODO
            language: jQuery("html").attr('lang'),
            on_click: function(task) {
                console.log(task);
            }
        });

        // remove resize handles for the moment
        /*var handles = document.querySelectorAll("#gantt-chart .handle-group");
        for (var i = 0; i < handles.length; i++) {
            handles[i].remove();
        }*/

        console.log("gantt chart loaded");

        KB.onClick('#gantt-mode-day', function(e) {
            console.log("mode day clicked");
            gantt.change_view_mode('Day');
        });

        KB.onClick('#gantt-mode-week', function(e) {
            console.log("mode week clicked");
            gantt.change_view_mode('Week');
        });

        KB.onClick('#gantt-mode-month', function(e) {
            console.log("mode month clicked");
            gantt.change_view_mode('Month');
        });
    }
});
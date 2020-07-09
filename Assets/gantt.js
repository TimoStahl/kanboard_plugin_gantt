KB.on('dom.ready', function() {
    if (KB.exists('#gantt-chart')) {
        var tasks = jQuery("#gantt-chart").data("records");
        var gantt = new Gantt("#gantt-chart", tasks);
    }
});
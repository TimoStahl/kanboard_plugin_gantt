KB.on("dom.ready", function () {
  if (KB.exists("#gantt-chart")) {
    console.log("load gantt chart");

    var tasks = jQuery("#gantt-chart").data("records");
    var gantt = new Gantt("#gantt-chart", tasks, {
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
        const start_date = format_date(task._start);
        const end_date = format_date(task._end);
        // TODO: add more info and translate text
        return `
                <div class="details-container">
                    <a href="${task.url}">
                        <b>#${task.id} ${task.name}</b>
                    </a>
                    <table>
                        <tr>
                            <td>Start</td>
                            <td>${start_date}</td>
                        </tr>
                        <tr>
                            <td>End</td>
                            <td>${end_date}</td>
                        </tr>
                        <tr>
                            <td>Progress</td>
                            <td>${task.progress}%</td>
                        </tr>
                        <tr>
                            <td>Column</td>
                            <td>${task.column}</td>
                        </tr>
                        <tr>
                            <td>Swimlane</td>
                            <td>${task.swimlane}</td>
                        </tr>
                        <tr>
                            <td>Category</td>
                            <td>${task.category}</td>
                        </tr>
                    </table>
                </div>
                `;
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

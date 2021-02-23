<section id="main">
    <?php echo $this->projectHeader->render(
        $project,
        "TaskGanttController",
        "show",
        false,
        "Gantt"
    ); ?>
    <div class="table-list-header">
        <?php echo t("Gantt settings:"); ?>
        <ul class="gantt views">
            <li class="gantt-change-mode" data-mode-view="Day">
                <?php echo t("Day"); ?>
            </li>
            <li class="gantt-change-mode" data-mode-view="Week">
                <?php echo t("Week"); ?>
            </li>
            <li class="gantt-change-mode" data-mode-view="Month">
                <?php echo t("Month"); ?>
            </li>
            <li class="gantt-change-mode" data-mode-view="Year">
                <?php echo t("Year"); ?>
            </li>
            <li>
                <label id="hide-first-column-label"><input type="checkbox" value="<?= $firstColumnId ?>" id="hide-first-column">&nbsp;<?= t(
    "Hide first Column"
) ?></label>
            </li>
        </ul>
    </div>    
        <div id="gantt-chart"
             data-save-url="<?php echo $this->url->href(
                 "TaskGanttController",
                 "save",
                 ["project_id" => $project["id"], "plugin" => "Gantt"]
             ); ?>" 
             data-load-url="<?php echo $data_load_url; ?>" 
        >
        <i class="fa fa-spinner fa-2x fa-pulse"></i> Loading...
        </div>
        <div id="gantt-popup-template" class="hide">
            <?= $this->render("Gantt:task_gantt/popup", [
                "project" => $project,
            ]) ?>
        </div>    
</section>
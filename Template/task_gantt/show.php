<section id="main">
    <?php echo $this->projectHeader->render($project, 'TaskGanttController', 'show', false, 'Gantt'); ?>
    <div class="table-list-header">
        <?php echo t('Gantt settings:'); ?>     
        <ul class="views">
            <li id="gantt-mode-day">
                <?php echo t('Day'); ?>
            </li>
            <li id="gantt-mode-week">
                <?php echo t('Week'); ?>
            </li>
            <li id="gantt-mode-month">
                <?php echo t('Month'); ?>
            </li>
        </ul>
    </div>
    <?php if (!empty($tasks)) { ?>
        <div        
            id="gantt-chart"
            data-save-url="<?php echo $this->url->href('TaskGanttController', 'save', ['project_id' => $project['id'], 'plugin' => 'Gantt']); ?>"
            data-records='<?php echo json_encode($tasks); ?>'
        ></div>        
    <?php } else { ?>
        <p class="alert"><?php echo t('There is no task in your project.'); ?></p>
    <?php } ?>
</section>
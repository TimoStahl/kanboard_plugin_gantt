<section id="main">
    <?php echo $this->projectHeader->render($project, 'TaskGanttController', 'show', false, 'Gantt'); ?>
    <?php if (!empty($tasks)) { ?>
        <div
            id="gantt-chart"
            data-records='<?php echo json_encode($tasks, JSON_HEX_APOS); ?>'
        ></div>        
    <?php } else { ?>
        <p class="alert"><?php echo t('There is no task in your project.'); ?></p>
    <?php } ?>
</section>
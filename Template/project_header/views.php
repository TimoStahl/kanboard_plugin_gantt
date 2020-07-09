<?php if ($this->user->hasProjectAccess('TaskGanttController', 'show', $project['id'])) { ?>
    <li <?php echo $this->app->checkMenuSelection('TaskGanttController'); ?>>
        <?php echo $this->url->icon('sliders', t('Gantt'), 'TaskGanttController', 'show', ['project_id' => $project['id'], 'search' => $filters['search'], 'plugin' => 'Gantt'], false, 'view-gantt', t('Keyboard shortcut: "%s"', 'v g')); ?>
    </li>
<?php } ?>
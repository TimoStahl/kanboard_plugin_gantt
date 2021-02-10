<div class="details-container">
    <a href="${task.url}">
        <b>#${task.id} ${task.name}</b>
    </a>
    <table>
        <?= $this->hook->render('template:gantt:task:popup:beginning-table', array('project' => $project)) ?>
        <tr>
            <td><?= t('Start') ?></td>
            <td>${start_date}</td>
        </tr>
        <tr>
            <td><?= t('End') ?></td>
            <td>${end_date}</td>
        </tr>
        <tr>
            <td><?= t('Progress') ?></td>
            <td>${task.progress}%</td>
        </tr>
        <tr>
            <td><?= t('Column') ?></td>
            <td>${task.column}</td>
        </tr>
        <tr>
            <td><?= t('Swimlane') ?></td>
            <td>${task.swimlane}</td>
        </tr>
        <tr>
            <td><?= t('Category') ?></td>
            <td>${task.category}</td>
        </tr>
        <?= $this->hook->render('template:gantt:task:popup:end-table', array('project' => $project)) ?>
    </table>
</div>
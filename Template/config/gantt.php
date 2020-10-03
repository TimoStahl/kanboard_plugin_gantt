<div class="page-header">
    <h2><?php echo t('Gantt settings'); ?></h2>
</div>

    <?php if (!empty($links)) { ?>
    <table class="table-striped table-scrolling">
        <tr>
            <th><?php echo t('Link labels'); ?></th>
            <th><?php echo t('Gantt visibility'); ?></th>
            <th><?php echo t('Actions'); ?></th>
        </tr>
        <?php foreach ($links as $link) { ?>
        <tr>
            <td>
                <strong><?php echo t($link['label']); ?></strong>
            </td>
            <td>
                <?php echo $link['gantt_visible'] ? t('visible') : t('hidden'); ?>
            </td>
            <td>
                <ul>
                <?php echo $this->url->link(t('change visibility'), 'ConfigController', 'change_visibility', ['plugin' => 'gantt', 'link_id' => $link['id']]); ?>
                </ul>
            </td>
        </tr>
        <?php } ?>
    </table>
<?php } else { ?>
    <?php echo t('There is no link.'); ?>
<?php } ?>
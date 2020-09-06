<li <?php echo $this->app->checkMenuSelection('ConfigController', 'show', 'Gantt'); ?>>
    <?php echo $this->url->link(t('Gantt settings'), 'ConfigController', 'show', ['plugin' => 'Gantt']); ?>
</li>
<?php

namespace Kanboard\Plugin\Gantt;

use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Security\Role;
use Kanboard\Core\Translator;
use Kanboard\Plugin\Gantt\Formatter\TaskGanttFormatter;

class Plugin extends Base
{
    public function initialize()
    {
        $this->projectAccessMap->add('ProjectGanttController', 'save', Role::PROJECT_MANAGER);

        $this->template->hook->attach('template:project-header:view-switcher', 'Gantt:project_header/views');

        $this->hook->on('template:layout:js', ['template' => 'plugins/Gantt/Assets/frappe-gantt.min.js']);
        $this->hook->on('template:layout:js', ['template' => 'plugins/Gantt/Assets/gantt.js']);
        $this->hook->on('template:layout:css', ['template' => 'plugins/Gantt/Assets/frappe-gantt.css']);

        $this->template->hook->attach('template:config:sidebar', 'Gantt:config/sidebar');

        $this->container['taskGanttFormatter'] = $this->container->factory(function ($c) {
            return new TaskGanttFormatter($c);
        });
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function getPluginName()
    {
        return 'Gantt';
    }

    public function getPluginDescription()
    {
        return t('Gantt');
    }

    public function getPluginAuthor()
    {
        return 'BlueTeck';
    }

    public function getPluginVersion()
    {
        return '1.0.0';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/BlueTeck/kanboard_plugin_gantt';
    }

    public function getCompatibleVersion()
    {
        return '>=1.2.13';
    }
}

<?php

namespace Kanboard\Plugin\Gantt\Formatter;

use Kanboard\Core\Filter\FormatterInterface;
use Kanboard\Formatter\BaseFormatter;

class TaskGanttFormatter extends BaseFormatter implements FormatterInterface
{
    /**
     * Local cache for project columns.
     *
     * @var array
     */
    private $columns = [];
    private $links = [];

    /**
     * Apply formatter.
     *
     * @return array
     */
    public function format()
    {
        $bars = [];

        foreach ($this->query->findAll() as $task) {
            $bars[] = $this->formatTask($task);
        }

        array_multisort(
            array_column($bars, 'start'),
            SORT_ASC,
            array_column($bars, 'end'),
            SORT_ASC,
            $bars
        );

        return $bars;
    }

    /**
     * Format a single task.
     *
     * @return array
     */
    private function formatTask(array $task)
    {
        if (!isset($this->columns[$task['project_id']])) {
            $this->columns[$task['project_id']] = $this->columnModel->getList($task['project_id']);
        }

        // calculate some days
        // Start ❌ Duration ✔ End ❌
        if (!$task['date_started'] && $task['time_estimated'] && !$task['date_due']) {
            $start = time();
            $secondsToAdd = $task['time_estimated'] * (60 * 60);
            $end = $start + $secondsToAdd;
            // Start ✔ Duration ✔ End ❌
        } elseif ($task['date_started'] && $task['time_estimated'] && !$task['date_due']) {
            $start = $task['date_started'];
            $secondsToAdd = $task['time_estimated'] * (60 * 60);
            $end = $start + $secondsToAdd;
            // Start ❌ Duration ✔ End  ✔
        } elseif (!$task['date_started'] && $task['time_estimated'] && $task['date_due']) {
            $end = $task['date_due'];
            $secondsToSub = $task['time_estimated'] * (60 * 60);
            $start = $end - $secondsToSub;
            // Start ❌ Duration ❌ End ❌
            // Start ✔ Duration ✔ End ✔
            // Start ✔ Duration ❌ End ❌
            // Start ✔ Duration ❌ End ✔
            // Start ❌ Duration ❌ End  ✔
        } else {
            $start = $task['date_started'] ?: time();
            $end = $task['date_due'] ?: $start;
        }
        // TODO: use links to calculate dates, e.g. blocked

        // Task connections
        $tasklinks = '';

        $link_settings = $this->linkModel->getAll();

        foreach ($this->taskLinkModel->getAllGroupedByLabel($task['id']) as $type => $links) {
            $settings_key = array_search($type, array_column($link_settings, 'label'));
            $type_visible = $link_settings[$settings_key]['gantt_visible'];

            if ($type_visible) {
                foreach ($links as $link) {
                    $tasklinks = $tasklinks . ', ' . $link['task_id'];
                }
            }
        }

        return [
            'id' => $task['id'],
            'name' => $task['title'],
            'start' => date('Y-m-d', $start),
            'end' => date('Y-m-d', $end),
            'progress' => $this->taskModel->getProgress($task, $this->columns[$task['project_id']]),
            'dependencies' => $tasklinks,
            'url' => $this->helper->url->to('TaskViewController', 'show', ['task_id' => $task['id'], 'project_id' => $task['project_id']], '', true),
            'column' => $task['column_name'],
            'swimlane' => $task['swimlane_name'],
            'category' => $task['category_name'],
            'custom_class' => 'bar-color-' . $task['color_id'],
            'column_id' => $task['column_id'],
        ];
    }
}

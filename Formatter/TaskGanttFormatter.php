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

        foreach ($this->taskLinkModel->getAllGroupedByLabel($task['id']) as $type => $links) {
            foreach ($links as $link) {
                // check if link already exists to avoid arrows in both direction
                // TODO should be improved to point the arrows in the right direction
                if (!array_key_exists($task['id'], $this->links)) {
                    $this->links[$task['id']] = [];
                }
                if (!array_key_exists($link['task_id'], $this->links)) {
                    $this->links[$link['task_id']] = [];
                }

                if (!in_array($task['id'], $this->links[$link['task_id']])) {
                    array_push($this->links[$task['id']], $link['task_id']);

                    $tasklinks = $tasklinks.', '.$link['task_id'];
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
        ];
    }
}

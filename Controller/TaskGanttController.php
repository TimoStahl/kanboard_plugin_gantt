<?php

namespace Kanboard\Plugin\Gantt\Controller;

use Kanboard\Controller\BaseController;
use Kanboard\Filter\TaskProjectFilter;
use Kanboard\Model\TaskModel;

class TaskGanttController extends BaseController
{
    /**
     * Show Gantt chart for one project.
     */
    public function show()
    {
        $project = $this->getProject();
        $search = $this->helper->projectHeader->getSearchQuery($project);
        $filter = $this->taskLexer->build($search)->withFilter(new TaskProjectFilter($project['id']));

        //die('<pre>' . print_r($filter->format($this->taskGanttFormatter), 1) . '</pre>');

        $this->response->html($this->helper->layout->app('Gantt:task_gantt/show', [
            'project' => $project,
            'title' => $project['name'],
            'description' => $this->helper->projectHeader->getDescription($project),
            'tasks' => $filter->format($this->taskGanttFormatter),
            'firstColumnId' => $this->columnModel->getFirstColumnId($project['id']),
        ]));
    }

    /**
     * Save new task start date and due date
     */
    public function save()
    {
        $this->getProject();
        $changes = $this->request->getJson();
        $values = [];

        if (!empty($changes['start'])) {
            $values['date_started'] = strtotime($changes['start']);
        }

        if (!empty($changes['end'])) {
            $values['date_due'] = strtotime($changes['end']);
        }

        if (!empty($values)) {
            $values['id'] = $changes['id'];
            print_r($values);
            $result = $this->taskModificationModel->update($values);

            if (!$result) {
                $this->response->json(array('message' => 'Unable to save task'), 400);
            } else {
                $this->response->json(array('message' => 'OK'), 201);
            }
        } else {
            $this->response->json(array('message' => 'Ignored'), 200);
        }
    }
}

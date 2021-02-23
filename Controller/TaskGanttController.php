<?php

namespace Kanboard\Plugin\Gantt\Controller;

use Kanboard\Controller\BaseController;
use Kanboard\Filter\TaskProjectFilter;

class TaskGanttController extends BaseController
{
    /**
     * Show Gantt chart for one project.
     */
    public function show()
    {
        $project = $this->getProject();
        $search = $this->helper->projectHeader->getSearchQuery($project);
        $filter = $this->taskLexer
            ->build($search)
            ->withFilter(new TaskProjectFilter($project["id"]));

        $this->response->html(
            $this->helper->layout->app("Gantt:task_gantt/show", [
                "project" => $project,
                "title" => $project["name"],
                "description" => $this->helper->projectHeader->getDescription(
                    $project
                ),
                "data_load_url" => $this->helper->url->href(
                    "TaskGanttController",
                    "data",
                    [
                        "project_id" => $project["id"],
                        "search" => $search,
                        "plugin" => "Gantt",
                    ]
                ),
                "firstColumnId" => $this->columnModel->getFirstColumnId(
                    $project["id"]
                ),
            ])
        );
    }

    public function data()
    {
        $project = $this->getProject();
        $search = $this->helper->projectHeader->getSearchQuery($project);
        $filter = $this->taskLexer
            ->build($search)
            ->withFilter(new TaskProjectFilter($project["id"]));

        $this->response->json([
            "tasks" => $filter->format($this->taskGanttFormatter),
        ]);
    }

    /**
     * Save new task start date and due date.
     */
    public function save()
    {
        $this->getProject();
        $changes = $this->request->getJson();
        $values = [];

        if (!empty($changes["start"])) {
            $values["date_started"] = strtotime($changes["start"]);
        }

        if (!empty($changes["end"])) {
            $values["date_due"] = strtotime($changes["end"]);
        }

        if (!empty($values)) {
            $values["id"] = $changes["id"];
            print_r($values);
            $result = $this->taskModificationModel->update($values);

            if (!$result) {
                $this->response->json(
                    ["message" => "Unable to save task"],
                    400
                );
            } else {
                $this->response->json(["message" => "OK"], 201);
            }
        } else {
            $this->response->json(["message" => "Ignored"], 200);
        }
    }
}

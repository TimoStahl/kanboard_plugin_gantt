<?php

namespace Kanboard\Plugin\Gantt\Controller;

use Kanboard\Model\LinkModel;

/**
 * Class ConfigController.
 */
class ConfigController extends \Kanboard\Controller\ConfigController
{
    public function show()
    {
        $this->response->html(
            $this->helper->layout->config(
                'Gantt:config/gantt',
                [
                    'title' => t('Settings').' &gt; '.t('Gantt settings'),
                    'links' => $this->linkModel->getAll(),
                ]
            )
        );
    }

    public function change_direction()
    {
        $link_id = $this->request->getIntegerParam('link_id');

        $link = $this->linkModel->getById($link_id);

        $gantt_direction = !$link['gantt_direction'] ? 1 : 0;

        if ($this->updateLinkDirection($link['id'], $gantt_direction)) {
            $this->flash->success(t('Direction changed.'));
        } else {
            $this->flash->failure(t('Direction not changed.'));
        }

        $this->response->redirect($this->helper->url->to('ConfigController', 'show', ['plugin' => 'Gantt']), true);
    }

    public function updateLinkDirection($id, $direction)
    {
        return $this->db
            ->table(LinkModel::TABLE)
            ->eq('id', $id)
            ->update(
                [
                    'gantt_direction' => $direction,
                ]
            )
        ;
    }

    public function save()
    {
        $values = $this->request->getValues();
        $values += ['calendar_user_subtasks_time_tracking' => 0];

        if ($this->configModel->save($values)) {
            $this->flash->success(t('Settings saved successfully.'));
        } else {
            $this->flash->failure(t('Unable to save your settings.'));
        }

        $this->response->redirect($this->helper->url->to('ConfigController', 'show', ['plugin' => 'Gantt']));
    }
}

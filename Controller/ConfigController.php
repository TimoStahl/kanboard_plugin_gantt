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

    public function change_visibility()
    {
        $link_id = $this->request->getIntegerParam('link_id');

        $link = $this->linkModel->getById($link_id);

        $gantt_visible = !$link['gantt_visible'] ? 1 : 0;

        if ($this->updateLinkVisibility($link['id'], $gantt_visible)) {
            $this->flash->success(t('Visibility changed.'));
        } else {
            $this->flash->failure(t('Visibility not changed.'));
        }

        $this->response->redirect($this->helper->url->to('ConfigController', 'show', ['plugin' => 'Gantt']), true);
    }

    public function updateLinkVisibility($id, $visible)
    {
        // should be moved to model
        return $this->db
            ->table(LinkModel::TABLE)
            ->eq('id', $id)
            ->update(
                [
                    'gantt_visible' => $visible,
                ]
            )
        ;
    }
}

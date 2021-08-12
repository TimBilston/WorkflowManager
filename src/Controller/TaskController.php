<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;


/**
 * Task Controller
 *
 * @property \App\Model\Table\TaskTable $Task
 * @method \App\Model\Entity\Photograph[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TaskController extends AppController
{

    public function display(string ...$path): ?Response
    {

        $task = $this->paginate($this->Task);

        $this->set(compact('task'));

        if (!$path) {
            return $this->redirect('/');
        }
        if (in_array('..', $path, true) || in_array('.', $path, true)) {
            throw new ForbiddenException();
        }
        $page = $subpage = null;

        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }
       //$this->set(compact('page', 'subpage'));

        try {
            return $this->render(implode('/', $path));
        } catch (MissingTemplateException $exception) {
            if (Configure::read('debug')) {
                throw $exception;
            }
            throw new NotFoundException();
        }
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $task = $this->paginate($this->Task);

        $this->set(compact('task'));
    }

    /**
     * View method
     *
     * @param string|null $id Photograph id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->set(compact('task'));
    }
}

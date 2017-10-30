<?php

App::uses('AppController', 'Controller');

/**
 * DishCategories Controller
 *
 * @property DishCategory $DishCategory
 * @property PaginatorComponent $Paginator
 * @property FlashComponent $Flash
 */
class DishCategoriesController extends AppController {

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator', 'Flash');

    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $this->DishCategory->recursive = 0;
        $this->set('dishCategories', $this->Paginator->paginate());
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        if (!$this->DishCategory->exists($id)) {
            throw new NotFoundException(__('Invalid dish category'));
        }
        $options = array('conditions' => array('DishCategory.' . $this->DishCategory->primaryKey => $id));
        $this->set('dishCategory', $this->DishCategory->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $this->DishCategory->create();
            if ($this->DishCategory->save($this->request->data)) {
                return $this->flash(__('The dish category has been saved.'), array('action' => 'index'));
            }
        }
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        if (!$this->DishCategory->exists($id)) {
            throw new NotFoundException(__('Invalid dish category'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->DishCategory->save($this->request->data)) {
                return $this->flash(__('The dish category has been saved.'), array('action' => 'index'));
            }
        } else {
            $options = array('conditions' => array('DishCategory.' . $this->DishCategory->primaryKey => $id));
            $this->request->data = $this->DishCategory->find('first', $options);
        }
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
        $this->DishCategory->id = $id;
        if (!$this->DishCategory->exists()) {
            throw new NotFoundException(__('Invalid dish category'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->DishCategory->delete()) {
            return $this->flash(__('The dish category has been deleted.'), array('action' => 'index'));
        } else {
            return $this->flash(__('The dish category could not be deleted. Please, try again.'), array('action' => 'index'));
        }
    }

    /**
     * admin_index method
     *
     * @return void
     */
    public function admin_assoindex() {
        $this->DishCategory->recursive = 0;
        $this->paginate = array('conditions' => array('AND' => array('DishCategory.isshow' => 1, 'DishCategory.uid' => $this->Auth->user('id'))));
        $this->set('dishCategories', $this->Paginator->paginate());
    }

    /**
     * admin_index method
     *
     * @return void
     */
    public function admin_index() {
        $this->DishCategory->recursive = 0;
        $this->paginate = array('conditions' => array('AND' => array('DishCategory.isshow' => 0, 'DishCategory.uid' => $this->Auth->user('id'))));
        $this->set('dishCategories', $this->Paginator->paginate());
    }

    /**
     * admin_view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_view($id = null) {
        if (!$this->DishCategory->exists($id)) {
            throw new NotFoundException(__('Invalid dish category'));
        }
        $options = array('conditions' => array('AND' => array('DishCategory.' . $this->DishCategory->primaryKey => $id, 'DishCategory.uid' => $this->Auth->user('id'))));
        $this->set('dishCategory', $this->DishCategory->find('first', $options));
    }

    public function admin_assoview($id = null) {
        if (!$this->DishCategory->exists($id)) {
            throw new NotFoundException(__('Invalid dish category'));
        }
        $options = array('conditions' => array('AND' => array('DishCategory.' . $this->DishCategory->primaryKey => $id, 'DishCategory.uid' => $this->Auth->user('id'))));
        $this->set('dishCategory', $this->DishCategory->find('first', $options));
    }

    /**
     * admin_assoadd method
     *
     * @return void
     */
    public function admin_assoadd() {
        if ($this->request->is('post')) {
            $this->request->data['DishCategory']['uid'] = $this->Auth->user('id');
            $d = $this->request->data['DishCategory']['image'];
            // print_r($this->request->data);
            $uploadfolderpath = "catimage";
            $uploadpath = WWW_ROOT . '/files/' . $uploadfolderpath;
            if ($d['error'] == 0) {
                $imagename = $d['name'];
                if (file_exists($uploadpath . DS . $imagename)) {
                    $imagename = time() . $imagename;
                }
                $fullpathimage = $uploadpath . DS . $imagename;
                $this->request->data['DishCategory']['image'] = $imagename;
                move_uploaded_file($d['tmp_name'], $fullpathimage);
            }
            $this->DishCategory->create();
            if ($this->DishCategory->save($this->request->data)) {
                $this->Session->setFlash(__('The dish category has been saved.'));
                return $this->redirect(array('action' => 'assoindex'));
            }
        }
    }

    /**
     * admin_add method
     *
     * @return void
     */
    public function admin_add() {
        if ($this->request->is('post')) {
            $this->request->data['DishCategory']['uid'] = $this->Auth->user('id');
            $d = $this->request->data['DishCategory']['image'];
            // print_r($this->request->data);
            $uploadfolderpath = "catimage";
            $uploadpath = WWW_ROOT . '/files/' . $uploadfolderpath;
            if ($d['error'] == 0) {
                $imagename = $d['name'];
                if (file_exists($uploadpath . DS . $imagename)) {
                    $imagename = time() . $imagename;
                }
                $fullpathimage = $uploadpath . DS . $imagename;
                $this->request->data['DishCategory']['image'] = $imagename;
                move_uploaded_file($d['tmp_name'], $fullpathimage);
            }
            // print_r($this->request->data);exit;
            $this->DishCategory->create();
            if ($this->DishCategory->save($this->request->data)) {
                $this->Session->setFlash(__('The dish category has been saved.'));
                return $this->redirect(array('action' => 'index'));
            }
        }
    }

    /**
     * admin_edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_edit($id = null) {
        if (!$this->DishCategory->exists($id)) {
            throw new NotFoundException(__('Invalid dish category'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $this->request->data['DishCategory']['uid'] = $this->Auth->user('id');
            $d = $this->request->data['DishCategory']['image'];
            if ($d['name'] == '') {
                $this->request->data['DishCategory']['image'] = $this->request->data['DishCategory']['img'];
            }

            $uploadfolderpath = "catimage";
            $uploadpath = WWW_ROOT . '/files/' . $uploadfolderpath;
            if ($d['error'] == 0) {
                $imagename = $d['name'];
                if (file_exists($uploadpath . DS . $imagename)) {
                    $imagename = time() . $imagename;
                }
                $fullpathimage = $uploadpath . DS . $imagename;
                $this->request->data['DishCategory']['image'] = $imagename;
                move_uploaded_file($d['tmp_name'], $fullpathimage);
            }

            if ($this->DishCategory->save($this->request->data)) {
                $this->Session->setFlash(__('The dish category has been saved.'));
                return $this->redirect(array('action' => 'index'));
            }
        } else {
            $options = array('conditions' => array('DishCategory.' . $this->DishCategory->primaryKey => $id));
            $this->request->data = $this->DishCategory->find('first', $options);
        }
    }

    public function admin_assoedit($id = null) {
        if (!$this->DishCategory->exists($id)) {
            throw new NotFoundException(__('Invalid dish category'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $this->request->data['DishCategory']['uid'] = $this->Auth->user('id');
            $d = $this->request->data['DishCategory']['image'];
            if ($d['name'] == '') {
                $this->request->data['DishCategory']['image'] = $this->request->data['DishCategory']['img'];
            }

            $uploadfolderpath = "catimage";
            $uploadpath = WWW_ROOT . '/files/' . $uploadfolderpath;
            if ($d['error'] == 0) {
                $imagename = $d['name'];
                if (file_exists($uploadpath . DS . $imagename)) {
                    $imagename = time() . $imagename;
                }
                $fullpathimage = $uploadpath . DS . $imagename;
                $this->request->data['DishCategory']['image'] = $imagename;
                move_uploaded_file($d['tmp_name'], $fullpathimage);
            }
            if ($this->DishCategory->save($this->request->data)) {
                $this->Session->setFlash(__('The dish category has been saved.'));
                return $this->redirect(array('action' => 'index'));
            }
        } else {
            $options = array('conditions' => array('DishCategory.' . $this->DishCategory->primaryKey => $id));
            $this->request->data = $this->DishCategory->find('first', $options);
        }
    }

    /**
     * admin_delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_delete($id = null) {
        $this->DishCategory->id = $id;
        if (!$this->DishCategory->exists()) {
            throw new NotFoundException(__('Invalid dish category'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->DishCategory->delete()) {
            $this->Session->setFlash(__('The dish category has been deleted.'));
            return $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash(__('The dish category could not be deleted. Please, try again.'));
            return $this->redirect(array('action' => 'index'));
        }
    }

    public function admin_assodelete($id = null) {
        $this->DishCategory->id = $id;
        if (!$this->DishCategory->exists()) {
            throw new NotFoundException(__('Invalid dish category'));
        }
        $this->request->allowMethod('post', 'assodelete');
        if ($this->DishCategory->delete()) {
            $this->Session->setFlash(__('The dish category has been deleted.'));
            return $this->redirect(array('action' => 'assoindex'));
        } else {
            $this->Session->setFlash(__('The dish category could not be deleted. Please, try again.'));
            return $this->redirect(array('action' => 'assoindex'));
        }
    }

}

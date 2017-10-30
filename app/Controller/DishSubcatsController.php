<?php

App::uses('AppController', 'Controller');

/**
 * DishSubcats Controller
 *
 * @property DishSubcat $DishSubcat
 * @property PaginatorComponent $Paginator
 */
class DishSubcatsController extends AppController {

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator');

    /**
     * index method
     *
     * @return void
     */
    public function index() {
        $this->DishSubcat->recursive = 0;
        $this->set('dishSubcats', $this->Paginator->paginate());
    }

    /**
     * view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function view($id = null) {
        if (!$this->DishSubcat->exists($id)) {
            throw new NotFoundException(__('Invalid dish subcat'));
        }
        $options = array('conditions' => array('DishSubcat.' . $this->DishSubcat->primaryKey => $id));
        $this->set('dishSubcat', $this->DishSubcat->find('first', $options));
    }

    /**
     * add method
     *
     * @return void
     */
    public function add() {
        if ($this->request->is('post')) {
            $d = $this->request->data['DishSubcat']['image'];
            // print_r($this->request->data);
            $uploadfolderpath = "subcatimage";
            $uploadpath = WWW_ROOT . '/files/' . $uploadfolderpath;
            if ($d['error'] == 0) {
                $imagename = $d['name'];
                if (file_exists($uploadpath . DS . $imagename)) {
                    $imagename = time() . $imagename;
                }
                $fullpathimage = $uploadpath . DS . $imagename;
                $this->request->data['DishSubcat']['image'] = $imagename;
                move_uploaded_file($d['tmp_name'], $fullpathimage);
            }
            $this->DishSubcat->create();
            if ($this->DishSubcat->save($this->request->data)) {
                $this->Session->setFlash(__('The dish subcat has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The dish subcat could not be saved. Please, try again.'));
            }
        }
        $dishCategories = $this->DishSubcat->DishCategory->find('list');
        $this->set(compact('dishCategories'));
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function edit($id = null) {
        if (!$this->DishSubcat->exists($id)) {
            throw new NotFoundException(__('Invalid dish subcat'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if ($this->DishSubcat->save($this->request->data)) {
                $this->Session->setFlash(__('The dish subcat has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The dish subcat could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('DishSubcat.' . $this->DishSubcat->primaryKey => $id));
            $this->request->data = $this->DishSubcat->find('first', $options);
        }
        $dishCategories = $this->DishSubcat->DishCategory->find('list');
        $this->set(compact('dishCategories'));
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function delete($id = null) {
        $this->DishSubcat->id = $id;
        if (!$this->DishSubcat->exists()) {
            throw new NotFoundException(__('Invalid dish subcat'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->DishSubcat->delete()) {
            $this->Session->setFlash(__('The dish subcat has been deleted.'));
        } else {
            $this->Session->setFlash(__('The dish subcat could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

    /**
     * admin_index method
     *
     * @return void
     */
    public function admin_index() {
        $this->DishSubcat->recursive = 0;
        $this->paginate = array('conditions' => array('AND' => array('DishSubcat.isshow' => 0, 'DishSubcat.uid' => $this->Auth->user('id'))));
        $this->set('dishSubcats', $this->Paginator->paginate());
    }

    public function admin_assoindex() {
        $this->DishSubcat->recursive = 0;
        $this->paginate = array('conditions' => array('AND' => array('DishSubcat.isshow' => 1, 'DishSubcat.uid' => $this->Auth->user('id'))));
        $this->set('dishSubcats', $this->Paginator->paginate());
    }

    /**
     * admin_view method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_view($id = null) {
        $this->DishSubcat->recursive = 2;
        if (!$this->DishSubcat->exists($id)) {
            throw new NotFoundException(__('Invalid dish subcat'));
        }
        $options = array('conditions' => array('AND' => array('DishSubcat.' . $this->DishSubcat->primaryKey => $id, 'DishSubcat.uid' => $this->Auth->user('id'))));
        $a = $this->DishSubcat->find('first', $options);
        // debug($a);exit;
        $this->set('dishSubcat', $this->DishSubcat->find('first', $options));
    }

    public function admin_assoview($id = null) {
        $this->DishSubcat->recursive = 2;
        if (!$this->DishSubcat->exists($id)) {
            throw new NotFoundException(__('Invalid dish subcat'));
        }
        $options = array('conditions' => array('AND' => array('DishSubcat.' . $this->DishSubcat->primaryKey => $id, 'DishSubcat.uid' => $this->Auth->user('id'))));
        $a = $this->DishSubcat->find('first', $options);
        // debug($a);exit;
        $this->set('dishSubcat', $this->DishSubcat->find('first', $options));
    }

    /**
     * admin_add method
     *
     * @return void
     */
    public function admin_add() {
        if ($this->request->is('post')) {
            $d = $this->request->data['DishSubcat']['image'];
            $this->request->data['DishSubcat']['uid'] = $this->Auth->user('id');
            // print_r($this->request->data);
            $uploadfolderpath = "subcatimage";
            $uploadpath = WWW_ROOT . '/files/' . $uploadfolderpath;
            if ($d['error'] == 0) {
                $imagename = $d['name'];
                if (file_exists($uploadpath . DS . $imagename)) {
                    $imagename = time() . $imagename;
                }
                $fullpathimage = $uploadpath . DS . $imagename;
                $this->request->data['DishSubcat']['image'] = $imagename;
                move_uploaded_file($d['tmp_name'], $fullpathimage);
            }
            $this->DishSubcat->create();
            if ($this->DishSubcat->save($this->request->data)) {
                $this->Session->setFlash(__('The dish subcat has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The dish subcat could not be saved. Please, try again.'));
            }
        }
        $dishCategories = $this->DishSubcat->DishCategory->find('list', array('conditions' => array('AND'=>array('DishCategory.isshow' => 0,'DishCategory.uid' => $this->Auth->user('id')))));
        $this->set(compact('dishCategories'));
    }

    public function admin_assoadd() {
        if ($this->request->is('post')) {
            $d = $this->request->data['DishSubcat']['image'];
            $this->request->data['DishSubcat']['uid'] = $this->Auth->user('id');
            // print_r($this->request->data);
            $uploadfolderpath = "subcatimage";
            $uploadpath = WWW_ROOT . '/files/' . $uploadfolderpath;
            if ($d['error'] == 0) {
                $imagename = $d['name'];
                if (file_exists($uploadpath . DS . $imagename)) {
                    $imagename = time() . $imagename;
                }
                $fullpathimage = $uploadpath . DS . $imagename;
                $this->request->data['DishSubcat']['image'] = $imagename;
                move_uploaded_file($d['tmp_name'], $fullpathimage);
            }
            $this->DishSubcat->create();
            if ($this->DishSubcat->save($this->request->data)) {
                $this->Session->setFlash(__('The dish subcat has been saved.'));
                return $this->redirect(array('action' => 'assoindex'));
            } else {
                $this->Session->setFlash(__('The dish subcat could not be saved. Please, try again.'));
            }
        }
        $dishCategories = $this->DishSubcat->DishCategory->find('list', array('conditions' => array('AND'=>array('DishCategory.isshow' => 1,'DishCategory.uid' => $this->Auth->user('id')))));
        $this->set(compact('dishCategories'));
    }

    /**
     * admin_edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_assoedit($id = null) {
        if (!$this->DishSubcat->exists($id)) {
            throw new NotFoundException(__('Invalid dish subcat'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $this->request->data['DishSubcat']['uid'] = $this->Auth->user('id');
            $d = $this->request->data['DishSubcat']['image'];
            if ($d['name'] == '') {
                $this->request->data['DishSubcat']['image'] = $this->request->data['DishSubcat']['img'];
            }

            $uploadfolderpath = "subcatimage";
            $uploadpath = WWW_ROOT . '/files/' . $uploadfolderpath;
            if ($d['error'] == 0) {
                $imagename = $d['name'];
                if (file_exists($uploadpath . DS . $imagename)) {
                    $imagename = time() . $imagename;
                }
                $fullpathimage = $uploadpath . DS . $imagename;
                $this->request->data['DishSubcat']['image'] = $imagename;
                move_uploaded_file($d['tmp_name'], $fullpathimage);
            }
            if ($this->DishSubcat->save($this->request->data)) {
                $this->Session->setFlash(__('The dish subcat has been saved.'));
                return $this->redirect(array('action' => 'assoindex'));
            } else {
                $this->Session->setFlash(__('The dish subcat could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('DishSubcat.' . $this->DishSubcat->primaryKey => $id));
            $this->request->data = $this->DishSubcat->find('first', $options);
        }
         $dishCategories = $this->DishSubcat->DishCategory->find('list', array('conditions' => array('AND'=>array('DishCategory.isshow' => 1,'DishCategory.uid' => $this->Auth->user('id')))));
        $this->set(compact('dishCategories'));
    }

    public function admin_edit($id = null) {
        if (!$this->DishSubcat->exists($id)) {
            throw new NotFoundException(__('Invalid dish subcat'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $this->request->data['DishSubcat']['uid'] = $this->Auth->user('id');
            $d = $this->request->data['DishSubcat']['image'];
            if ($d['name'] == '') {
                $this->request->data['DishSubcat']['image'] = $this->request->data['DishSubcat']['img'];
            }

            $uploadfolderpath = "subcatimage";
            $uploadpath = WWW_ROOT . '/files/' . $uploadfolderpath;
            if ($d['error'] == 0) {
                $imagename = $d['name'];
                if (file_exists($uploadpath . DS . $imagename)) {
                    $imagename = time() . $imagename;
                }
                $fullpathimage = $uploadpath . DS . $imagename;
                $this->request->data['DishSubcat']['image'] = $imagename;
                move_uploaded_file($d['tmp_name'], $fullpathimage);
            }
            if ($this->DishSubcat->save($this->request->data)) {
                $this->Session->setFlash(__('The dish subcat has been saved.'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The dish subcat could not be saved. Please, try again.'));
            }
        } else {
            $options = array('conditions' => array('DishSubcat.' . $this->DishSubcat->primaryKey => $id));
            $this->request->data = $this->DishSubcat->find('first', $options);
        }
          $dishCategories = $this->DishSubcat->DishCategory->find('list', array('conditions' => array('AND'=>array('DishCategory.isshow' => 0,'DishCategory.uid' => $this->Auth->user('id')))));
        $this->set(compact('dishCategories'));
    }

    /**
     * admin_delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_delete($id = null) {
        $this->DishSubcat->id = $id;
        if (!$this->DishSubcat->exists()) {
            throw new NotFoundException(__('Invalid dish subcat'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->DishSubcat->delete()) {
            $this->Session->setFlash(__('The dish subcat has been deleted.'));
        } else {
            $this->Session->setFlash(__('The dish subcat could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'index'));
    }

    public function admin_assodelete($id = null) {
        $this->DishSubcat->id = $id;
        if (!$this->DishSubcat->exists()) {
            throw new NotFoundException(__('Invalid dish subcat'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->DishSubcat->delete()) {
            $this->Session->setFlash(__('The dish subcat has been deleted.'));
        } else {
            $this->Session->setFlash(__('The dish subcat could not be deleted. Please, try again.'));
        }
        return $this->redirect(array('action' => 'assoindex'));
    }

}

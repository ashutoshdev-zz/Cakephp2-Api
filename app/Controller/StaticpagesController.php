<?php

App::uses('AppController', 'Controller');

/**

 * Staticpages Controller

 *

 * @property Staticpage $Staticpage

 * @property PaginatorComponent $Paginator

 */

class StaticpagesController extends AppController {

    

    public function beforeFilter() {

        parent::beforeFilter();

        $this->Auth->allow(array('terms','whatwedo'));

    }



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

		$this->Staticpage->recursive = 0;

		$this->set('staticpages', $this->Paginator->paginate());

	}



/**

 * view method

 *

 * @throws NotFoundException

 * @param string $id

 * @return void

 */

	public function view($id = null) {

		if (!$this->Staticpage->exists($id)) {

			throw new NotFoundException(__('Invalid staticpage'));

		}

		$options = array('conditions' => array('Staticpage.' . $this->Staticpage->primaryKey => $id));

		$this->set('staticpage', $this->Staticpage->find('first', $options));

	}



/**

 * add method

 *

 * @return void

 */

	public function add() {

		if ($this->request->is('post')) {

			$this->Staticpage->create();

			if ($this->Staticpage->save($this->request->data)) {

				$this->Session->setFlash(__('The staticpage has been saved.'));

				return $this->redirect(array('action' => 'index'));

			} else {

				$this->Session->setFlash(__('The staticpage could not be saved. Please, try again.'));

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

		if (!$this->Staticpage->exists($id)) {

			throw new NotFoundException(__('Invalid staticpage'));

		}

		if ($this->request->is(array('post', 'put'))) {

			if ($this->Staticpage->save($this->request->data)) {

				$this->Session->setFlash(__('The staticpage has been saved.'));

				return $this->redirect(array('action' => 'index'));

			} else {

				$this->Session->setFlash(__('The staticpage could not be saved. Please, try again.'));

			}

		} else {

			$options = array('conditions' => array('Staticpage.' . $this->Staticpage->primaryKey => $id));

			$this->request->data = $this->Staticpage->find('first', $options);

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

		$this->Staticpage->id = $id;

		if (!$this->Staticpage->exists()) {

			throw new NotFoundException(__('Invalid staticpage'));

		}

		$this->request->allowMethod('post', 'delete');

		if ($this->Staticpage->delete()) {

			$this->Session->setFlash(__('The staticpage has been deleted.'));

		} else {

			$this->Session->setFlash(__('The staticpage could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}
/**

 * admin_index method

 *

 * @return void

 */

	public function admin_index() {

		$this->Staticpage->recursive = 0;

                 if($this->request->is("post")){

            if($this->request->data["keyword"]){

                    $keyword = trim($this->request->data["keyword"]);

                $this->paginate = array("limit"=>20,"conditions"=>array("OR"=>array(

                    "Staticpage.title LIKE"=>"%".$keyword."%",
                    "Staticpage.image LIKE"=>"%".$keyword."%",
                    "Staticpage.created LIKE"=>"%".$keyword."%"

                )));

            $this->set("keyword",$keyword);

            }

        }
		$this->set('staticpages', $this->Paginator->paginate());

	}
/**

 * admin_view method

 *

 * @throws NotFoundException

 * @param string $id

 * @return void

 */

	public function admin_view($id = null) {

		if (!$this->Staticpage->exists($id)) {

			throw new NotFoundException(__('Invalid staticpage'));

		}

		$options = array('conditions' => array('Staticpage.' . $this->Staticpage->primaryKey => $id));

		$this->set('staticpage', $this->Staticpage->find('first', $options));

	}



/**

 * admin_add method

 *

 * @return void

 */

	public function admin_add() {

	if ($this->request->is('post')) {

            $one = $this->request->data['Staticpage']['image'];

            $image_name = $this->request->data['Staticpage']['image'] = date('dmHis') . $one['name'];

            $this->Staticpage->create();

            if ($this->Staticpage->save($this->request->data)) {

                if ($one['error'] == 0) {

                    $pth = 'files' . DS . 'staticpage' . DS . $image_name;

                    move_uploaded_file($one['tmp_name'], $pth);

                }

                $this->Session->setFlash(__('The staticpage has been saved'));

                $this->redirect(array('action' => 'index'));

            } else {

                $this->Session->setFlash(__('The staticpage could not be saved. Please, try again.'));

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

		$this->Staticpage->id = $id;

        if (!$this->Staticpage->exists()) {

            throw new NotFoundException(__('Invalid Staticpage'));

        }

        if ($this->request->is('post') || $this->request->is('put')) {

            $one = $this->request->data['Staticpage']['image'];

            $image_name = $this->request->data['Staticpage']['image'] = date('dmHis') . $one['name'];

            if ($one['name'] != "") {

                $x = $this->Staticpage->read('image', $id);

                $x = 'files' . DS . 'staticpage' . DS . $x['Staticpage']['image'];

//                unlink($x);

                $pth = 'files' . DS . 'staticpage' . DS . $image_name;

                move_uploaded_file($one['tmp_name'], $pth);

            }

            if ($one['name'] == "") {

                $xc = $this->Staticpage->read('image', $id);

                $this->request->data['Staticpage']['image'] = $xc['Staticpage']['image'];

            }

            if ($this->Staticpage->save($this->request->data)) {

                $this->Session->setFlash(__('The Staticpage has been saved'));

                $this->redirect(array('action' => 'admin_index'));

            } else {

                $this->Session->setFlash(__('The Staticpage could not be saved. Please, try again.'));

            }

        } else {

            $this->request->data = $this->Staticpage->read(null, $id);

        }

        $this->set('admin_edit', $this->Staticpage->find('first', array('conditions' => array('Staticpage.id' => $id))));

    }



/**

 * admin_delete method

 *

 * @throws NotFoundException

 * @param string $id

 * @return void

 */

	public function admin_delete($id = null) {

		$this->Staticpage->id = $id;

		if (!$this->Staticpage->exists()) {

			throw new NotFoundException(__('Invalid staticpage'));

		}

		$this->request->allowMethod('post', 'delete');

		if ($this->Staticpage->delete()) {

			$this->Session->setFlash(__('The staticpage has been deleted.'));

		} else {

			$this->Session->setFlash(__('The staticpage could not be deleted. Please, try again.'));

		}

		return $this->redirect(array('action' => 'index'));

	}

        public function admin_activate($id = null) {
        $this->Staticpage->id = $id;
        if ($this->Staticpage->exists()) {
            $x = $this->Staticpage->save(array(
                'Staticpage' => array(
                    'status' => '1'
                )
            ));
            $this->Session->setFlash(__("Activated successfully."));
            $this->redirect($this->referer());
        } else {
            $this->Session->setFlash(__("Unable to activate."));
            $this->redirect($this->referer());
        }
    }

    public function admin_deactivate($id = null) {
        $this->Staticpage->id = $id;
        if ($this->Staticpage->exists()) {
            $x = $this->Staticpage->save(array(
                'User' => array(
                    'status' => '0'
                )
            ));
            $this->Session->setFlash(__("Activated successfully."));
            $this->redirect($this->referer());
        } else {
            $this->Session->setFlash(__("Unable to activate."));
            $this->redirect($this->referer());
        }
    }

        public function terms(){

            $data=$this->Staticpage->find('all',array('conditions'=>array('AND'=>array('Staticpage.position'=>'terms','Staticpage.status'=>1))));

            $this->set('staticterm',$data);

        }

        

         public function whatwedo(){

            $data=$this->Staticpage->find('all',array('conditions'=>array('AND'=>array('Staticpage.position'=>'what','Staticpage.status'=>1))));

            $this->set('staticwhats',$data);

        }

}


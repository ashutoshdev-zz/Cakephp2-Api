<?php

App::uses('AppController', 'Controller');

class ProductsController extends AppController {

////////////////////////////////////////////////////////////

    public $components = array(
        'RequestHandler',
        'Paginator',
        'Flash'
    );

////////////////////////////////////////////////////////////
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('getproduct', 'api_getproductbyid', 'api_getsingleproduct','api_favproductlist','api_favproduct');
    }

////////////////////////////////////////////////////////////

    public function index() {

        $this->loadModel('Restaurant');
//          $data= $this->Restaurant->find('all',array('conditions'=>array(
//            
//                'Restaurant.status' => 1,
//
//             
//        ),
//           'recursive' => -1,
//           'limit' => 6,
//           ));
        $homeproducts = $this->Restaurant->find('all', array('conditions' => array(
                'AND' => array(
                    'Restaurant.status' => 1,
                )
            ),
            'recursive' => 2,
            'limit' => 10,
            'order' => array(
                'Restaurant.review_avg' => 'desc',
                'Restaurant.review_count' => 'desc'),
        ));
        // debug($data);exit;
//        $products = $this->Product->find('all', array(
//            'recursive' => -1,
//            'limit' => 20,
//            'conditions' => array(
//                'Product.active' => 1,
//                'Brand.active' => 1
//            ),
//            'order' => array(
//                'Product.views' => 'ASC'
//            )
//        ));
        $this->set(compact('homeproducts'));
    }

////////////////////////////////////////////////////////////
    public function api_index() {

        return $this->redirect(array('controller' => 'users', 'action' => 'login', 'admin' => false));
    }

    public function products() {

        $this->Paginator = $this->Components->load('Paginator');

        $this->Paginator->settings = array(
            'Product' => array(
                'recursive' => -1,
                'contain' => array(
                    'Brand'
                ),
                'limit' => 20,
                'conditions' => array(
                    'Product.active' => 1,
                    'Brand.active' => 1
                ),
                'order' => array(
                    'Product.name' => 'ASC'
                ),
                'paramType' => 'querystring',
            )
        );
        $products = $this->Paginator->paginate();
        $this->set(compact('products'));

        $this->set('title_for_layout', 'All Products - ' . Configure::read('Settings.SHOP_TITLE'));
    }

////////////////////////////////////////////////////////////

    public function view($id = null) {

        $product = $this->Product->find('first', array(
            'recursive' => -1,
            'contain' => array(
                'Category',
                'Brand'
            ),
            'conditions' => array(
                'Brand.active' => 1,
                'Product.active' => 1,
                'Product.slug' => $id
            )
        ));
        if (empty($product)) {
            return $this->redirect(array('action' => 'index'), 301);
        }

        $this->Product->updateViews($product);

        $this->set(compact('product'));

        $productmods = $this->Product->Productmod->getAllProductMods($product['Product']['id'], $product['Product']['price']);
        $this->set('productmodshtml', $productmods['productmodshtml']);

        $this->set('title_for_layout', $product['Product']['name'] . ' ' . Configure::read('Settings.SHOP_TITLE'));
    }

////////////////////////////////////////////////////////////


    public function search() {
        $search = null;
        if (!empty($this->request->query['search']) || !empty($this->request->data['name'])) {
            $search = empty($this->request->query['search']) ? $this->request->data['name'] : $this->request->query['search'];
            $search = preg_replace('/[^a-zA-Z0-9 ]/', '', $search);
            $terms = explode(' ', trim($search));
            $terms = array_diff($terms, array(''));
            $conditions = array(
                'Brand.active' => 1,
                'Product.active' => 1,
            );
            foreach ($terms as $term) {
                $terms1[] = preg_replace('/[^a-zA-Z0-9]/', '', $term);
                $conditions[] = array('Product.name LIKE' => '%' . $term . '%');
            }
            $products = $this->Product->find('all', array(
                'recursive' => -1,
                'contain' => array(
                    'Brand'
                ),
                'conditions' => $conditions,
                'limit' => 200,
            ));
            if (count($products) == 1) {
                return $this->redirect(array('controller' => 'products', 'action' => 'view', 'slug' => $products[0]['Product']['slug']));
            }
            $terms1 = array_diff($terms1, array(''));
            $this->set(compact('products', 'terms1'));
        }
        $this->set(compact('search'));

        if ($this->request->is('ajax')) {
            $this->layout = false;
            $this->set('ajax', 1);
        } else {
            $this->set('ajax', 0);
        }

        $this->set('title_for_layout', 'Search');

        $description = 'Search';
        $this->set(compact('description'));

        $keywords = 'search';
        $this->set(compact('keywords'));
    }

////////////////////////////////////////////////////////////

    public function searchjson() {

        $term = null;
        if (!empty($this->request->query['term'])) {
            $term = $this->request->query['term'];
            $terms = explode(' ', trim($term));
            $terms = array_diff($terms, array(''));
            $conditions = array(
                // 'Brand.active' => 1,
                'Product.active' => 1
            );
            foreach ($terms as $term) {
                $conditions[] = array('Product.name LIKE' => '%' . $term . '%');
            }
            $products = $this->Product->find('all', array(
                'recursive' => -1,
                'contain' => array(
                // 'Brand'
                ),
                'fields' => array(
                    'Product.id',
                    'Product.name',
                    'Product.image'
                ),
                'conditions' => $conditions,
                'limit' => 20,
            ));
        }
        // $products = Hash::extract($products, '{n}.Product.name');
        echo json_encode($products);
        $this->autoRender = false;
    }

////////////////////////////////////////////////////////////

    public function sitemap() {
        $products = $this->Product->find('all', array(
            'recursive' => -1,
            'contain' => array(
                'Brand'
            ),
            'fields' => array(
                'Product.slug'
            ),
            'conditions' => array(
                'Brand.active' => 1,
                'Product.active' => 1
            ),
            'order' => array(
                'Product.created' => 'DESC'
            ),
        ));
        $this->set(compact('products'));

        $website = Configure::read('Settings.WEBSITE');
        $this->set(compact('website'));

        $this->layout = 'xml';
        $this->response->type('xml');
    }

////////////////////////////////////////////////////////////

    public function admin_reset() {
        $this->Session->delete('Product');
        return $this->redirect(array('action' => 'index'));
    }

    public function admin_resreset($id = NULL) {
        $this->Session->delete('Product');
        return $this->redirect(array('action' => 'resindex/' . $id));
    }

    public function admin_assoresreset($id = NULL) {
        $this->Session->delete('Product');
        return $this->redirect(array('action' => 'assoresindex/' . $id));
    }

////////////////////////////////////////////////////////////
    public function admin_index() {
        if ($this->request->is('post')) {
            // print_r($this->request->data);exit;

            $filter = $this->request->data['Product']['filter'];
            $cid = $this->request->data['Product']['dish_catid'];
            $sid = $this->request->data['Product']['dish_scatid'];
            $name = $this->request->data['Product']['name'];
            if (empty($name)) {
                $this->Session->write('Product.filter', '');
                $this->Session->write('Product.name', '');
                $conditions[] = array('OR' => array(
                        'Product.dishcategory_id' => $cid,
                        'Product.dishsubcat_id' => $sid,
                ));
            } else if (empty($cid)) {
                $conditions[] = array('OR' => array(
                        'Product.' . $filter . ' LIKE' => '%' . $name . '%',
                        'Product.dishsubcat_id' => $sid,
                ));
            } else if (empty($sid)) {
                $conditions[] = array('OR' => array(
                        'Product.' . $filter . ' LIKE' => '%' . $name . '%',
                        'Product.dishcategory_id' => $cid,
                ));
            } else if (!empty($cid) && !empty($sid)) {
                $conditions[] = array('AND' => array(
                        'Product.dishcategory_id' => $cid,
                        'Product.dishsubcat_id' => $sid,
                ));
            } else if (empty($cid) && empty($sid)) {
                $conditions[] = array(
                    'Product.' . $filter . ' LIKE' => '%' . $name . '%',
                );
            } else {
                $conditions[] = array('AND' => array(
                        'Product.' . $filter . ' LIKE' => '%' . $name . '%',
                        'Product.dishcategory_id' => $cid,
                        'Product.dishsubcat_id' => $sid,
                ));
            }
            $this->Session->write('Product.filter', $filter);
            $name = $this->request->data['Product']['name'];
            $this->Session->write('Product.name', $name);



            $this->Session->write('Product.conditions', $conditions);
            return $this->redirect(array('action' => 'index'));
        }

        if ($this->Session->check('Product')) {
            $all = $this->Session->read('Product');
        } else {
            $all = array(
                'name' => '',
                'filter' => '',
                'conditions' => ''
            );
        }
        $this->set(compact('all'));

        $this->Paginator = $this->Components->load('Paginator');

        $this->Paginator->settings = array(
            'Product' => array(
                'contain' => array(
                    'DishCategory',
                    'DishSubcat',
                ),
                'recursive' => -1,
                'limit' => 50,
                'conditions' => $all['conditions'],
                'order' => array(
                    'Product.name' => 'ASC'
                ),
                'paramType' => 'querystring',
            )
        );
        $this->loadModel('DishCategory');
        $this->loadModel('DishSubcat');
        $products = $this->Paginator->paginate();
        $this->layout = "admin";
        $this->Product->recursive = 2;
        $this->set('products', $this->Paginator->paginate());
        $this->set('DishCategory', $this->DishCategory->find('list'));
        $this->set('DishSubcat', $this->DishSubcat->find('list'));
    }

    public function admin_resindex($id = NULL) {

        if ($this->request->is('post')) {
            $filter = $this->request->data['Product']['filter'];
            $cid = $this->request->data['Product']['dish_catid'];
            $sid = $this->request->data['Product']['dish_scatid'];
            $name = $this->request->data['Product']['name'];
            if (empty($name)) {
                $this->Session->write('Product.filter', '');
                $this->Session->write('Product.name', '');
                $conditions[] = array('OR' => array(
                        'Product.dishcategory_id' => $cid,
                        'Product.dishsubcat_id' => $sid,
                    ), 'AND' => array('Product.res_id' => $id, 'Product.sale' => 0));
            } else if (empty($cid)) {
                $conditions[] = array('OR' => array(
                        'Product.' . $filter . ' LIKE' => '%' . $name . '%',
                        'Product.dishsubcat_id' => $sid,
                    ), 'AND' => array('Product.res_id' => $id, 'Product.sale' => 0));
            } else if (empty($sid)) {
                $conditions[] = array('OR' => array(
                        'Product.' . $filter . ' LIKE' => '%' . $name . '%',
                        'Product.dishcategory_id' => $cid,
                    ), 'AND' => array('Product.res_id' => $id, 'Product.sale' => 0));
            } else if (!empty($cid) && !empty($sid)) {
                $conditions[] = array('AND' => array(
                        'Product.dishcategory_id' => $cid,
                        'Product.dishsubcat_id' => $sid,
                        'Product.res_id' => $id,
                        'Product.sale' => 0
                ));
            } else if (empty($cid) && empty($sid)) {
                $conditions[] = array('AND' => array('Product.id' => $id,
                        'Product.' . $filter . ' LIKE' => '%' . $name . '%',
                        'Product.sale' => 0
                ));
            } else {
                $conditions[] = array('AND' => array(
                        'Product.' . $filter . ' LIKE' => '%' . $name . '%',
                        'Product.dishcategory_id' => $cid,
                        'Product.dishsubcat_id' => $sid,
                        'Product.res_id' => $id,
                        'Product.sale' => 0
                ));
            }
            $this->Session->write('Product.filter', $filter);
            $name = $this->request->data['Product']['name'];
            $this->Session->write('Product.name', $name);



            $this->Session->write('Product.conditions', $conditions);
            return $this->redirect(array('action' => 'resindex/' . $id));
        }

        if ($this->Session->check('Product')) {
            $all = $this->Session->read('Product');
        } else {
            $all = array(
                'name' => '',
                'filter' => '',
                'conditions' => array('AND' => array('Product.res_id' => $id, 'Product.sale' => 0))
            );
        }
        $this->set(compact('all'));

        $this->Paginator = $this->Components->load('Paginator');

        $this->Paginator->settings = array(
            'Product' => array(
                'contain' => array(
                    'DishCategory',
                    'DishSubcat',
                ),
                'recursive' => -1,
                'limit' => 50,
                'conditions' => $all['conditions'],
                'order' => array(
                    'Product.name' => 'ASC'
                ),
                'paramType' => 'querystring',
            )
        );
        $this->loadModel('DishCategory');
        $this->loadModel('DishSubcat');
        $products = $this->Paginator->paginate();
        $this->layout = "admin";
        $this->Product->recursive = 2;
        $this->set('products', $this->Paginator->paginate());
        $this->set('DishCategory', $this->DishCategory->find('list', array('conditions' => array('DishCategory.isshow' => 0))));
        $this->set('DishSubcat', $this->DishSubcat->find('list', array('conditions' => array('DishSubcat.isshow' => 0))));
        $this->set('resid', $id);
    }

    public function admin_assoresindex($id = NULL) {

        if ($this->request->is('post')) {
            $filter = $this->request->data['Product']['filter'];
            $cid = $this->request->data['Product']['dish_catid'];
            $sid = $this->request->data['Product']['dish_scatid'];
            $name = $this->request->data['Product']['name'];
            if (empty($name)) {
                $this->Session->write('Product.filter', '');
                $this->Session->write('Product.name', '');
                $conditions[] = array('OR' => array(
                        'Product.dishcategory_id' => $cid,
                        'Product.dishsubcat_id' => $sid,
                    ), 'AND' => array('Product.res_id' => $id, 'Product.sale' => 1));
            } else if (empty($cid)) {
                $conditions[] = array('OR' => array(
                        'Product.' . $filter . ' LIKE' => '%' . $name . '%',
                        'Product.dishsubcat_id' => $sid,
                    ), 'AND' => array('Product.res_id' => $id, 'Product.sale' => 1));
            } else if (empty($sid)) {
                $conditions[] = array('OR' => array(
                        'Product.' . $filter . ' LIKE' => '%' . $name . '%',
                        'Product.dishcategory_id' => $cid,
                    ), 'AND' => array('Product.res_id' => $id, 'Product.sale' => 1));
            } else if (!empty($cid) && !empty($sid)) {
                $conditions[] = array('AND' => array(
                        'Product.dishcategory_id' => $cid,
                        'Product.dishsubcat_id' => $sid,
                        'Product.res_id' => $id,
                        'Product.sale' => 1
                ));
            } else if (empty($cid) && empty($sid)) {
                $conditions[] = array('AND' => array('Product.id' => $id,
                        'Product.' . $filter . ' LIKE' => '%' . $name . '%',
                        'Product.sale' => 1
                ));
            } else {
                $conditions[] = array('AND' => array(
                        'Product.' . $filter . ' LIKE' => '%' . $name . '%',
                        'Product.dishcategory_id' => $cid,
                        'Product.dishsubcat_id' => $sid,
                        'Product.res_id' => $id,
                        'Product.sale' => 1
                ));
            }
            $this->Session->write('Product.filter', $filter);
            $name = $this->request->data['Product']['name'];
            $this->Session->write('Product.name', $name);



            $this->Session->write('Product.conditions', $conditions);
            return $this->redirect(array('action' => 'assoresindex/' . $id));
        }

        if ($this->Session->check('Product')) {
            $all = $this->Session->read('Product');
        } else {
            $all = array(
                'name' => '',
                'filter' => '',
                'conditions' => array('AND' => array('Product.res_id' => $id, 'Product.sale' => 1))
            );
        }
        $this->set(compact('all'));

        $this->Paginator = $this->Components->load('Paginator');

        $this->Paginator->settings = array(
            'Product' => array(
                'contain' => array(
                    'DishCategory',
                    'DishSubcat',
                ),
                'recursive' => -1,
                'limit' => 50,
                'conditions' => $all['conditions'],
                'order' => array(
                    'Product.name' => 'ASC'
                ),
                'paramType' => 'querystring',
            )
        );
        $this->loadModel('DishCategory');
        $this->loadModel('DishSubcat');
        $products = $this->Paginator->paginate();
        $this->layout = "admin";
        $this->Product->recursive = 2;
        $this->set('products', $this->Paginator->paginate());
        $this->set('DishCategory', $this->DishCategory->find('list', array('conditions' => array('DishCategory.isshow' => 1))));
        $this->set('DishSubcat', $this->DishSubcat->find('list', array('conditions' => array('DishSubcat.isshow' => 1))));
        $this->set('resid', $id);
    }

////////////////////////////////////////////////////////////
    /**
     * 
     * @param type $id
     * @throws NotFoundException
     */
    public function admin_view($id = null) {
        Configure::write("debug", 0);
        if (!$this->Product->exists($id)) {
            throw new NotFoundException('Invalid product');
        }
        $this->loadModel('DishCategory');
        $product = $this->Product->find('first', array(
            'recursive' => -1,
            'contain' => array(
                'DishCategory',
                'DishSubcat',
            ),
            'conditions' => array(
                'Product.id' => $id
            )
        ));
        $alrg_id = unserialize($product['Product']['alergi']);
        $al_id = explode(',', $alrg_id);
        if ($alrg_id == '') {
            $alrgitems = "";
        } else {
            $this->loadModel('Alergy');
            $alrgitems = $this->Alergy->find('all', array('conditions' => array('Alergy.id' => $al_id)));
        }
        $pro_id = unserialize($product['Product']['pro_id']);
        if ($pro_id == '') {
            $assoproduct = "";
        } else {
            $assoproduct = $this->Product->query("SELECT * FROM products as PT INNER JOIN dish_categories ON PT.dishcategory_id=dish_categories.id INNER JOIN dish_subcats ON PT.dishsubcat_id=dish_subcats.id WHERE PT.id IN($pro_id)");
        }
        //debug($assoproduct);exit;
        $this->set(compact('product', 'assoproduct', 'alrgitems'));
    }

    /**
     * 
     * @param type $id
     * @throws NotFoundException
     */
    public function admin_resview($id = null) {
        Configure::write("debug", 0);
        if (!$this->Product->exists($id)) {
            throw new NotFoundException('Invalid product');
        }
        $this->loadModel('DishCategory');
        $product = $this->Product->find('first', array(
            'recursive' => -1,
            'contain' => array(
                'DishCategory',
                'DishSubcat',
            ),
            'conditions' => array(
                'Product.id' => $id
            )
        ));
        $alrg_id = unserialize($product['Product']['alergi']);
        $al_id = explode(',', $alrg_id);
        if ($alrg_id == '') {
            $alrgitems = "";
        } else {
            $this->loadModel('Alergy');
            $alrgitems = $this->Alergy->find('all', array('conditions' => array('Alergy.id' => $al_id)));
        }
        $pro_id = unserialize($product['Product']['pro_id']);
        if ($pro_id == '') {
            $assoproduct = "";
        } else {
            $assoproduct = $this->Product->query("SELECT * FROM products as PT INNER JOIN dish_categories ON PT.dishcategory_id=dish_categories.id INNER JOIN dish_subcats ON PT.dishsubcat_id=dish_subcats.id WHERE PT.id IN($pro_id)");
        }
        //debug($assoproduct);exit;
        $this->set(compact('product', 'assoproduct', 'alrgitems'));
    }

    /**
     * 
     * @param type $id
     * @throws NotFoundException
     */
    public function admin_assoresview($id = null) {
        Configure::write("debug", 0);
        if (!$this->Product->exists($id)) {
            throw new NotFoundException('Invalid product');
        }
        $this->loadModel('DishCategory');
        $product = $this->Product->find('first', array(
            'recursive' => -1,
            'contain' => array(
                'DishCategory',
                'DishSubcat',
            ),
            'conditions' => array(
                'Product.id' => $id
            )
        ));
        $alrg_id = unserialize($product['Product']['alergi']);
        $al_id = explode(',', $alrg_id);
        if ($alrg_id == '') {
            $alrgitems = "";
        } else {
            $this->loadModel('Alergy');
            $alrgitems = $this->Alergy->find('all', array('conditions' => array('Alergy.id' => $al_id)));
        }
        $pro_id = unserialize($product['Product']['pro_id']);
        if ($pro_id == '') {
            $assoproduct = "";
        } else {
            $assoproduct = $this->Product->query("SELECT * FROM products as PT INNER JOIN dish_categories ON PT.dishcategory_id=dish_categories.id INNER JOIN dish_subcats ON PT.dishsubcat_id=dish_subcats.id WHERE PT.id IN($pro_id)");
        }
        //debug($assoproduct);exit;
        $this->set(compact('product', 'assoproduct', 'alrgitems'));
    }

    /**
     * 
     * @param type $id
     * @return type
     * @throws NotFoundException
     */
    public function admin_assoresedit($id = null) {
        Configure::write("debug", 2);
        if (!$this->Product->exists($id)) {
            throw new NotFoundException('Invalid product');
        }

        if ($this->request->is('post') || $this->request->is('put')) {

//            $this->request->data['Product']['pro_id'] = serialize($this->request->data['Product']['proassociate']);
            $this->request->data['Product']['alergi'] = serialize($this->request->data['Product']['alergi']);
            $product = $this->Product->find('first', array(
                'conditions' => array(
                    'Product.id' => $id
                )
            ));
            //debug($this->request->data);exit;
            $image = $this->request->data['Product']['image'];
            $uploadFolder = "product";
            //full path to upload folder
            $uploadPath = WWW_ROOT . '/files/' . $uploadFolder;
            //check if there wasn't errors uploading file on serwer
            if ($image['error'] == 0) {
                //image file name
                $imageName = $image['name'];
                //check if file exists in upload folder
                if (file_exists($uploadPath . DS . $imageName)) {
                    //create full filename with timestamp
                    $imageName = date('His') . $imageName;
                }
                //create full path with image
                $full_image_path = $uploadPath . DS . $imageName;
                $this->request->data['Product']['image'] = $imageName;
                move_uploaded_file($image['tmp_name'], $full_image_path);
                $this->Product->id = $id;
            } else {

                $admin_edit = $this->Product->find('first', array('conditions' => array('Product.id' => $id)));
                $this->request->data['Product']['image'] = !empty($admin_edit['Product']['image']) ? $admin_edit['Product']['image'] : ' ';
            }
            if ($this->Product->save($this->request->data)) {
                $this->Session->setFlash('The product has been saved');
                return $this->redirect(array('action' => 'assoresindex/' . $product['Product']['res_id']));
            } else {
                $this->Session->setFlash('The product could not be saved. Please, try again.');
            }
        }
        $product = $this->Product->find('first', array(
            'conditions' => array(
                'Product.id' => $id
            )
        ));
        $this->loadModel('Alergy');
        // echo $product['Product']['alergi'];
        //echo $product['Product']['pro_id'];

        $alrg_id = unserialize($product['Product']['alergi']);
        $al_id = explode(',', $alrg_id);
        if ($alrg_id == '') {
            $alrgitems = "";
        } else {

            $alrgitems = $this->Alergy->find('list', array('conditions' => array('Alergy.id' => $al_id)));
        }
//        $pro_id = unserialize($product['Product']['pro_id']);
//        $pr_id = explode(',', $pro_id);
//        if ($pro_id == '') {
//            $assoproduct = "";
//        } else {
//            $assoproduct = $this->Product->find('list',array('conditions' =>array('Product.id'=>$pr_id)));
//        }
//        print_r($alrgitems);
//        echo "<br/>";
//        print_r($assoproduct);
//        exit;
        $this->set('alrgi', $alrgitems);
        // $this->set('pro_product',$assoproduct);
        $this->request->data = $product;
        $this->set(compact('product'));
        $this->loadModel('Restaurant');
        $this->loadModel('DishCategory');
        $this->loadModel('DishSubcat');
        $this->set('DishCategory', $this->DishCategory->find('list', array('conditions' => array('AND'=>array('DishCategory.isshow' => 1,'DishCategory.uid' => $this->Auth->user('id'))))));
        $this->set('DishSubcat', $this->DishSubcat->find('list', array('conditions' => array('DishSubcat.isshow' => 1))));
        $this->set('restaurants', $this->Restaurant->find('list', array('conditions' => array('Restaurant.id' => $product['Product']['res_id']))));
        $this->set('id', $product['Product']['res_id']);
    }

    /**
     * 
     * @param type $id
     * @return type
     * @throws NotFoundException
     */
    public function admin_resedit($id = null) {
        Configure::write("debug", 0);
        if (!$this->Product->exists($id)) {
            throw new NotFoundException('Invalid product');
        }

        if ($this->request->is('post') || $this->request->is('put')) {

            $this->request->data['Product']['pro_id'] = serialize($this->request->data['Product']['proassociate']);
            $this->request->data['Product']['alergi'] = serialize($this->request->data['Product']['alergi']);
            $product = $this->Product->find('first', array(
                'conditions' => array(
                    'Product.id' => $id
                )
            ));
            //debug($this->request->data);exit;
            $image = $this->request->data['Product']['image'];
            $uploadFolder = "product";
            //full path to upload folder
            $uploadPath = WWW_ROOT . '/files/' . $uploadFolder;
            //check if there wasn't errors uploading file on serwer
            if ($image['error'] == 0) {
                //image file name
                $imageName = $image['name'];
                //check if file exists in upload folder
                if (file_exists($uploadPath . DS . $imageName)) {
                    //create full filename with timestamp
                    $imageName = date('His') . $imageName;
                }
                //create full path with image
                $full_image_path = $uploadPath . DS . $imageName;
                $this->request->data['Product']['image'] = $imageName;
                move_uploaded_file($image['tmp_name'], $full_image_path);
                $this->Product->id = $id;
            } else {

                $admin_edit = $this->Product->find('first', array('conditions' => array('Product.id' => $id)));
                $this->request->data['Product']['image'] = !empty($admin_edit['Product']['image']) ? $admin_edit['Product']['image'] : ' ';
            }
            if ($this->Product->save($this->request->data)) {
                $this->Session->setFlash('The product has been saved');
                return $this->redirect(array('action' => 'resindex/' . $product['Product']['res_id']));
            } else {
                $this->Session->setFlash('The product could not be saved. Please, try again.');
            }
        }
        $product = $this->Product->find('first', array(
            'conditions' => array(
                'Product.id' => $id
            )
        ));
        $this->loadModel('Alergy');
        // echo $product['Product']['alergi'];
        //echo $product['Product']['pro_id'];

        $alrg_id = unserialize($product['Product']['alergi']);
        $al_id = explode(',', $alrg_id);
        if ($alrg_id == '') {
            $alrgitems = "";
        } else {

            $alrgitems = $this->Alergy->find('list', array('conditions' => array('Alergy.id' => $al_id)));
        }
        $pro_id = unserialize($product['Product']['pro_id']);
        $pr_id = explode(',', $pro_id);
        if ($pro_id == '') {
            $assoproduct = "";
        } else {
            $assoproduct = $this->Product->find('list', array('conditions' => array('Product.id' => $pr_id)));
        }
//        print_r($alrgitems);
//        echo "<br/>";
//        print_r($assoproduct);
//        exit;
        $this->set('alrgi', $alrgitems);
        $this->set('pro_product', $assoproduct);
        $this->request->data = $product;
        $this->set(compact('product'));
        $this->loadModel('Restaurant');
        $this->loadModel('DishCategory');
        $this->loadModel('DishSubcat');
        $this->set('DishCategory', $this->DishCategory->find('list', array('conditions' => array('AND'=>array('DishCategory.isshow' => 0,'DishCategory.uid' => $this->Auth->user('id'))))));
        $this->set('DishSubcat', $this->DishSubcat->find('list', array('conditions' => array('DishSubcat.isshow' => 0))));
        $this->set('restaurants', $this->Restaurant->find('list', array('conditions' => array('Restaurant.id' => $product['Product']['res_id']))));
        $this->set('id', $product['Product']['res_id']);
    }

    public function admin_resdelete($id = null) {
        $this->Product->id = $id;
        $product = $this->Product->find('first', array(
            'conditions' => array(
                'Product.id' => $id
            )
        ));
        if (!$this->Product->exists()) {
            throw new NotFoundException('Invalid product');
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Product->delete()) {
            $this->Session->setFlash('Product deleted');

            return $this->redirect(array('action' => 'resindex/' . $product['Product']['res_id']));
        }
        $this->Session->setFlash('Product was not deleted');
        return $this->redirect(array('action' => 'resindex'));
    }

    public function admin_assoresdelete($id = null) {
        $this->Product->id = $id;
        $product = $this->Product->find('first', array(
            'conditions' => array(
                'Product.id' => $id
            )
        ));
        if (!$this->Product->exists()) {
            throw new NotFoundException('Invalid product');
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Product->delete()) {
            $this->Session->setFlash('Product deleted');

            return $this->redirect(array('action' => 'assoresindex/' . $product['Product']['res_id']));
        }
        $this->Session->setFlash('Product was not deleted');
        return $this->redirect(array('action' => 'assoresindex'));
    }

////////////////////////////////////////////////////////////

    public function admin_add() {
        if ($this->request->is('post')) {
            // debug($this->request->data);exit;
            $this->request->data['Product']['pro_id'] = serialize($this->request->data['Product']['proassociate']);
            $this->request->data['Product']['alergi'] = serialize($this->request->data['Product']['alergi']);
            $image = $this->request->data['Product']['image'];
            $uploadFolder = "product";
            //full path to upload folder
            $uploadPath = WWW_ROOT . '/files/' . $uploadFolder;
            //check if there wasn't errors uploading file on serwer
            if ($image['error'] == 0) {
                //image file name
                $imageName = $image['name'];
                //check if file exists in upload folder
                if (file_exists($uploadPath . DS . $imageName)) {
                    //create full filename with timestamp
                    $imageName = date('His') . $imageName;
                }
                //create full path with image
                $full_image_path = $uploadPath . DS . $imageName;
                $this->request->data['Product']['image'] = $imageName;
                move_uploaded_file($image['tmp_name'], $full_image_path);
                $this->Product->create();
                if ($this->Product->save($this->request->data)) {
                    $this->Session->setFlash('The product has been saved');
                    return $this->redirect(array('action' => 'index'));
                } else {
                    $this->Session->setFlash('The product could not be saved. Please, try again.');
                }
            }
        }
        $this->loadModel('Restaurant');
        $this->loadModel('DishCategory');
        $this->set('DishCategory', $this->DishCategory->find('list'));
        $this->set('restaurants', $this->Restaurant->find('list'));
    }

    public function admin_getsubcat() {
        $this->layout = 'ajax';
        $id = $_POST['id'];
        if ($this->request->is('post')) {
            $this->loadModel('DishSubcat');
            $data = $this->DishSubcat->find('list', array("conditions" => array('DishSubcat.dish_catid' => $id)));
            echo json_encode($data);
        }
        exit;
    }

    public function admin_getproduct() {
        $this->layout = 'ajax';
        $id = $_POST['id'];
        if ($this->request->is('post')) {
            $data = $this->Product->find('list', array("conditions" => array('AND' => array('Product.res_id' => $id, 'Product.sale' => 1))));
            echo json_encode($data);
        }
        exit;
    }

    public function admin_getalergy() {
        $this->layout = 'ajax';

        $this->loadModel('Alergy');
        $data = $this->Alergy->find('list');
        echo json_encode($data);
        exit;

        exit;
    }

////////////////////////////////////////////////////////////

    public function admin_edit($id = null) {
        Configure::write("debug", 2);
        if (!$this->Product->exists($id)) {
            throw new NotFoundException('Invalid product');
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            // debug($this->request->data);
            $this->request->data['Product']['pro_id'] = serialize($this->request->data['Product']['proassociate']);
            $this->request->data['Product']['alergi'] = serialize($this->request->data['Product']['alergi']);
            $image = $this->request->data['Product']['image'];
            $uploadFolder = "product";
            //full path to upload folder
            $uploadPath = WWW_ROOT . '/files/' . $uploadFolder;
            //check if there wasn't errors uploading file on serwer
            if ($image['error'] == 0) {
                //image file name
                $imageName = $image['name'];
                //check if file exists in upload folder
                if (file_exists($uploadPath . DS . $imageName)) {
                    //create full filename with timestamp
                    $imageName = date('His') . $imageName;
                }
                //create full path with image
                $full_image_path = $uploadPath . DS . $imageName;
                $this->request->data['Product']['image'] = $imageName;
                move_uploaded_file($image['tmp_name'], $full_image_path);
                $this->Product->id = $id;
            } else {

                $admin_edit = $this->Product->find('first', array('conditions' => array('Product.id' => $id)));
                $this->request->data['Product']['image'] = !empty($admin_edit['Product']['image']) ? $admin_edit['Product']['image'] : ' ';
            }
            if ($this->Product->save($this->request->data)) {
                $this->Session->setFlash('The product has been saved');
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('The product could not be saved. Please, try again.');
            }
        }
        $product = $this->Product->find('first', array(
            'conditions' => array(
                'Product.id' => $id
            )
        ));
        $this->loadModel('Alergy');
        // echo $product['Product']['alergi'];
        //echo $product['Product']['pro_id'];

        $alrg_id = unserialize($product['Product']['alergi']);
        $al_id = explode(',', $alrg_id);
        if ($alrg_id == '') {
            $alrgitems = "";
        } else {

            $alrgitems = $this->Alergy->find('list', array('conditions' => array('Alergy.id' => $al_id)));
        }
        $pro_id = unserialize($product['Product']['pro_id']);
        $pr_id = explode(',', $pro_id);
        if ($pro_id == '') {
            $assoproduct = "";
        } else {
            $assoproduct = $this->Product->find('list', array('conditions' => array('Product.id' => $pr_id)));
        }
//        print_r($alrgitems);
//        echo "<br/>";
//        print_r($assoproduct);
//        exit;
        $this->set('alrgi', $alrgitems);
        $this->set('pro_product', $assoproduct);
        $this->request->data = $product;
        $this->set(compact('product'));
        $this->loadModel('Restaurant');
        $this->loadModel('DishCategory');
        $this->loadModel('DishSubcat');
        $this->set('DishCategory', $this->DishCategory->find('list'));
        $this->set('DishSubcat', $this->DishSubcat->find('list'));
        $this->set('restaurants', $this->Restaurant->find('list'));
        $this->set('id', $product['Product']['res_id']);
    }

////////////////////////////////////////////////////////////

    public function admin_tags($id = null) {

        $tags = ClassRegistry::init('Tag')->find('all', array(
            'recursive' => -1,
            'fields' => array(
                'Tag.name'
            ),
            'order' => array(
                'Tag.name' => 'ASC'
            ),
        ));
        $tags = Hash::combine($tags, '{n}.Tag.name', '{n}.Tag.name');
        $this->set(compact('tags'));

        if ($this->request->is('post') || $this->request->is('put')) {

            $tagstring = '';

            foreach ($this->request->data['Product']['tags'] as $tag) {
                $tagstring .= $tag . ', ';
            }

            $tagstring = trim($tagstring);
            $tagstring = rtrim($tagstring, ',');

            $this->request->data['Product']['tags'] = $tagstring;

            $this->Product->save($this->request->data, false);

            return $this->redirect(array('action' => 'tags', $this->request->data['Product']['id']));
        }

        $product = $this->Product->find('first', array(
            'conditions' => array(
                'Product.id' => $id
            )
        ));
        if (empty($product)) {
            throw new NotFoundException('Invalid product');
        }
        $this->set(compact('product'));

        $selectedTags = explode(',', $product['Product']['tags']);
        $selectedTags = array_map('trim', $selectedTags);
        $this->set(compact('selectedTags'));

        $neighbors = $this->Product->find('neighbors', array('field' => 'id', 'value' => $id));
        $this->set(compact('neighbors'));
    }

////////////////////////////////////////////////////////////

    public function admin_csv() {
        $products = $this->Product->find('all', array(
            'recursive' => -1,
        ));
        $this->set(compact('products'));
        $this->layout = false;
    }

    public function admin_csvbyid() {
        $uid = $this->Auth->user('id');
        $this->loadModel('Restaurant');
        $res_first_data = $this->Restaurant->find('first', array('conditions' => array('Restaurant.user_id' => $uid)));
        $products = $this->Product->find('all', array(
            'conditions' => array(
                'Product.res_id' => $res_first_data['Restaurant']['id']
            ),
            'recursive' => -1,
        ));
        $this->set(compact('products'));
        $this->layout = false;
    }

    public function admin_csva() {
        $this->loadModel('Restaurant');
        $restaurant = $this->Restaurant->find('all', array(
            'recursive' => -1,
        ));
        $this->set(compact('restaurant'));
        $this->layout = false;
    }

////////////////////////////////////////////////////////////

    public function admin_delete($id = null) {
        $this->Product->id = $id;
        if (!$this->Product->exists()) {
            throw new NotFoundException('Invalid product');
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Product->delete()) {
            $this->Session->setFlash('Product deleted');
            return $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash('Product was not deleted');
        return $this->redirect(array('action' => 'index'));
    }

////////////////////////////////////////////////////////////
    /**
     * get restaurant by qrcode no
     */
    public function getproduct() {

        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $itmcd = $this->request->data['Product']['itemcodeno'] = $_POST['itemcodeno']; //=627933026404;
        ob_start();
        var_dump($this->request->data);
        $c = ob_get_clean();
        $fc = fopen('files' . DS . 'detail.txt', 'w');
        fwrite($fc, $c);
        fclose($fc);
        if ($this->request->is('post')) {
            $resp = $this->Product->find('all', array('conditions' => array('Product.itemcodeno' => $itmcd)));
            foreach ($resp as $res) {
                if ($res['Product']['image']) {
                    $res['Product']['image'] = FULL_BASE_URL . $this->webroot . 'images/large/' . $res['Product']['image'];
                } else {
                    $res['Product']['image'] = FULL_BASE_URL . $this->webroot . 'img/no-image.jpg';
                }
                $res1[] = $res;
            }
            $response['error'] = 0;
            $response['list'] = $res1;
        } else {
            $response['error'] = 1;
            $response['msg'] = 'Sorry Product has not been found!';
        }
        echo json_encode($response);
        exit;
    }

    /**
     * get restaurant by qrcode no
     */
    public function api_getsingleproduct() {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        $pro_id = $redata->Product->id;
        ob_start();
        var_dump($this->request->data);
        $c = ob_get_clean();
        $fc = fopen('files' . DS . 'detail.txt', 'w');
        fwrite($fc, $c);
        fclose($fc);
        $this->Product->recursive = 2;
        if ($redata) {
            $resp = $this->Product->find('first', array('conditions' => array('Product.id' => $pro_id)));

            if ($resp['Product']['image']) {
                $resp['Product']['image'] = FULL_BASE_URL . $this->webroot . 'files/product/' . $resp['Product']['image'];
            } else {
                $resp['Product']['image'] = FULL_BASE_URL . $this->webroot . 'img/no-image.jpg';
            }

            $this->loadModel('Alergy');
            $alrg_id = $resp['Product']['alergi'];
            $al_id = explode(',', $alrg_id);
            if ($alrg_id == '') {
                $alrgitems = "No data";
            } else {
                $resp['Product']['AssoAlergy'][] = $this->Alergy->find('all', array('conditions' => array('Alergy.id' => $al_id)));
            }
            $pro_id = unserialize($resp['Product']['pro_id']);
            $pro_id = explode(',', $pro_id);
            // print_r($pro_id);
            if ($pro_id == '') {
                $assoproduct = "No data";
            } else {
                $prodata = $this->Product->find('all', array('conditions' => array('Product.id' => $pro_id)));
                //print_r($prodata);
                foreach ($prodata as $res) {
                    if ($res['Product']['image']) {
                        $res['Product']['image'] = FULL_BASE_URL . $this->webroot . 'files/product/' . $res['Product']['image'];
                    } else {
                        $res['Product']['image'] = FULL_BASE_URL . $this->webroot . 'img/no-image.jpg';
                    }
                    $res2[] = $res;
                }
                $resp['Product']['AssoPro'] = $res2;
            }

            $response['error'] = 0;
            $response['list'] = $resp;
        } else {
            $response['error'] = 1;
            $response['msg'] = 'Sorry Product has not been found!';
        }
        echo json_encode($response);
        exit;
    }

    public function api_getproductbyid() {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
//        ob_start();
//        var_dump($redata);
//        $c = ob_get_clean();
//        $fc = fopen('files' . DS . 'detail.txt', 'w');
//        fwrite($fc, $c);
//        fclose($fc);
        $res_id = $redata->Restaurant->id; //666;//$redata->Restaurant->id;
        $dis_id = $redata->Restaurant->dishid; //9; //$redata->Restaurant->dishid;

        if ($redata) {
            $resp = $this->Product->find('all', array('conditions' => array('AND' => array('Product.res_id' => $res_id, 'Product.dishsubcat_id' => $dis_id, 'Product.sale' => 0))));
            // print_r($resp);exit;
            foreach ($resp as $res) {
                if ($res['Product']['image']) {
                    $res['Product']['image'] = FULL_BASE_URL . $this->webroot . 'files/product/' . $res['Product']['image'];
                } else {
                    $res['Product']['image'] = FULL_BASE_URL . $this->webroot . 'img/no-image.jpg';
                }
                $res1[] = $res;
            }
//   /print_r($res1);exit;
            $this->loadModel('Alergy');
            $cnt = count($res1);
            for ($i = 0; $i < $cnt; $i++) {
                $alrg_id = $res1[$i]['Product']['alergi'];
                $al_id = explode(',', $alrg_id);
                if ($alrg_id == '') {
                    $alrgitems = "No data";
                } else {
                    $res1[$i]['Product']['AssoAlergy'][] = $this->Alergy->find('all', array('conditions' => array('Alergy.id' => $al_id)));
                }
                $pro_id = unserialize($resp[$i]['Product']['pro_id']);
                if ($pro_id == '') {
                    $assoproduct = "No data";
                } else {
                    $prodata = $this->Product->find('all', array('conditions' => array('Product.id' => $pro_id)));
                    foreach ($prodata as $res) {
                        if ($res['Product']['image']) {
                            $res['Product']['image'] = FULL_BASE_URL . $this->webroot . 'files/product/' . $res['Product']['image'];
                        } else {
                            $res['Product']['image'] = FULL_BASE_URL . $this->webroot . 'img/no-image.jpg';
                        }
                        $res2[] = $res;
                    }
                    $res1[$i]['Product']['AssoPro'] = $res2;
                }
            }

            $response['error'] = 0;
            $response['list'] = $res1;
        } else {
            $response['error'] = 1;
            $response['msg'] = 'Sorry Product has not been found!';
        }
        echo json_encode($response);
        exit;
    }

    public function admin_proimportres() {
        Configure::write("debug", 0);
        if (!empty($_FILES)) {

            $file = $_FILES['file'];
            $uploadFolder = "resfile";
            $uploadPath = WWW_ROOT . '/files/' . $uploadFolder;
            if ($file['error'] == 0) {
                $fileName = $file['name'];
                $full_image_path = $uploadPath . DS . $fileName;
                move_uploaded_file($file['tmp_name'], $full_image_path);
                $messages = $this->Product->import($fileName);
                $this->Session->setFlash($messages['messages'][0]);
            }
        }


        //exit;
    }

    public function admin_prouploadimage() {
        //print_r($_FILES);
        if (!empty($_FILES)) {
            $image = $_FILES['file'];
            $uploadFolder = "product";
            $uploadPath = WWW_ROOT . '/files/' . $uploadFolder;
            if ($image['error'] == 0) {
                $imageName = $image['name'];
                if (file_exists($uploadPath . DS . $imageName)) {
                    $imageName = date('His') . $imageName;
                }
                $full_image_path = $uploadPath . DS . $imageName;
                move_uploaded_file($image['tmp_name'], $full_image_path);
            }
        }
    }
    /**
     *  completed
     */
    public function api_favproduct(){
        configure::write('debug', 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);   
        $this->layout = "ajax";
        if (!empty($redata)) {
        $id = $redata->user->id;
        $proid = $redata->Pro->id;
        $this->loadModel('Favourite');
        if ($this->Favourite->hasAny(array('AND'=>array('Favourite.uid' =>$id,'Favourite.proid' =>$proid)))) {
                 $response['error'] = '0'; 
                $response['msg'] = 'You have been already addded in the Favourite list';
            }else {
                $this->Favourite->create();
                $this->request->data['Favourite']['uid']=$id;
                $this->request->data['Favourite']['proid']=$proid;
                if($this->Favourite->save($this->request->data)){
                    $response['error'] = '0'; 
                    $response['msg'] = 'You have added  in the Favourite list'; 
                }else {
                     $response['error'] = '1'; 
                    $response['msg'] = 'error'; 
                }
                
            }
        }
         echo json_encode($response);
        exit;
    }
     public function api_favproductlist(){
        configure::write('debug', 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);   
        $this->layout = "ajax";
        if (!empty($redata)) {
        $id = $redata->user->id;
        $this->loadModel('Favourite');
        $this->Favourite->recursive=2;
       $data= $this->Favourite->find('all',array('conditions'=>array('Favourite.uid'=>$id)));
                if($data){
                    $response['error'] = '0'; 
                    $response['data'] = $data; 
                }else {
                     $response['error'] = '1'; 
                    $response['data'] = 'no data available'; 
                }
                
            
        }
         echo json_encode($response);
        exit;
    }
}

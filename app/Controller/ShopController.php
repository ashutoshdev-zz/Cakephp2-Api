<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class ShopController extends AppController {

//////////////////////////////////////////////////

    public $components = array(
        'Cart',
        'Paypal',
        'AuthorizeNet'
    );
//////////////////////////////////////////////////

    public $uses = 'Product';

//////////////////////////////////////////////////

    public function beforeFilter() {
        parent::beforeFilter();
        $this->disableCache();
        $this->Auth->allow('api_allcountry', 'api_displayreviews', 'api_review', 'newsletter',
                'api_webtime', 'api_CancelOrder', 'api_tablehistry',
                'api_TableCancelOrder', 'api_walletpayment', 'api_allpage', 'api_addaddress', 'api_getaddress','api_checkout');
        //$this->Security->validatePost = false;
    }

//////////////////////////////////////////////////

    public function clear() {
        $this->Cart->clear();
        $this->Session->setFlash('All item(s) removed from your shopping cart', 'flash_error');
        return $this->redirect('/');
    }

//////////////////////////////////////////////////

    public function add() {
        if ($this->request->is('post')) {

            $id = $this->request->data['Product']['id'];

            $quantity = isset($this->request->data['Product']['quantity']) ? $this->request->data['Product']['quantity'] : null;

            $productmodId = isset($this->request->data['mods']) ? $this->request->data['mods'] : null;

            $product = $this->Cart->add($id, $quantity, $productmodId);
        }
        if (!empty($product)) {
            $this->Session->setFlash($product['Product']['name'] . ' was added to your shopping cart.', 'flash_success');
        } else {
            $this->Session->setFlash('Unable to add this product to your shopping cart.', 'flash_error');
        }
        $this->redirect($this->referer());
    }

//////////////////////////////////////////////////
    public function admin_itemupdate() {

        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];

            $tid = isset($this->request->data['tid']) ? $this->request->data['tid'] : NULL;
            $quantity = isset($this->request->data['quantity']) ? $this->request->data['quantity'] : null;
            if (isset($this->request->data['mods']) && ($this->request->data['mods'] > 0)) {
                $productmodId = $this->request->data['mods'];
            } else {
                $productmodId = null;
            }
            $product = $this->Cart->adminadd($id, $quantity, $productmodId, $tid);
        }

        $this->loadModel('Cart');
        $sid = $this->Session->id();
        $table_no = $this->Session->read('Cart.tableno');
        $data = $this->Cart->find('all', array('conditions' => array('AND' => array('Cart.tno' => $table_no, 'Cart.sessionid' => $sid))));
        if ($data) {
            $this->Session->write('Shop.Order.tax', '');
            $cart = $this->Session->read('Shop');
            $cnt = count($cart);
            $total = 0;
            foreach ($data as $d) {
                $total += $d['Cart']['quantity'] * $d['Cart']['price'];

                $k[$d['Cart']['product_id'] . '_' . $d['Cart']['tno']] = $d['Cart']['product_id'] . '_' . $d['Cart']['tno'];
            }
            $cart['Order']['subtotal'] = $total;
            $cart['Order']['total'] = $total;

            $getkey = array_intersect_key($cart['OrderItem'], $k);
            $cart['OrderItem'] = $getkey;
            $res_id = $cart['Order']['restaurant_id'];
            $this->loadModel('Tax');

            $d = $this->Tax->find('first', array('conditions' => array('Tax.resid' => $res_id)));
            if (empty($cart['Order']['tax'])) {
                if ($d) {
                    $add = ($cart['Order']['total'] * $d['Tax']['tax']) / 100;
                    //echo $add;
                    $tol = $cart['Order']['total'] + $add;
                    // echo $tol; exit;
                    $this->Session->write('Shop.Order.total', $tol);

                    $cart['Order']['tax'] = $add;
                    $cart['Order']['total'] = $tol;
                    $this->Session->write('Shop.Order.tax', $add);
                }
            }
        } else {
            $cart['OrderItem'] = NULL;
        }



        echo json_encode($cart);
        $this->autoRender = false;
        exit;
    }

    public function itemupdate() {
        Configure::write("debug", 0);

        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];

            $quantity = isset($this->request->data['quantity']) ? $this->request->data['quantity'] : null;
            if (isset($this->request->data['mods']) && ($this->request->data['mods'] > 0)) {
                $productmodId = $this->request->data['mods'];
            } else {
                $productmodId = null;
            }
            $product = $this->Cart->add($id, $quantity, $productmodId);
        }
        $this->loadModel('Product');
        $data = $this->Product->find('first', array('conditions' => array('Product.id' => $id)));
        $cart = $this->Session->read('Shop');
        $cart['alergi'] = unserialize($data['Product']['alergi']);
        $cart['productasso'] = unserialize($data['Product']['pro_id']);
        $cart['id'] = $data['Product']['id'];
        echo json_encode($cart);
        $this->autoRender = false;
        exit;
    }

    public function getcartitem() {
        $cart = $this->Session->read('Shop');
        echo json_encode($cart);
        $this->autoRender = false;
        exit;
    }

    public function reviewgetcartitem() {
        $cart = $this->Session->read('Shop');
        // print_r($cart);exit;

        $res_id = $cart['Order']['restaurant_id'];
        $this->loadModel('Tax');
        $d = $this->Tax->find('first', array('conditions' => array('Tax.resid' => $res_id)));
        if (empty($cart['Order']['tax'])) {
            if ($d) {
                $add = ($cart['Order']['total'] * $d['Tax']['tax']) / 100;
                //echo $add;
                $tol = $cart['Order']['total'] + $add;
                // echo $tol; exit;
                $this->Session->write('Shop.Order.total', $tol);

                if ($add) {
                    $this->Session->write('Shop.Order.tax', $add);
                } else {
                    $this->Session->write('Shop.Order.tax', 0);
                }
            }
        }
        echo json_encode($this->Session->read('Shop'));
        $this->autoRender = false;
        exit;
    }

    public function admin_getcartitem() {
        Configure::write("debug", 2);
        $this->loadModel('Cart');

        $table_no = $this->Session->read('Cart.tableno');
        $rid = $this->Session->read('Cart.resid');
        $sid = $this->Session->id();
        $mobile_data = $this->Cart->find('all', array('conditions' => array('AND' => array('Cart.tno' => $table_no, 'Cart.resid' => $rid))));
//        print_r($mobile_data);
//        exit;
        $promode = NULL;
        foreach ($mobile_data as $mbdata) {
            $this->Cart = $this->Components->load('Cart');
            $this->Cart->adminaddqr($mbdata['Cart']['product_id'], $mbdata['Cart']['quantity'], $promode, $mbdata['Cart']['tno']);
        }

        // exit;
        // $this->Cart->updateAll(array('Cart.sessionid' => "'$sid'"),array('Cart.tno' => $table_no, 'Cart.resid' => $rid));        
        $this->loadModel('Cart');
        $data = $this->Cart->find('all', array('conditions' => array('AND' => array('Cart.tno' => $table_no, 'Cart.resid' => $rid))));
        if ($data) {
            $this->Session->write('Shop.Order.tax', '');
            $cart = $this->Session->read('Shop');
            $cnt = count($cart);
            $total = 0;
            foreach ($data as $d) {
                $total += $d['Cart']['quantity'] * $d['Cart']['price'];

                $k[$d['Cart']['product_id'] . '_' . $d['Cart']['tno']] = $d['Cart']['product_id'] . '_' . $d['Cart']['tno'];
            }
            $cart['Order']['subtotal'] = $total;
            $cart['Order']['total'] = $total;

            $getkey = array_intersect_key($cart['OrderItem'], $k);
            $cart['OrderItem'] = $getkey;
            $res_id = $cart['Order']['restaurant_id'];
            $this->loadModel('Tax');

            $d = $this->Tax->find('first', array('conditions' => array('Tax.resid' => $res_id)));
            if (empty($cart['Order']['tax'])) {
                if ($d) {
                    $add = ($cart['Order']['total'] * $d['Tax']['tax']) / 100;
                    //echo $add;
                    $tol = $cart['Order']['total'] + $add;
                    // echo $tol; exit;
                    $this->Session->write('Shop.Order.total', $tol);

                    $cart['Order']['tax'] = $add;
                    $cart['Order']['total'] = $tol;
                    $this->Session->write('Shop.Order.tax', $add);
                }
            }
        } else {
            $cart['OrderItem'] = NULL;
        }


        //print_r($cart);exit;

        echo json_encode($cart);
        $this->autoRender = false;
        exit;
    }

//////////////////////////////////////////////////

    public function update() {
        $this->Cart->update($this->request->data['Product']['id'], 1);
    }

//////////////////////////////////////////////////

    public function admin_crtremove() {
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $tid = $this->request->data['tid'];
            $product = $this->Cart->adminremove($id, $tid);
        }
        $this->loadModel('Cart');
        $table_no = $this->Session->read('Cart.tableno');
        $sid = $this->Session->id();
        $data = $this->Cart->find('all', array('conditions' => array('AND' => array('Cart.tno' => $table_no, 'Cart.sessionid' => $sid))));
        if ($data) {
            $this->Session->write('Shop.Order.tax', '');
            $cart = $this->Session->read('Shop');
            $cnt = count($cart);
            $total = 0;
            foreach ($data as $d) {
                $total += $d['Cart']['quantity'] * $d['Cart']['price'];

                $k[$d['Cart']['product_id'] . '_' . $d['Cart']['tno']] = $d['Cart']['product_id'] . '_' . $d['Cart']['tno'];
            }
            $cart['Order']['subtotal'] = $total;
            $cart['Order']['total'] = $total;

            $getkey = array_intersect_key($cart['OrderItem'], $k);
            $cart['OrderItem'] = $getkey;

            $res_id = $cart['Order']['restaurant_id'];
            $this->loadModel('Tax');

            $d = $this->Tax->find('first', array('conditions' => array('Tax.resid' => $res_id)));
            if (empty($cart['Order']['tax'])) {
                if ($d) {
                    $add = ($cart['Order']['total'] * $d['Tax']['tax']) / 100;
                    //echo $add;
                    $tol = $cart['Order']['total'] + $add;
                    // echo $tol; exit;
                    $this->Session->write('Shop.Order.total', $tol);

                    $cart['Order']['tax'] = $add;
                    $cart['Order']['total'] = $tol;
                    $this->Session->write('Shop.Order.tax', $add);
                }
            }
        } else {
            $cart['OrderItem'] = array();
            $cart['Order']['subtotal'] = 0;
            $cart['Order']['total'] = 0;
            $cart['Order']['tax'] = 0;
        }


        echo json_encode($cart);
        $this->autoRender = false;
        exit;
    }

    public function crtremove() {
        if ($this->request->is('ajax')) {

            $id = $this->request->data['id'];
            $product = $this->Cart->remove($id);
        }
        $a = $this->Session->read('Shop.Order.tax');
        if ($a) {
            $this->Session->write('Shop.Order.tax', '');
        }
        $cart = $this->Session->read('Shop');
        $cart = $this->Session->read('Shop');
        echo json_encode($cart);
        $this->autoRender = false;
    }

    public function testaddremovecart() {
        $this->loadModel('Cart');
        $data = $this->Cart->find('all', array(
            'conditions' => array(
                'AND' => array(
                    'Cart.sessionid' => 'd701b36b281e96879c2bf8c4025c2391',
                    'Cart.product_id' => 66
        ))));
        //$this->Session->write('Shop.Order.quantity',3);  
        debug($data);
        exit;
    }

    public function admin_addremovecart() {
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $tid = $this->request->data['tid'];
            $ses_id = $this->Session->id();
            $this->loadModel('Cart');
            $dcrt = $this->Cart->find('all', array(
                'conditions' => array(
                    'AND' => array(
                        'Cart.sessionid' => $ses_id,
                        'Cart.product_id' => $id,
                        'Cart.tno' => $tid
            ))));
            foreach ($dcrt as $d) {
                $qty = $d['Cart']['quantity'] + 1;
                $weight_total = $d['Cart']['weight_total'] + $d['Cart']['weight'];
                $subtotal = $d['Cart']['subtotal'] + $d['Cart']['price'];
            }
            $this->Cart->updateAll(array('Cart.subtotal' => $subtotal, 'Cart.quantity' => $qty, 'Cart.weight_total' => $weight_total), array('Cart.sessionid' => $ses_id, 'Cart.product_id' => $id, 'Cart.tno' => $tid)
            );
            $data = '';
            //$data['quantity']=$qty;
            $data['subtotal'] = $subtotal;
            $data['totalweight'] = $weight_total;
            $data['quantity'] = $qty;
            $this->Session->write('Shop.OrderItem.' . $id . '.quantity', $data['quantity']);
            $this->Session->write('Shop.OrderItem.' . $id . '.subtotal', $data['subtotal']);
            $this->Session->write('Shop.OrderItem.' . $id . '.totalweight', $data['totalweight']);
            $totalqty = $this->Session->read('Shop.Order.quantity');
            $totalsubtotal = $this->Session->read('Shop.Order.subtotal');
            $totalweight = $this->Session->read('Shop.Order.totalweight');
            $this->Session->write('Shop.Order.quantity', $totalqty + 1);
            $this->Session->write('Shop.Order.subtotal', $totalsubtotal + $dcrt[0]['Cart']['price']);
            $this->Session->write('Shop.Order.total', $totalsubtotal + $dcrt[0]['Cart']['price']);
            $this->Session->write('Shop.Order.weight', $weight_total + $dcrt[0]['Cart']['weight']);
        }
        $this->loadModel('Cart');
        $table_no = $this->Session->read('Cart.tableno');
        $sid = $this->Session->id();
        $data = $this->Cart->find('all', array('conditions' => array('AND' => array('Cart.tno' => $table_no, 'Cart.sessionid' => $sid))));
        if ($data) {
            $this->Session->write('Shop.Order.tax', '');
            $cart = $this->Session->read('Shop');
            $cnt = count($cart);
            $total = 0;
            foreach ($data as $d) {
                $total += $d['Cart']['quantity'] * $d['Cart']['price'];

                $k[$d['Cart']['product_id'] . '_' . $d['Cart']['tno']] = $d['Cart']['product_id'] . '_' . $d['Cart']['tno'];
            }
            $cart['Order']['subtotal'] = $total;
            $cart['Order']['total'] = $total;

            $getkey = array_intersect_key($cart['OrderItem'], $k);
            $cart['OrderItem'] = $getkey;
            $res_id = $cart['Order']['restaurant_id'];
            $this->loadModel('Tax');

            $d = $this->Tax->find('first', array('conditions' => array('Tax.resid' => $res_id)));
            if (empty($cart['Order']['tax'])) {
                if ($d) {
                    $add = ($cart['Order']['total'] * $d['Tax']['tax']) / 100;
                    //echo $add;
                    $tol = $cart['Order']['total'] + $add;
                    // echo $tol; exit;
                    $this->Session->write('Shop.Order.total', $tol);

                    $cart['Order']['tax'] = $add;
                    $cart['Order']['total'] = $tol;
                    $this->Session->write('Shop.Order.tax', $add);
                }
            }
        } else {
            $cart['OrderItem'] = NULL;
        }

        echo json_encode($cart);
        $this->autoRender = false;
        exit;
    }

    public function addremovecart() {
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $ses_id = $this->Session->id();
            $this->loadModel('Cart');
            $dcrt = $this->Cart->find('all', array(
                'conditions' => array(
                    'AND' => array(
                        'Cart.sessionid' => $ses_id,
                        'Cart.product_id' => $id
            ))));
            foreach ($dcrt as $d) {
                $qty = $d['Cart']['quantity'] + 1;
                $weight_total = $d['Cart']['weight_total'] + $d['Cart']['weight'];
                $subtotal = $d['Cart']['subtotal'] + $d['Cart']['price'];
            }
            $this->Cart->updateAll(array('Cart.subtotal' => $subtotal, 'Cart.quantity' => $qty, 'Cart.weight_total' => $weight_total), array('Cart.sessionid' => $ses_id, 'Cart.product_id' => $id)
            );
            $data = '';
            //$data['quantity']=$qty;
            $data['subtotal'] = $subtotal;
            $data['totalweight'] = $weight_total;
            $data['quantity'] = $qty;
            $this->Session->write('Shop.OrderItem.' . $id . '.quantity', $data['quantity']);
            $this->Session->write('Shop.OrderItem.' . $id . '.subtotal', $data['subtotal']);
            $this->Session->write('Shop.OrderItem.' . $id . '.totalweight', $data['totalweight']);
            $totalqty = $this->Session->read('Shop.Order.quantity');
            $totalsubtotal = $this->Session->read('Shop.Order.subtotal');
            $totalweight = $this->Session->read('Shop.Order.totalweight');
            $this->Session->write('Shop.Order.quantity', $totalqty + 1);
            $this->Session->write('Shop.Order.subtotal', $totalsubtotal + $dcrt[0]['Cart']['price']);
            $this->Session->write('Shop.Order.total', $totalsubtotal + $dcrt[0]['Cart']['price']);
            $this->Session->write('Shop.Order.weight', $weight_total + $dcrt[0]['Cart']['weight']);
        }
        $cart = $this->Session->read('Shop');
        echo json_encode($cart);
        $this->autoRender = false;
        exit;
    }

    public function addremovecartn() {
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            echo $id;
            $ses_id = $this->Session->id();
            $this->loadModel('Cart');
            $total = '';
            $dcrt = $this->Cart->find('all', array(
                'conditions' => array(
                    'AND' => array(
                        'Cart.sessionid' => $ses_id,
                        'Cart.product_id' => $id
            ))));
            debug($dcrt);
            foreach ($dcrt as $d) {
                $qty = $d['Cart']['quantity'] + 1;
                $weight_total = $d['Cart']['weight_total'] + $d['Cart']['weight'];
                $subtotal = $d['Cart']['subtotal'] + $d['Cart']['price'];
                $total += $subtotal;
            }
            $this->Cart->updateAll(array('Cart.subtotal' => $subtotal, 'Cart.quantity' => $qty, 'Cart.weight_total' => $weight_total), array('Cart.sessionid' => $ses_id, 'Cart.product_id' => $id)
            );
            $carts = $this->Cart->find('all', array(
                'conditions' => array(
                    'Cart.sessionid' => $ses_id)));
            $cart['OrderItem'] = $carts;
            $cart['Order']['subtotal'] = $total;
            //$data['quantity']=$qty;
        }

        echo json_encode($cart);
        $this->autoRender = false;
        exit;
    }

    public function admin_minusremovecart() {

        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $tid = $this->request->data['tid'];
            $ses_id = $this->Session->id();
            $this->loadModel('Cart');
            $dcrt = $this->Cart->find('all', array(
                'conditions' => array(
                    'AND' => array(
                        'Cart.sessionid' => $ses_id,
                        'Cart.product_id' => $id,
                        'Cart.tno' => $tid
            ))));
            $cnt = $dcrt[0]['Cart']['quantity'];
            if ($cnt > 1) {
                foreach ($dcrt as $d) {
                    $qty = $d['Cart']['quantity'] - 1;
                    $weight_total = $d['Cart']['weight_total'] - $d['Cart']['weight'];
                    $subtotal = $d['Cart']['subtotal'] - $d['Cart']['price'];
                }
                $this->Cart->updateAll(array('Cart.subtotal' => $subtotal, 'Cart.quantity' => $qty, 'Cart.weight_total' => $weight_total), array('Cart.sessionid' => $ses_id, 'Cart.product_id' => $id, 'Cart.tno' => $tid)
                );
                $data = '';
                //$data['quantity']=$qty;
                $data['subtotal'] = $subtotal;
                $data['totalweight'] = $weight_total;
                $data['quantity'] = $qty;
                $this->Session->write('Shop.OrderItem.' . $id . '.quantity', $data['quantity']);
                $this->Session->write('Shop.OrderItem.' . $id . '.subtotal', $data['subtotal']);
                $this->Session->write('Shop.OrderItem.' . $id . '.totalweight', $data['totalweight']);
                $totalqty = $this->Session->read('Shop.Order.quantity');
                $totalsubtotal = $this->Session->read('Shop.Order.subtotal');
                $totalweight = $this->Session->read('Shop.Order.totalweight');
                $this->Session->write('Shop.Order.quantity', $totalqty - 1);
                $this->Session->write('Shop.Order.subtotal', $totalsubtotal - $dcrt[0]['Cart']['price']);
                $this->Session->write('Shop.Order.total', $totalsubtotal - $dcrt[0]['Cart']['price']);
                $this->Session->write('Shop.Order.weight', $weight_total - $dcrt[0]['Cart']['weight']);
            }
        }
        $this->loadModel('Cart');
        $table_no = $this->Session->read('Cart.tableno');
        $sid = $this->Session->id();
        $data = $this->Cart->find('all', array('conditions' => array('AND' => array('Cart.tno' => $table_no, 'Cart.sessionid' => $sid))));
        if ($data) {
            $this->Session->write('Shop.Order.tax', '');
            $cart = $this->Session->read('Shop');
            $cnt = count($cart);
            $total = 0;
            foreach ($data as $d) {
                $total += $d['Cart']['quantity'] * $d['Cart']['price'];

                $k[$d['Cart']['product_id'] . '_' . $d['Cart']['tno']] = $d['Cart']['product_id'] . '_' . $d['Cart']['tno'];
            }
            $cart['Order']['subtotal'] = $total;
            $cart['Order']['total'] = $total;

            $getkey = array_intersect_key($cart['OrderItem'], $k);
            $cart['OrderItem'] = $getkey;
            $res_id = $cart['Order']['restaurant_id'];
            $this->loadModel('Tax');

            $d = $this->Tax->find('first', array('conditions' => array('Tax.resid' => $res_id)));
            if (empty($cart['Order']['tax'])) {
                if ($d) {
                    $add = ($cart['Order']['total'] * $d['Tax']['tax']) / 100;
                    //echo $add;
                    $tol = $cart['Order']['total'] + $add;
                    // echo $tol; exit;
                    $this->Session->write('Shop.Order.total', $tol);

                    $cart['Order']['tax'] = $add;
                    $cart['Order']['total'] = $tol;
                    $this->Session->write('Shop.Order.tax', $add);
                }
            }
        } else {
            $cart['OrderItem'] = NULL;
        }

        echo json_encode($cart);
        $this->autoRender = false;
        exit;
    }

    public function minusremovecart() {

        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $ses_id = $this->Session->id();
            $this->loadModel('Cart');
            $dcrt = $this->Cart->find('all', array(
                'conditions' => array(
                    'AND' => array(
                        'Cart.sessionid' => $ses_id,
                        'Cart.product_id' => $id
            ))));
            $cnt = $dcrt[0]['Cart']['quantity'];
            if ($cnt > 1) {
                foreach ($dcrt as $d) {
                    $qty = $d['Cart']['quantity'] - 1;
                    $weight_total = $d['Cart']['weight_total'] - $d['Cart']['weight'];
                    $subtotal = $d['Cart']['subtotal'] - $d['Cart']['price'];
                }
                $this->Cart->updateAll(array('Cart.subtotal' => $subtotal, 'Cart.quantity' => $qty, 'Cart.weight_total' => $weight_total), array('Cart.sessionid' => $ses_id, 'Cart.product_id' => $id)
                );
                $data = '';
                //$data['quantity']=$qty;
                $data['subtotal'] = $subtotal;
                $data['totalweight'] = $weight_total;
                $data['quantity'] = $qty;
                $this->Session->write('Shop.OrderItem.' . $id . '.quantity', $data['quantity']);
                $this->Session->write('Shop.OrderItem.' . $id . '.subtotal', $data['subtotal']);
                $this->Session->write('Shop.OrderItem.' . $id . '.totalweight', $data['totalweight']);
                $totalqty = $this->Session->read('Shop.Order.quantity');
                $totalsubtotal = $this->Session->read('Shop.Order.subtotal');
                $totalweight = $this->Session->read('Shop.Order.totalweight');
                $this->Session->write('Shop.Order.quantity', $totalqty - 1);
                $this->Session->write('Shop.Order.subtotal', $totalsubtotal - $dcrt[0]['Cart']['price']);
                $this->Session->write('Shop.Order.total', $totalsubtotal - $dcrt[0]['Cart']['price']);
                $this->Session->write('Shop.Order.weight', $weight_total - $dcrt[0]['Cart']['weight']);
            }
        }
        $cart = $this->Session->read('Shop');
        echo json_encode($cart);
        $this->autoRender = false;
        exit;
    }

    public function remove($id = null) {
        $product = $this->Cart->remove($id);
        if (!empty($product)) {
            $this->Session->setFlash($product['Product']['name'] . ' was removed from your shopping cart', 'flash_error');
        }
        return $this->redirect(array('action' => 'cart'));
    }

//////////////////////////////////////////////////

    public function cartupdate() {
        //debug($this->request->data['Product']);exit;

        if ($this->request->is('post')) {
            foreach ($this->request->data['Product'] as $key => $value) {
                $p = explode('-', $key);
                $p = explode('_', $p[1]);
                $this->Cart->add($p[0], $value, $p[1]);
            }
            $this->Session->setFlash('Shopping Cart is updated.', 'flash_success');
        }
        return $this->redirect(array('action' => 'cart'));
    }

//////////////////////////////////////////////////

    public function cart() {
        $shop = $this->Session->read('Shop');
        $this->set(compact('shop'));
    }

//////////////////////////////////////////////////

    public function address($id = NULL) {

        $shop = $this->Session->read('Shop');
        if (!$shop['Order']['total']) {
            return $this->redirect('/');
        }

        if ($this->request->is('post')) {
            $this->loadModel('Order');
            $this->Order->set($this->request->data);
            if ($this->Order->validates()) {
                $order = $this->request->data['Order'];
                $order['order_type'] = 'creditcard';
                $this->Session->write('Shop.Order', $order + $shop['Order']);
                return $this->redirect(array('action' => 'review'));
            } else {
                $this->Session->setFlash('The form could not be saved. Please, try again.', 'flash_error');
            }
        }
        if (!empty($shop['Order'])) {
            $this->request->data['Order'] = $shop['Order'];
        }
        $this->loadModel('Restaurant');
        $this->set('restaurant', $this->Restaurant->find('first', array('conditions' => array('Restaurant.id' => $id))));
        $this->set('day', date('d'));
    }

    /**
     * tableaddress
     * @param type $id
     */
    public function tableaddress($id = NULL) {
        Configure::write("debug", 0);
        $this->loadModel('Restaurant');
        $this->loadModel('Restable');
        $data = $this->Restable->find("all", array('conditions' => array('Restable.res_id' => $id)));
        $dta = $this->Restable->find("all", array('conditions' => array('AND' => array('Restable.res_id' => $id, 'Restable.booked' => 1))));
        if ($dta) {
            $tid = array();
            foreach ($dta as $d) {
                $tid[] = $d['Restable']['tableno'];
            }
        }
        $this->set('restable', $data);
        $this->set('booktable', $tid);
        $this->set('restaurant', $this->Restaurant->find('first', array('conditions' => array('Restaurant.id' => $id))));
    }

    public function tablesucess() {
        if ($this->request->is('post')) {
            $this->loadModel('TableReservation');
            $data = $this->request->data;
            if ($this->TableReservation->save($this->request->data)) {
                $data['TableReservation']['id'] = $this->TableReservation->getLastInsertID();
                $this->set('data', $data);
            } else {
                $this->Session->setFlash('The form could not be saved. Please, try again.', 'flash_error');
                return $this->redirect(array('action' => 'tablesucess'));
            }
        } else {
            return $this->redirect('/');
        }
    }

    public function api_tablesucess() {
        configure::write('debug', 2);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        ob_start();
        print_r($redata);
        $c = ob_get_clean();
        $fc = fopen('files' . DS . 'detail.txt', 'w');
        fwrite($fc, $c);
        fclose($fc);
        $this->layout = "ajax";
        if ($this->request->is('post')) {
            $data['fname'] = $redata->Table->fname;
            $data['lname'] = $redata->Table->lname;
            $data['res_id'] = $redata->Table->rid;
            $data['phone'] = $redata->Table->phone;
            $data['email'] = $redata->Table->email;
            $data['d_day'] = $redata->Table->date;
            $data['d_time'] = $redata->Table->time;
            $data['no_of_people'] = $redata->Table->number;
            $data['uid'] = $redata->Table->uid;
            $data['table_no'] = $redata->Table->tid;
            if ($redata->Table->tid == 0) {
                $this->loadModel('Restable');
                $resbook = $this->Restable->find('all', array('conditions' => array('AND' => array('Restable.res_id' => $redata->Table->rid, 'Restable.booked' => 0))));
                if (empty($resbook)) {
                    $data['table_no'] = "0";
                }
                foreach ($resbook as $resb) {
                    $update = $this->Restable->updateAll(array('Restable.booked' => 1), array('Restable.res_id' => $resb['Restable']['res_id'], 'Restable.tableno' => $resb['Restable']['tableno']));
                    $data['table_no'] = $resb['Restable']['tableno'];
                    break;
                }
            } else {
                $data['table_no'] = $data['table_no'];
            }

            $this->loadModel('TableReservation');
            if ($this->TableReservation->save($data)) {
                $response['id'] = $this->TableReservation->getLastInsertID();
                $d['referby'] = $redata->Table->uid;
                $d['promocode'] = base64_encode($response['id']);
                $d['orderid'] = $response['id'];
                $this->loadModel('Promocode');
                $this->Promocode->save($d);
                $response['data'] = "Your details has been shared with Restaurant,you will be notified when Restaurant confirm it. Thank you!";
                $response['data'] = $data;
                $response['table_no'] = base64_encode($data['table_no']);
            } else {
                $response['data'] = "null";
                $response['error'] = 1;
            }
        } else {
            $response['data'] = "null";
            $response['error'] = 1;
        }
        echo json_encode($response);
        $this->render('ajax');
        exit;
    }

    public function api_tableconfirm() {
        configure::write('debug', 2);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        ob_start();
        print_r($redata);
        $c = ob_get_clean();
        $fc = fopen('files' . DS . 'detail.txt', 'w');
        fwrite($fc, $c);
        fclose($fc);
        $this->layout = "ajax";
        $email = $redata->User->email;
        if ($this->request->is('post') && $email) {
            $data = $this->loadModel('TableReservation');
            $data = $this->TableReservation->find('all', array(
                'conditions' => array(
                    'AND' => array(
                        'TableReservation.email' => $email
            ))));
            $this->loadModel('Restaurant');
            $data1 = array();
            foreach ($data as $k)
                array_push($data1, $this->Restaurant->find('first', array(
                            'conditions' => array(
                                'AND' => array(
                                    'Restaurant.id' => $k['TableReservation']['res_id']
                )))));
            $data2 = array();
            foreach ($data1 as $l)
                array_push($data2, FULL_BASE_URL . $this->webroot . "files/restaurants/" . $l['Restaurant']['logo']);

            if ($data) {
                $response['error'] = "0";
                $response['data'] = $data;
            }
            if ($data1) {
                $response['data1'] = $data1;
                $response['error'] = "0";
            }


            if ($data2) {
                $response['data2'] = $data2;
                $response['error'] = "0";
            }
        }
        echo json_encode($response);
        $this->render('ajax');
        exit;
    }

//////////////////////////////////////////////////

    public function step1() {
        $paymentAmount = $this->Session->read('Shop.Order.total');
        if (!$paymentAmount) {
            return $this->redirect('/');
        }
        $this->Session->write('Shop.Order.order_type', 'paypal');
        $this->Paypal->step1($paymentAmount);
    }

//////////////////////////////////////////////////

    public function step2() {

        $token = $this->request->query['token'];
        $paypal = $this->Paypal->GetShippingDetails($token);
        //print_r($paypal);exit;

        $ack = strtoupper($paypal['ACK']);
        if ($ack == 'SUCCESS' || $ack == 'SUCESSWITHWARNING') {
            $this->Session->write('Shop.Paypal.Details', $paypal);
            return $this->redirect(array('action' => 'review'));
        } else {
            $ErrorCode = urldecode($paypal['L_ERRORCODE0']);
            $ErrorShortMsg = urldecode($paypal['L_SHORTMESSAGE0']);
            $ErrorLongMsg = urldecode($paypal['L_LONGMESSAGE0']);
            $ErrorSeverityCode = urldecode($paypal['L_SEVERITYCODE0']);
            echo 'GetExpressCheckoutDetails API call failed. ';
            echo 'Detailed Error Message: ' . $ErrorLongMsg;
            echo 'Short Error Message: ' . $ErrorShortMsg;
            echo 'Error Code: ' . $ErrorCode;
            echo 'Error Severity Code: ' . $ErrorSeverityCode;
            die();
        }
    }

//////////////////////////////////////////////////   
    public function review() {
        Configure::write("debug", 0);
        $shop = $this->Session->read('Shop');
        // print_r($shop);
        $uid = $this->Auth->user('id');
        if (empty($shop) || empty($uid)) {
            return $this->redirect('/');
        }
        $this->loadModel('User');
        $uid = $this->Auth->user('id');
        $user = $this->User->find('first', array('conditions' => array('User.id' => $uid)));

        if ($this->request->is('post')) {
            $this->loadModel('User');
            $uid = $this->Auth->user('id');
            $pst_Data = $this->request->data;
            // debug($this->request->data);exit;
            $this->loadModel('Order');
            $this->Order->set($this->request->data);
            if ($this->Order->validates()) {
                $order = $shop;
                if ($pst_Data['payment_method'] == 'wallet') {
                    if ($user['User']['loyalty_points'] < $order['Order']['total']) {
                        $this->Session->setFlash($user['User']['loyalty_points'] . ' is in your wallet so please add amount in your cart', 'flash_success');
                        return $this->redirect(array('action' => 'review'));
                    } else {
                        $user['User']['loyalty_points'] = $user['User']['loyalty_points'] - $order['Order']['total'];
                        $this->User->updateAll(array('User.loyalty_points' => $user['User']['loyalty_points']), array('User.id' => $uid));
                        $order['Order']['status'] = 1;
                        $order['Order']['order_type'] = $pst_Data['payment_method'];
                        $save = $this->Order->saveAll($order, array('validate' => 'first'));
                        if ($save) {
                            $this->set(compact('shop'));
                            $email = new CakeEmail();
                            $email->from('restaurants@test.com')
                                    //->cc(Configure::read('Settings.ADMIN_EMAIL'))
                                    ->cc('ashutosh@avainfotech.com')
                                    ->to($shop['Order']['email'])
                                    ->subject('Shop Order')
                                    ->template('order')
                                    ->emailFormat('html')
                                    ->viewVars(array('shop' => $shop))
                                    ->send('html');
                            return $this->redirect(array('action' => 'success'));
                        }
                    }
                }

                if ($pst_Data['payment_method'] == 'paypal') {
                    $val = $order['Order']['total'];
                    $order['Order']['status'] = 0;
                    $order['Order']['order_type'] = $pst_Data['payment_method'];
                    $save = $this->Order->saveAll($order, array('validate' => 'first'));
                    $order_email = $order['Order']['email'];
                    if ($save) {

                        $this->set(compact('shop'));
                        $email = new CakeEmail();
                        $email->from('restaurants@test.com')
                                //->cc(Configure::read('Settings.ADMIN_EMAIL'))
                                ->cc('ashutosh@avainfotech.com')
                                ->to($shop['Order']['email'])
                                ->subject('Shop Order')
                                ->template('order')
                                ->emailFormat('html')
                                ->viewVars(array('shop' => $shop))
                                ->send('html');

//                           print_r($shop);
//                           exit;
                        // return $this->redirect(array('action' => 'success'));

                        $last_id = $this->Order->getLastInsertId();
                        ///////////////////////////////////////////////payment////////////////////////////////////////////////
                        echo ".<form name=\"_xclick\" action=\"https://www.sandbox.paypal.com/cgi-bin/webscr\" method=\"post\">
                    <input type=\"hidden\" name=\"cmd\" value=\"_xclick\">
                    <input type=\"hidden\" name=\"email\" value=\"$order_email\">
                    <input type=\"hidden\" name=\"business\" value=\"ashutosh@avainfotech.com\">
                    <input type=\"hidden\" name=\"currency_code\" value=\"USD\">
                    <input type=\"hidden\" name=\"custom\" value=\"$last_id\">
                    <input type=\"hidden\" name=\"amount\" value=\"$val\">
                    <input type=\"hidden\" name=\"return\" value=\"http://rajdeep.crystalbiltech.com/ecasnik/shop/success\">
                    <input type=\"hidden\" name=\"notify_url\" value=\"http://rajdeep.crystalbiltech.com/ecasnik/shop/ipn\"> 
                    </form>";
//                    exit;
                        echo "<script>document._xclick.submit();</script>";
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////
                    }
                }

                if ((Configure::read('Settings.AUTHORIZENET_ENABLED') == 1) && $pst_Data['payment_method'] == 'creditcard') {
                    //echo "working ";
                    //exit;
//                    $payment = array(
//                        'creditcard_number' => $this->request->data['Order']['creditcard_number'],
//                        'creditcard_month' => $this->request->data['Order']['creditcard_month'],
//                        'creditcard_year' => $this->request->data['Order']['creditcard_year'],
//                        'creditcard_code' => $this->request->data['Order']['creditcard_code'],
//                    );
//                    try {
//                        $authorizeNet = $this->AuthorizeNet->charge($shop['Order'], $payment);
//                    } catch (Exception $e) {
//                        $this->Session->setFlash($e->getMessage());
//                        return $this->redirect(array('action' => 'review'));
//                    }
//                    $order['Order']['authorization'] = $authorizeNet[4];
//                    $order['Order']['transaction'] = $authorizeNet[6];
                }
                if ($pst_Data['payment_method'] == 'cod') {
                    $order['Order']['status'] = 1;
                    $order['Order']['order_type'] = $pst_Data['payment_method'];
                    $save = $this->Order->saveAll($order, array('validate' => 'first'));
                    if ($save) {

                        $this->set(compact('shop'));
                        $email = new CakeEmail();
                        $email->from('restaurants@test.com')
                                //->cc(Configure::read('Settings.ADMIN_EMAIL'))
                                ->cc('ashutosh@avainfotech.com')
                                ->to($shop['Order']['email'])
                                ->subject('Shop Order')
                                ->template('order')
                                ->emailFormat('html')
                                ->viewVars(array('shop' => $shop))
                                ->send('html');
                        return $this->redirect(array('action' => 'success'));
                    } else {
                        $errors = $this->Order->invalidFields();
                        $this->set(compact('errors'));
                    }
                }
            }
        }
        $this->set('walletmoney', $user);
        $this->set(compact('shop'));
    }

    public function ipn() {
        $fc = fopen('files/ipn.txt', 'wb');
        ob_start();
        print_r($this->request);
        $req = 'cmd=' . urlencode('_notify-validate');
        foreach ($_POST as $key => $value) {
            $value = urlencode(stripslashes($value));
            $req .= "&$key=$value";
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.sandbox.paypal.com/cgi-bin/webscr');
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.developer.paypal.com'));
        $res = curl_exec($ch);
        curl_close($ch);
        if (strcmp($res, "VERIFIED") == 0) {
            $custom_field = $_POST['custom'];
            $payer_email = $_POST['payer_email'];
            $trn_id = $_POST['txn_id'];
            $pay = $_POST['mc_gross'];
            $this->loadModel('Order');
            $this->Order->query("UPDATE `orders` SET `status` = 1, `paypal_status` = '$res',`paypal_transaction_id`='$trn_id', `paypal_price`='$pay' WHERE `id` ='$custom_field';");
            $l = new CakeEmail('smtp');
            $l->emailFormat('html')->template('default', 'default')->subject('Payment')->to($payer_email)->send('Payment Done Successfully');
            $this->set('smtp_errors', "none");
        } else if (strcmp($res, "INVALID") == 0) {
            
        }
        $xt = ob_get_clean();
        fwrite($fc, $xt);
        fclose($fc);
        exit;
        //$this->render('payment_confirm', 'ajax');
    }

//////////////////////////////////////////////////

    public function success() {
        $shop = $this->Session->read('Shop');

        $this->Cart->clear();
        if (empty($shop)) {
            return $this->redirect('/');
        }
        $this->set(compact('shop'));
    }

//////////////////////////////////////////////////

    public function app_add() {
        if ($this->request->is('post')) {

            $id = $this->request->data['Product']['id'];

            $quantity = isset($this->request->data['Product']['quantity']) ? $this->request->data['Product']['quantity'] : null;

            $productmodId = isset($this->request->data['mods']) ? $this->request->data['mods'] : null;

            $product = $this->Cart->add($id, $quantity, $productmodId);
        }
        if (!empty($product)) {
            $this->Session->setFlash($product['Product']['name'] . ' was added to your shopping cart.', 'flash_success');
        } else {
            $this->Session->setFlash('Unable to add this product to your shopping cart.', 'flash_error');
        }
        echo json_encode($response);
        exit;
    }

    /*     * ************************************** Api******************************************* */

    /**
     * @name $postdata
     * @param  api_cart
     */
    public function api_cart() {
        configure::write('debug', 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        ob_start();
        print_r($redata);
        $c = ob_get_clean();
        $fc = fopen('files' . DS . 'detail.txt', 'w');
        fwrite($fc, $c);
        fclose($fc);
        $id = $redata->Product->id;
        $uid = $redata->User->uid;
        $quantity = $redata->Quantity->qty;
        $sid = $redata->SnId->sid;
        $productmodId = "";
        //  pr($d=$this->Cart->checkcrt($sid,$id));exit;
        if (!empty($redata)) {
            $d = $this->Cart->checkcrt($sid, $id);
            if (!empty($d)) {
                $response['error'] = "0";
                $response['data'] = "Product already added in the cart";
            } else {
                if ($this->Cart->appadd($id, $quantity, $productmodId, $uid, $sid)) {
                    $response['error'] = "0";
                    $response['data'] = "cart has been updated!";
                } else {
                    $response['error'] = "1";
                    $response['data'] = "error";
                }
            }
        }
        echo json_encode($response);
        exit;
    }

    public function api_cartqr() {
        configure::write('debug', 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        ob_start();
        print_r($redata);
        $c = ob_get_clean();
        $fc = fopen('files' . DS . 'detail.txt', 'w');
        fwrite($fc, $c);
        fclose($fc);
        $id = $redata->Product->id;
        $uid = $redata->User->uid;
        $quantity = $redata->Quantity->qty;
        $sid = $redata->SnId->sid;
        $tid = $redata->tnid->tid;
        $resid = $redata->resID->rid;
        $productmodId = "";
        //  pr($d=$this->Cart->checkcrt($sid,$id));exit;
        if (!empty($redata)) {
            $d = $this->Cart->checkcrtqr($id, $uid, $tid, $resid);
            if (!empty($d)) {
                $response['error'] = "0";
                $response['data'] = "Product already added in the cart";
            } else {
                if ($this->Cart->appaddqr($id, $quantity, $productmodId, $uid, $sid, $tid)) {
                    $response['error'] = "0";
                    $response['data'] = "cart has been updated!";
                } else {
                    $response['error'] = "1";
                    $response['data'] = "error";
                }
            }
        }
        echo json_encode($response);
        exit;
    }

    public function api_displaycartqr() {
        configure::write('debug', 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        ob_start();
        print_r($redata);
        $c = ob_get_clean();
        $fc = fopen('files' . DS . 'detail.txt', 'w');
        fwrite($fc, $c);
        fclose($fc);
        $uid = $redata->User->uid;
        $rid = $redata->resId->rid;
        $tid = $redata->Tid->tid;
        if (!empty($redata)) {
            $data = $this->Cart->appcartqr($rid, $tid);
            $response['error'] = "0";
            $response['data'] = $data;
        } else {
            $response['error'] = "1";
            $response['data'] = "error";
        }

        echo json_encode($response);
        exit;
    }

    public function api_displaycart() {
        configure::write('debug', 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        ob_start();
        print_r($redata);
        $c = ob_get_clean();
        $fc = fopen('files' . DS . 'detail.txt', 'w');
        fwrite($fc, $c);
        fclose($fc);
        $uid = $redata->User->uid;
        $sid = $redata->SnId->sid;
        if (!empty($redata)) {
            $data = $this->Cart->appcart($uid, $sid);
            $response['error'] = "0";
            $response['data'] = $data;
        } else {
            $response['error'] = "1";
            $response['data'] = "error";
        }

        echo json_encode($response);
        exit;
    }

    public function api_tablehistry() {
        configure::write('debug', 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        ob_start();
        print_r($redata);
        $c = ob_get_clean();
        $fc = fopen('files' . DS . 'detail.txt', 'w');
        fwrite($fc, $c);
        fclose($fc);
        $uid = $redata->User->uid;
        $this->loadModel('TableReservation');
        $this->TableReservation->recursive = 1;
        if (!empty($redata)) {
            $data = $this->TableReservation->find('all', array('conditions' => array('TableReservation.uid' => $uid)));
            $response['error'] = "0";
            $response['data'] = $data;
        } else {
            $response['error'] = "1";
            $response['data'] = "error";
        }

        echo json_encode($response);
        exit;
    }

    public function tablehistry() {
        configure::write('debug', 0);
        $uid = $this->Auth->user('id');
        $this->loadModel('TableReservation');
        $data = $this->TableReservation->find('all', array('conditions' => array('TableReservation.uid' => $uid)));
        $this->set('data', $data);
    }

    public function api_increaseqty() {
        configure::write('debug', 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);


        $id = $redata->Cart->id;
        $this->loadModel('Cart');
        $data = $this->Cart->find('all', array('conditions' => array('Cart.id' => $id)));

        foreach ($data as $d) {
            $qty = $d['Cart']['quantity'] + 1;
            $weight_total = $d['Cart']['weight_total'] + $d['Cart']['weight'];
            $subtotal = $d['Cart']['subtotal'] + $d['Cart']['price'];
        }

        $this->Cart->updateAll(array('Cart.subtotal' => $subtotal, 'Cart.quantity' => $qty, 'Cart.weight_total' => $weight_total), array('Cart.id' => $id)
        );

        if (!empty($redata)) {

            $response['error'] = "0";
        } else {
            $response['error'] = "1";
        }

        echo json_encode($response);

        exit;
    }

    public function api_decreaseqty() {
        configure::write('debug', 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);


        $id = $redata->Cart->id;
        $this->loadModel('Cart');
        $d = $this->Cart->find('all', array('conditions' => array('Cart.id' => $id)));


        if ($d[0]['Cart']['quantity'] > 1) {
            $qty = $d[0]['Cart']['quantity'] - 1;
            $weight_total = $d[0]['Cart']['weight_total'] - $d[0]['Cart']['weight'];
            $subtotal = $d[0]['Cart']['subtotal'] - $d[0]['Cart']['price'];



            $this->Cart->updateAll(array('Cart.subtotal' => $subtotal, 'Cart.quantity' => $qty, 'Cart.weight_total' => $weight_total), array('Cart.id' => $id)
            );
        }
        if (!empty($redata)) {

            $response['error'] = "0";
        } else {
            $response['error'] = "1";
        }

        echo json_encode($response);

        exit;
    }

    private function removeappcart($id = NULL) {
        if ($id) {
            $this->loadModel('Cart');
            $data = $this->Cart->deleteAll(array('Cart.uid' => $id), false);
        }
    }

    public function api_checkout() {
        
        configure::write('debug', 0);
//             $this->loadModel('Order');
//             $this->Order->recursive=2;
//            $data=$this->Order->find('first',array('conditions'=>array('Order.id'=>49)));
//            print_r($data);

        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        ob_start();
        print_r($redata);
        $c = ob_get_clean();
        $fc = fopen('files' . DS . 'details.txt', 'w');
        fwrite($fc, $c);
        fclose($fc);
        // exit;
        if ($redata) {
            $order = array();
            $order['Order']['order_type'] = $redata->payment->mode;
            $order['Order']['uid'] = $redata->User->id;
            $order['Order']['total'] = $redata->payment->total;
            // $order['Order']['order_item_count'] = $redata->products->prod->data[0]->order_item_count;
            $order['Order']['quantity'] = $redata->products->prod->data[0]->quantity;
            $order['Order']['weight'] = $redata->products->prod->data[0]->weight;
            $order['Order']['subtotal'] = $redata->products->prod->data[0]->subtotal;
            $order['Order']['status'] = 1;
            $order['Order']['shop'] = 1;
            $order['Order']['first_name'] = $redata->address->billing->fname;
            $order['Order']['last_name'] = $redata->address->billing->lname;
            $order['Order']['phone'] = $redata->address->billing->phone;
            $order['Order']['email'] = $redata->address->billing->email;
            $order['Order']['billing_address'] = $redata->address->billing->address;
            $order['Order']['billing_city'] = $redata->address->billing->city;
            $order['Order']['billing_state'] = $redata->address->billing->state;
            $order['Order']['billing_zip'] = $redata->address->billing->zip;
            $order['Order']['shipping_address'] = $redata->address->shipping->address;
            $order['Order']['shipping_city'] = $redata->address->shipping->city;
            $order['Order']['shipping_state'] = $redata->address->shipping->state;
            $order['Order']['shipping_zip'] = $redata->address->shipping->zip;
            $order['Order']['notes'] = $redata->notes->notes;
            if ($redata->delivery->status) {
                $order['Order']['delivery_status'] = $redata->delivery->status;
            }
            if ($redata->Table->no) {
                $order['Order']['table_no'] = $redata->Table->no;
            }
            if ($redata->paypal->paymentid) {
                $order['Order']['paypal_transaction_id'] = $redata->paypal->paymentid;
                $order['Order']['paypal_status'] = $redata->paypal->status;
            }
            $arr = array();
            foreach ($redata->products->prod->data[1] as $key => $value) {
                $ky = $redata->products->prod->data[1][$key]->Cart->product_id;
                $order['OrderItem'][$ky . '_0']['product_id'] = $redata->products->prod->data[1][$key]->Cart->product_id;
                $order['OrderItem'][$ky . '_0']['name'] = $redata->products->prod->data[1][$key]->Cart->name;
                $order['OrderItem'][$ky . '_0']['weight'] = $redata->products->prod->data[1][$key]->Cart->weight;
                $order['OrderItem'][$ky . '_0']['price'] = $redata->products->prod->data[1][$key]->Cart->price;
                $order['OrderItem'][$ky . '_0']['quantity'] = $redata->products->prod->data[1][$key]->Cart->quantity;
                $order['OrderItem'][$ky . '_0']['subtotal'] = $redata->products->prod->data[1][$key]->Cart->subtotal;
                //$order['OrderItem'][$ky.'_0']['totalweight']=$redata->products->prod->data[1][$key]->Cart->totalweight;
                $order['OrderItem'][$ky . '_0']['Product'] = (array) $redata->products->prod->data[1][$key]->Cart;
                $order['Order']['restaurant_id'] = $redata->products->prod->data[1][$key]->Cart->resid;
            }
            ob_start();
            print_r($order);
            $c = ob_get_clean();
            $fc = fopen('files' . DS . 'detail.txt', 'w');
            fwrite($fc, $c);
            fclose($fc);
            $this->loadModel('Order');
            $save = $this->Order->saveAll($order, array('validate' => 'first'));
            $order_id = $this->Order->getInsertID();
            if ($save) {

                $this->removeappcart($redata->User->id);
//            App::uses('CakeEmail', 'Network/Email');
//            $email = new CakeEmail();
//            $email->from('restaurants@test.com')
//                    ->cc('ashutosh@avainfotech.com')
//                    ->to($order['Order']['email'])
//                    ->subject('Shop Order')
//                    ->template('order')
//                    ->emailFormat('text')
//                    ->viewVars(array('shop' => $order))
//                    ->send();
               $this->Order->recursive = 2;
               $data = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id)));
               $cnt = count($data['OrderItem']); 
               for($i=0;$i<$cnt;$i++){
               $data['OrderItem'][$i]['Product']['image'] = FULL_BASE_URL . $this->webroot . "files/product/" . $data['OrderItem'][$i]['Product']['image'];            
               }
               $response['error'] = "0";
                $response['data'] = $data;
            } else {
                $response['error'] = "1";
                $response['data'] = "Error";
            }
        }
        echo json_encode($response);
        exit;
    }

    public function api_removeitems() {
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        ob_start();
        print_r($redata);
        $c = ob_get_clean();
        $fc = fopen('files' . DS . 'detail.txt', 'w');
        fwrite($fc, $c);
        fclose($fc);
        $id = $redata->Cart->id;
        $this->loadModel('Cart');
        if (!empty($redata)) {
            $this->Cart->id = $id;
            $data = $this->Cart->delete();
            $response['error'] = "0";
            $response['data'] = $data;
        } else {
            $response['error'] = "1";
            $response['data'] = "error";
        }

        echo json_encode($response);
        exit;
    }

    public function api_removeitemsall() {
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        ob_start();
        print_r($redata);
        $c = ob_get_clean();
        $fc = fopen('files' . DS . 'detail.txt', 'w');
        fwrite($fc, $c);
        fclose($fc);
        $id = $redata->User->uid;
        $this->loadModel('Cart');
        if (!empty($redata)) {
            $data = $this->Cart->deleteAll(array('Cart.uid' => $id), false);
            $response['error'] = "0";
            $response['data'] = $data;
        } else {
            $response['error'] = "1";
            $response['data'] = "error";
        }
        echo json_encode($response);
        exit;
    }

    public function checkpin() {
        if ($this->request->is('ajax')) {
            $id = $this->request->data['id'];
            $rid = $this->request->data['rid'];
            $ses_id = $this->Session->id();
            $this->loadModel('Picode');
            $this->loadModel('Cart');
            $dcrt = $this->Picode->find('first', array(
                'conditions' => array(
                    'AND' => array(
                        'Picode.res_id' => $rid,
                        'Picode.pincode' => $id
            ))));

            if ($dcrt) {
                $dlcharge = $this->Session->read('Shop.Order.dlcharge');
                $pin = $this->Session->read('Shop.Order.pin');

                if (empty($dlcharge)) {
                    $totalsubtotal = $this->Session->read('Shop.Order.subtotal');
                    $totalweight = $this->Session->read('Shop.Order.totalweight');
                    $this->Session->write('Shop.Order.subtotal', $totalsubtotal);
                    $this->Session->write('Shop.Order.total', $totalsubtotal + $dcrt['Picode']['price']);
                    $this->Session->write('Shop.Order.dlcharge', $dcrt['Picode']['price']);
                    $this->Session->write('Shop.Order.pin', $id);
                } else if ($pin != $id) {
                    $totalsubtotal = $this->Session->read('Shop.Order.subtotal');
                    $totalweight = $this->Session->read('Shop.Order.totalweight');
                    $this->Session->write('Shop.Order.subtotal', $totalsubtotal);
                    $this->Session->write('Shop.Order.total', $totalsubtotal - $dlcharge);
                    $totalsubtotal = $this->Session->read('Shop.Order.subtotal');
                    $totalweight = $this->Session->read('Shop.Order.totalweight');
                    $this->Session->write('Shop.Order.subtotal', $totalsubtotal);
                    $this->Session->write('Shop.Order.total', $totalsubtotal + $dcrt['Picode']['price']);
                    $this->Session->write('Shop.Order.dlcharge', $dcrt['Picode']['price']);
                    $this->Session->write('Shop.Order.pin', $id);
                }
                $response['status'] = true;
                $response['cart'] = $this->Session->read('Shop');
                // $cart = $this->Session->read('Shop');
                echo json_encode($response);
            } else {
                $response['status'] = false;
                $response['cart'] = 'No data found';
                echo json_encode($response);
            }
        }
//        $cart = $this->Session->read('Shop');
//        echo json_encode($cart);
        $this->autoRender = false;
    }

    public function updateTotal() {
        if ($this->request->is('ajax')) {
            $totalsubtotal = $this->Session->read('Shop.Order.subtotal');
            $totalweight = $this->Session->read('Shop.Order.totalweight');
            $dlcharge = $this->Session->read('Shop.Order.dlcharge');
            $tax = $this->Session->read('Shop.Order.tax');
            $this->Session->write('Shop.Order.subtotal', $totalsubtotal);
            $this->Session->write('Shop.Order.total', $totalsubtotal + $dlcharge + $tax);
            $this->Session->write('Shop.Order.delivery_status', 0);
            $response['status'] = true;
        } else {
            $response['status'] = false;
        }
        $response['cart'] = $this->Session->read('Shop');
        echo json_encode($response);
        $this->autoRender = false;
    }

    public function takeawaypin() {
        if ($this->request->is('ajax')) {
            $tax = $this->Session->read('Shop.Order.tax');
            if ($tax) {
                $totalsubtotal = $this->Session->read('Shop.Order.subtotal');
                $totalweight = $this->Session->read('Shop.Order.totalweight');
                $this->Session->write('Shop.Order.subtotal', $totalsubtotal);
                $this->Session->write('Shop.Order.total', $totalsubtotal + $tax);
                $this->Session->write('Shop.Order.delivery_status', 1);
                $this->Session->delete('Shop.Order.dlcharge');
            }
        }
        $cart = $this->Session->read('Shop');
        echo json_encode($cart);
        $this->autoRender = false;
    }

    public function api_orderHistory() {
        Configure::write("debug", 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        ob_start();
        print_r($redata);
        $c = ob_get_clean();
        $fc = fopen('files' . DS . 'detail.txt', 'w');
        fwrite($fc, $c);
        fclose($fc);
        $id = $redata->User->id;
        $this->loadModel('Order');
        

        if (!empty($redata)) {
            $this->Order->recursive = 2;
            $data = $this->Order->find('all', array('conditions' => array('Order.uid' => $id)));
            
             $cnt = count($data);
            for ($i = 0; $i < $cnt; $i++) {
                $data[$i]['Restaurant']['logo'] = FULL_BASE_URL . $this->webroot . "files/restaurants/" . $data[$i]['Restaurant']['logo'];
               $data[$i]['OrderItem'][0]['Product']['image'] = FULL_BASE_URL . $this->webroot . "files/product/" . $data[$i]['OrderItem'][0]['Product']['image'];
            }

            $response['error'] = "0";
            $response['data'] = $data;
        } else {
            $response['error'] = "1";
            $response['data'] = "error";
        }
        echo json_encode($response);
        exit;
    }

    public function api_allcountry() {

        Configure::write("debug", 2);
        $this->loadModel('Country');
        //$this->Country->recursive=2;
        $data = $this->Country->find('all');
        // print_r($data);exit;
        if (!empty($data)) {
            $response['error'] = "0";
            $response['data'] = $data;
        } else {
            $response['error'] = "1";
            $response['data'] = "error";
        }
        echo json_encode($response);
        exit;
    }

    public function api_allcity() {
        Configure::write("debug", 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        $id = $redata->Country->id;
        $this->loadModel('City');
        if (!empty($redata)) {
            //$this->City->recursive = 2;
            $data = $this->City->find('all', array('conditions' => array('City.country_id' => $id)));
            $response['error'] = "0";
            $response['data'] = $data;
        } else {
            $response['error'] = "1";
            $response['data'] = "error";
        }
        echo json_encode($response);
        exit;
    }

    public function api_review() {
        configure::write('debug', 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        ob_start();
        // print_r($redata);
        // $resid = $redata->Review->resid;
        $uid = $redata->Review->uid;
        $text = $redata->Review->text;
        $resid = $redata->Review->resid;
        $email = $redata->Review->email;
        $food_quality = $redata->Review->food_quality;
        $price = $redata->Review->price;
        $punctuality = $redata->Review->price;
        $courtesy = $redata->Review->courtesy;
        $name = $redata->Review->name;

        $c = ob_get_clean();
        $fc = fopen('files' . DS . 'review.txt', 'w');
        fwrite($fc, $c);
        fclose($fc);
        if ($redata) {
            $this->loadModel('Review');
            $this->loadModel('Restaurant');

            $this->request->data['Review']['resid'] = $resid;
            $this->request->data['Review']['name'] = $name;
            $this->request->data['Review']['email'] = $email;
            $this->request->data['Review']['food_quality'] = $food_quality;
            $this->request->data['Review']['price'] = $price;
            $this->request->data['Review']['punctuality'] = $punctuality;
            $this->request->data['Review']['courtesy'] = $courtesy;
            $this->request->data['Review']['text'] = $text;
            $this->request->data['Review']['uid'] = $uid;

            //debug($this->request->data);exit;
            $avg_rtng = $food_quality + $price + $punctuality + $courtesy;
            $cnt = $this->Review->find('count', array('conditions' => array('AND' => array('Review.uid' => $uid, 'Review.resid' => $resid))));
            if ($cnt == 0) {
                $this->Review->save($this->request->data);
                $resrev = $this->Restaurant->find('first', array('conditions' => array('Restaurant.id' => $resid)));
                $rc = $resrev['Restaurant']['review_count'] + 1;
                $avrc = $resrev['Restaurant']['total_avr'] + $avg_rtng;
                ob_start();
                //echo $avrc;
                //echo "here ";
                //echo $rc;
                //echo "here1 ";
                $avg_rtng = ($avrc / $rc) / 4;

                $c = ob_get_clean();
                $fc = fopen('files' . DS . 'review.txt', 'w');
                fwrite($fc, $c);
                fclose($fc);
                $this->Restaurant->updateAll(array('Restaurant.review_count' => $rc, 'Restaurant.review_avg' => $avg_rtng, 'Restaurant.total_avr' => $avrc), array('Restaurant.id' => $resid));
                $response['error'] = "0";
                $response['rating'] = $avg_rtng;
                $response['msg'] = "You have submitted the review";
            } else {
                $response['error'] = "1";
                $response['msg'] = "You have been already submitted the review";
            }
        }
        echo json_encode($response);
        exit;
    }

    public function api_webtime() {
        //print_r($d);exit;
        Configure::write("debug", 0);
        $start = strtotime('12:00 AM');
        $end = strtotime('11:59 PM');
        $time3 = strtotime(date('h:i A', strtotime('+30 minutes', time())));
        $id = $_POST['id'];
        if ($id == 1) {
            for ($i = $time3; $i <= $end; $i+=1800) {
                $time[] = date('G:i', $i);
            }
        } else {
            for ($i = $start; $i <= $end; $i+=1800) {
                $time[] = date('G:i', $i);
            }
        }
        $d = array();
        for ($i = 0; $i < 29; $i++) {
            $d[] = date("d-m-Y", strtotime('+' . $i . ' days'));
        }
        $response['day'] = $d;
        $response['time'] = $time;

        echo json_encode($response);
        exit;
    }

    public function api_time() {
        //print_r($d);exit;
        Configure::write("debug", 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        $start = strtotime('12:00 AM');
        $end = strtotime('11:59 PM');
        $time3 = strtotime(date('h:i A', strtotime('+30 minutes', time())));
        $id = $redata->time->id;
        if ($id == 1) {
            for ($i = $time3; $i <= $end; $i+=1800) {
                $time[] = date('G:i', $i);
            }
        } else {
            for ($i = $start; $i <= $end; $i+=1800) {
                $time[] = date('G:i', $i);
            }
        }
        $d = array();
        for ($i = 0; $i < 29; $i++) {
            $d[] = date("d-m-Y", strtotime('+' . $i . ' days'));
        }
        $response['day'] = $d;
        $response['time'] = $time;

        echo json_encode($response);
        exit;
    }

    public function api_displayreviews() {
        configure::write('debug', 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        ob_start();
        print_r($redata);
        $uid = $redata->Review->uid;
        $resid = $redata->Review->resid;

        $c = ob_get_clean();
        $fc = fopen('files' . DS . 'detail.txt', 'w');
        fwrite($fc, $c);
        fclose($fc);
        if ($this->request->is('post')) {
            $this->loadModel('Review');
            $this->loadModel('User');

            $data = $this->Review->find('all', array(
                'joins' => array(
                    array(
                        'table' => 'users',
                        'alias' => 'UserJoin',
                        'type' => 'INNER',
                        'conditions' => array(
                            'UserJoin.id = Review.uid',
                            'Review.resid = "' . $resid . '"'
                        )
                    )
                ),
                'fields' => array('UserJoin.*', 'Review.*')
            ));
            for ($i = 0; $i < count($data); $i++) {
                if ($data[$i]['UserJoin']['image']) {
                    $data[$i]['UserJoin']['image'] = FULL_BASE_URL . $this->webroot . "files/profile_pic/" . $data[$i]['UserJoin']['image'];
                } else {
                    $data[$i]['UserJoin']['image'] = FULL_BASE_URL . $this->webroot . "files/profile_pic/noimagefound.jpg";
                }
            }
        }
        if (!empty($data)) {

            $response['error'] = "0";
            $response['data'] = $data;
        } else {
            $response['error'] = "1";
            $response['msg'] = "error";
        }

        echo json_encode($response);
        exit;
    }

    public function api_displaycarttable() {
        configure::write('debug', 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        ob_start();
        print_r($redata);
        $c = ob_get_clean();
        $fc = fopen('files' . DS . 'detail.txt', 'w');
        fwrite($fc, $c);
        fclose($fc);
        $this->loadModel('Cart');
        $resid = $redata->Restaurant->resid;
        $tno = $redata->Table->no;
        if (!empty($redata)) {
            $data = $this->Cart->find('all', array('conditions' => array('Cart.resid' => $resid, 'Cart.tno' => $tno)));
            $cnt = count($data);
            for ($i = 0; $i < $cnt; $i++) {
                $data[$i]['Cart']['image'] = FULL_BASE_URL . $this->webroot . "files/product/" . $data[$i]['Cart']['image'];
            }
            $response['error'] = "0";
            $response['data'] = $data;
        } else {
            $response['error'] = "1";
            $response['data'] = "error";
        }

        echo json_encode($response);
        exit;
    }

    public function api_tablecheckout() {
        configure::write('debug', 2);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        $resid = $redata->Restaurant->id;
        $tbnumber = $redata->Restaurant->tablenumber;
        ob_start();
        print_r($redata);
        $c = ob_get_clean();
        $fc = fopen('files' . DS . 'aksh.txt', 'w');
        fwrite($fc, $c);
        fclose($fc);
        $cart = $this->Session->read('Shop');
        if ($cart) {
            foreach ($cart['OrderItem'] as $c) {

                if ($c['tno'] == $tbnumber) {
                    @$a+=$c['subtotal'];
                }
            }
            $this->loadModel('Tax');
            $tax = $this->Tax->find('first', array('conditions' => array('Tax.resid' => $resid)));

            if ($tax) {
                $all = ($tax['Tax']['tax'] * $a) / 100;
                $total['all'] = $all + $a;
            } else {
                $total['all'] = $a;
            }
        } else {
            $total['all'] = "NO data";
        }
        // print_r($cart);exit;
        echo json_encode($total);
        exit;
    }

    public function newsletter() {
        $api_key = "35833bae8881ce0ecced3fc3daa81482-us14";
        $list_id = "1a2fe1e7c5";
        require '../Lib/src/Mailchimp.php';
        //require('Mailchimp.php');
        $Mailchimp = new Mailchimp($api_key);
        $Mailchimp_Lists = new Mailchimp_Lists($Mailchimp);
        $subscriber = $Mailchimp_Lists->subscribe($list_id, array('email' => htmlentities($_POST['email'])));
        // print_r($subscriber); 
        if (!empty($subscriber['leid'])) {
            echo "success";
        } else {
            echo "fail";
        }
        exit;
    }

    public function api_TableCancelOrder() {
        configure::write('debug', 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        $OrderID = $redata->TableReservation->id;
        ob_start();
        print_r($redata);
        $c = ob_get_clean();
        $fc = fopen('files' . DS . 'details.txt', 'w');
        fwrite($fc, $c);
        fclose($fc);
        if ($redata) {
            $this->loadModel('TableReservation');
            $data = $this->TableReservation->find('first', array('conditions' => array('TableReservation.id' => $OrderID)));
            $ctime = date('Y-m-d H:i:s');
            $ordertime = $data['TableReservation']['created'];
            $interval = abs(strtotime($ctime) - strtotime($ordertime));
            $minutes = round($interval / 60);
            if ($minutes < 30) {
                $this->Order->updateAll(array('TableReservation.dl_status' => 2), array('TableReservation.id' => $OrderID));
                $response['error'] = "0";
                $response['dl_status'] = "2";
                $response['isSucess'] = "true";
                $response['isSucess'] = "You have canceled your Order";
            } else {
                $response['error'] = "1";
                $response['isSucess'] = "Order will be not cancel";
            }
        } else {
            $response['error'] = "1";
            $response['isSucess'] = "false";
        }
        echo json_encode($response);
        exit;
    }

    public function api_CancelOrder() {
        configure::write('debug', 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        $OrderID = $redata->order->id;
        ob_start();
        print_r($redata);
        $c = ob_get_clean();
        $fc = fopen('files' . DS . 'details.txt', 'w');
        fwrite($fc, $c);
        fclose($fc);
        if ($redata) {
            $this->loadModel('Order');
            $data = $this->Order->find('first', array('conditions' => array('Order.id' => $OrderID)));
            $ctime = date('Y-m-d H:i:s');
            $ordertime = $data['Order']['created'];
            $interval = abs(strtotime($ctime) - strtotime($ordertime));
            $minutes = round($interval / 60);
            if ($minutes < 30) {
                $this->Order->updateAll(array('Order.dl_status' => 2), array('Order.id' => $OrderID));
                $response['error'] = "0";
                $response['dl_status'] = "2";
                $response['isSucess'] = "true";
                $response['isSucess'] = "You have canceled your Order";
            } else {
                $response['error'] = "1";
                $response['isSucess'] = "Order will be not cancel";
            }
        } else {
            $response['error'] = "1";
            $response['isSucess'] = "false";
        }
        echo json_encode($response);
        exit;
    }

    public function api_allpage() {
        configure::write('debug', 0);
        $this->loadModel('Staticpage');
        $data = $this->Staticpage->find('all');
        if ($data) {
            $response['data'] = $data;
            $response['error'] = "0";
            $response['isSucess'] = "sucess";
        } else {
            $response['error'] = "1";
            $response['isSucess'] = "false";
        }
        echo json_encode($response);
        exit;
    }

    public function api_addaddress() {
        configure::write('debug', 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        ob_start();
        print_r($redata);
        $c = ob_get_clean();
        $fc = fopen('files' . DS . 'details.txt', 'w');
        fwrite($fc, $c);
        fclose($fc);
        if ($redata) {
            $this->loadModel('Address');
            $this->request->data['Address']['user_id'] = $redata->User->id;
            $this->request->data['Address']['titile'] = $redata->Address->titile;
            $this->request->data['Address']['first_name'] = $redata->Address->first_name;
            $this->request->data['Address']['last_name'] = $redata->Address->last_name;
            $this->request->data['Address']['email'] = $redata->Address->email;
            $this->request->data['Address']['phone'] = $redata->Address->phone;
            $this->request->data['Address']['address_1'] = $redata->Address->address_1;
            $this->request->data['Address']['address_2'] = $redata->Address->address_2;
            $this->request->data['Address']['shipping_city'] = $redata->Address->city;
            $this->request->data['Address']['shipping_state'] = $redata->Address->state;
            $this->request->data['Address']['shipping_zip'] = $redata->Address->zip;
            $this->request->data['Address']['shipping_country'] = $redata->Address->country;
            if ($redata->Address->id) {
                $this->request->data['Address']['id'] = $redata->Address->id;
            }
            $data = $this->Address->saveAll($this->request->data);
            if ($data) {
                $response['data'] = $redata;
                $response['error'] = "0";
                $response['isSucess'] = "sucess";
            } else {
                $response['error'] = "1";
                $response['isSucess'] = "false";
            }
        } else {
            $response['error'] = "1";
            $response['isSucess'] = "false";
        }
        echo json_encode($response);
        exit;
    }

    public function api_getaddress() {
        configure::write('debug', 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        $userid = $redata->User->id;
        ob_start();
        print_r($redata);
        $c = ob_get_clean();
        $fc = fopen('files' . DS . 'details.txt', 'w');
        fwrite($fc, $c);
        fclose($fc);
        if ($redata) {
            $this->loadModel('Address');
            $data = $this->Address->find('all', array('conditions' => array('Address.user_id' => $userid)));
            if ($data) {
                $response['data'] = $data;
                $response['error'] = "0";
                $response['isSucess'] = "sucess";
            } else {
                $response['error'] = "1";
                $response['isSucess'] = "false";
            }
        } else {
            $response['error'] = "1";
            $response['isSucess'] = "false";
        }
        echo json_encode($response);
        exit;
    }

}

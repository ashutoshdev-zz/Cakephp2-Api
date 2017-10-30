<?php

//ob_start();

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

class UsersController extends AppController {

////////////////////////////////////////////////////////////

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('login', 'admin_add', 'api_login', 'api_registration', 'admin', 
                'admin_login', 'api_contact', 'api_forgetpwd', 'api_trackorder',
                'api_addresslist', 'api_resetpwd', 'api_fblogin', 'walletipn', 'api_wallet', 'api_loginwork');
    }

////////////////////////////////////////////////////////////

    public function admin_login() {
        Configure::write("debug", 0);
        $this->layout = "admin2";

        // echo AuthComponent::password('admin');

        if ($this->request->is('post')) {
            //echo $this->request->data['User']['server'];exit;
            $sesid = $this->Session->id();
            if ($this->Auth->login()) {

                $this->User->id = $this->Auth->user('id');
                $this->User->saveField('logins', $this->Auth->user('logins') + 1);
                $this->User->saveField('last_login', date('Y-m-d H:i:s'));
                $this->loadModel('Cart');
                $updatesess = $this->Session->id();
                $this->Cart->updateAll(array('Cart.sessionid' => "'$updatesess'"), array('Cart.sessionid' => $sesid));
                if ($this->Auth->user('role') == 'customer') {
                    return $this->redirect('http://' . $this->request->data['User']['server']);
                } elseif ($this->Auth->user('role') == 'admin') {
                    $uploadURL = Router::url('/') . 'app/webroot/upload';
                    $_SESSION['KCFINDER'] = array(
                        'disabled' => false,
                        'uploadURL' => $uploadURL,
                        'uploadDir' => ''
                    );

                    return $this->redirect(array(
                                'controller' => 'orders',
                                'action' => 'index',
                                'manager' => false,
                                'admin' => true
                    ));
                } elseif ($this->Auth->user('role') == 'rest_admin') {
                    $uploadURL = Router::url('/') . 'app/webroot/upload';
                    $_SESSION['KCFINDER'] = array(
                        'disabled' => false,
                        'uploadURL' => $uploadURL,
                        'uploadDir' => ''
                    );


                    return $this->redirect(array(
                                'controller' => 'orders',
                                'action' => 'cartall',
                                'manager' => false,
                                'admin' => true
                    ));
                } else {
                    $this->Session->setFlash('Login is incorrect');
                }
            } else {
                $this->Session->setFlash('Login is incorrect');
            }
        }
    }

    public function login() {
        // echo AuthComponent::password('admin');

        if ($this->request->is('post')) {
            //echo $this->request->data['User']['server'];exit;
            $sesid = $this->Session->id();
            if ($this->Auth->login()) {

                $this->User->id = $this->Auth->user('id');
                $this->User->saveField('logins', $this->Auth->user('logins') + 1);
                $this->User->saveField('last_login', date('Y-m-d H:i:s'));
                $this->loadModel('Cart');
                $updatesess = $this->Session->id();
                $this->Cart->updateAll(array('Cart.sessionid' => "'$updatesess'"), array('Cart.sessionid' => $sesid));
                if ($this->Auth->user('role') == 'customer') {
                    return $this->redirect('http://' . $this->request->data['User']['server']);
                } elseif ($this->Auth->user('role') == 'admin') {
                    $uploadURL = Router::url('/') . 'app/webroot/upload';
                    $_SESSION['KCFINDER'] = array(
                        'disabled' => false,
                        'uploadURL' => $uploadURL,
                        'uploadDir' => ''
                    );

                    return $this->redirect(array(
                                'controller' => 'dashboards',
                                'action' => 'dashboard',
                                'manager' => false,
                                'admin' => true
                    ));
                } else {
                    $this->Session->setFlash('Login is incorrect');
                }
            } else {
                $this->Session->setFlash('Login is incorrect');
            }
        } else {

            return $this->redirect(array('controller' => 'products', 'action' => 'index'));
        }
    }

////////////////////////////////////////////////////////////

    public function logout() {
        $this->Session->setFlash('Good-Bye');
        $_SESSION['KCEDITOR']['disabled'] = true;
        unset($_SESSION['KCEDITOR']);
        return $this->redirect($this->Auth->logout());
    }

    public function admin_logout() {
        $this->Session->setFlash('Good-Bye');
        $_SESSION['KCEDITOR']['disabled'] = true;
        unset($_SESSION['KCEDITOR']);
        $this->Auth->logout();
        return $this->redirect('/admin');
    }

////////////////////////////////////////////////////////////

    public function customer_dashboard() {
        
    }

////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////

    public function admin_index() {
        Configure::write("debug", 0);
        if ($this->request->is("post")) {
            //  Array ( [User] => Array ( [search] => aaa ) )
            //  print_r($this->request->data); 
            $keyword = $this->request->data['User']['search'];
            $users = $this->User->find('all', array('conditions' => array('User.username LIKE' => '%' . $keyword . '%')));
        } else {
            $this->Paginator = $this->Components->load('Paginator');
            $this->Paginator->settings = array(
                'User' => array(
                    'recursive' => -1,
                    'contain' => array(
                    ),
                    'conditions' => array(
                    ),
                    'order' => array(
                        'Users.name' => 'ASC'
                    ),
                    'limit' => 20,
                    'paramType' => 'querystring',
                )
            );
            $users = $this->Paginator->paginate();
        }
        $this->set(compact('users'));
    }

////////////////////////////////////////////////////////////

    public function admin_view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException('Invalid user');
        }
        $this->set('user', $this->User->read(null, $id));
    }

////////////////////////////////////////////////////////////

    public function admin_resadd() {
        if ($this->request->is('post')) {
            // debug($this->request->data);exit;
            if ($this->User->hasAny(array('User.username' => $this->request->data['User']['username']))) {
                $this->Session->setFlash(__('Username already exist!!!'));
                return $this->redirect(array('action' => 'resadd'));
            }
            $this->User->create();
            $resname = $this->request->data['User']['name'];
            if ($this->User->save($this->request->data)) {
                $this->loadModel('Restaurant');
                $uid = $this->User->getLastInsertID();
                $this->request->data['Restaurant']['status'] = 1;
                $this->request->data['Restaurant']['taxes'] = 0;
                $this->request->data['Restaurant']['user_id'] = $uid;
                $resname = $this->request->data['Restaurant']['name'] = $resname;
                if ($this->Restaurant->save($this->request->data)) {
                    $id = $this->Restaurant->getLastInsertID();
                    $this->loadModel('Tax');
                    $this->request->data['Tax']['tax'] = 0;
                    $this->request->data['Tax']['resid'] = $id;
                    $this->Tax->save($this->request->data);
                    return $this->redirect(array('controller' => 'restaurants', 'action' => 'edit/' . $id . '/' . $uid));
                }
            } else {
                $this->Session->setFlash('The user could not be saved. Please, try again.');
            }
        }
    }

    public function admin_add() {
        if ($this->request->is('post')) {
            if ($this->User->hasAny(array('User.username' => $this->request->data['User']['username']))) {
                $this->Session->setFlash(__('Username already exist!!!'));
                return $this->redirect(array('action' => 'add'));
            }
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash('The user has been saved');
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('The user could not be saved. Please, try again.');
            }
        }
    }

    public function add() {
        Configure::write("debug", 0);
        if ($this->request->is('post')) {
            $this->request->data['User']['name'] = $this->request->data['User']['fname'] . " " . $this->request->data['User']['lname'];

            $this->request->data['User']['email'] = $this->request->data['User']['username'];


            $this->request->data['User']['active'] = 1;
            $this->request->data['User']['role'] = 'customer';
            if ($this->User->hasAny(array('User.username' => $this->request->data['User']['username']))) {
                $this->Session->setFlash(__('Username already exist!!!'));
                return $this->redirect('http://' . $this->request->data['User']['server']);
            }
            $this->User->create();
            $fu = $this->request->data;
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash('The user has been saved');
                $l = new CakeEmail('smtp');
                $ms = "Welcome !Your Username & password we mentioned below <br/>";
                $ms.="Username:" . $this->request->data['User']['username'] . "<br/>";
                $ms.="Password:" . $this->request->data['User']['password'] . "<br/>";
                $l->emailFormat('html')->template('default', 'default')->subject('Welcome To register to our store')
                        ->to($this->request->data['User']['username'])->send($ms);
                $this->set('smtp_errors', "none");
                return $this->redirect('http://' . $this->request->data['User']['server']);
            } else {
                $this->Session->setFlash('The user could not be saved. Please, try again.');
            }
        }
    }

    public function forgetpwd() {
        Configure::write("debug", 0);
        $this->User->recursive = -1;
        if (!empty($this->data)) {
            if (empty($this->data['User']['username'])) {
                $this->Session->setFlash('Please Provide Your Username that You used to Register with Us');
            } else {
                $username = $this->data['User']['username'];
                $fu = $this->User->find('first', array('conditions' => array('User.username' => $username)));
                if ($fu['User']['email']) {
                    if ($fu['User']['active'] == "1") {
                        $key = Security::hash(CakeText::uuid(), 'sha512', true);
                        $hash = sha1($fu['User']['email'] . rand(0, 100));
                        $url = Router::url(array('controller' => 'Users', 'action' => 'reset'), true) . '/' . $key . '#' . $hash;
                        $ms = "<p>Click the Link below to reset your password.</p><br /> " . $url;
                        $fu['User']['tokenhash'] = $key;
                        $this->User->id = $fu['User']['id'];
                        if ($this->User->saveField('tokenhash', $fu['User']['tokenhash'])) {
                            $l = new CakeEmail('smtp');
                            $l->emailFormat('html')->template('default', 'default')->subject('Reset Your Password')
                                    ->to($fu['User']['email'])->send($ms);
                            $this->set('smtp_errors', "none");
                            $this->Session->setFlash(__('Check Your Email To Reset your password', true));
                            $this->redirect(array('controller' => 'Products', 'action' => 'index'));
                        } else {
                            $this->Session->setFlash("Error Generating Reset link");
                        }
                    } else {
                        $this->Session->setFlash('This Account is not Active yet.Check Your mail to activate it');
                    }
                } else {
                    $this->Session->setFlash('Username does Not Exist');
                }
            }
        }
    }

    public function reset($token = null) {
        configure::write('debug', 0);
        $this->User->recursive = -1;
        if (!empty($token)) {
            $u = $this->User->findBytokenhash($token);
            if ($u) {
                $this->User->id = $u['User']['id'];
                if (!empty($this->data)) {
                    if ($this->data['User']['password'] != $this->data['User']['password_confirm']) {
                        $this->Session->setFlash("Both the passwords are not matching...");
                        return;
                    }
                    $this->User->data = $this->data;
                    $this->User->data['User']['email'] = $u['User']['email'];
                    $new_hash = sha1($u['User']['email'] . rand(0, 100)); //created token
                    $this->User->data['User']['tokenhash'] = $new_hash;
                    if ($this->User->validates(array('fieldList' => array('password', 'password_confirm')))) {
                        if ($this->User->save($this->User->data)) {
                            $this->Session->setFlash('Password Has been Updated');
                            $this->redirect(array('controller' => 'Products', 'action' => 'index'));
                        }
                    } else {
                        $this->set('errors', $this->User->invalidFields());
                    }
                }
            } else {
                $this->Session->setFlash('Token Corrupted, Please Retry.the reset link 
                        <a style="cursor: pointer; color: rgb(0, 102, 0); text-decoration: none;
                        background: url("http://files.adbrite.com/mb/images/green-double-underline-006600.gif") 
                        repeat-x scroll center bottom transparent; margin-bottom: -2px; padding-bottom: 2px;"
                        name="AdBriteInlineAd_work" id="AdBriteInlineAd_work" target="_top">work</a> only for once.');
            }
        } else {
            $this->Session->setFlash('Pls try again...');
            $this->redirect(array('controller' => 'pages', 'action' => 'login'));
        }
    }

////////////////////////////////////////////////////////////

    public function admin_edit($id = null) {
        Configure::write("debug", 0);
        $this->User->id = $id;

        if (!$this->User->exists()) {
            throw new NotFoundException('Invalid user');
        }

        // get saved page permissions
        $this->loadModel('Userpermission');
        $AuthPermission = $this->Userpermission->find('first', array('conditions' => array('Userpermission.user_id' => $id)));
        if ($AuthPermission) {
            $authorized_pages = unserialize($AuthPermission['Userpermission']['view_pages']);
            $this->set('authorized_pages', $authorized_pages);
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $view_pages = serialize($this->request->data['Userpermission']['view_pages']);
            $dataprm = array('user_id' => $id, 'view_pages' => $view_pages);

            if ($this->User->save($this->request->data)) {
                $cnt = $this->Userpermission->find('count', array('conditions' => array('Userpermission.user_id' => $id)));
                if ($cnt < 1) {
                    $this->Userpermission->save($dataprm);
                } else {
                    $this->Userpermission->updateAll(
                            array('view_pages' => "'$view_pages'"), array('user_id' => $id)
                    );
                }
                $this->Session->setFlash('The user has been saved');

                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('The user could not be saved. Please, try again.');
            }
        } else {
            $this->request->data = $this->User->read(null, $id);
        }
    }

    public function edit() {
        $id = $this->Auth->user('id');
        $this->User->id = $this->Auth->user('id');
        if (!$this->User->exists($id)) {
            return $this->redirect(array('action' => 'myaccount'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $email = $this->Auth->user('email');
            $username = $this->Auth->user('username');
            if (($email == $this->request->data['User']['email']) && ($username == $this->request->data['User']['username'])) {
                if ($this->User->save($this->request->data)) {
                    $this->Session->setFlash(__('Your profile has been updated.'));
                    return $this->redirect(array('action' => 'myaccount'));
                } else {
                    $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
                }
            } else if ($this->User->hasAny(array('User.email' => $this->request->data['User']['email']))) {
                $this->Session->setFlash(__('Email already exist!'));
                return $this->redirect(array('action' => 'edit'));
            } else if ($this->User->hasAny(array('User.username' => $this->request->data['User']['username']))) {
                $this->Session->setFlash(__('Username already exist!'));
                return $this->redirect(array('action' => 'edit'));
            } else {
                if ($this->User->save($this->request->data)) {
                    $this->Session->setFlash(__('Your profile has been updated.'));
                    return $this->redirect(array('action' => 'myaccount'));
                } else {
                    $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
                }
            }
        } else {
            $options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
            $data = $this->request->data = $this->User->find('first', $options);
            $this->set('data', $data);
        }
    }

////////////////////////////////////////////////////////////

    public function admin_password($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException('Invalid user');
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash('The user has been saved');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('The user could not be saved. Please, try again.');
            }
        } else {
            $this->request->data = $this->User->read(null, $id);
        }
    }

////////////////////////////////////////////////////////////

    public function admin_delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException('Invalid user');
        }
        if ($this->User->delete()) {
            $this->Session->setFlash('User deleted');
            return $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash('User was not deleted');
        return $this->redirect(array('action' => 'index'));
    }

////////////////////////////////////////////////////////////
    public function api_loginwork() {
        Configure::write('debug', 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        ob_start();
        print_r($redata);
        $c = ob_get_clean();
        $fc = fopen('files' . DS . 'detail.txt', 'w');
        fwrite($fc, $c);
        fclose($fc);
        $this->layout = "ajax";
        $username = $redata->user->username;
        $password = $redata->user->password;
        $this->request->data['User']['username'] = $username;
        //$this->request->data['email'];        
        $this->request->data['password'] = $password;
        if ($redata) {
            $check = $this->User->find('first', array('conditions' => array(
                    "User.username" => $this->request->data['User']['username']
                ), 'fields' => array('username'), 'recursive' => '-1'));
            $this->request->data['User']['username'] = $check['User']['username'];
            $this->request->data['User']['password'] = $password;
            if (!$this->Auth->login()) {
                $response['status'] = false;
                $response['msg'] = 'User is not valid';
            } else {
                $user = $this->User->find('first', array('conditions' => array('id' => $this->Auth->user('id'))));
                $response['status'] = true;
                $response['msg'] = 'You have successfully logged in';
                $response['id'] = $user['User']['id'];
                unset($user['User']['password']);
                unset($user['User']['tokenhash']);
                //$response['name'] = $user['User']['name'];
                $response['name'] = $user['User'];
            }
        }
        echo json_encode($response);
        exit;
    }

    public function api_login() {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->request->data['User']['username'] = $this->request->data['email'];
        $pass = $this->request->data['password'];
        if ($this->request->is('post')) {
            $check = $this->User->find('first', array('conditions' => array(
                    "User.username" => $this->request->data['User']['username']
                ), 'fields' => array('username'), 'recursive' => '-1'));


            $this->request->data['User']['username'] = $check['User']['username'];
            $this->request->data['User']['password'] = $pass;

            if (!$this->Auth->login()) {


                $response['msg'] = 'User is not valid';
            } else {
                $user = $this->User->find('first', array('conditions' => array('id' => $this->Auth->user('id'))));

                $response['status'] = true;
                $response['msg'] = 'You have successfully logged in';
                $response['id'] = $user['User']['id'];
                $response['name'] = $user['User']['name'];
            }
        }

        echo json_encode($response);
        exit;
    }

    public function api_registration() {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        ob_start();
        $this->request->data['User']['name'] = $this->request->data['firstname'] . " " . $this->request->data['lastname'];
        $this->request->data['User']['username'] = $this->request->data['email'];
        $this->request->data['User']['email'] = $this->request->data['email'];
        $this->request->data['User']['password'] = $this->request->data['password'];
        $this->request->data['User']['phone'] = $this->request->data['phone'];
        $this->request->data['User']['active'] = 1;
        $this->request->data['User']['role'] = 'customer';


        if ($this->request->is('post')) {

            if ($this->User->hasAny(array('User.username' => $this->request->data['User']['username']))) {

                $response['msg'] = 'Email_id already exist';
            } else {
                $this->User->create();
                $this->User->save($this->request->data);
                $response['status'] = true;
                //print_r($this->request->data);
                $response['msg'] = 'Registration has been successful';
            }
        } else {

            $response['msg'] = 'Sorry please try again';
        }
        echo json_encode($response);
        exit;
    }

////////////////////////////////////////////////////
//      public function api_login() {
//
//        // echo AuthComponent::password('admin');
//
//        if ($this->request->is('post')) {
//            if ($this->Auth->login()) {
//
//                $this->User->id = $this->Auth->user('id');
//                $this->User->saveField('logins', $this->Auth->user('logins') + 1);
//                $this->User->saveField('last_login', date('Y-m-d H:i:s'));
//
//                if ($this->Auth->user('role') == 'customer') {
//                    return $this->redirect(array(
//                                'controller' => 'users',
//                                'action' => 'dashboard',
//                                'customer' => true,
//                                'admin' => false
//                    ));
//                } elseif ($this->Auth->user('role') == 'admin') {
//                    $uploadURL = Router::url('/') . 'app/webroot/upload';
//                    $_SESSION['KCFINDER'] = array(
//                        'disabled' => false,
//                        'uploadURL' => $uploadURL,
//                        'uploadDir' => ''
//                    );
//                    return $this->redirect(array(
//                                'controller' => 'users',
//                                'action' => 'dashboard',
//                                'manager' => false,
//                                'admin' => true
//                    ));
//                } else {
//                    $this->Session->setFlash('Login is incorrect');
//                }
//            } else {
//                $this->Session->setFlash('Login is incorrect');
//            }
//        }
//    }
    /* -------------------------------------------------------Webservice--------------------------------------------- */

//    public function api_login() {
//
//
//        configure::write('debug', 0);
//
//
//        $this->User->recursive = -1;
//
//        $this->layout = 'ajax';
//
//        ob_start();
//
//        echo var_dump($this->request->data);
//
//        $c = ob_get_clean();
//
//        $fc = fopen('files' . DS . 'detail.txt', 'w');
//
//        fwrite($fc, $c);
//
//        fclose($fc);
////
////         $this->request->data['User']['email']="admin@gmail.com";
////         $this->request->data['User']['password']="admixxn";
//
//        if ($this->request->is('post')) {
//
//            $check = $this->User->find('first', array('conditions' => array(
//                    "OR" => array(
//                        "User.username" => $this->request->data['User']['email']
//                        , "User.email" => $this->request->data['User']['email']
//                    )
//                ), 'fields' => array('username'), 'recursive' => '-1'));
//
//            $this->request->data['User']['username'] = $check['User']['username'];
//
//            if (!$this->Auth->login()) {
//
//                $response['isSucess'] = 'false';
//
//                $response['msg'] = 'Email ID or password is  incorrect';
//            } else {
//
//                $user = $this->User->find('first', array('conditions' => array('id' => $this->Auth->user('id'))));
//                $array = array("www", "com", "http", "https");
//
//
//                if ($this->strposa($user['User']['image'], $array)) {
//                    $user['User']['image'];
//                } else {
//                    $user['User']['image'] = FULL_BASE_URL . $this->webroot . "files/profile_pic/" . $user['User']['image'];
//                }
//
//                if ($user['User']['status'] == 0) {
//
//                    $response['isSucess'] = 'false';
//
//                    $response['msg'] = 'You have still not verified your Email ID';
//                } else {
//
//                    $this->User->id = $user['User']['id'];
//
//                    $this->User->saveField('device_token', $this->request->data['User']['device_token']);
//                    $this->User->saveField('plateform', $this->request->data['User']['plateform']);
//                    $this->User->saveField('latitude', $this->request->data['User']['latitude']);
//                    $this->User->saveField('longitude', $this->request->data['User']['longitude']);
//
//                    $response['isSucess'] = 'true';
//
//                    $response['data'] = $user;
//                }
//            }
//        }
//
//        $this->set('response', $response);
//
//        $this->render('ajax');
//    }

    public function api_chekdata() {


        configure::write('debug', 0);




        $this->layout = 'ajax';

        ob_start();

        var_dump($this->request->data);

        $c = ob_get_clean();

        $fc = fopen('files' . DS . 'detail.txt', 'w');

        fwrite($fc, $c);

        fclose($fc);

        exit;
    }

    public function api_logout() {


        configure::write('debug', 0);
        $this->layout = 'ajax';

        if ($this->request->is('post')) {

            $this->User->id = $this->request->data[User][id];

            $this->Auth->logout();

            //$this->Cookie->delete('User');

            $response['isSucess'] = 'true';

            $response['msg'] = "Logout Successfully";
        }

        $this->set('response', $response);

        $this->render('ajax');
    }

//    public function api_registration() {
//        //   $this->request->data['User']['email']="ashutosh@netsmartx.net";
//        // $this->request->data['User']['email']="ashutoshaa@avainfotech.com";
//        //   $this->request->data['User']['password']="ashu";
//
//        Configure::write('debug', 0);
//
//
//        $this->layout = 'ajax';
//
//        ob_start();
//
//        print_r($this->request->data);
//
//        $c = ob_get_clean();
//
//        $fc = fopen('files' . DS . 'detail.txt', 'w');
//
//        fwrite($fc, $c);
//
//        fclose($fc);
//
//
//        $this->request->data['User']['username'] = $this->request->data['User']['email'];
//
//        if ($this->request->is('post')) {
//
//            if ($this->User->hasAny(array('User.username' => $this->request->data['User']['username']))) {
//
//                $response['isSucess'] = 'false';
//
//                $response['msg'] = 'Email ID already exist';
//            } else {
//
//                if ($this->User->hasAny(array('User.email' => $this->request->data['User']['email']))) {
//
//                    $response['isSucess'] = 'false';
//
//                    $response['msg'] = 'Email ID already exist';
//                } else {
//
//                    $this->User->create();
//
//                    $this->request->data['User']['role'] = 'user';
//                    $this->request->data['User']['status'] = 0;
//
//                    if ($this->User->save($this->request->data)) {
//
//                        $verify_id = base64_encode($this->User->getLastInsertID());
//                        $url = FULL_BASE_URL . $this->webroot . "api/users/verify/" . $verify_id;
//                        $ms = "Welcome to Mobile 
//                             <b><a href='" . $url . "' style='text-decoration:none'>Click to verify your email.</a></b><br/>";
//                        $l = new CakeEmail('smtp');
//                        $l->emailFormat('html')->template('default', 'default')->subject('Registration Successfully!!!')->
//                                to($this->request->data['User']['email'])->send($ms);
//
//                        $response['isSucess'] = 'true';
//
//                        $response['msg'] = 'Register successfully';
//                    } else {
//
//                        $response['isSucess'] = 'false';
//
//                        $response['msg'] = 'Sorry please try again';
//                    }
//                }
//            }
//        }
//
//        $this->set('response', $response);
//
//        $this->render('ajax');
//    }

    public function api_editprofile() {
        configure::write('debug', 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        ob_start();
        print_r($redata);
        $c = ob_get_clean();
        $fc = fopen('files' . DS . 'detail.txt', 'w');
        fwrite($fc, $c);
        fclose($fc);
        $this->User->recursive = 2;
        $this->layout = "ajax";
        if (!empty($redata)) {
            $id = $redata->user->id;
            $name = $redata->user->name;
            $phone = $redata->user->phone;
            $data = $this->User->updateAll(array('User.phone' => "'$phone'", 'User.name' => "'$name'"), array('User.id' => $id));
            if ($data) {
                $response['data'] = $redata;
                $response['error'] = 0;
            }
        }
        echo json_encode($response);
        exit;
    }

    public function api_user() {
        configure::write('debug', 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        ob_start();
        print_r($redata);
        $c = ob_get_clean();
        $fc = fopen('files' . DS . 'detail.txt', 'w');
        fwrite($fc, $c);
        fclose($fc);
        $this->User->recursive = 2;
        $this->layout = "ajax";

        if (!empty($redata)) {
            $id = $redata->user->id;
            $data = $this->User->find('all', array('conditions' => array('User.id' => $id)));
            if ($data[0]['User']['image']) {
                $data[0]['User']['image'] = FULL_BASE_URL . $this->webroot . "files/profile_pic/" . $data[0]['User']['image'];
            } else {
                $data[0]['User']['image'] = FULL_BASE_URL . $this->webroot . "files/profile_pic/noimagefound.jpg";
            }
            if ($data) {

                $response['msg'] = 'Success';

                $response['data'] = $data;
            } else {

                $response['isSucess'] = 'false';

                $response['msg'] = 'Sorry There are no data';
            }
        }
        echo json_encode($response);
        exit;
    }

    public function api_alluser() {

        $this->layout = 'ajax';

        $resp = $this->User->find('all', array('conditions' => array(
                "User.status" => 1
            ), 'recursive' => '-1'));

        if ($resp) {

            $response['isSucess'] = 'true';

            $response['msg'] = 'Success';

            $response['data'] = $resp;
        } else {

            $response['isSucess'] = 'false';

            $response['msg'] = 'Sorry there are no data';
        }

        $this->set('response', $response);

        $this->render('ajax');
    }

    public function api_changepasswordwork() {
        Configure::write('debug', 0);
        $this->layout = "ajax";
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        $oldpassword = $redata->User->old_password;
        $newpassword = $redata->User->new_password;
        $username = $redata->User->username;
        $this->layout = "ajax";
        if ($this->request->is('post')) {
            $password = AuthComponent::password($oldpassword);
            $pass = $this->User->find('first', array('conditions' => array('AND' => array('User.password' => $password, 'User.username' => $username))));
            if ($pass) {
                $this->User->data['User']['password'] = $newpassword;
                $this->User->id = $pass['User']['id'];
                if ($this->User->exists()) {
                    $pass['User']['password'] = $newpassword;
                    if ($this->User->save()) {
                        $response['isSucess'] = 'true';
                        $response['msg'] = "your password has been updated";
                    }
                }
            } else {
                $response['isSucess'] = 'false';
                $response['msg'] = "Your old password did not match";
            }
        }
        echo json_encode($response);
        exit;
    }

    public function changepassword() {

        if ($this->request->is('post')) {
            $password = AuthComponent::password($this->data['User']['old_password']);
            $em = $this->Auth->user('username');
            $pass = $this->User->find('first', array('conditions' => array('AND' => array('User.password' => $password, 'User.username' => $em))));
            if ($pass) {
                if ($this->data['User']['new_password'] != $this->data['User']['cpassword']) {
                    $this->Session->setFlash(__("New password and Confirm password field do not match"));
                } else {
                    $this->User->data['User']['password'] = $this->data['User']['new_password'];
                    $this->User->id = $pass['User']['id'];
                    if ($this->User->exists()) {
                        $pass['User']['password'] = $this->data['User']['new_password'];
                        if ($this->User->save()) {
                            $this->Session->setFlash(__("Password Updated"));
                            $this->redirect(array('controller' => 'Users', 'action' => 'myaccount'));
                        }
                    }
                }
            } else {
                $this->Session->setFlash(__("Your old password did not match."));
            }
        }
    }

    public function api_forgetpwd() {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        $this->layout = "ajax";
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        $username = $redata->User->username;
        $this->User->recursive = -1;
        if (empty($redata)) {
            $response['isSucess'] = 'false';
            $response['msg'] = 'Please Provide Your Username that You used to register with us';
        } else {
            $fu = $this->User->find('first', array('conditions' => array('User.username' => $username)));
            if ($fu['User']['email']) {
                if ($fu['User']['active'] == "1") {
                    $key = Security::hash(CakeText::uuid(), 'sha512', true);
                    $hash = sha1($fu['User']['email'] . rand(0, 100));
                    $url = Router::url(array('controller' => 'users', 'action' => 'api_resetpwd'), true) . '/' . $key . '#' . $hash;
                    $ms = "Welcome to Mobile
      <b><a href='" . $url . "' style='text-decoration:none'>Click here to reset your password.</a></b><br/>";
                    $fu['User']['tokenhash'] = $key;
                    $this->User->id = $fu['User']['id'];
                    if ($this->User->saveField('tokenhash', $fu['User']['tokenhash'])) {
                        $l = new CakeEmail('smtp');
                        $l->emailFormat('html')->template('default', 'default')->subject('Reset Your Password')
                                ->to($fu['User']['email'])->send($ms);
                        $response['isSucess'] = 'true';
                        $response['msg'] = 'Check Your Email ID to reset your password';
                    } else {
                        $response['isSucess'] = 'false';
                        $response['msg'] = 'Error Generating Reset link';
                    }
                } else {
                    $response['isSucess'] = 'false';
                    $response['msg'] = 'This Account is still not Active .Check Your Email ID to activate it';
                }
            } else {
                $response['isSucess'] = 'false';
                $response['msg'] = 'Email ID does Not Exist';
            }
        }
        echo json_encode($response);
        exit;
    }

    public function api_verify($id = null) {


        Configure::write('debug', 0);

        $id = base64_decode($id);

        $this->User->id = $id;

        $arr1 = $this->User->query("update `users` set `status`='1' where `id`=$id");

        $this->Session->setFlash(__('Congratulations your account has been verified!!! Now you can login!!! '));

        return $this->redirect('/');
    }

    public function api_resetpwd($token = null) {

        configure::write('debug', 0);
        $this->User->recursive = -1;
        if (!empty($token)) {
            $u = $this->User->findBytokenhash($token);
            if ($u) {

                $this->User->id = $u['User']['id'];
                if (!empty($this->data)) {

                    if ($this->data['User']['password'] != $this->data['User']['password_confirm']) {
                        $this->Session->setFlash("Both the passwords are not matching...");
                        return;
                    }
                    $this->User->data = $this->data;
                    $this->User->data['User']['email'] = $u['User']['email'];
                    $new_hash = sha1($u['User']['email'] . rand(0, 100)); //created token
                    $this->User->data['User']['tokenhash'] = $new_hash;
                    if ($this->User->validates(array('fieldList' => array('password', 'password_confirm')))) {
                        if ($this->User->save($this->User->data)) {
                            $this->Session->setFlash('Password Has been Updated');
                            $this->redirect(array('controller' => 'products', 'action' => 'index'));
                        }
                    } else {
                        $this->set('errors', $this->User->invalidFields());
                    }
                }
            } else {

                $this->Session->setFlash('Token Corrupted, Please Retry.the reset link 
                        <a style="cursor: pointer; color: rgb(0, 102, 0); text-decoration: none;
                        background: url("http://files.adbrite.com/mb/images/green-double-underline-006600.gif") 
                        repeat-x scroll center bottom transparent; margin-bottom: -2px; padding-bottom: 2px;"
                        name="AdBriteInlineAd_work" id="AdBriteInlineAd_work" target="_top">work</a> only for once.');
            }
        } else {
            $this->Session->setFlash('Pls try again...');
            $this->redirect(array('controller' => 'pages', 'action' => 'login'));
        }
    }

    /**
     * facebook login
     */
    public function api_fblogin() {

        Configure::write('debug', 0);
        $this->layout = "ajax";
        $this->User->recursive = -1;
        if ($this->request->is('post')) {
            ob_start();
            print_r($this->request->data);
            $c = ob_get_clean();
            $fc = fopen('files' . DS . 'detail.txt', 'w');
            fwrite($fc, $c);
            fclose($fc);
            $this->request->data['User']['username'] = $this->request->data['email'];
            $this->request->data['User']['name'] = $this->request->data['name'];
            $this->request->data['User']['email'] = $this->request->data['email'];
            $this->request->data['User']['fb_id'] = $this->request->data['facebook_id'];
            if (!$this->User->hasAny(array(
                        'OR' => array('User.username' => $this->request->data['email'], 'User.email' => $this->request->data['email'])
                    ))) {
                $this->User->create();
                $this->request->data['User']['role'] = 'customer';
                $this->request->data['User']['status'] = 1;
                if ($this->User->save($this->request->data)) {
                    $user = $this->User->find('first', array('conditions' => array('email' => $this->request->data['email'])));
                    $response['isSucess'] = 'true';
                    $response['data'] = $user;
                } else {
                    $response['isSucess'] = 'false';
                    $response['msg'] = 'Sorry please try again';
                }
            } else {
                $user = $this->User->find('first', array('conditions' => array('email' => $this->request->data['email'])));
                $this->User->id = $user['User']['id'];
                // $this->User->saveField('image', $this->request->data['User']['image']);
                $response['isSucess'] = 'true';
                $response['data'] = $user;
            }
        }
        $this->set('response', $response);
        $this->render('ajax');
    }

    public function api_changepassword() {
        configure::write('debug', 0);

        $this->layout = "ajax";
        if ($this->request->is('post')) {
            $password = AuthComponent::password($this->data['User']['old_password']);
            $em = $this->request->data['User']['email'];
            $pass = $this->User->find('first', array('conditions' => array('AND' => array('User.password' => $password, 'User.email' => $em))));
            if ($pass) {

                $this->User->data['User']['password'] = $this->data['User']['new_password'];
                $this->User->id = $pass['User']['id'];
                if ($this->User->exists()) {
                    $pass['User']['password'] = $this->data['User']['new_password'];
                    if ($this->User->save()) {

                        $response['isSucess'] = 'false';
                        $response['msg'] = "your password has been updated";
                    }
                }
            } else {
                $response['isSucess'] = 'false';
                $response['msg'] = "Your old password did not match";
            }
        }



        $this->set('response', $response);

        $this->render('ajax');
    }

    ///////////29 aug 2016/////////////////////////////   
    public function api_saveimage() {
        configure::write('debug', 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        ob_start();
        print_r($redata);
        $c = ob_get_clean();
        $fc = fopen('files' . DS . 'detail.txt', 'w');
        fwrite($fc, $c);
        fclose($fc);
        $one = $redata->user->img;
        $img = base64_decode($one);
        $im = imagecreatefromstring($img);

        if ($im !== false) {

            $image = "1" . time() . ".png";
            imagepng($im, WWW_ROOT . "files" . DS . "profile_pic" . DS . $image);
            imagedestroy($im);
            $response['msg'] = "image is uploaded";
        } else {
            $response['isSucess'] = 'true';
            $response['msg'] = 'Image did not create';
        }


        $this->User->recursive = 2;
        $this->layout = "ajax";
        if (!empty($redata)) {

            $id = $redata->user->id;
            $name = $redata->user->name;
            $data = $this->User->updateAll(array('User.image' => "'$image'"), array('User.id' => $id));
            if ($data) {
//$response['img']=  FULL_BASE_URL . $this->webroot . "files/profile_pic/" . $data['User']['image'];
                $response['data'] = $data;
                $response['error'] = 0;
            }
        }
        echo json_encode($response);
        exit;
    }

    public function api_contact() {
        configure::write('debug', 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        if ($redata) {
            $Email = new CakeEmail('smtp');
            $Email->from(array('noreply@rajdeep.crystalbiltech.com' => 'ecasnik'));
            $Email->to('ashutosh@avainfotech.com');
            $Email->subject($redata->Contact->subject);
            $Email->send($redata->Contact->message);
            $response['isSucess'] = "true";
            $response['msg'] = "Message Sent";
        } else {
            $response['isSucess'] = 'false';
            $response['msg'] = 'Message not sent';
        }
        echo json_encode($response);
        exit;
    }

    public function myaccount() {
        Configure::write("debug", 2);
        $uid = $this->Auth->user('id');
        if (empty($uid)) {
            return $this->redirect(array('controller' => 'products', 'action' => 'index'));
        }
        if ($this->request->is("post")) {
            $image = $this->request->data['User']['image'];
            $uploadFolder = "profile_pic";
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
                move_uploaded_file($image['tmp_name'], $full_image_path);
                $this->User->updateAll(array('User.image' => "'$imageName'"), array('User.id' => $uid));
                return $this->redirect(array('action' => 'myaccount'));

                exit;
            }
        }
        $data = $this->User->find('first', array('conditions' => array('User.id' => $uid)));
        $this->set('data', $data);
    }

    public function api_trackorder() {
        configure::write('debug', 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        if ($redata) {
            $this->loadModel('Order');
            $order_id = $redata->Order->id;
            $data = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id)));
            $response['order'] = $data;
            $response['isSucess'] = "true";
            $response['msg'] = "Order has been found";
        } else {
            $response['isSucess'] = 'false';
            $response['msg'] = 'Order has not be found';
        }
        echo json_encode($response);
        exit;
    }

    public function api_addresslist() {
        configure::write('debug', 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        if ($redata) {
            $this->loadModel('Order');
            $uid = $redata->User->id;
            $data = $this->User->find('all', array('conditions' => array('User.id' => $uid)));
            $response['user'] = $data;
            $response['isSucess'] = "true";
        } else {
            $response['isSucess'] = 'false';
        }
        echo json_encode($response);
        exit;
    }

    public function wallet() {
        $this->loadModel("Wallet");
        $val = $this->request->data['User']['money'];
        $uid = $this->request->data['User']['uid'];

        $this->Wallet->create();
        $this->request->data['Wallet']['money'] = $val;
        $this->request->data['Wallet']['uid'] = $uid;

        $save = $this->Wallet->save($this->request->data);
        if ($save) {
            $last_id = $this->Wallet->getLastInsertId();
            $id = $last_id . '-' . $uid;
            ///////////////////////////////////////////////payment////////////////////////////////////////////////
            echo ".<form name=\"_xclick\" action=\"https://www.sandbox.paypal.com/cgi-bin/webscr\" method=\"post\">
                    <input type=\"hidden\" name=\"cmd\" value=\"_xclick\">
                 
                    <input type=\"hidden\" name=\"business\" value=\"ashutosh@avainfotech.com\">
                    <input type=\"hidden\" name=\"currency_code\" value=\"USD\">
                    
                    <input type=\"hidden\" name=\"custom\" value=\"$id\">
                    <input type=\"hidden\" name=\"amount\" value=\"$val\">
                    <input type=\"hidden\" name=\"return\" value=\"http://rajdeep.crystalbiltech.com/ecasnik/users/walletsuccess\">
                    <input type=\"hidden\" name=\"notify_url\" value=\"http://rajdeep.crystalbiltech.com/ecasnik/users/walletipn\"> 
                    </form>";
//                    exit;
            echo "<script>document._xclick.submit();</script>";
            ////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
    }

    public function walletsuccess() {
        $this->Session->setFlash('You have sucessfully added amount in your wallet so please check the wallt', 'flash_success');
        return $this->redirect(array('controller' => 'users', 'action' => 'myaccount'));
    }

    public function walletipn() {
        $fc = fopen('files/ipn1.txt', 'wb');
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
            $custom_field = explode('-', $_POST['custom']);
            $payer_email = $_POST['payer_email'];
            $uid = $custom_field[1];

            $trn_id = $_POST['txn_id'];
            $pay = $_POST['mc_gross'];
            $this->loadModel('Wallet');
            $this->Wallet->query("UPDATE `wallets` SET `status` = 1, `paypal_status` = '$res',`txnid`='$trn_id', `amount`='$pay' WHERE `id` ='$custom_field[0]';");
            $user = $this->User->find('first', array('conditions' => array('User.id' => $uid)));
            $total_p = $user['User']['loyalty_points'] + $pay;
            $this->User->updateAll(array('User.loyalty_points' => $total_p), array('User.id' => $uid));
            $l = new CakeEmail('smtp');
            $l->emailFormat('html')->template('default', 'default')->subject('Payment')->to($payer_email)->send('Payment Done Successfully');
            $this->set('smtp_errors', "none");
        } else if (strcmp($res, "INVALID") == 0) {
            
        }
        $xt = ob_get_clean();
        fwrite($fc, $xt);
        fclose($fc);
        exit;
    }

    public function api_wallet() {
        $this->layout = "ajax";
        configure::write("debug", 0);
        $postdata = file_get_contents("php://input");
        $redata = json_decode($postdata);
        ob_start();
        print_r($redata);
        $c = ob_get_clean();
        $fc = fopen('files' . DS . 'detail.txt', 'w');
        fwrite($fc, $c);
        fclose($fc);
        if ($redata) {        
           $amt= $this->request->data['Wallet']['amount'] = $redata->paypal->total;
            $this->request->data['Wallet']['txnid'] = $redata->paypal->paymentid;
            $this->request->data['Wallet']['status'] = 1;
            $this->request->data['Wallet']['paypal_status'] = $redata->paypal->status;
            $uid= $this->request->data['Wallet']['uid'] = $redata->user->id;

            if ($this->request->data['Wallet']['paypal_status'] == '"approved"') {
                $this->loadModel('Wallet');
                $this->loadModel('User');
                $this->Wallet->create();
                $this->Wallet->save($this->request->data);
                $user = $this->User->find('first', array('conditions' => array('User.id' => $uid)));
                $total_p = $user['User']['loyalty_points'] + $amt;
                $this->User->updateAll(array('User.loyalty_points' => $total_p), array('User.id' => $uid));
                $response['sucsess'] = "true";
            } else {
                $response['sucsess'] = "false";
            }
        } else {
            $response['sucsess'] = "false";
        }
        echo json_encode($response);
        $this->render('ajax');
        exit;
    }

}

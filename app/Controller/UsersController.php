<?php

class UsersController extends AppController {

    /**
     * Index action
     */
    public function index() {
        
    }

    /**
     * Register user action
     */
    public function add() {
        if ($this->request->is('post')) {
            $user_data = $this->request->data['User'];
            $cc_data = $this->request->data['cc'];
            $this->User->create();
            $this->User->set($this->request->data['User']);
            if ($this->User->validates()) {
                $customer = PaymentUtility::createCustomer(
                    $user_data['first_name'] . " " . $user_data['last_name'], 
                    $cc_data['stripe_token'], 
                    $user_data['email']
                );
                if (is_object($customer) && get_class($customer) === 'Stripe_Customer') {
                    $this->User->set(array(
                        'stripe_customer_id' => $customer->id
                    ));
                    $this->User->save();
                    $email_queued = $this->_addWelcomeEmail();
                    $this->Session->setFlash(__('New user registered. ' . ($email_queued ? 'Welcome email saved.' : 'Welcome email could not be saved.')));
                    return $this->redirect(array('action' => 'index'));
                } else {
                    $this->set('stripe_api_error', $customer);
                }
            }
            $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
        }
    }

    /**
     * Saves welcome email for a new user
     * 
     * @return boolean success
     */
    private function _addWelcomeEmail() {
        $user = $this->User->findById($this->User->id);
        $notification = array(
            'model' => 'User',
            'object_id' => $this->User->id,
            'property' => 'email',
            'type' => 'EMAIL',
            'data' => json_encode(array(
                'settings' => 'default',
                'subject' => 'Welcome!',
                'template' => 'welcome',
                'emailFormat' => 'html',
                'viewVars' => array(
                    'first_name' => $user['User']['first_name'],
                    'last_name' => $user['User']['last_name'],
                    'email' => $user['User']['email']
                )
            ))
        );
        try {
            $NotificationModel = new Notification();
            $NotificationModel->create();
            $NotificationModel->save($notification);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

}
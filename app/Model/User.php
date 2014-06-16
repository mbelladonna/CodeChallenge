<?php

class User extends AppModel {
    
    public $validate = array(
        'first_name' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'First Name required'
            ),
            'alpha_numeric' => array(
				'rule'     => 'alphaNumeric',
				'message'  => 'Alphabets and numbers only'
			),
            'between' => array(
                'rule'    => array('between', 3, 50),
                'message' => 'First name must be between 3 and 50 characters long'
            )
        ),
        'last_name' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Last Name required'
            ),
            'alpha_numeric' => array(
				'rule'     => 'alphaNumeric',
				'message'  => 'Alphabets and numbers only'
			),
            'between' => array(
                'rule'    => array('between', 3, 50),
                'message' => 'Last name must be between 3 and 50 characters long'
            )
        ),
        'email' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Email required'
            ),
            'email' => array(
                'rule'    => array('email', true),
                'message' => 'Please supply a valid email address'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Password required'
            ),
            'between' => array(
                'rule'    => array('between', 5, 15),
                'message' => 'Passwords must be between 5 and 15 characters long'
            )
        ),
    );
    
    public function beforeSave($options = array()) {
        if (!empty($this->data['User']['password'])) {
            $this->data['User']['password'] = Security::hash($this->data['User']['password'], null, true);
        }
        return true;
    }

}
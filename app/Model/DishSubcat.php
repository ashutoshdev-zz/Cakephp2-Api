<?php
App::uses('AppModel', 'Model');
/**
 * DishSubcat Model
 *
 * @property DishCategory $DishCategory
 */
class DishSubcat extends AppModel {

/**
 * Use database config
 *
 * @var string
 */
	public $useDbConfig = 'test';

/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'name';


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'DishCategory' => array(
			'className' => 'DishCategory',
			'foreignKey' => 'dish_catid',
                        'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}

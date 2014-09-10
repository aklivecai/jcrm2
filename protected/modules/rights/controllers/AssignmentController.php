<?php
/**
* Rights assignment controller class file.
*
* @author Christoffer Niska <cniska@live.com>
* @copyright Copyright &copy; 2010 Christoffer Niska
* @since 0.9.1
*/
class AssignmentController extends RController
{
	/**
	* @property RAuthorizer
	*/
	private $_authorizer;

	/**
	* Initializes the controller.
	*/
	public function init()
	{
		$this->_authorizer = $this->module->getAuthorizer();
		$this->layout = $this->module->layout;
		$this->defaultAction = 'view';

		// Register the scripts
		$this->module->registerScripts();
	}

	/**
	* @return array action filters
	*/
	public function filters()
	{
		return array('accessControl');
	}

	/**
	* Specifies the access control rules.
	* This method is used by the 'accessControl' filter.
	* @return array access control rules
	*/
	public function accessRules()
	{
		return array(
			array('allow', // Allow superusers to access Rights
				'actions'=>array(
					'view',
					'user',
					'revoke',
					'viewRoles',
				),
				'users'=>$this->_authorizer->getSuperusers(),
			),
			array('deny', // Deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	* Displays an overview of the users and their assignments.
	*/
	public function actionView()
	{

			$fromid = AK::getFormid();
			$criteria = new CDbCriteria;
		   $criteria->compare('fromid',$fromid);
		   $criteria->compare('manageid','<>'.$fromid);
		   $criteria->compare('user_name','<>admin');

		$module = new AssignmentForm();

		$assignSelectOptions = Rights::getAuthItemSelectOptions(null);
		array_unshift($assignSelectOptions,'操作');
		$module->unsetAttributes();

		$get = Tak::getParam('AssignmentForm',false);

		if($get){
			$module->attributes = $get;
			if($module->itemname&&$module->itemname!='Authenticated') {
        $comm = Ak::getDb('db')->createCommand("SELECT `userid` FROM {{Rbac_Authassignment}}  AS rbac WHERE fromid=$fromid AND itemname='{$module->itemname}'");
        $tags = $comm->queryColumn();
				$criteria->addInCondition("manageid",$tags);
			}
			if ($module->username) {

				$sql = "(user_name LIKE :uname OR user_nicename LIKE :uname )";
				$criteria->addCondition($sql);
				 $criteria->params[':uname']="%$module->username%";  
				// $criteria->compare('user_nicename',$module->username,'OR');
			}
			
		}


		// 找到管理员ID，然后屏蔽掉
		/*	if(Yii::app()->user){
		   		$criteria['condition'] .= ' AND userid!='.Yii::app()->user->id;
		   	}*/
        //$criteria = new CDbCriteria;
        //$criteria->compare('username', $this->id, true);
		// Create a data provider for listing the users
		$dataProvider = new RAssignmentDataProvider(array(
			'pagination'=>array(
				'pageSize'=>12,
			),
		    'criteria'=> $criteria,
		    'sort' =>array(
				'attributes'=>array(
				    'name'=>array(
				        'asc'=>'user_name',
				        'desc'=>'user_name DESC',
				        'label'=>'Item name',
				        'default'=>'desc',
				    ),
				)
			)
		));

		// Render the view
		$this->render('view', array(
			'dataProvider' => $dataProvider,
			'module' => $module,
			'assignSelectOptions' => $assignSelectOptions,
		));
	}

	/**
	* Displays the authorization assignments for an user.
	*/
	public function actionUser()
	{
		// Create the user model and attach the required behavior
		
		$userClass = $this->module->userClass;
		$model = CActiveRecord::model($userClass)->findByPk($_GET['id']);
		$this->_authorizer->attachUserBehavior($model);

		$assignedItems = $this->_authorizer->getAuthItems(null, $model->getId());
		$assignments = array_keys($assignedItems);

		// Make sure we have items to be selected
		$assignSelectOptions = Rights::getAuthItemSelectOptions(null, $assignments);

		
		if( $assignSelectOptions!==array() )
		{
			$formModel = new AssignmentForm();

		    // Form is submitted and data is valid, redirect the user
		    if( isset($_POST['AssignmentForm'])===true )
			{
				$formModel->attributes = $_POST['AssignmentForm'];
				if( $formModel->validate()===true )
				{
					
					// Update and redirect
					$this->_authorizer->authManager->assign($formModel->itemname, $model->getId());
					
					//aklivecai update
							$query = Yii::app()->db->createCommand("
							    UPDATE
							        tak_rbac_authassignment
							    SET
							        fromid = :fromid
							    WHERE
							    	 fromid = 0
							         AND userid = :userid
							         AND itemname = :itemname
							");
							$query->execute(array(
							    'fromid'    => $model->fromid,
							    'userid'    => $model->getId(),
							    'itemname' => $formModel->itemname
							));

					$item = $this->_authorizer->authManager->getAuthItem($formModel->itemname);
			
					$item = $this->_authorizer->attachAuthItemBehavior($item);

					Yii::app()->user->setFlash($this->module->flashSuccessKey,
						Rights::t('core', 'Permission :name assigned.', array(':name'=>$item->getNameText()))
					);

					$this->redirect(array('assignment/user', 'id'=>$model->getId()));
				}
			}
		}
		// No items available
		else
		{
		 	$formModel = null;
		}

		// Create a data provider for listing the assignments
		$dataProvider = new RAuthItemDataProvider('assignments', array(
			'userId'=>$model->getId(),
		));

		// Render the view
		$this->render('user', array(
			'model'=>$model,
			'dataProvider'=>$dataProvider,
			'formModel'=>$formModel,
			'assignSelectOptions'=>$assignSelectOptions,
		));
	}

	/**
	* Revokes an assignment from an user.
	*/
	public function actionRevoke()
	{
		// We only allow deletion via POST request
		if( Yii::app()->request->isPostRequest===true )
		{
			$itemName = $this->getItemName();
			
			// Revoke the item from the user and load it
			$this->_authorizer->authManager->revoke($itemName, $_GET['id']);
			$item = $this->_authorizer->authManager->getAuthItem($itemName);
			$item = $this->_authorizer->attachAuthItemBehavior($item);

			// Set flash message for revoking the item
			Yii::app()->user->setFlash($this->module->flashSuccessKey,
				Rights::t('core', 'Permission :name revoked.', array(':name'=>$item->getNameText()))
			);

			// if AJAX request, we should not redirect the browser
			if( isset($_POST['ajax'])===false )
				$this->redirect(array('assignment/user', 'id'=>$_GET['id']));
		}
		else
		{
			throw new CHttpException(400, Rights::t('core', 'Invalid request. Please do not repeat this request again.'));
		}
	}
	
	/**
	* @return string the item name or null if not set.
	*/
	public function getItemName()
	{
		return isset($_GET['name'])===true ? urldecode($_GET['name']) : null;
	}


	/*aklivecai */
	public function actionViewRoles(){
		
		$connection=Yii::app()->db;   // 假设你已经建立了一个 "db" 连接
		// 如果没有，你可能需要显式建立一个连接：
		$sql = 'SELECT name,t1.type,description,t1.bizrule,t1.data,weight
							FROM {{rbac_authitem}} t1
							LEFT JOIN {{rbac_rights}} t2 ON name=itemname WHERE t1.type=2 	
							ORDER BY t1.type DESC, weight ASC';
		$command=$connection->createCommand($sql);
		$dataReader=$command->query();
		$rows = $dataReader->readAll();

		$tags = array();
		$authenticated = null;
		foreach ($rows as $key => $value) {
			$_name = $value['name'];
			$model = $this->_authorizer->authManager->getAuthItem($_name);
			$model = $this->_authorizer->attachAuthItemBehavior($model);	
			// Tak::KD($model,1);	
			$_t = array(
				'label'=> $value['description'],
				'data' => new RAuthItemChildDataProvider($model),
			);
			if ('Authenticated'==$_name) {
				$authenticated = $_t;
			}else{
				$tags[$_name] = $_t;
			}
		}
		if ($authenticated!=null) {			
			$tags['Authenticated'] = $authenticated;
		}
		
		$this->render('//chip/viewRoles', array(
			'tags'=>$tags,
		));

	}		
}

<?php
App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class DeshBoardController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('GiveHelp','GetHelp','User','UserBank');

/**
 * Displays a view
 *
 * @return void
 * @throws NotFoundException When the view file could not be found
 *	or MissingViewException in debug mode.
 */
	function giveHelp() {
		$this->autoRender = false;
	    $this->layout = "";
	     if($this->data){
	    	$userData = $this->Session->read('User');
	    	$helpData['amount'] = $this->data['amount'];
	    	$helpData['email'] = $userData['username'];
	    	$helpData['user_id'] = $userData['UserId'];
	    	$helpData['start_time'] = date("Y-m-d h:i:s");
	    	$helpData['is_active'] = 1;
	    	$helpData['end_time'] = date('Y-m-d h:i:s', strtotime(' +2 day'));
	    	$this->GiveHelp->save($helpData);
	    } else {
	    	return false;
	    }
	}
	function getHelp() {
		$this->autoRender = false;
	    $this->layout = "";
	    if($this->data){
	    	$userData = $this->Session->read('User');
	    	$helpData['amount'] = $this->data['amount'];
	    	$helpData['email'] = $userData['username'];
	    	$helpData['user_id'] = $userData['UserId'];
	    	$helpData['start_time'] = date("Y-m-d h:i:s");
	    	$helpData['end_time'] = date('Y-m-d h:i:s', strtotime(' +2 day'));
	    	$this->GetHelp->save($helpData);
	    } else {
	    	return false;
	    } 
	}
	function saveBankDetails() {
		$this->autoRender = false;
	    $this->layout = "";
	    $userData = $this->Session->read('User');
		$data['user_id'] = $userData['UserId'];
		if($this->data){
			$data['bank_name'] = $this->data['bankName'];
			$data['account_number'] = $this->data['accountNumber'];
			$data['ifsc_code'] = $this->data['ifsc'];
			$data['branch'] = $this->data['branch'];
			$data['is_active'] = 1;
			$this->UserBank->updateAll(array("is_active"=>0),array("user_id"=>$userData['UserId']));
			$this->UserBank->save($data);
			$this->redirect( array( 'controller' => 'home_pages', 'action' => 'deshBoard?bankDetails=1' ) );
		}
	}
	function adminLogin(){
		$userData = $this->Session->read('User');
		$data['giveHelp'] = $this->GiveHelp->find('all', array( 'fields' =>array('User.name','GiveHelp.user_id','GiveHelp.amount','GiveHelp.start_time','User.email'),'conditions' => array('GiveHelp.is_active' => 1),
			'joins' => array(
                    array('table'=>'users','alias'=>'User','type'=>'inner','conditions'=>array('GiveHelp.user_id = User.id'))
                )));
		$data['getHelp'] = $this->GetHelp->find('all', array( 'fields' =>array('User.name','GetHelp.user_id','GetHelp.amount','GetHelp.start_time','User.email'),'conditions' => array('GetHelp.is_active' => 1),
			'joins' => array(
                    array('table'=>'users','alias'=>'User','type'=>'inner','conditions'=>array('GetHelp.user_id = User.id'))
                )));
		$this->set('HelpRecords',$data);
	}
	function acceptGetHelp() {
		$this->autoRender = false;
	    $this->layout = "";
		if(!empty($this->data['id'])) {
			$this->GetHelp->updateAll(array("is_active"=>0,"is_accepted" =>1),array("id"=>$this->data['id']));
			return true;
		} else {
			return false;
		}
	}
	function submitGiveHelp() {
		$this->autoRender = false;
	    $this->layout = "";
		if(!empty($this->data['id'])) {
			$this->GiveHelp->updateAll(array("is_active"=>0,"status" =>1),array("id"=>$this->data['id']));
			return true;
		} else {
			return false;
		}
	}
}
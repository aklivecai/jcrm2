<?php

class EventsController extends Controller
{
	public function init()  
	{     
    	parent::init();
    	$this->modelName = 'Events';
	}
	
	public function actionList($start=0,$end=0){
		if (!is_numeric($start)||!is_numeric($end)) {
			exit;
		}
		$criteria = new CDbCriteria;

		$criteria->addBetweenCondition('start_time', $start, $end,'OR');

		$criteria->addBetweenCondition('end_time', $start, $end,'OR');
		$data = Events::model()->findAll($criteria);

		$arr = array();
		foreach ($data as $key => $value) {
			$arr[] = array(
				'id' => $value->itemid,
				'title' => $value->subject,
				'start' => $value->start_time,
				'end' => $value->end_time,
				'url' => $value->getLink(),
				'note'=> $value->note,
				'color'=> $value->color,
				'textColor'=> $value->text_color
			);
		}
		$data = json_encode($arr);
		 echo $data;
	}
}

<?php
class MyTopData extends CWidget  {
    public $htmlOptions;
    public $template = 'mytopdata';
    public $tags = array();
    public $title = 'mydata';
	public function init()
	{
		parent::init();
	}
	public function run()
	{
		// Tak::KD($this->tags,1);
     	$this->render($this->template);
	}

}
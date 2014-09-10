<?php
class AppcacheController extends CController
{
	/*start 默认函数*/
	public function init() {     
		parent::init();   
		header('Content-Type: text/cache-manifest');
		header('Cache-Control: no-cache');		
		$this->url = Yii::app()->homeUrl;
	}
	private $url = null;
	public function actionIndex(){
		// Tak::KD(Yii::app()->homeUrl);
		$assetsPath = Yii::app()->theme->getBasePath().'/assets/';
		$assetsPath = 'assets';
		$imgs = $this->list_files($assetsPath);

		$arr = array(
				'site/help',
		);
		$notArr = array(
			'order',
		);		
		foreach ($arr as $key => $value) {
			$arr[$key] = Yii::app()->createUrl($value);
		}

		$files = array_merge($arr,$imgs);

		foreach ($notArr as $key => $value) {
			$notArr[$key] = Yii::app()->createUrl($value).' '.$this->url.'index.php';
		}		
		$NETWORK = array(
				'site/login',
				'appcache/index',
		);
		foreach ($NETWORK as $key => $value) {
			$NETWORK[$key] = Yii::app()->createUrl($value);
		}
		
		// $img = array();
echo "CACHE MANIFEST
# 2014-01-15:v3.4
CACHE:\n".
	join("\n",$files)
."\n\nNETWORK:\n".
	join("\n",$NETWORK)
."\n\nFALLBACK:\n".
	join("\n",$notArr)
;
		flush();
		exit;
	}
	public function list_files( $folder = '', $levels = 100 ) {
	    if( empty($folder) || ! $levels){
	        return false;
	    }
	    $files = array();

	    if ( $dir = @opendir( $folder ) ) {
	    while (($file = readdir( $dir ) ) !== false ) {
	        if ( in_array($file, array('.', '..') ) )
	            continue;
	            if ( is_dir( $folder . '/' . $file ) ) {
	                $files2 = $this->list_files( $folder . '/' . $file, $levels - 1);
	                if( $files2 )
	                $files = array_merge($files, $files2 );
	                else
	                $files[] = $folder . '/' . $file . '/';
	                } else {
	                	$files[] = $this->url.$folder . '/' . $file;
	            }
	        }
	    }
	    @closedir( $dir );
	    return $files;
	}
	public function echoArray($array){
	    for($i=0;$i<count($array);$i++)
	    {
	        echo $array[$i]."\n";
	    }
	}	
}
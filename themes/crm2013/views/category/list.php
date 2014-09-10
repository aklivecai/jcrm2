<?php
$this->breadcrumbs=array(
	Tk::g('Category') => array('index'),
);

$time = Tak::fastUuid();
 ?>
 <a href="list?m=<?php echo $time?>" class="data-ajax" title="<?php echo $time?>">更多</a>

<div id="using_json_2" class="demo">
	
-------------------------
</div>
<script type="text/javascript">
jQuery(function($){
});
	$('#using_json_2').jstree({ 'core' : {
	    'data' : [
	       { "id" : "ajson1", "parent" : "#", "text" : "分类1" },
	       { "id" : "ajson2", "parent" : "#", "text" : "分类2" },
	       { "id" : "ajson7", "parent" : "#", "text" : "分类3" },
	       { "id" : "ajson8", "parent" : "#", "text" : "分类4" },
	       { "id" : "ajson9", "parent" : "#", "text" : "分类5" },
	       { "id" : "ajson10", "parent" : "#", "text" : "分类6" },
	       { "id" : "ajson3", "parent" : "ajson2", "text" : "子分类 1" },
	       { "id" : "ajson4", "parent" : "ajson2", "text" : "子分类 2" },
	       { "id" : "ajson5", "parent" : "ajson2", "text" : "子分类 3" },

	       { "id" : "", "parent" : "ajson3", "text" : "子分类 1" },
	       { "id" : "", "parent" : "ajson3", "text" : "子分类 1" },
	       { "id" : "", "parent" : "ajson3", "text" : "子分类 1" },
	       { "id" : "", "parent" : "ajson3", "text" : "子分类 1" },
	       { "id" : "", "parent" : "ajson3", "text" : "子分类 1" },

	       { "parent" : "#", "text" : "分类6" },
	       { "parent" : "#", "text" : "分类6" },
	       { "parent" : "#", "text" : "分类6" },
	       { "parent" : "#", "text" : "分类6" },
	       { "parent" : "#", "text" : "分类6" },
	       { "parent" : "#", "text" : "分类6" },

	    ]
	} }).on('loaded.jstree', function() {
    $treeview.jstree('open_all');
  });
</script>
<?php
/* @var $this TestMemeberController */
/* @var $model TestMemeber */

$this->breadcrumbs = array(
    Tk::g('Contact531') => array(
        'admin'
    ) ,
    '管理',
);
$msg = Yii::app()->user->getFlash('msg', false);
if ($msg) {
    echo "<div class=\"flash success\">$msg</div>";
}
?>

<div>
 <form method="post" action="http://www.szcredit.com.cn/web/WebPages/Search/SZSEntList.aspx?flag=0&type=1" target="_blank">
    <label>关键字:
        <input type="text" id="kw" name="txtNameKey" />
        <button id="btn-search-zzjg" type="button">搜索组织机构代码</button>
        &nbsp;
        <button type="submit">深圳诚信网</button>
    </label>    
</form>
</div>
<hr  />

<?php echo CHtml::link(Tk::g('Advanced Search') , '#', array(
    'class' => 'search-button'
)); ?>
<div class="search-form" style="<?php echo isset($_GET['search']) ? '' : 'display:none'; ?>">
<?php $this->renderPartial('_search', array(
    'model' => $model,
)); ?>
</div><!-- search-form -->
<hr />
<style type="text/css">
    .ibtn{color: #FFF !important;
background-color: #f3f3f3;
background-position: 0 0;
border: 1px solid #b1aeae;
border-radius: 2px;
text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
padding: 4px 10px;
_padding: 5px 10px 4px;
display: inline-block;
cursor: pointer;
font-size: 100%;
line-height: normal;
text-decoration: none;
overflow: visible;
vertical-align: middle;
text-align: center;
zoom: 1;
white-space: nowrap;
font-family: inherit;
margin: 0 3px 0 0;
background-position: 0 -120px;
background-color: #3b7dc3;
text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
border-color: #0f75a4 #0e6191 #0c497c #0e6191;
    }
</style>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'contact531-grid',
    'dataProvider' => $model->search() ,
    'enableSorting' => false,
    'ajaxUpdate' => false,
    'enableHistory' => false,
    'columns' => array(
        array(
            'type' => 'raw',
            'name' => '',
            'value' => 'JHtml::link("正式转入",Yii::app()->getController()->createUrl("toClientele",array("id"=>$data->primaryKey)),array("class"=>"ibtn btn-confirm","data-itemid"=>$data->primaryKey))." <hr/>".JHtml::button("机构代码",array("title"=>$data->clientele_name,"class"=>"view-zc-code"))',
            'headerHtmlOptions' => array(
                'style' => 'width: 60px'
            ) ,
        ) ,
        'clientele_name',
        array(
            'name' => 'nicename',
            'headerHtmlOptions' => array(
                'style' => 'width: 60px'
            ) ,
        ) ,
        array(
            'name' => '联系电话',
            'type' => 'raw',
            'value' => '$data->phone."<hr />".$data->mobile',
            'htmlOptions' => array(
                'style' => 'width: 95px'
            ) ,
        ) ,
        array(
            'name' => 'address',
            'htmlOptions' => array(
                'style' => 'width: 150px'
            ) ,
        ) ,
        array(
            'name' => 'business',
            'htmlOptions' => array(
                'style' => 'width: 120px;word-break:break-all;'
            ) ,
        ) ,
        /*
        array(
            'name' => 'add_time',
            'value' => 'Tak::timetodate($data->add_time, 4)',
        ) ,
        'note',
        */
        array(
            'class' => 'CButtonColumn',
        ) ,
    ) ,
));

Tak::regScriptFile('_ak/js/crypto.js', 'static');
?>

<script type="text/javascript">
$('.view-zc-code').click(function(event) {
    event.preventDefault();
    goto($(this).attr('title'));
});
$('#btn-search-zzjg').click(function(event) {
    event.preventDefault();
    goto($('#kw').val());
});
function goto(keyword) {
    var aeskey = "phabro",
        strtp = "jgmc=" + keyword,
        xzqhName1 = xzqhName2 = lastxzqh = 'alll',
        xzqhName1 = 'alll',
        kind = 2;
    // console.log(strtp, keyword, xzqhName1, xzqhName2, lastxzqh);
    var tags = ['x=' + Crypto.AES.encrypt(lastxzqh, aeskey), 'k=' + Crypto.AES.encrypt(kind, aeskey), 's=' + Crypto.AES.encrypt(strtp, aeskey), 'y=' + Crypto.AES.encrypt(keyword, aeskey), ];
    var url = "https://s.nacao.org.cn/specialResult.html?" + tags.join('&');
    window.open(url);
}
</script>

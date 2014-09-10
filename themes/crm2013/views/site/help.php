<?php
$this->pageTitle = Yii::app()->name . ' - 帮助中心';
$this->breadcrumbs = array(
'帮助中心 ',
);

$jss = array(
'plugins/highlight/jquery.highlight-4.js',
'faq.js'
);
$this->regScriptFile($jss);
?>
<div class="page-header">
    <h1>帮助中心 <small>和常见问题</small></h1>
</div>

<div class="row-fluid">
    <div class="span8">
        <div class="headInfo">
            <div class="input-append">
                <input type="text" name="text" placeholder="关键词.." id="widgetInputMessage" class="faqSearchKeyword"/><button class="btn btn-success" id="faqSearch" type="button">搜索</button>
            </div>
            <div class="arrow_down"></div>
        </div>
        <div class="block-fluid">
            <div class="toolbar clearfix">
                <div class="left">
                    <div id="faqSearchResult" class="note"></div>
                </div>
                <div class="right">
                    <div class="btn-group">
                        <button class="btn btn-small" id="faqOpenAll" title="打开所有"><span class="icon-chevron-down icon-white"></span></button>
                        <button class="btn btn-small" id="faqCloseAll" title="折叠所有"><span class="icon-chevron-up icon-white"></span></button>
                        <button class="btn btn-small" id="faqRemoveHighlights" title="关闭高亮"><span class="icon-remove icon-white"></span></button>
                    </div>
                </div>
            </div>
            <div class="faq">
                <div class="item" id="faq-1">
                    <div class="title">进入系统之后怎么添加客户资料？</div>
                    <div class="text"><p>
                    在左侧工具栏选“我的客户” 然后选“客户新建”</p></div>
                </div>

                <div class="item" id="faq-2">
                    <div class="title">具人同行在线营销管理系统 需要下载安装吗？</div>
                    <div class="text"><p>
                    租用我们的软件服务器平台。客户的软件客户端无需安装，只需使用以IE8或以上版本浏览器便可直接访问使用，推荐使用谷歌官方 Chrome 浏览器.</p></div>
                </div>

                <div class="item" id="faq-3">
                    <div class="title">什么是云端网盘？</div>
                    <div class="text"><p>网盘是一个在线的基于云端的文件管理和存储空间。</p></div>
                </div>

                <div class="item" id="faq-4">
                    <div class="title">具人同行在线营销管理系统有何亮点和优势？</div>
                    <div class="text"><p>本系统有“在线”“营销”“管理”集于一身的亮点；
                        其优点分别为：<br />
                        “在线”：在任何有网络的环境里，通过电脑、手机均可实时进入本系统；<br />
                        “营销”：用户可通过系统展示、发布自己的产品信息，提升用户成交机会；<br />
                        “管理”用户可随时随地管理企业和客户信息；
                    </p></div>
                </div>

                <div class="item" id="faq-6">
                    <div class="title">应用本系统的服务后，如何保障用户资料的安全性和保密性？</div>
                    <div class="text"><p>系统经过多层数据加密链接方式对数据库进行动态访问，避免了数据外泄，因此确保了数据内容的安全性，客户可以安心应用并与我们签订保密协议。
                    </p><p>我们签订保密协议。。</p></div>
                </div>

                <div class="item" id="faq-7">
                    <div class="title">系统用户在付费应用期内享受免费的升级及服务吗？</div>
                    <div class="text"><p>具人同行以客户为中心，乐于服务，客户自购买系统后的使用期内均可享受管理系统的免费升级服务，详细细则请咨询全程系统售后服务电话：4000-168-488</p></div>
                </div>

                <div class="item" id="faq-9">
                    <div class="title">本系统适用哪种类型的企业？</div>
                    <div class="text"><p>本系统可应用于各类销售型、服务型企业，是一套侧重于营销和管理型的综合型系统，使企业员工能根据授权范围来跟踪客户，服务客户，管理客户。</p></div>
                </div>

                <div class="item" id="faq-10">
                    <div class="title">用户应用本系统的核心是什么？</div>
                    <div class="text"><p>本系统是以用户的销售和管理为核心系统，因此，在进行规划的时候应从客户的价值和客户的满意度出发。本系统不仅仅是追求业务处理效率的提升，也不是一个单纯的面向员工和内部的流程，而是面向客户的整个流程，其强调的是客户体验，也就是说规划本系统流程时是围绕着客户体验来进行设计，而不仅仅将规划的着眼点放在企业内部。 </p></div>
                </div>
            </div>
        </div>
    </div>
    <div class="span4">

        <div class="block-fluid without-head">
            <div class="toolbar nopadding-toolbar clear clearfix">
                <h4>常见问题</h4>
            </div>

            <ul class="list nol" id="faqListController">
                <li><a href="#faq-3">什么是云端网盘？</a></li>
                <li><a href="#faq-6">客户信息安全?</a></li>
                <li><a href="#faq-9">本系统适用于什么类型的公司?</a></li>
            </ul>

        </div>

        <?php echo JHtml::beginForm('', $method = 'post', array(
        "id" => "signupForm"
        )); ?>
        <div class="block-fluid nm without-head">
            <div class="toolbar nopadding-toolbar clear clearfix">
                <h4>问题反馈</h4>
            </div>

            <div class="row-form clearfix">
                <div class="span3">名字</div>
                <div class="span9">
                    <input type="text" placeholder="名字" required="required" name="post[name]">
                </div>
            </div>
            <div class="row-form clearfix">
                <div class="span3">Email</div>
                <div class="span9">
                    <input type="email" placeholder="Email" required='required' name="post[email]">
                </div>
            </div>
            <div class="row-form clearfix">
                <div class="span12">
                    <textarea placeholder="内容" required="required" name="post[note]"></textarea>
                </div>
            </div>
            <div class="footer tar">
                <button class="btn">提交</button>
            </div>
        </div>
    </div>
</div>
<?php echo JHtml::endForm(); ?>
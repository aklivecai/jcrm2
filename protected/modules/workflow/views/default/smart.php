<!-- <link href="styles/smart_wizard.css" rel="stylesheet" type="text/css"> -->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/smart_wizard.css">
<!-- <script type="text/javascript" src="js/jquery.smartWizard-2.0.min.js"></script> -->

<script type="text/javascript">
    $(document).ready(function(){
        $('#wizard').smartWizard({
                // selected: 1,
                // errorSteps:[0],
                // labelNext: "下一步",
                // labelPrevious: "上一步",
                // labelFinish: "提交",
                // onFinish: submitAction,
                // transitionEffect:"slideleft",
                // onLeaveStep: leaveAStepCallback,
                // onFinish:onFinishCallback,
                // enableFinishButton: true
            });
        
        // function onFinishCallback(){
        //     $('#wizard').smartWizard('showMessage','Finish Clicked');
        // }     
    });
</script>


<table align="center" border="0" cellpadding="0" cellspacing="0">
<tr><td> 
<!-- Smart Wizard -->
        <div id="wizard" class="swMain">
            <ul>
                <li><a href="#step-1">
                <label class="stepNumber">1</label>
                <span class="stepDesc">
                   第一步<br />
                   <small>Step 1 description</small>
                </span>
            </a></li>
                <li><a href="#step-2">
                <label class="stepNumber">2</label>
                <span class="stepDesc">
                   Step 2<br />
                   <small>Step 2 description</small>
                </span>
            </a></li>
                <li><a href="#step-3">
                <label class="stepNumber">3</label>
                <span class="stepDesc">
                   Step 3<br />
                   <small>Step 3 description</small>
                </span>                   
             </a></li>
                <li><a href="#step-4">
                <label class="stepNumber">4</label>
                <span class="stepDesc">
                   Step 4<br />
                   <small>Step 4 description</small>
                </span>                   
            </a></li>
            </ul>
            <div id="step-1">   
            <h2 class="StepTitle">Step 1 Content</h2>
            <ul type="disk">
                    <li>List 1</li>
                    <li>List 2</li>
            </ul>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, 
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            </p>
        </div>
        <div id="step-2">
            <h2 class="StepTitle">Step 2 Content</h2>   
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, 
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            </p> 
        </div>                      
        <div id="step-3">
            <h2 class="StepTitle">Step 3 Content</h2>   
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, 
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            </p>
        </div>
        <div id="step-4">
            <h2 class="StepTitle">Step 4 Content</h2>   
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, 
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, 
            quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            </p>                         
        </div>
        </div>
<!-- End SmartWizard Content -->        
        
</td></tr>
</table>
            
</body>
</html>
<?php 
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.smartWizard-2.0.min.js');
?>
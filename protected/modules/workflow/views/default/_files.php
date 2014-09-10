<div id="wap-files">
    <div class="mod hide" data-bind="css: { hide: (lines().length==0) }">
        <div class="mod-head">附件信息</div>
        <div class="mod-body">
            <table class="itable sm-list">
                <colgroup align="center">
                <col width="auto" />
                    <col width="200px" span="2" />
                    <col width="120px" />
                </colgroup>
                <thead>
                    <tr>
                        <th>附件名字</th>
                        <th>上传者</th>
                        <th>上传时间</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody data-bind="template: { name: 'list-files', foreach: lines }"></tbody>
            </table>
        </div>
    </div>
</div>

<a href="" target="_blank"></a>
<script type="text/html" id="list-files">
        <tr>
                <td data-bind="text:name"></td>
                <td data-bind="text:user"></td>
                <td data-bind="text: time"></td>
                <td>
                <a  target="_blank" data-bind="attr:{href:$root.getDown(itemid)}">下载</a>
		    <!-- ko if: del -->
                                    <input type="hidden" name="files[]" data-bind="value:itemid" />
		        <a href="javascript:;" data-bind="click:$root.removeI,attr:{id:itemid}">删除</a>
		    <!-- /ko -->                
                </td>
        </tr>
</script>
<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:84:"F:\programs\yuetanglvju\public/../application/admin\view\goods\ajax_spec_select.html";i:1533208697;}*/ ?>
<table class="layui-table" id="goods_spec_table1">
    <thead>
        <tr>
            <td colspan="2"><b>商品规格:</b></td>
        </tr>
    </thead>

    <?php if(is_array($specList) || $specList instanceof \think\Collection || $specList instanceof \think\Paginator): $i = 0; $__LIST__ = $specList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
        <tr>
            <td><?php echo $vo['name']; ?></td>
            <td>
                <?php if(is_array($vo['spec_item']) || $vo['spec_item'] instanceof \think\Collection || $vo['spec_item'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['spec_item'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?>
                    <button type="button" data-spec_id='<?php echo $vo["id"]; ?>' data-item_id='<?php echo $vo2['id']; ?>' class='layui-btn layui-btn-radius <?php if(!in_array($vo2['id'],$items_ids)): ?>layui-btn-primary<?php endif; ?>'>
                        <?php echo $vo2['item']; ?>
                    </button>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </td>
        </tr>
    <?php endforeach; endif; else: echo "" ;endif; ?>
</table>
<div id="goods_spec_table2"> <!--ajax 返回 规格对应的库存--> </div>

<script>
    layui.use(['form','table','upload'], function() {
        var form = layui.form, table = layui.table, upload = layui.upload,$ = layui.jquery;

        uploadImg();
        function uploadImg() {
            upload.render({
                elem: '.upload'
                ,url: '<?php echo url("upload/upload"); ?>'
                ,multiple: false
                ,size: 1024
                ,before: function(obj){
                    //预读本地文件示例，不支持ie8
                    var $this = this;
                    obj.preview(function(index, file, result){
                        var item = $this.item;
                        item.next().next().attr('src',result)
                        // $('#showImg').attr('src', result); //图片链接（base64）
                    });
                }
                ,done: function(res){
                    //如果上传失败
                    if(res.code > 0){
                        return layer.msg('上传失败');
                    }
                    //上传成功
                    // $('#path').val(res.data.src); // 将上传后的图片路径赋值给隐藏域
                    var item = this.item;
                    // item.next().attr('src',result)
                    item.next().next().next().val(res.data.src)
                    // $(this).html('<input type="hidden" name="image[]" value="'+res.data.src+'">');
                }
            });
        }

        if ($(this).hasClass('layui-btn-primary')) {
            $(this).removeClass('layui-btn-primary');
        } else {
            $(this).addClass('layui-btn-primary');
        }
        ajaxGetSpecInput();
        // 规格按钮切换 class
        $("#ajax_spec_data button").click(function () {
            if ($(this).hasClass('layui-btn-primary')) {
                $(this).removeClass('layui-btn-primary');
            } else {
                $(this).addClass('layui-btn-primary');
            }
            ajaxGetSpecInput();
        });

        $(function () {
            $(document).on("click", '.delete_item', function (e) {
                if($(this).text() == '无效'){
                    $(this).parent().parent().find('input[type=text]').attr('disabled','disabled');
                    $(this).parent().parent().find('input[type=text]').val(0);
                    $(this).parent().parent().find('input[type=hidden]').val('');
                    $(this).parent().parent().find('img').attr('src','');
                    $(this).text('有效');
                }else{
                    $(this).text('无效');
                    $(this).parent().parent().find('input[type=text]').removeAttr('disabled');
                }
            })
        })
        /**
         *  点击商品规格触发下面输入框显示
         */
        function ajaxGetSpecInput() {
            var spec_arr = {};// 用户选择的规格数组
            // 选中了哪些属性
            $("#goods_spec_table1  button").each(function () {
                if (!$(this).hasClass('layui-btn-primary')) {
                    var spec_id = $(this).data('spec_id');
                    var item_id = $(this).data('item_id');
                    if (!spec_arr.hasOwnProperty(spec_id))
                        spec_arr[spec_id] = [];
                    spec_arr[spec_id].push(item_id);
                }
            });
            ajaxGetSpecInput2(spec_arr); // 显示下面的输入框
        }


        /**
         * 根据用户选择的不同规格选项
         * 返回 不同的输入框选项
         */
        function ajaxGetSpecInput2(spec_arr) {

            var product_id = $("input[name='id']").val();
            $.ajax({
                type: 'POST',
                data: {spec_arr: spec_arr, product_id: product_id},
                url: "<?php echo url('Goods/ajaxGetSpecInput'); ?>",
                success: function (data) {
                    $("#goods_spec_table2").html('').append(data);
                    uploadImg()
                    hbdyg();  // 合并单元格
                }
            });
        }

        // 合并单元格
        function hbdyg() {
            var tab = document.getElementById("spec_input_tab"); //要合并的tableID
            var maxCol = 2, val, count, start;  //maxCol：合并单元格作用到多少列
            if (tab != null) {
                for (var col = maxCol - 1; col >= 0; col--) {
                    count = 1;
                    val = "";
                    for (var i = 0; i < tab.rows.length; i++) {
                        if (val == tab.rows[i].cells[col].innerHTML) {
                            count++;
                        } else {
                            if (count > 1) { //合并
                                start = i - count;
                                tab.rows[start].cells[col].rowSpan = count;
                                for (var j = start + 1; j < i; j++) {
                                    tab.rows[j].cells[col].style.display = "none";
                                }
                                count = 1;
                            }
                            val = tab.rows[i].cells[col].innerHTML;
                        }
                    }
                    if (count > 1) { //合并，最后几行相同的情况下
                        start = i - count;
                        tab.rows[start].cells[col].rowSpan = count;
                        for (var j = start + 1; j < i; j++) {
                            tab.rows[j].cells[col].style.display = "none";
                        }
                    }
                }
            }
        }
    })
</script>
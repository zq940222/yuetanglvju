<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:73:"F:\programs\yuetanglvju\public/../application/admin\view\goods\model.html";i:1533713162;s:61:"F:\programs\yuetanglvju\application\admin\view\base\base.html";i:1530860248;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>悦棠旅居</title>
    <link rel="stylesheet" href="/static/css/layui.css">
</head>
<body>

<div class="layui-anim layui-anim-up layui-field-box">
    <form class="layui-form layui-form-pane" action="<?php echo url('model'); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
        <div class="layui-form-item">
            <label class="layui-form-label">商品模型</label>
            <div class="layui-input-inline">
                <select name="type" lay-verify="type" lay-filter="type" lay-search="">
                    <option value="0"> -请选择商品模型- </option>
                    <?php if(is_array($product_type) || $product_type instanceof \think\Collection || $product_type instanceof \think\Paginator): $i = 0; $__LIST__ = $product_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                    <option value="<?php echo $item['id']; ?>" <?php if($item['id']==$product['product_type_id']): ?>selected<?php endif; ?>><?php echo $item['name']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
        </div>
        <div id="ajax_spec_data">
            <table id="product_spec" lay-filter="product_spec" class="layui-table">
                <thead>
                <tr>
                    <td colspan="2"><b>商品规格:</b></td>
                </tr>
                </thead>

                <?php if(is_array($spec) || $spec instanceof \think\Collection || $spec instanceof \think\Paginator): $i = 0; $__LIST__ = $spec;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$items): $mod = ($i % 2 );++$i;?>
                <tr>
                    <td><?php echo $items['name']; ?></td>

                    <td>
                        <?php if(is_array($items['item']) || $items['item'] instanceof \think\Collection || $items['item'] instanceof \think\Paginator): $i = 0; $__LIST__ = $items['item'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                        <button class="layui-btn layui-btn-radius layui-btn-primary" lay-filter="">
                            <?php echo $item['item']; ?>
                        </button>
                        <?php endforeach; endif; else: echo "" ;endif; ?>

                    </td>
                </tr>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </table>
            <div id="goods_spec_table2"> <!--ajax 返回 规格对应的库存--> </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="*">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>
</div>

</body>
<script src="/static/layui.js"></script>

<script>
    layui.use(['form','table','upload'], function() {
        var form = layui.form, table = layui.table, $ = layui.jquery, upload = layui.upload;

        form.on('select(type)', function(data){
            var product_id = '<?php echo $product['id']; ?>';
            var spec_type = data.value;
            $.ajax({
                type:'GET',
                data:{product_id:product_id,spec_type:spec_type},
                url:"<?php echo url('Goods/ajaxGetSpecSelect'); ?>",
                success:function(data){
                    $("#ajax_spec_data").html('')
                    $("#ajax_spec_data").append(data);
                }
            });

        });
        var product_id = '<?php echo $product['id']; ?>';
        var spec_type = '<?php echo $product['product_type_id']; ?>';
        $.ajax({
            type:'GET',
            data:{product_id:product_id,spec_type:spec_type},
            url:"<?php echo url('Goods/ajaxGetSpecSelect'); ?>",
            success:function(data){
                $("#ajax_spec_data").html('')
                $("#ajax_spec_data").append(data);
            }
        });

        //监听提交
        form.on('submit(*)', function(data) {
            
            $.ajax({
                url:'model',
                type:'post',
                data:data.field,
                success:function (data) {
                    var code = data.code.toString();
                    var startChar = code.charAt(0);
                    if (startChar == '2')
                    {
                        layer.alert(data.msg, {icon: 6},function () {
                            // 获得frame索引
                            var index = parent.layer.getFrameIndex(window.name);
                            //关闭当前frame
                            parent.layer.close(index);
                        });
                    }else{
                        layer.alert(data.msg, {icon: 7});
                    }
                },
                error:function (error) {
                    layer.msg(error.responseJSON.msg);
                }
            })
            return false;
        });

    });
</script>

</html>

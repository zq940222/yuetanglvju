<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:72:"F:\programs\yuetanglvju\public/../application/admin\view\hotel\edit.html";i:1533776913;s:61:"F:\programs\yuetanglvju\application\admin\view\base\base.html";i:1530860248;}*/ ?>
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
    <form class="layui-form layui-form-pane" action="<?php echo url('edit',['id'=>$hotel['id']]); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $hotel['id']; ?>" name="id">
        <div class="layui-form-item">
            <label class="layui-form-label">酒店名</label>
            <div class="layui-input-inline">
                <input type="text" name="name" lay-verify="required"  placeholder="请输入酒店名" class="layui-input" value="<?php echo $hotel['name']; ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">电话</label>
            <div class="layui-input-inline">
                <input type="text" name="phone" lay-verify="required"  placeholder="请输入电话" class="layui-input" value="<?php echo $hotel['phone']; ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">起价</label>
            <div class="layui-input-inline">
                <input type="text" name="min_price" lay-verify="required"  placeholder="请输入起价" class="layui-input" value="<?php echo $hotel['min_price']; ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">省</label>
            <div class="layui-input-inline">
                <select name="province" id="province" lay-search lay-filter="province">
                    <option value="0">-请选择省-</option>
                    <?php if(is_array($region) || $region instanceof \think\Collection || $region instanceof \think\Paginator): $i = 0; $__LIST__ = $region;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                    <option value="<?php echo $item['id']; ?>" <?php if($hotel['province']==$item['id']): ?>selected<?php endif; ?>><?php echo $item['name']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">市</label>
            <div class="layui-input-inline">
                <select name="city" id="city" lay-search lay-filter="city">
                    <option value="0">-请选择市-</option>
                    <?php if(is_array($city) || $city instanceof \think\Collection || $city instanceof \think\Paginator): $i = 0; $__LIST__ = $city;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                    <option value="<?php echo $item['id']; ?>" <?php if($hotel['city']==$item['id']): ?>selected<?php endif; ?>><?php echo $item['name']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">区</label>
            <div class="layui-input-inline">
                <select name="district" id="district" lay-search lay-filter="district">
                    <option value="0">-请选择区-</option>
                    <?php if(is_array($district) || $district instanceof \think\Collection || $district instanceof \think\Paginator): $i = 0; $__LIST__ = $district;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                    <option value="<?php echo $item['id']; ?>" <?php if($hotel['district']==$item['id']): ?>selected<?php endif; ?>><?php echo $item['name']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">详细地址</label>
            <div class="layui-input-inline">
                <input type="text" name="detail_address" lay-verify="required"  placeholder="请输入详细地址" class="layui-input" value="<?php echo $hotel['detail_address']; ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">景区</label>
            <div class="layui-input-inline">
                <select name="scenic_area_id" id="scenic_area" lay-search>
                    <option value="0">-请选择景区-</option>
                    <?php if(is_array($scenic_area) || $scenic_area instanceof \think\Collection || $scenic_area instanceof \think\Paginator): $i = 0; $__LIST__ = $scenic_area;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                    <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">备注</label>
            <div class="layui-input-inline">
                <input type="text" name="remark"  placeholder="请输入备注" class="layui-input" value="<?php echo $hotel['remark']; ?>">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">几人成团</label>
            <div class="layui-input-inline">
                <input type="number" name="group_num"  placeholder="请输入成团人数" class="layui-input" value="<?php echo $hotel['group_num']; ?>">
            </div>
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
    layui.use(['form'],function () {
        var form = layui.form,$ = layui.jquery;
        form.on('select(province)', function(data) {
            var $next = $("#city");
            $next.get(0).options.length=1;
            var id = data.value;
            $.post("<?php echo url('getRegion'); ?>", {id:id}, function(data) {
                $.each(data, function(index, item) {
                    var $option = $('<option value="'+item.id+'">'+item.name+'</option>');
                    $next.append($option);
                });
                form.render('select');//重新渲染
            });

        });
        form.on('select(city)', function(data) {
            var $next = $("#district");
            $next.get(0).options.length=1;
            var id = data.value;
            $.post("<?php echo url('getRegion'); ?>", {id:id}, function(data) {
                $.each(data, function(index, item) {
                    var $option = $('<option value="'+item.id+'">'+item.name+'</option>');
                    $next.append($option);
                });
                form.render('select');//重新渲染
            });

        });
        //监听提交
        form.on('submit(*)', function(data){
            $.ajax({
                url:'edit',
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
    })
</script>

</html>

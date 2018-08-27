<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:73:"F:\programs\yuetanglvju\public/../application/admin\view\goods\lists.html";i:1533726889;s:61:"F:\programs\yuetanglvju\application\admin\view\base\base.html";i:1530860248;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>悦棠旅居</title>
    <link rel="stylesheet" href="/static/css/layui.css">
</head>
<body>

<blockquote class="layui-elem-quote">
    <form class="layui-form">
        <div class="layui-inline">
            <div class="layui-input-inline">
                <input type="text" placeholder="请输入商品名称" name="title" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-input-inline">

            <select name="category_id" lay-verify="category" lay-filter="category" lay-search="">
                <option value="0"> 商品分类 </option>
                <?php if(is_array($category) || $category instanceof \think\Collection || $category instanceof \think\Paginator): $i = 0; $__LIST__ = $category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
        <div class="layui-input-inline">
            <select name="status">
                <option value="close"> 商品状态 </option>
                <option value="0"> 已下架 </option>
                <option value="1"> 已上架 </option>
            </select>
        </div>

        <button lay-submit class="layui-btn" lay-filter="search">查询</button>
    </form>
</blockquote>
<button class="layui-btn layui-btn-danger" id="del-all">
    批量删除
</button>
<button class="layui-btn" id="add">
    <i class="layui-icon">&#xe608;</i> 添加
</button>
<table class="layui-table" lay-data="{id:'product-list', height:600, url:'<?php echo url('page'); ?>', page: true}" lay-filter="product-list">
    <thead>
    <tr>
        <th lay-data="{field:'id', width: 80, sort: true}">商品ID</th>
        <th lay-data="{checkbox:true}"></th>
        <th lay-data="{field:'title'}">商品名称</th>
        <th lay-data="{field:'subhead'}">副标题</th>
        <th lay-data="{field:'image'}">商品图片</th>
        <th lay-data="{field:'brand'}">品牌名称</th>
        <th lay-data="{field:'category_name'}">商品分类</th>
        <th lay-data="{field:'min_price',sort: true}">商品价格</th>
        <th lay-data="{field:'store', sort: true}">商品库存</th>
        <th lay-data="{field:'num_sold', sort: true}">总销量</th>
        <th lay-data="{field:'create_time', sort: true}">添加时间</th>
        <th lay-data="{field:'is_recommend', templet:'#is_recommend'}">是否推荐</th>
        <th lay-data="{field:'status', templet:'#status'}">商品状态</th>
        <th lay-data="{fixed:'right', toolbar: '#action',width:178, align:'center'}">操作</th>
    </tr>
    </thead>
</table>
<script type="text/html" id="is_recommend">
    <input type="checkbox" name="is_recommend" value="{{d.id}}" title="是" lay-filter="is_recommend" {{ d.is_recommend == 1 ? 'checked' : '' }}>
</script>
<script type="text/html" id="status">
    <input type="checkbox" name="status" value="{{d.id}}" title="已上架" lay-filter="status" {{ d.status == 1 ? 'checked' : '' }}>
</script>
<div class="layui-hide" id="action">
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-xs" lay-event="model">商品模型</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</div>

</body>
<script src="/static/layui.js"></script>

<script>

    layui.use(['layer', 'table', 'form'], function(){
        var layer = layui.layer, table = layui.table, $ = layui.jquery, form = layui.form;

        //监听工具条
        table.on('tool(product-list)', function(obj){
            var data = obj.data;
            if (obj.event === 'edit'){
                //详情
                var index = layer.open({
                    type: 2,
                    area: ['900px', '700px'],
                    fixed: false, //不固定
                    maxmin: true,
                    content: '<?php echo url("edit"); ?>?id='+obj.data.id
                });
                layer.full(index);
            }else if(obj.event === 'model'){
                var index = layer.open({
                    type: 2,
                    area: ['900px', '700px'],
                    fixed: false, //不固定
                    maxmin: true,
                    content: '<?php echo url("model"); ?>?id='+obj.data.id
                });
                layer.full(index);
            }else if (obj.event === 'del'){
                layer.msg('你确定删除么？', {
                    time: 0 //不自动关闭
                    ,btn: ['确定', '取消']
                    ,yes: function(index){
                        $.post('<?php echo url("delete"); ?>', {id:obj.data.id}, function(data) {
                            if (data.code){
                                obj.del();
                                layer.close(index);
                                layer.msg('已删除');
                            }else{
                                layer.msg('删除失败');
                            }
                        });

                    }
                });
            }
        });
        //添加
        $("#add").on('click',function () {
            var index= layer.open({
                type: 2,
                title:'添加商品',
                area: ['600px', '400px'],
                maxmin: true,
                fixed: false, //不固定
                content: '<?php echo url("add"); ?>',
                end: function () {
                    location.reload();
                }
            });
            layer.full(index);
        });
        //监听是否推荐操作
        form.on('checkbox(is_recommend)', function(obj){
            $.post('<?php echo url("change"); ?>', {id:this.value, field:'is_recommend', model:'product'}, function(data) {});
            layer.tips(this.value + ' ' + this.name + '：'+ obj.elem.checked, obj.othis);
        });
        form.on('checkbox(status)', function(obj){
            var e = obj;
            $.post('<?php echo url("putaway"); ?>', {id:this.value}, function(data) {
                var code = data.code.toString();
                var startChar = code.charAt(0);
                if (startChar == '2')
                {
                    layer.alert(data.msg, {icon: 6});
                }else{
                    layer.alert(data.msg, {icon: 7},function () {
                        location.reload();
                    });
                }
            });
        });
        // 监听表单提交事件
        form.on('submit(search)', function(data) {
            table.reload('table', {
                where:data.field
            });
            return false; // 阻止表单提交
        })
        //批量删除
        $("#del-all").on('click',function () {
            var checkStatus = table.checkStatus('product-list');
            var delList = [];
            checkStatus.data.forEach(function(n,i){
                delList.push(n.id);
            });
            if(delList == ''){
                layer.msg("请选择需要删除的选项");
                return ;
            }
            layer.msg('你确定删除么？', {
                time: 0 //不自动关闭
                ,btn: ['确定', '取消']
                ,yes: function(index){
                    $.post('<?php echo url("delete"); ?>', {id:delList}, function(data) {
                        var code = data.code.toString();
                        var startChar = code.charAt(0);
                        if (startChar == '2')
                        {
                            table.reload('product-list',{});
                            layer.alert(data.msg, {icon: 6});
                        }else{
                            layer.alert(data.msg, {icon: 7});
                        }

                    });

                }
            });
        })
        // 监听表单提交事件
        form.on('submit(search)', function(data) {
            table.reload('product-list', {
                where:data.field
            });
            return false; // 阻止表单提交
        })
    });
</script>

</html>

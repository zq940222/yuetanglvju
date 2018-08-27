<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:80:"F:\programs\yuetanglvju\public/../application/admin\view\product_spec\lists.html";i:1531382113;s:61:"F:\programs\yuetanglvju\application\admin\view\base\base.html";i:1530860248;}*/ ?>
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
    <form action="lists" class="layui-form" type="post">
        <div class="layui-input-inline">
            <select name="type_id" id="product_type" class="layui-input" lay-filter="product_type">
                <option value="0">-所有模型-</option>
                <?php if(is_array($typeList) || $typeList instanceof \think\Collection || $typeList instanceof \think\Paginator): $i = 0; $__LIST__ = $typeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
        <button lay-submit class="layui-btn" lay-filter="search"> 查询 </button>
    </form>
</blockquote>
<button class="layui-btn layui-btn-danger" id="del-all">
    批量删除
</button>
<button class="layui-btn" id="add">
    <i class="layui-icon">&#xe608;</i> 添加
</button>

<table id="list" lay-filter="list"></table>


</body>
<script src="/static/layui.js"></script>


<script type="text/html" id="bar">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>

<script>

    layui.use(['layer', 'table', 'form'], function(){
        var layer = layui.layer, table = layui.table, $ = layui.jquery, form = layui.form;

        //表格数据
        table.render({
            elem: '#list'
            ,height: 600
            ,url: 'page' //数据接口
            ,page: true //开启分页
            ,cols: [[ //表头
                {type:'checkbox'},
                {field: 'id', title: 'ID', width:80, sort: true}
                ,{field: 'name', title: '规格名称'}
                ,{field: 'type', title: '所属模型'}
                ,{field: 'item', title: '规格项'}
                ,{fixed: 'right', title: '操作', width:150, align:'center', toolbar: '#bar'}
            ]]
        })

        //监听工具条
        table.on('tool(list)', function(obj){
            var data = obj.data;
            if(obj.event === 'edit'){
                layer.open({
                    type: 2,
                    title:'编辑商品规格',
                    area: ['900px', '700px'],
                    maxmin: true,
                    fixed: false, //不固定
                    content: '<?php echo url("edit"); ?>?id='+obj.data.id,
                    end: function () {
                        location.reload();
                    }
                });
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
            //iframe层-父子操作
            var index = layer.open({
                type: 2,
                title:'添加商品规格',
                area: ['600px', '400px'],
                maxmin: true,
                fixed: false, //不固定
                content: '<?php echo url("add"); ?>',
                end: function () {
                    location.reload();
                }
            });
            layer.full(index);
        })
        //批量删除
        $("#del-all").on('click',function () {
            var checkStatus=table.checkStatus('list');
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
                        if (data.code){
                            table.reload('list',{});
                            layer.msg('已删除');
                        }else{
                            layer.msg('删除失败');
                        }
                    });

                }
            });
        })
        // 监听表单提交事件
        form.on('submit(search)', function(data) {
            table.reload('list', {
                where:data.field
            });
            return false; // 阻止表单提交
        })
    });
</script>

</html>

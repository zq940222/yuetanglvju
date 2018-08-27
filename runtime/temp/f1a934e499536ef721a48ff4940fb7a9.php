<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:73:"F:\programs\yuetanglvju\public/../application/admin\view\admin\lists.html";i:1533712609;s:61:"F:\programs\yuetanglvju\application\admin\view\base\base.html";i:1530860248;}*/ ?>
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
            <input type="text" name="keywords" class="layui-input" placeholder="请输入搜索内容">
        </div>

        <button lay-submit class="layui-btn" lay-filter="search"> 查询 </button>
    </form>
</blockquote>
<!--<button class="layui-btn layui-btn-danger" id="del-all">-->
    <!--批量删除-->
<!--</button>-->
<button class="layui-btn" id="add">
    <i class="layui-icon">&#xe608;</i> 添加管理员
</button>

<table id="list" lay-filter="list"></table>


</body>
<script src="/static/layui.js"></script>


<script type="text/html" id="status">
    <input type="checkbox" name="status" value="{{d.id}}" title="已启用" lay-filter="status" {{ d.status == 1 ? 'checked' : '' }}>
</script>
<script type="text/html" id="bar">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>

<script>

    layui.use(['layer', 'table', 'laydate', 'form'], function(){
        var layer = layui.layer, table = layui.table, $ = layui.jquery, laydate = layui.laydate, form = layui.form;

        //表格数据
        table.render({
            elem: '#list'
            ,height: 600
            ,url: 'page' //数据接口
            ,page: true //开启分页
            ,cols: [[ //表头
                {type:'checkbox'},
                {field: 'id', title: 'ID', width:80, sort: true}
                ,{field: 'username', title: '用户名'}
                ,{field: 'role', title: '所属角色'}
                ,{field: 'status', title: '状态',  templet:'#status'}
                ,{field: 'create_time', title: '创建时间'}
                ,{fixed: 'right', title: '操作', width:150, align:'center', toolbar: '#bar'}
            ]]
        })

        //监听是否推荐操作
        form.on('checkbox(status)', function(obj){
            $.post('<?php echo url("change"); ?>', {id:this.value, field:'status', model:'admin'}, function(data) {});
            layer.tips(this.value + ' ' + this.name + '：'+ obj.elem.checked, obj.othis);
        });
        //监听工具条
        table.on('tool(list)', function(obj){
            var data = obj.data;
            if(obj.event === 'edit'){
                layer.open({
                    type: 2,
                    title:'编辑管理员',
                    area: ['600px', '400px'],
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
                            var code = data.code.toString();
                            var startChar = code.charAt(0);
                            if (startChar == '2')
                            {
                                obj.del();
                                layer.close(index);
                                layer.alert(data.msg, {icon: 6});
                            }else{
                                layer.alert(data.msg, {icon: 7});
                            }

                        });

                    }
                });
            }
        });
        //添加
        $("#add").on('click',function () {
            //iframe层-父子操作
            layer.open({
                type: 2,
                title:'添加管理员',
                area: ['600px', '400px'],
                maxmin: true,
                fixed: false, //不固定
                content: '<?php echo url("add"); ?>',
                end: function () {
                    location.reload();
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

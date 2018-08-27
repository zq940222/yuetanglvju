<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:74:"F:\programs\yuetanglvju\public/../application/admin\view\coupon\lists.html";i:1533890438;s:61:"F:\programs\yuetanglvju\application\admin\view\base\base.html";i:1530860248;}*/ ?>
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
                <input type="text" placeholder="请输入名称" name="name" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-inline">
            <input type="text" class="layui-input" id="date" name="date" placeholder="请选择日期" readonly>
        </div>

        <button lay-submit class="layui-btn" lay-filter="search">查询</button>
    </form>
</blockquote>
<button class="layui-btn layui-btn-danger" id="del-all">
    批量删除
</button>
<button class="layui-btn" id="add">
    <i class="layui-icon">&#xe608;</i> 发送优惠券
</button>
<table id="list" lay-filter="list"></table>

</body>
<script src="/static/layui.js"></script>

<script type="text/html" id="bar">
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>

<script type="text/html" id="status">
    <input type="checkbox" name="status" value="{{d.id}}" title="正常" lay-filter="status" {{ d.status == 1 ? 'checked' : '' }}>
</script>

<script>
    layui.use(['layer', 'table', 'laydate', 'form'], function() {
        var layer = layui.layer, table = layui.table, $ = layui.jquery, laydate = layui.laydate, form = layui.form;

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
                        var code = data.code.toString();
                        var startChar = code.charAt(0);
                        if (startChar == '2')
                        {
                            table.reload('list',{});
                            layer.alert(data.msg, {icon: 6});
                        }else{
                            layer.alert(data.msg, {icon: 7});
                        }
                    });

                }
            });
        })
        //监听是否推荐操作
        form.on('checkbox(status)', function(obj){
            $.post('<?php echo url("change"); ?>', {id:this.value, field:'status', model:'coupon'}, function(data) {});
            layer.tips(this.value + ' ' + this.name + '：'+ obj.elem.checked, obj.othis);
        });
        //监听工具条
        table.on('tool(list)', function(obj){
            var data = obj.data;
            if(obj.event === 'edit'){
                layer.open({
                    type: 2,
                    title:'编辑优惠券',
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
                title:'发放优惠券',
                area: ['600px', '400px'],
                maxmin: true,
                fixed: false, //不固定
                content: '<?php echo url("add"); ?>',
                end: function () {
                    location.reload();
                }
            });
        })
        //表格数据
        table.render({
            elem: '#list'
            ,height: 600
            ,url: 'page' //数据接口
            ,page: true //开启分页
            ,cols: [[ //表头
                {type:'checkbox'},
                {field: 'id', title: 'ID', width:80, sort: true}
                ,{field: 'name', title: '名称'}
                ,{field: 'stime', title: '起始时间'}
                ,{field: 'etime', title: '结束时间'}
                ,{field: 'day', title: '过期天数'}
                ,{field: 'status', title: '状态', templet:'#status'}
                ,{field: 'create_time', title: '创建时间'}
                ,{fixed: 'right', title: '操作', align:'center', toolbar: '#bar'}
            ]]
        })

        laydate.render({
            elem: '#date' //指定元素
            ,type: 'date'
            ,trigger: 'click'
            ,lang: 'cn'
            //,lang: 'en'
            ,range: true //开启日期范围，默认使用“_”分割
            ,done: function(value, date, endDate){
                console.log(value, date, endDate);
            }
            ,change: function(value, date, endDate){
                console.log(value, date, endDate);
            }
        });

        // 监听表单提交事件
        form.on('submit(search)', function(data) {
            table.reload('list', {
                where:data.field
            });
            return false; // 阻止表单提交
        })
    })
</script>

</html>

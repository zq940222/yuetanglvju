<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:72:"F:\programs\yuetanglvju\public/../application/admin\view\push\lists.html";i:1533365040;s:61:"F:\programs\yuetanglvju\application\admin\view\base\base.html";i:1530860248;}*/ ?>
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
                <input type="text" placeholder="请输入标题" name="title" autocomplete="off" class="layui-input">
            </div>
        </div>

        <div class="layui-input-inline">
            <select name="status">
                <option value="close"> 推送状态 </option>
                <option value="0"> 推送中 </option>
                <option value="1"> 成功 </option>
                <option value="2"> 失败 </option>
            </select>
        </div>

        <div class="layui-inline">
            <input type="text" class="layui-input" id="date" name="date" placeholder="请选择日期" readonly>
        </div>

        <button lay-submit class="layui-btn" lay-filter="search">查询</button>
    </form>
</blockquote>

<button class="layui-btn" id="add">
    <i class="layui-icon">&#xe608;</i> 发布推送
</button>
<table id="list" lay-filter="list"></table>

</body>
<script src="/static/layui.js"></script>

<script type="text/html" id="status">
    {{# if (d.status == 0){ }}
    推送中
    {{# }else if (d.status == 1){ }}
    成功
    {{# }else if (d.status == 2){ }}
    失败
    {{# } }}
</script>
<script>
    layui.use(['layer', 'table', 'laydate', 'form'], function() {
        var layer = layui.layer, table = layui.table, $ = layui.jquery, laydate = layui.laydate, form = layui.form;

        //添加
        $("#add").on('click',function () {
            //iframe层-父子操作
            layer.open({
                type: 2,
                title:'发布推送',
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
                ,{field: 'title', title: '标题'}
                ,{field: 'content', title: '内容'}
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

<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:74:"F:\programs\yuetanglvju\public/../application/admin\view\expend\lists.html";i:1532508669;s:61:"F:\programs\yuetanglvju\application\admin\view\base\base.html";i:1530860248;}*/ ?>
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
            <select name="admin_id" id="admin" class="layui-input" lay-filter="admin" lay-search>
                <option value="0">-请选择管理员-</option>
                <?php if(is_array($admin) || $admin instanceof \think\Collection || $admin instanceof \think\Paginator): $i = 0; $__LIST__ = $admin;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $item['id']; ?>"><?php echo $item['username']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>

        <div class="layui-inline">
            <input type="text" class="layui-input" id="date" name="date" placeholder="请选择申请入驻日期" readonly>
        </div>
        <button lay-submit class="layui-btn" lay-filter="search"> 查询 </button>
    </form>
</blockquote>

<table id="list" lay-filter="list"></table>


</body>
<script src="/static/layui.js"></script>


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
                ,{field: 'money', title: '支出金额'}
                ,{field: 'type', title: '支出类型'}
                ,{field: 'user_id', title: '关联用户ID'}
                ,{field: 'hotel_id', title: '关联酒店ID'}
                ,{field: 'order_id', title: '关联订单ID'}
                ,{field: 'admin_name', title: '操作人'}
                ,{field: 'create_time', title: '日志时间', sort: true}
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
    });
</script>

</html>

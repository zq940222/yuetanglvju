<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:85:"F:\programs\yuetanglvju\public/../application/admin\view\merchants_deposit\lists.html";i:1533192610;s:61:"F:\programs\yuetanglvju\application\admin\view\base\base.html";i:1530860248;}*/ ?>
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
            <select name="hotel_id" id="hotel" class="layui-input" lay-filter="hotel" lay-search>
                <option value="0">-请选择酒店-</option>
                <?php if(is_array($hotel) || $hotel instanceof \think\Collection || $hotel instanceof \think\Paginator): $i = 0; $__LIST__ = $hotel;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>

        <div class="layui-input-inline">
            <select name="status" class="layui-input" lay-filter="status">
                <option value="0">-状态-</option>
                <option value="2">待转账</option>
                <option value="3">已转账</option>
            </select>
        </div>
        <div class="layui-input-inline">
            <input type="text" name="bank_account" class="layui-input" placeholder="请输入银行账户">
        </div>
        <div class="layui-input-inline">
            <input type="text" name="bank_account_num" class="layui-input" placeholder="请输入银行账号">
        </div>
        <div class="layui-inline">
            <input type="text" class="layui-input" id="date" name="date" placeholder="请选择申请入驻日期" readonly>
        </div>
        <button lay-submit class="layui-btn" lay-filter="search"> 查询 </button>
    </form>
</blockquote>
<!--<button class="layui-btn layui-btn-danger" id="excel">-->
    <!--导出Excel-->
<!--</button>-->

<table id="list" lay-filter="list"></table>


</body>
<script src="/static/layui.js"></script>

<script type="text/html" id="status">
    {{#  if(d.status == 2){ }}
    待转账
    {{#  } else { }}
    已转账
    {{#  } }}
</script>
<script type="text/html" id="bar">
    {{#  if(d.status == 2){ }}
    <a class="layui-btn layui-btn-xs" lay-event="edit">转账</a>
    {{#  } else { }}
    {{#  } }}
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
                ,{field: 'hotel_id', title: '酒店ID'}
                ,{field: 'hotel_name', title: '酒店名称'}
                ,{field: 'money', title: '申请金额'}
                ,{field: 'bank_name', title: '银行名称'}
                ,{field: 'bank_account_num', title: '银行账号'}
                ,{field: 'bank_account', title: '银行账户'}
                ,{field: 'status', title: '状态', templet:'#status'}
                ,{field: 'create_time', title: '申请时间', sort: true}
                ,{field: 'remark', title: '备注'}
                ,{fixed: 'right', title: '操作', width:80, align:'center', toolbar: '#bar'}
            ]]
        })

        //监听工具条
        table.on('tool(list)', function(obj){
            if(obj.event === 'edit'){
                layer.msg('你确定已转账么？', {
                    time: 0 //不自动关闭
                    ,btn: ['确定', '取消']
                    ,yes: function(index){
                        $.post('<?php echo url("edit"); ?>', {id:obj.data.id}, function(data) {
                            if (data.code){
                                layer.msg('已完成');
                                table.reload('list');
                            }else{
                                layer.msg('失败');
                            }
                        });

                    }
                });
            }
        });

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

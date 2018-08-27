<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:73:"F:\programs\yuetanglvju\public/../application/admin\view\hotel\lists.html";i:1533713251;s:61:"F:\programs\yuetanglvju\application\admin\view\base\base.html";i:1530860248;}*/ ?>
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
            <select name="province" id="province" class="layui-input" lay-filter="province" lay-search>
                <option value="0">-请选择省-</option>
                <?php if(is_array($region) || $region instanceof \think\Collection || $region instanceof \think\Paginator): $i = 0; $__LIST__ = $region;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
        <div class="layui-input-inline">
            <select name="city" id="city" class="layui-input" lay-filter="city" lay-search>
                <option value="0">-请选择市-</option>
            </select>
        </div>
        <div class="layui-input-inline">
            <select name="district" id="district" class="layui-input" lay-search>
                <option value="0">-请选择区-</option>
            </select>
        </div>

        <div class="layui-input-inline">
            <input type="text" name="name" class="layui-input" placeholder="请输入酒店名称">
        </div>
        <div class="layui-inline">
            <input type="text" class="layui-input" id="date" name="date" placeholder="请选择申请入驻日期" readonly>
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

<table id="hotel-list" lay-filter="hotel-list"></table>


</body>
<script src="/static/layui.js"></script>


<script type="text/html" id="is_recommend">
    <input type="checkbox" name="is_recommend" value="{{d.id}}" title="推荐" lay-filter="is_recommend" {{ d.is_recommend == 1 ? 'checked' : '' }}>
</script>
<script type="text/html" id="status">
    {{# if (d.status == 1){ }}
    正常
    {{# }else{ }}
    关闭
    {{# } }}
</script>
<script type="text/html" id="bar">
    <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">审核</a>
    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
</script>

<script>

    layui.use(['layer', 'table', 'laydate', 'form'], function(){
        var layer = layui.layer, table = layui.table, $ = layui.jquery, laydate = layui.laydate, form = layui.form;

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

        //表格数据
        table.render({
            elem: '#hotel-list'
            ,height: 600
            ,url: 'page' //数据接口
            ,page: true //开启分页
            ,cols: [[ //表头
                {type:'checkbox'},
                {field: 'id', title: 'ID', width:80, sort: true}
                ,{field: 'name', title: '酒店名'}
                ,{field: 'phone', title: '电话'}
                ,{field: 'min_price', title: '起价', sort: true}
                ,{field: 'remark', title: '备注'}
                ,{field: 'avg_score', title: '评分', sort: true}
                ,{field: 'comment_num', title: '评论条数', sort: true}
                ,{field: 'is_recommend', title: '是否推荐', templet:'#is_recommend'}
                ,{field: 'group_num', title: '拼团人数'}
                ,{field: 'status', title: '状态', templet:'#status'}
                ,{field: 'create_time', title: '创建时间'}
                ,{fixed: 'right', title: '操作', width:178, align:'center', toolbar: '#bar'}
            ]]
        })
        //监听是否推荐操作
        form.on('checkbox(is_recommend)', function(obj){
            $.post('<?php echo url("change"); ?>', {id:this.value, field:'is_recommend', model:'hotel'}, function(data) {});
            layer.tips(this.value + ' ' + this.name + '：'+ obj.elem.checked, obj.othis);
        });
        //监听是否推荐操作
        form.on('checkbox(status)', function(obj){
            $.post('<?php echo url("change"); ?>', {id:this.value, field:'status', model:'hotel'}, function(data) {});
            layer.tips(this.value + ' ' + this.name + '：'+ obj.elem.checked, obj.othis);
        });
        //监听工具条
        table.on('tool(hotel-list)', function(obj){
            var data = obj.data;
            if (obj.event === 'detail'){
                //详情
                var index = layer.open({
                    type: 2,
                    area: ['900px', '700px'],
                    fixed: false, //不固定
                    maxmin: true,
                    content: '<?php echo url("detail"); ?>?id='+obj.data.id
                });
                layer.full(index);
            }else if(obj.event === 'edit'){
                layer.open({
                    type: 2,
                    title:'编辑酒店',
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
                title:'添加酒店',
                area: ['600px', '400px'],
                maxmin: true,
                fixed: false, //不固定
                content: '<?php echo url("add"); ?>',
                end: function () {
                    location.reload();
                }
            });
        })
        //批量删除
        $("#del-all").on('click',function () {
            var checkStatus=table.checkStatus('hotel-list');
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
                            table.reload('hotel-list',{});
                            layer.msg('已删除');
                        }else{
                            layer.msg('删除失败');
                        }
                    });

                }
            });
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
            table.reload('hotel-list', {
                where:data.field
            });
            return false; // 阻止表单提交
        })
    });
</script>

</html>

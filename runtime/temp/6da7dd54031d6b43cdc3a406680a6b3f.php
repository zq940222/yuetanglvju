<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:78:"F:\programs\yuetanglvju\public/../application/admin\view\statistics\hotel.html";i:1534152265;s:61:"F:\programs\yuetanglvju\application\admin\view\base\base.html";i:1530860248;}*/ ?>
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
        <div class="layui-inline">
            <input type="text" class="layui-input" id="date" name="date" placeholder="请选择日期" readonly>
        </div>
        <button lay-submit class="layui-btn" lay-filter="search"> 查询 </button>
    </form>
</blockquote>

<fieldset class="layui-elem-field">
    <legend>销售概况</legend>
    <div class="layui-field-box">
        <h5>今日销售总额：￥<empty name="today.today_amount">0<else/><?php echo $today['today_amount']; ?></empty>|人均客单价：￥<?php echo $today['sign']; ?>|今日订单数：<?php echo $today['today_order']; ?></h5>
    </div>
</fieldset>
<div>
    <!-- 为 ECharts 准备一个具备大小（宽高）的 DOM -->
    <div id="chart" style="width: 1400px; height: 600px;"></div>
</div>


</body>
<script src="/static/layui.js"></script>

<script src="/static/echarts.common.min.js"></script>
<script type="text/javascript">
    layui.use(['layer', 'table', 'laydate', 'form'], function(){
        var layer = layui.layer, table = layui.table, $ = layui.jquery, laydate = layui.laydate, form = layui.form;
        //图表
        charts({});
        function charts (date){
            $.ajax({
                url: '<?php echo url("hotelChart"); ?>',
                type: 'post',
                data: date,
                success: function (data) {
                    var res = data;
                    var myChart = echarts.init(document.getElementById('chart'));
                    option = {
                        tooltip: {
                            trigger: 'axis'
                        },
                        toolbox: {
                            show: true,
                            feature: {
                                mark: {show: true},
                                dataView: {show: true, readOnly: false},
                                magicType: {show: true, type: ['line', 'bar']},
                                restore: {show: true},
                                saveAsImage: {show: true}
                            }
                        },
                        calculable: true,
                        legend: {
                            data: ['交易金额', '订单数', '客单价']
                        },
                        xAxis: [
                            {
                                type: 'category',
                                data: res.time
                            }
                        ],
                        yAxis: [
                            {
                                type: 'value',
                                name: '金额',
                                axisLabel: {
                                    formatter: '{value} ￥'
                                }
                            },
                            {
                                type: 'value',
                                name: '客单价',
                                axisLabel: {
                                    formatter: '{value} ￥'
                                }
                            }
                        ],
                        series: [
                            {
                                name: '交易金额',
                                type: 'bar',
                                data: res.amount
                            },
                            {
                                name: '订单数',
                                type: 'bar',
                                data: res.order
                            },
                            {
                                name: '客单价',
                                type: 'line',
                                yAxisIndex: 1,
                                data: res.sign
                            }
                        ]
                    };
                    myChart.setOption(option);
                }

            });
        }
        laydate.render({
            elem: '#date' //指定元素
            ,type: 'date'
            ,trigger: 'click'
            ,lang: 'cn'
            //,lang: 'en'
            ,range: true //开启日期范围，默认使用“_”分割
            ,done: function(value, date, endDate){
            }
            ,change: function(value, date, endDate){
            }
        });
        // 监听表单提交事件
        form.on('submit(search)', function(data) {
            charts(data.field);
            return false; // 阻止表单提交
        })
    });
</script>

</html>

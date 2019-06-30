@extends('layouts.formpage')
@section('formrange')
    @include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'pageTitle'=>$taskMode=='pk'?'创建PK任务':'创建约定任务', 'right'=>'home' ])
    <style type="text/css">body{background-color:#f5f5f9;}</style>
    <form id="myForm" method="post" action="/{{$taskMode}}taskwizzard/savebase">
    <input name="_token" type="hidden" value="" />
    <div id="taskContainer" style="width:100%">
        <div class="ym_cm_card ym_pub_card">
            <div class="ym_cm_cardheader no_border">
               <input name="title" type="text" class="row_input" placeholder="请填写任务名称" value="" />
            </div>
            <div class="errtext">请填写任务名称。</div>
        </div>
        <div class="ym_cm_card ym_pub_card">
            @if($taskMode == 'pk')
            <div class="ym_cm_cardheader">
                <label>评审日期</label> 
                <span><input name="delivery_date" type="date" class="right_input"  placeholder="未设置" value="{{date('Y-m-d', strtotime('+2 week'))}}" /></span>
            </div>
            <div class="errtext">请填写日期, 并且日期不要早于今天。</div>
            <span class="description">
                任务到达评审日期后，会自动进入评审状态，此时发起人无法再添加参与人，参与人也无法再交付任务成果。
            </span>
            @elseif($taskMode == 'appoint')
            <div class="ym_cm_cardheader">
                <label>交付日期</label> 
                <span><input name="delivery_date" type="date" class="right_input"  placeholder="未设置" value="{{date('Y-m-d', strtotime('+2 week'))}}" /></span>
            </div>
            <div class="errtext">请填写日期, 并且日期不要早于今天。</div>
            <span class="description">
                请填写您期望任务交付的日期，任务到达交付日期不会自动关闭,但是任务到达交付日期时仍未开始则会自动关闭。
            </span>
            @endif
        </div>
        <div class="ym_cm_card ym_pub_card">
            <div class="ym_cm_cardheader">
                <label>任务酬金</label>
                <span><input name="amount" type="number" class="right_input"  placeholder="未设置" value="1000" />&nbsp;金币</span>
            </div>
            <div class="errtext">任务酬金需要大于0。</div>
            @if($taskMode == 'pk')
            <span class="description">
                PK任务将会加收部分金币作为<b>执行成本</b>，目前任务的执行成本为<b>酬金的5%</b>,并且<b>不少于2000金币</b>。
                任务的酬金和执行成本会在<b>发布任务</b>的时候从你的账户中扣除,任务一旦发布，执行成本将不会退回。
            </span>
            @elseif($taskMode == 'appoint')
            <span class="description">
                约定任务的酬金会在您发布任务的时候从您的账户中扣除并由有妹社区保管，酬金会在您确认任务完成的时候支付给任务参与者。
            </span>
            @endif
        </div>
        @if($taskMode == 'pk')
        <div id ="assign_solution" class="ym_cm_card ym_pub_card">
            <input type="hidden" name="assign_solution" value="1" />
            <div class="ym_cm_cardheader">
                <label>分配方案</label>
                <span> 选择方案 </span>
            </div>
            <div id="assign_solution_show" class="ym_select_item">
                <label class="ym_select_item_title"></label>
                <span class="ym_select_item_desc"></span>
            </div>
        </div>
        @elseif($taskMode == 'appoint' & false)
        <div id="guarantee" class="ym_cm_card ym_pub_card">
            <div class="ym_cm_cardheader ym_pub_card">
                <label>第三方评审</label>
                <span id="guarantee_show"> 选择第三方评审</span>
            </div>
            <span class="description">
                第三方评审,描述
            </span>
        </div>
        @endif
    </div>

    <div class="ym_footerbar ym_pub_control">
        <button class="btn" type="button" onclick="submitForm()" >下一步，填写任务描述</button>
    </div>
    <script type="text/javascript" src="/js/selectcontrol.js"></script>
    <script type="text/javascript">
    function submitForm() {
        if(validate()){
            $('input[name=_token]').val($.ymFunc.getToken());
            $('#myForm').submit();
        }
    }
    function validate(){
        var isVidated = true;
        var $name = $('input[name="title"]');
        if($name.val() == ''){
            isVidated = false;
            $name.parent().parent().addClass('error');
        }
        else{$name.parent().parent().removeClass('error');}

        var $amount = $('input[name="amount"]');
        if($amount.val() == ''|| parseInt($amount.val())<=0){
            isVidated = false;
            $amount.parent().parent().parent().addClass('error');
        }
        else{$amount.parent().parent().parent().removeClass('error');}
        var $date = $('input[name="delivery_date"]');
        if($date.val() == ''){
            isVidated = false;
            $date.parent().parent().parent().addClass('error');
        }
        else{
            var d = $date.val().split('-');
            var v = new Date();
            v.setFullYear(d[0], d[1], d[2]);
            var today = new Date();
            if(v< today){
                isVidated = false;
                $date.parent().parent().parent().addClass('error');
            }else{
                $date.parent().parent().parent().removeClass('error');
            }
        }
        return isVidated;
    }
    @if($taskMode == 'pk')
    $('#assign_solution>.ym_cm_cardheader').ymSelectControl({
        title:'选择分配方案',
        columns:1,
        style:1,
        dataList:[
            {'value':1, 'text':'首席独得奖金分配方案', 'desc':'根据有妹用户对于作品的评价进行评审。<br /> 最佳成绩的交付作品作者获得<b>80%</b>的任务奖金（首席），第二名获得<b>15%</b>，第三名获得<b>5%。</b><br /> 分配的奖金四舍五入取整数，参评人数如果少于三人，剩余的未分配任务酬金将退还给任务发起方。'},
            {'value':2, 'text':'顺序均摊分配方案',     'desc':'根据有妹用户对于作品的评价进行评审。<br /> 最佳成绩的交付作品作者获得<b>50%</b>的任务奖金，第二名获得<b>30%</b>，第三名以及后的<b>所有的参与者平均分配任务20%。</b><br /> 分配的奖金四舍五入取整数。所有奖金剩余将会被系统分配给参与者。'}
        ]
    }) .bind('getValue',function(){
        return $('input[name=assign_solution]').val();
    }) .bind('setValue',function(value){
        var valueitem =$('input[name=assign_solution]');
        valueitem.val(value.value);
        var text = (value.text==''?'(未设置)':value.text);
        var show = $('#assign_solution_show');
        show.find('label').html(value.text);
        show.find('span').html(value.desc);
    }).select('1');
    @elseif($taskMode == 'appoint')
    $('#guarantee').ymSelectControl({
        title:'选择分配方案',
        columns:1,
        dataList:{
            '1':'不需要第三方评估(默认)',
            '2':'需要第三方评估'
        }
    }) .bind('getValue',function(){
        return $('input[name=guarantee]').val();
    }) .bind('setValue',function(value){
        var valueitem =$('input[name=guarantee]');
        valueitem.val(value.value);
        var text = (value.text==''?'(未设置)':value.text);
        $('#guarantee_show').text(text);
    });
    @endif
    </script>
@stop

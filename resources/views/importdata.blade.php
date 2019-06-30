@extends('layouts.block')
@section('serverload')
<style>
.thumb{ width:100%; }
.thumb>img{ width:100%; }
.testDiv{
    width:285px;
    border:solid 1px #e2e2e2;
    background-color:#f0f0f0;
    margin:15px;
    margin-bottom:0;
    padding:15px;
}
.testDiv button{
    width:100%;
}
</style>
<div class="testDiv">
    <form method="post" action="/test/importData/ip" enctype="multipart/form-data">
        <label>导入ip数据</label>
        <br />
        分类：<select name ="type">
                <option value="cartoon">动漫</option>
                <option value="story">小说</option>
                <option value="game" selected>游戏</option>
            </select>
        <br />
        <br />
        <input type="file" name ="dataFile" id="dataFile"/>
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <br />
        <button type="submit" style="width:100px;height:50px">提交</button>
    </form>
</div>
<div class="testDiv">
    <form method="post" action="/test/importData/tags" enctype="multipart/form-data">
        <label>导入Tags基础数据</label>
        <br />
        <input type="file" name ="dataFile" id="dataFile"/>
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <br />
        <button type="submit" style="width:100px;height:50px">提交</button>
    </form>
</div>
<div class="testDiv">
    <form method="post" action="/test/importData/iptag" enctype="multipart/form-data">
        <label>导入IpTags</label>
        <br />
        <input type="file" name ="dataFile" id="dataFile"/>
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <br />
        <button type="submit" style="width:100px;height:50px">提交</button>
    </form>
</div>
@stop
@section('runScript')
<script type="text/javascript"> </script>
@stop

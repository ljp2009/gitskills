@extends('admins.layouts.admin')
@section('detailcontent')
<!-- content start -->
<div class="admin-content">
    <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">首页</strong> / <small>更新作品属性</small></div>
    </div>
    <div class="am-tabs am-margin" data-am-tabs>
        <ul class="am-tabs-nav am-nav am-nav-tabs">
            <li class="am-active"><a href="#tab1">更新属性</a></li>
        </ul>
    </div>
    <div class="am-tabs-bd" style="min-height:300px;">
      <form id='form1' method="post" enctype="multipart/form-data">
          <input type="hidden" name="_token" value="{{csrf_token()}}">
          <div class="am-tab-panel am-fade am-in am-active" id="tab1">
            <div class="am-g am-margin-top">
              <div class="am-u-sm-4 am-u-md-2 am-text-right">修改属性</div>
              <div class="am-u-sm-8 am-u-md-10">
                <select name="attrName" data-am-selected="{btnSize: 'sm'}">
                  <option value="cartoon_10002" {{(isset($attrName)&&$attrName=='10002')?'selected':''}}>作品状态(动漫)</option>
                  <option value="story_10009" {{(isset($attrName)&&$attrName=='10009')?'selected':''}}>作品状态(小说)</option>
                  <option value="cartoon_10004" {{(isset($attrName)&&$attrName=='10004')?'selected':''}}>集数(动漫)</option>
                  <option value="story_10011" {{(isset($attrName)&&$attrName=='10011')?'selected':''}}>字数(小说)</option>
                  <option value="ip_type" {{(isset($attrName)&&$attrName=='10011')?'selected':''}}>作品分类</option>
                </select>
              </div>
            </div>
            <div class="am-g am-margin-top">
              <div class="am-u-sm-4 am-u-md-2 am-text-right">匹配字段</div>
              <div class="am-u-sm-8 am-u-md-10">
                <select name="keyName" data-am-selected="{btnSize: 'sm'}">
                  <option value="name">作品名称</option>
                  <option value="id">作品编号</option>
                </select>
              </div>
            </div>
            <div class="am-g am-margin-top">
              <div class="am-u-sm-4 am-u-md-2 am-text-right">空数据处理</div>
              <div class="am-u-sm-8 am-u-md-10">
                <div class="am-btn-group" data-am-button>
                  <label class="am-btn am-btn-default am-btn-xs am-active">
                    <input type="radio" name="emptyValue" value='none' id="option1" checked='checked'>不处理
                  </label>
                  <label class="am-btn am-btn-default am-btn-xs">
                    <input type="radio" name="emptyValue" value='default' id="option2">删除现有值
                  </label>
                </div>
              </div>
            </div>
            <div class="am-g am-margin-top">
              <div class="am-u-sm-4 am-u-md-2 am-text-right">数据文件</div>
              <div class="am-u-sm-8 am-u-md-10">
                <button type="button" class="am-btn am-btn-primary am-btn-xs" onclick="$('#dataFile').click()">选择文件</button>
                <input type="file" id='dataFile' name='dataFile' style='display:none'  onchange="fileChange()" />
                <label id='dataName'></label>
              </div>
            </div>
          </div>
      </form>
    </div>
  <div class="am-margin">
    <button type="button" class="am-btn am-btn-primary am-btn-xs" onclick="subUpdate()">
        提交保存
    </button>
  </div>
    @if(isset($items) && count($items)>0)
    <div class="am-g" style="min-height:35rem">
        <div class="am-u-sm-12">
            <h3>导入失败的数据</h3>
        </div>
        <div class="am-u-sm-12">
            <table class="am-table am-table-bd am-table-striped admin-content-table">
              <thead>
              <tr>
                <th>编号</th>
                <th>作品名称</th>
                <th>状态</th>
              </tr>
              </thead>
              <tbody>
              @foreach($items as $item)
                  <tr>
                    <td>{{$item['number']}}</td>
                    <td>{{$item['name']}}</td>
                    <td>{{$item['value']}}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@stop
@section('jscontent')
<script type="text/javascript" charset="utf-8">
    function fileChange(){
        var fileControl = $("#dataFile");
        var dataNameControl = $('#dataName');
        dataNameControl.html(fileControl.val());
    }
    function subUpdate(){
        $('#form1').submit();
    }
</script>
@stop

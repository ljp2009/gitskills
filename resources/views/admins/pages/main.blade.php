@extends('admins.layouts.admin')
@section('detailcontent')
<!-- content start -->
 <!-- content start -->
  <div class="admin-content">
    <div class="admin-content-body">
      <div class="am-cf am-padding">
        <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">首页</strong> / <small>一些常用模块</small></div>
      </div>



      <div class="am-g">
        <div class="am-u-md-6">
          <div class="am-panel am-panel-default">
            <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-1'}">今日新增内容<span class="am-icon-chevron-down am-fr" ></span></div>
            <div class="am-in" id="collapse-panel-1">
              <ul class="am-avg-sm-1 am-avg-md-5 am-padding am-text-center admin-content-list ">
                <li><a href="#" class="am-text-secondary"><span class="am-icon-btn am-icon-user-md"></span><br/>新增用户<br/>2300</a></li>
                <li><a href="#" class="am-text-secondary"><span class="am-icon-btn am-icon-file-text"></span><br/>新增任务<br/>3000</a></li>
                <li><a href="#" class="am-text-secondary"><span class="am-icon-btn am-icon-file-text"></span><br/>新增作品<br/>3000</a></li>
                <li><a href="#" class="am-text-secondary"><span class="am-icon-btn am-icon-file-text"></span><br/>新增帖子<br/>3000</a></li>
                <li><a href="#" class="am-text-secondary"><span class="am-icon-btn am-icon-file-text"></span><br/>新增评论<br/>3000</a></li>
              </ul>
            </div>
          </div>
          <div class="am-panel am-panel-default">
            <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-2'}">举报内容<span class="am-icon-chevron-down am-fr" ></span></div>
            <div id="collapse-panel-2" class="am-in">
              <table class="am-table am-table-bd am-table-bdrs am-table-striped am-table-hover">
                <tbody>
                <tr>
                  <th class="am-text-center">#</th>
                  <th>内容</th>
                  <th>分类</th>
                  <th>作者</th>
                  <th>举报次数</th>
                  <th>操作</th>
                </tr>
                @for($i=0;$i<3;$i++)
                <tr>
                  <td class="am-text-center">{{$i}}</td>
                  <td>银魂同人{{$i}}</td>
                  <td>用户作品</td>
                  <td>xxx</td>
                  <td>{{15-$i}}</td>
                  <td>
                    <div class="am-dropdown" data-am-dropdown>
                      <button class="am-btn am-btn-default am-btn-xs am-dropdown-toggle" data-am-dropdown-toggle><span class="am-icon-cog"></span> <span class="am-icon-caret-down"></span></button>
                      <ul class="am-dropdown-content">
                        <li><a href="#">1. 通过审核</a></li>
                        <li><a href="#">2. 删除 </a></li>
                      </ul>
                    </div>
                  </td>
                </tr>
                @endfor
                </tbody>
              </table>
              <ul class="am-pagination am-fr admin-content-pagination">
                <li class="am-disabled"><a href="#">&laquo;</a></li>
                <li class="am-active"><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">5</a></li>
                <li><a href="#">&raquo;</a></li>
              </ul>
            </div>
          </div>
        </div>

        <div class="am-u-md-6">
          <div class="am-panel am-panel-default">
            <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-3'}">昨日数据统计<span class="am-icon-chevron-down am-fr" ></span></div>
            <div class="am-in" id="collapse-panel-3">
              <ul class="am-avg-sm-1 am-avg-md-4 am-padding am-text-center admin-content-list ">
                <li><a href="#" class="am-text-secondary"><span class="am-icon-btn am-icon-file-text"></span><br/>新增用户量<br/>3000</a></li>
                <li><a href="#" class="am-text-secondary"><span class="am-icon-btn am-icon-file-text"></span><br/>用户会话<br/>3000</a></li>
                <li><a href="#" class="am-text-secondary"><span class="am-icon-btn am-icon-file-text"></span><br/>平均会话次数<br/>3000</a></li>
                <li><a href="#" class="am-text-secondary"><span class="am-icon-btn am-icon-file-text"></span><br/>平均会话时长<br/>3000</a></li>
              </ul>
            </div>
          </div>
          <div class="am-panel am-panel-default">
            <div class="am-panel-hd am-cf" data-am-collapse="{target: '#collapse-panel-4'}">待处理的IP<span class="am-icon-chevron-down am-fr" ></span></div>
            <div id="collapse-panel-4" class="am-in">
              <table class="am-table am-table-bd am-table-bdrs am-table-striped am-table-hover">
                <tbody>
                <tr>
                  <th class="am-text-center">#</th>
                  <th>内容</th>
                  <th>分类</th>
                  <th>作者</th>
                  <th>创建时间</th>
                  <th>操作</th>
                </tr>
                @for($i=0;$i<3;$i++)
                <tr>
                  <td class="am-text-center">{{$i}}</td>
                  <td>精灵王座{{$i}}</td>
                  <td>cartoon</td>
                  <td>王小二</td>
                  <td>{{date('Y-m-d h:i:s')}}</td>
                  <td>
                    <div class="am-dropdown" data-am-dropdown>
                      <button class="am-btn am-btn-default am-btn-xs am-dropdown-toggle" data-am-dropdown-toggle><span class="am-icon-cog"></span> <span class="am-icon-caret-down"></span></button>
                      <ul class="am-dropdown-content">
                        <li><a href="#">1. 通过审核</a></li>
                        <li><a href="#">2. 查找相似</a></li>
                        <li><a href="#">3. 删除 </a></li>
                      </ul>
                    </div>
                  </td>
                </tr>
                @endfor
                </tbody>
              </table>
              <ul class="am-pagination am-fr admin-content-pagination">
                <li class="am-disabled"><a href="#">&laquo;</a></li>
                <li class="am-active"><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">5</a></li>
                <li><a href="#">&raquo;</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <footer class="admin-content-footer">
      <hr>
      <p class="am-padding-left">© 2014 AllMobilize, Inc. Licensed under MIT license.</p>
    </footer>
  </div>
  <!-- content end -->
@stop
@section('jscontent')
<script type="text/javascript" charset="utf-8">
</script>
@stop

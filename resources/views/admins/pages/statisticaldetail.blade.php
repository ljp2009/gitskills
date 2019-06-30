@extends('admins.layouts.admin')
@section('detailcontent')
<!-- content start -->
<div class="admin-content">
    @include('admins.partviews.titlebar',['titles'=>['数据管理', '作品管理'], 'searchControl'=>'/admin/ip/list'])
    <div class="am-g" style="min-height:83rem">
        <div class="am-u-sm-12">
            <table class="am-table am-table-bd am-table-striped admin-content-table">
                <thead>
                  <tr>
                    <th>用户</th>
                    <th>会话数</th>
                    <th>会话总时长</th>
                    <th>会话平均时长</th>
                  </tr>
                </thead>
              <tbody>
                <?php $i = 0; ?>
                @foreach($models as $model)
                <tr>
                    <td>{{$model['userName']}}</td>
                    <td>{{$model['sessionCt']}}</td>
                    <td>{{$model['sessionTotalTime']}}</td>
                    <td>{{$model['sessionAvgTime']}}</td>
                </tr>
                @endforeach
            </tbody>
            </table>
        </div>
    </div>
    <div class="am-container" id="pageDiv">
        <?php echo $res->render(); ?>
    </div>
</div>
  <!-- content end -->
<!--confirm window-->
@include('admins.partviews.modalcontrols')

@stop
@section('jscontent')
<script type="text/javascript" charset="utf-8" src="/js/admin/admin.js"></script>
<script type="text/javascript" charset="utf-8">
    formatPager($('#pageDiv'));
</script>
@stop

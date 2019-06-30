<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */
/*测试页*/
Route::get('/test', 'TestFunctionController@getIndex');
Route::post('/test', 'TestFunctionController@postIndex');
Route::post('/test2', 'TestFunctionController@postIndex2');

//数据导入
//Route::get('/test/importData', 'Admin\ImportIpController@index');
//Route::post('/test/importData/ip', 'Admin\ImportIpController@postImportIp');
//Route::post('/test/importData/tags', 'Admin\ImportIpController@postImportTags');
//Route::post('/test/importData/iptag', 'Admin\ImportIpController@postImportIpTag');

/* 创建页面json数据读取 */
Route::get("/json/ipattrs", "IpController@getSysIpAttrs");
Route::get("/json/iptags", "IpController@getSysIpTags");
Route::get("/json/tasktags", "TaskPublishController@getSysTaskTags");
Route::get("/json/taskRule", "TaskController@getTaskRules");
Route::get("/json/userskill", "UserController@getUserSkill");
/*快速访问的url*/
Route::get('/goto/{code}', "GotoController@index");
//建设中
Route::get('/building', function(){return view('building');});
/* 主页 */
Route::get('/', 'Auth\AuthController@getLoginNormal');
/* 身份认证 */
Route::group(['prefix' => 'auth'], function () {
    //登录
    Route::get('/login/{redirectCode?}', 'Auth\AuthController@getLoginNormal');
    Route::get('/login-admin', 'Auth\AuthController@getLoginAdmin');
    Route::post('/login', ['uses'=>'Auth\AuthController@postLogins','middleware'=>'log']);
    //登出
    Route::get('/logout', 'Auth\AuthController@getLogout');
    //验证代码
    Route::post('/sendvalidatecode', 'Auth\AuthController@postSendValidateCode');
    //设置重置密码
    Route::get('/reset', 'Auth\AuthController@getResets');
    Route::post('/reset', 'Auth\AuthController@postResets');
    Route::get('/reset/success', 'Auth\AuthController@resetsSuccess');
    //微信认证
    Route::get('/weixin/login', 'Auth\AuthController@wechatLogin');
    Route::get('/weixin/regist', 'Auth\AuthController@wechatRegist');
    Route::get('/weixin/bind', 'Auth\AuthController@getWechatBind');
    Route::post('/weixin/bind', 'Auth\AuthController@postWechatBind');

    Route::get('/weixin/login-callback', 'Auth\AuthController@wxLoginCallback');
    Route::get('/weixin/regist-callback', 'Auth\AuthController@wxRegistCallback');

});
/*微信认证*/
Route::group(['prefix' => 'wechat'], function(){
    Route::get('login/{code?}', 'WechatController@getLogin');
    Route::get('callback', 'WechatController@getCallback');
});
/*qq认证*/
Route::group(['prefix' => 'qq'], function(){
    Route::get('login/{code?}', 'QQController@getLogin');
    Route::get('callback', 'QQController@getCallback');
});
/* 用户注册 */
Route::controller('regist', 'RegistController');

//Route::get('/userskill/{uid}', 'UserController@showCreateSkill');
/* 用户设置 */
Route::group(['prefix' => 'uset', 'middleware'=>'auth'], function(){
    //显示用户设置入口
    Route::get('/main', 'UserSetController@mainIndex');
    Route::post('/display', 'UserSetController@saveDisplayName');
    Route::post('/signature', 'UserSetController@saveSignature');
    //显示用户属性入口
    Route::get('/attr', 'UserSetController@attrIndex');
    Route::post('/attr', 'UserSetController@saveAttr');
    //设置头像
    Route::post('/avatar', 'UserSetController@saveAvatar');
    //设置背景
    Route::post('/background', 'UserSetController@saveBackground');
    //绑定手机号码
    Route::get('/mobile', 'UserSetController@mobileIndex');
    Route::post('/{type}-mobile', 'UserSetController@saveMobile');
    //绑定邮件地址
    Route::get('/email', 'UserSetController@emailIndex');
    Route::post('/{type}-email', 'UserSetController@saveEmail');
    //修改密码
    Route::get('/pwd', 'UserSetController@getPwdIndex');
    Route::post('/pwd', 'UserSetController@setPwd');

    //编辑用户基本信息
    Route::get('/userinfo/{uid}/{isCreate?}', 'UserSetController@getUserInfoPage');
    Route::post('/userinfo', 'UserSetController@postUserInfo');
    //用户技能设置
    Route::get('/skill', 'UserSetController@skillIndex');
    Route::get('/loadskill', 'UserSetController@loadSkill');
    Route::post('/addskill', 'UserSetController@addSkill');
    Route::post('/updateskill', 'UserSetController@updateSkill');
    Route::post('/removeskill', 'UserSetController@removeSkill');
    Route::post('/saveSkill', 'UserSetController@saveSkill');
    //编辑用户技能
    Route::get('/userskill/{uid}/{isCreate?}', 'UserSetController@getUserInfoPage');
    Route::post('/add-skill', 'UserSetController@postUserInfo');
    Route::post('/delete-skill', 'UserSetController@postUserInfo');
    //用户调查
    Route::get('/survey-{type}/{uid}', 'UserController@createSurvey');
    Route::post('/survey-{type}', 'UserController@addSurvey');
});
/* 资源大厅路由 */
Route::group(['prefix' => 'reshall'], function () {
    //大厅主页
    Route::get('/', 'ResHallController@index');
    Route::get('/{partview}', 'ResHallController@loadPartview');
    //更多推荐列表
    Route::get('/list/recommend/{page}', 'ResHallController@getRecommendMoreList')->where('page', '[0-9]+');
    Route::get('/list/recommend/{from}-{to}', 'ResHallController@getRecommendMoreListData');
});
/* 专辑 */
Route::group(['prefix'=>'special'], function(){
    //专辑详情页面
    Route::get('/detail-{id}/{page?}', 'ResHallController@getSpecialItem')->where(['id'=>'[0-9]+', 'page'=>'[0-9]+']);
    Route::get('/list/detail-{id}/{from}-{to}', 'ResHallController@getSpecialItemData')->where(['id'=>'[0-9]+', 'from'=>'[0-9]+', 'to'=>'[0-9]+']);
    //专辑列表
    Route::get('/list/default/{page}', 'ResHallController@getSpecialList')->where('page', '[0-9]+');
    Route::get('/list/default/{from}-{to}', 'ResHallController@getSpecialListData') ->where(['from'=>'[0-9]+', 'to'=>'[0-9]+']);
});
/* 排行榜 */
Route::group(['prefix'=>'ranking'], function(){
    //首页
    Route::get('/', 'RankingController@getRankingList');
    Route::get('/part/{partName}', 'RankingController@loadRankingPartview');
    //排行榜列表
    Route::get('/list/{partName}/{page}', 'RankingController@loadRankingPage')->where(['page'=>'[0-9]+']);
    Route::get('/list/{partName}/{from}-{to}', 'RankingController@loadRankingData')->where(['from'=>'[0-9]+', 'to'=>'[0-9]+']);
});

//分类
Route::group(['prefix' => 'classify'], function(){
    //首页
    Route::get('/', 'ClassifyController@show');
    Route::get('/{key}/list/{name}/{page}', 'ClassifyController@search')->where('page', '[0-9]+');
    Route::get('/{key}/list/{name}/{from}-{to}', 'ClassifyController@searchData')->where('page', '[0-9]+');
});

/* 搜索路由 */
Route::group(['prefix' => 'search'], function () {
    Route::post('/', 'ResHallController@postSearch');
    Route::get('/list/{key}/{page}', 'ResHallController@search')->where('page', '[0-9]+');
    Route::get('/list/{key}/{from}-{to}', 'ResHallController@searchData')->where('page', '[0-9]+');
});
/*任务路由（new）*/

/*任务大厅路由*/
Route::group(['prefix' => 'taskhall'], function () {
    Route::get('{page?}/{order?}/{filter?}',
        'TaskListController@showHallTask') ->where('page', '[0-9]+');
    Route::get('list/default/{page?}/{order?}/{filter?}',
        'TaskListController@showHallTask') ->where('page', '[0-9]+');
    Route::get('list/default/{from}-{to}/{order?}/{filter?}',
        'TaskListController@showHallTaskData');
});
/*用户任务列表*/
Route::group(['prefix' => 'usertask', 'middleware'=>'auth'], function () {
    Route::get('{listname?}/{page?}/{userid?}',
        'TaskListController@showUserTask') ->where('page', '[0-9]+');
    Route::get('list/{listname}/{page?}/{userid?}',
        'TaskListController@showUserTask') ->where('page', '[0-9]+');
    Route::get('list/{listname}/{from}-{to}/{userid?}',
        'TaskListController@showUserTaskData');
});
/*任务创建向导*/
Route::group(['prefix' => '{taskMode}taskwizzard', 'middleware'=>'auth'], function () {
    Route::get('/', 'TaskWizzardController@showCreate');
    Route::post('savebase', 'TaskWizzardController@createBase');
    Route::post('saverequire', 'TaskWizzardController@createRequirement');
    Route::post('savefilter', 'TaskWizzardController@createUserFilter');
});
/*任示编辑与发布路由*/
Route::group(['prefix' => 'pubtask', 'middleware' => 'auth'], function () {
    //创建PK任务
    Route::get('/create-{mode}', 'TaskPublishController@showCreatePage');
    //创建任务
    Route::get('/create', 'TaskPublishController@showCreatePage');
    Route::post('/create', 'TaskPublishController@createTask');
    //管理任务(打开子页面)
    Route::get('/manage-{pageName}/{taskId}', 'TaskPublishController@showManagePage');
    Route::get('/get-{listName}-data/{taskId}', 'TaskPublishController@getManageListData');
    Route::get('/load-{partName}/{id}', 'TaskPublishController@loadManagePartview');
    Route::get('/loadmilestone/{taskId}','TaskPublishController@loadMilestoneData');
    Route::get('/loadcondition/{taskId}','TaskPublishController@loadconditionData');

    //删除
    Route::post('/remove', 'TaskPublishController@removeTask');
    //发布
    Route::post('/publish', 'TaskPublishController@publishTask');
    //取消(约定任务未开始的时候)
    Route::post('/cancel', 'TaskPublishController@cancelTask');
    //编辑字段
    Route::post('/saveparam', 'TaskPublishController@saveParameter');
    //详细信息
    Route::post('/savedetail', 'TaskPublishController@saveDetail');
    //里程碑
    //Route::post('/savemilestone', 'TaskPublishController@saveMilestone');
    //Route::post('/removemilestone', 'TaskPublishController@removeMilestone');
    //交付条件
    Route::post('/savecondition', 'TaskPublishController@saveCondition');
    Route::post('/removecondition', 'TaskPublishController@removeCondition');
});
/*任务里程碑编辑*/
Route::group(['prefix'=>'milestone'], function(){
    Route::get('list/{taskid}', 'TaskMilestoneController@getListAll')->where(['taskid'=>'[0-9]+']);
    Route::get('show/{id}', 'TaskMilestoneController@getShow')->where(['id'=>'[0-9]+']);
    Route::post('signin', 'TaskMilestoneController@postSignIn');

    Route::group(['prefix'=>'manage','middleware'=>['owner:milestone']], function(){
        Route::get('{taskid}', 'TaskMilestoneController@getManage')->where(['taskid'=>'[0-9]+']);
        Route::get('all/{taskid}', 'TaskMilestoneController@getAllData')->where(['taskid'=>'[0-9]+']);
        Route::get('{taskid}/{id}', 'TaskMilestoneController@getEdit')->where(['taskid'=>'[0-9]+', 'id'=>'[0-9]+']);
        Route::post('delete', 'TaskMilestoneController@postDelete');
        Route::post('save', 'TaskMilestoneController@postSave');
    });

});
/*交付任务*/
Route::group(['prefix'=>'taskdelivery', 'middleware'=>'auth'], function(){
    Route::get('/{taskid}', 'TaskDeliveryController@showAddDeliveryPage')
        ->where(['taskid'=>'[0-9]+']);
    Route::get('/edit/{deliveryid}', 'TaskDeliveryController@showEditDeliveryPage')
        ->where(['deliveryid'=>'[0-9]+']);
    Route::post('/add', 'TaskDeliveryController@addDelivery');
    Route::post('/edit', 'TaskDeliveryController@editDelivery');
    Route::post('/delete', 'TaskDeliveryController@deleteDelivery');
    Route::get('/list/default/{page}/{taskid}', 'TaskDeliveryController@showDeliveryList')
        ->where(['taskid'=>'[0-9]+', 'page'=>'[0-9]+']);
    Route::get('/list/default/{from}-{to}/{taskid}', 'TaskDeliveryController@showDeliveryData')
        ->where(['taskid'=>'[0-9]+', 'from'=>'[0-9]+', 'to'=>'[0-9]+']);
    Route::get('/partview/{taskId}', 'TaskDeliveryController@showDeliveryPartview')
        ->where(['taskid'=>'[0-9]+']);
});
/*处理任务申请*/
Route::group(['prefix' => 'jointask', 'middleware' => 'auth'], function () {
    Route::post('/request','TaskJoinController@requestJoin');
    Route::post('/agree','TaskJoinController@agreeJoin');
    Route::post('/reject','TaskJoinController@rejectJoin');
    Route::post('/undo','TaskJoinController@undoAction');
    Route::post('/confirm','TaskJoinController@confirmJoin');
    Route::get('/list/request/{page}/{pid}','TaskJoinController@showJoinRequestListPage')->where('page', '[0-9]+');
    Route::get('/list/request/{from}-{to}/{pid}','TaskJoinController@getJoinRequestListData');
});


/*任务路由*/
Route::group(['prefix' => 'task'], function () {
    //显示任务
    Route::get('/{id}', 'TaskController@showTask')->where('id', '[0-9]+');
    Route::get('/{id}/{viewname}', 'TaskController@showTaskPartview')->where('id', '[0-9]+');

    Route::group(['middleware'=>'auth'], function(){
        //任务操作(取消，确认完成)
        Route::post('/act', 'TaskController@doTaskAction');
       //请求取消
        Route::get('/requestcancel/{taskId}', 'TaskController@getRequestCancelPage');
        Route::get('/showcancel/{taskId}', 'TaskController@getShowCancelPage');
        Route::post('/requestcancel', 'TaskController@requestCancel');
        Route::post('/confirmcancel', 'TaskController@confirmCancel');
        Route::post('/undocancel', 'TaskController@undoCancel');
        //完成任务
        Route::get('/finish/{taskId}', 'TaskController@getFinishPage');
        Route::post('/finish', 'TaskController@finishTask');
        //投票
        Route::get('/singlevote/{id}', ['uses' => 'VoteController@showSingleVote', 'middleware' => ['auth']]);
        Route::get('/multiplevote/{id}', ['uses' => 'VoteController@showMultipleVote', 'middleware' => ['auth']]);
        Route::post('/singlevote', 'VoteController@singlevote');
        Route::post('/multiplevote', 'VoteController@multiplevote');
    }) ;
});
Route::group(['middleware'=>'auth', 'prefix'=>'vote'], function(){
    Route::post('/check', 'VoteController@checkVote');
    Route::post('/post', 'VoteController@postVote');
});

/* 邀请路由(任务/活动)*/
Route::group(['middleware'=>'auth', 'prefix' => 'invite'], function(){
    Route::get('/createRange/{id}/{resourceType}/{inviteUserId?}', 'InviteController@createRange');
    Route::post('/publishRange','InviteController@publishRange');
    Route::get('/createDesignated/{id}/{resourceType}/{inviteUserId?}','InviteController@createDesignated');
    Route::post('/list', 'InviteController@searchInviteUser');
    Route::post('/publishDesignated','InviteController@publishDesignated');
});

/* 我的关注*/
Route::group(['prefix' => 'userfollow'],function(){
    Route::get('/list/user-{type}/{page}', 'UserFollowController@showUserFollowList')->where(['page'=>'[0-9]+']);
    Route::get('/list/user-dimension/{from}-{to}', 'UserFollowController@getUserDimensionFollowData')->where(['from'=>'[0-9]+','to'=>'[0-9]+']);
    Route::get('/list/user-production/{from}-{to}', 'UserFollowController@getUserProductionFollowData')->where(['from'=>'[0-9]+','to'=>'[0-9]+']);
});
/* 浏览历史记录*/
Route::group(['prefix'=>'myhistory'],function(){
    Route::get('/list/default/{page}', 'MyHistoryController@showMyHistoryList')->where(['page'=>'[0-9]+']);
    Route::get('/list/default/{from}-{to}', 'MyHistoryController@loadMyHistoryListData')->where(['from'=>'[0-9]+','to'=>'[0-9]+']);
});
/* 认证申请路由*/
Route::group(['prefix' => '/certification'], function(){
    Route::get('/list/{listName}', 'CertificationApplyController@showCertificationList');
    Route::get('/list/{listName}/{from}-{to}', 'CertificationApplyController@loadCertificationList');
    Route::get('/show/guidepager', 'CertificationApplyController@createGuidePager');
    Route::get('/create', 'CertificationApplyController@createCertification');
    Route::get('/edit/{id}', 'CertificationApplyController@editCertification');
    Route::post('/quicksearch', 'CertificationApplyController@postQuickSearch');
    Route::post('/apply', 'CertificationApplyController@apply');
    Route::post('/delete', 'CertificationApplyController@deleteCertifiApply');
});



/*请把需要登陆的路由放在这里*/
Route::group(['middleware' => 'auth'], function () {
    Route::get('/roleskill/create/{roleid}', 'RolesController@displayCreateRoleSkill');
    Route::get('/colleague/create/{ipid}', 'IpRelatedController@displayColleagueCreatePage');
    Route::get('/peripheral/create/{ipid}', 'IpRelatedController@displayPeripheralCreatePage');
    Route::get('/ip/create', 'IpController@displayCreatePage');
    Route::get('/ip/newcreate/{type}', 'IpController@displayNewCreatePage');
    Route::post('/ip/create', 'IpController@addNew');
    Route::post('/baidu/{type}', 'Common\BaiduImportController@attachSearch');
    Route::post('/baidu', 'Common\BaiduImportController@attachSearch');
    Route::get('/baidu', 'Common\BaiduImportController@displaySearch');
    Route::post('/user/receive', 'UserController@receiveMoney');
    Route::post('/private/relation', 'UserController@addUserRelation');
    Route::post('/private/privateletter', 'UserController@sendPrivateLetter');
});

/*IP详细页面加载路由*/
Route::group(['prefix' => 'ip'], function () {
    Route::get('/{id}', ['uses'=>'IpController@index', 'middleware'=>['log:ip']]);
    Route::get('/{id}/{partview}', 'IpController@loadPartview');
    Route::post('/userstatus', 'IpController@changeIpUserStatus');

    //参与IP的用户列表
    Route::get('/list/user/{page}/{ipid}', 'IpController@loadUserListPage')->where(['page'=>'[0-9]+']);
    Route::get('/list/user/{from}-{to}/{ipid}', 'IpController@loadUserListData')->where(['from'=>'[0-9]+', 'to'=>'[0-9]+']);
});
//IP相关的用户作品操作
Route::group(['prefix' => 'ip-{related}'], function () {
    Route::get('list/v/{page}/{ipid}', 'IpRelatedController@loadRelatedList')
        ->where(['ipid'=>'[0-9]+','page'=>'[0-9]+']);
    Route::get('list/v/{from}-{to}/{ipid}', 'IpRelatedController@loadRelatedListData')
        ->where(['ipid'=>'[0-9]+','from'=>'[0-9]+','to'=>'[0-9]+']);
    //创建入口
    Route::get('create/{ipid}', 'IpRelatedController@createRelated' );
    //打开编辑窗口
    Route::get('edit/{id}', 'IpRelatedController@showEditRelated' );
    //删除操作
    Route::post('delete', 'IpRelatedController@deleteRelated' );
});
/*列表加载路由*/
//同人作品
Route::group(['prefix' => 'colleague'], function () {
    Route::get('/list/default/{page}/{pid}', 'IpRelatedController@colleagueList')
        ->where('page', '[0-9]+');
    Route::get('/list/default/{from}-{to}/{pid}', 'IpRelatedController@getColleagueListData');
    Route::get('/{id}', 'IpRelatedController@colleagueDetail');

    Route::post('/delete', 'ProductionPublishController@postDelete');
    Route::get('/edit/{id}', 'ProductionPublishController@getModifyColl');
});
//周边产品
Route::group(['prefix' => 'peripheral'], function () {
    Route::get('/list/default/{page}/{pid}', 'IpRelatedController@peripheralList')
        ->where('page', '[0-9]+');
    Route::get('/list/default/{from}-{to}/{pid}', 'IpRelatedController@getPeripheralListData');
    Route::get('/{id}', 'IpRelatedController@peripheralDetail');
    Route::post('/delete', 'ProductionPublishController@postDelete');
    Route::get('/edit/{id}', 'ProductionPublishController@getModifyPeri');
});

/*主页列表加载路由*/
Route::group(['prefix' => 'home'], function () {
    Route::get('/list/{listName}/{page}/{id}', ['uses' => 'HomeController@showLikeList', 'middleware' => ['log']])
        ->where('page', '[0-9]+');
    Route::get('/list/default/{from}-{to}/{id}', 'HomeController@loadLikeListData');
    Route::get('/list/test/{from}-{to}/{id}', 'HomeController@loadLikeListData_tmp');

    Route::get('/list/works/{from}-{to}/{id}', 'HomeController@loadWorkListData');
    Route::get('/list/sales/{from}-{to}/{id}', 'HomeController@loadSalesListData');
    Route::get('/create/{id}', ['uses' => 'HomeController@displayCreateWork'
        , 'middleware' => ['auth']]);
    Route::post('/create', 'HomeController@addWork');
    Route::get('/edit/{id}', ['uses' => 'HomeController@displayEditWork'
    		, 'middleware' => ['auth']]);
    Route::post('/edit', 'HomeController@editWork');
    Route::post('/search', 'HomeController@searchIp');
    Route::post('/delete', 'HomeController@deleteWork');
});

/*Ip角色路由*/
Route::group(['prefix' => 'roles'], function () {
    Route::get('/list/default/{page}/{id}', 'RolesController@loadRoleList') ->where('page', '[0-9]+');
    Route::get('/list/default/{from}-{to}/{id}', 'RolesController@LoadRoleListData');

    Route::get('/{id}', 'RolesController@showDetail')->where('id', '[0-9]+');
    Route::get('/{id}/{partview}', 'RolesController@loadPartview')->where('id', '[0-9]+');

    Route::group(['middleware'=>'auth'], function(){
        Route::get('/create/{id}/', 'RolesController@displayCreateRoles');
        Route::post('/create', 'RolesController@addRoles');
        Route::get('/edit/{id}/', 'RolesController@displayEditRoles');
        Route::post('/edit', 'RolesController@editRoles');
        Route::post('/delete', 'RolesController@deleteRole');
        Route::post('/deleteskill', 'RolesController@deleteSkill');
        Route::post('/mainskill', 'RolesController@setSkill');
    });
});
/* 角色技能路由 */
Route::group(['prefix' => 'roleskill', 'middleware'=>'auth'], function () {
    Route::post('/create', 'RolesController@addRoleSkill');
    Route::get('/edit/{id}/', 'RolesController@displayEditRoleskill');
    Route::post('/edit', 'RolesController@editRoleskill');
});


/* 公共部分路由 评论部分和喜欢路由 、 评分路由 */
Route::group(['prefix' => 'common', 'namespace' => 'Common'], function () {
    Route::get('/discuss/normal/{resource}/{id}', 'CommonDiscussionController@loadDiscussionNormal');
    Route::get('/discuss/newest-{resource}/{pid}', 'CommonDiscussionController@loadNewestDiscussion');
    Route::get('/discuss/{resource}/list/{type}/{page}/{pid}', 'CommonDiscussionController@loadDiscussionList')
        ->where('page', '[0-9]+');
    Route::get('/discuss/{resource}/list/{type}/{from}-{to}/{pid}', 'CommonDiscussionController@loadDiscussionDetails');
    Route::get('/discuss/create/{type}/{resource}/{id}/{referenceid}', ['uses' => 'CommonDiscussionController@displayCreateDiscussion',
        'middleware' => ['auth']]);
    Route::post('/discuss/create/short',['uses'=>'CommonDiscussionController@addNewShortDiscussion', 'middleware'=>['auth']] );
    Route::get('/discuss/create/{type}/{resource}/{id}', ['uses' => 'CommonDiscussionController@displayCreateDiscussion',
        'middleware' => ['auth']]);
    Route::post('/discuss/create/long', 'CommonDiscussionController@addNewLongDiscussion');

    Route::get('/discuss/reply/{responseId}/{referenceId}', ['uses' => 'CommonDiscussionController@displayReplyCreationPage',
        'middleware' => ['auth']]);
    Route::get('/discuss/reply/list/{referenceId}/{page}', 'CommonDiscussionController@displayReplyList')
        ->where('page', '[0-9]+');
    Route::get('/discuss/reply/list/{referenceId}/{from}-{to}', 'CommonDiscussionController@displayReplyContent');
    Route::post('/discuss/reply', 'CommonDiscussionController@addNewReply');

    Route::post('/like', 'CommonLikeController@postLike');
    Route::post('/likeAndCount', 'CommonLikeController@postLikeAndReturnCount');
    Route::post('/switchlike', 'CommonLikeController@switchLike');

    Route::post('/userscore', 'CommonScoreController@setUserScore');
    Route::post('/score/user', 'CommonScoreController@postUserScore');
    Route::post('/score/sys', 'CommonScoreController@postSysScore');

    Route::post('/read', 'CommonReadController@postRead');

    Route::post('/baiduimport/search', 'BaiduImportController@applySearch');
    Route::post('/baiduimport/parse', 'BaiduImportController@fetchFromBaiduBaike');
    Route::post('/baiduimport/parse2', 'BaiduImportController@fetchFromBaiduBaike2');
    Route::post('/baiduimport/edit2', 'BaiduImportController@displayEditor2');
    Route::post('/baiduimport/import', 'BaiduImportController@postSave');
    //编辑评论
    Route::post('/discuss/delete', 'CommonDiscussionController@deleteDiscussion');
    Route::get('/discuss/edit/{id}',
        ['uses' => 'CommonDiscussionController@displayEditDiscussion',
         'middleware' => ['auth']]);
    Route::post('/discuss/edit', 'CommonDiscussionController@editShortDiscussion');

    Route::post('/discuss/deletelong', 'CommonDiscussionController@deleteLongDiscussion');
    Route::get('/discuss/editlong/{id}', ['uses' => 'CommonDiscussionController@displayLongEditDiscussion',
    'middleware'                                                               => ['auth']]);
    Route::post('/discuss/editlong', 'CommonDiscussionController@editLongDiscussion');

    Route::get('/discuss/createlong/{resource}/{id}', ['uses' => 'CommonDiscussionController@displayCreateLongDiscussion',
        'middleware'                                                 => ['auth']]);
    Route::post('/discuss/createlong', 'CommonDiscussionController@addLongDiscussion');
    //评论回复
    Route::get('/list/discussiondetail/{page}/{id}', 'CommonDiscussionController@loadDiscussionDetail')->where('page', '[0-9]+');
    Route::get('/list/discussiondetail/{from}-{to}/{id}', 'CommonDiscussionController@loadNewestDiscussionReplyList');

    Route::get('/longdiscussreply/newest-{resource}/{pid}', 'CommonDiscussionController@loadNewestDiscussionReply');

    Route::get('/list/{resource}/{page}/{pid}', 'CommonDiscussionController@loadLongDiscussionList')
    ->where('page', '[0-9]+');
    Route::get('/list/ip/{from}-{to}/{pid}', 'CommonDiscussionController@loadLongDiscussionDetails');
});
Route::group(['prefix'=>'discussion'],function(){
    Route::post('/publish',['uses'=>'DiscussionController@publish', 'middleware'=>'auth'] );
    Route::post('/delete',['uses'=>'DiscussionController@delete', 'middleware'=>'auth'] );

    Route::post('/count', 'DiscussionController@count');
    Route::get('/list/{type}-{resource}-{id}/{page?}', 'DiscussionController@getDiscussionList')->where('page', '[0-9]+');
    Route::get('/list/{type}-{resource}-{id}/{page?}', 'DiscussionController@getDiscussionList')->where('page', '[0-9]+');
    Route::get('/list/{type}-{resource}-{id}/{from}-{to}', 'DiscussionController@getDiscussionListData')->where(['from'=>'[0-9]+' ,'to'=>'[0-9]+']);
    Route::get('/{type}-{resource}-{id}', 'DiscussionController@getDiscussionPartview');
});
/*场景路由*/
Route::group(['prefix' => 'ipscene'], function () {
    Route::get('/list/verified/{page}/{ipid}', 'IpSceneDialogueController@getVerifiedSceneList')->where('page', '[0-9]+');
    Route::get('/list/verified/{from}-{to}/{ipid}', 'IpSceneDialogueController@getVerifiedSceneContent');
    Route::get('/{id}', 'IpSceneDialogueController@getSceneDetail');

    Route::group(['middleware'=>'auth'], function(){
        Route::get('/create/{ipid}', 'IpSceneDialogueController@displayCreateScene');
        Route::post('/create', 'IpSceneDialogueController@addScene');
        Route::get('/edit/{id}', 'IpSceneDialogueController@displayEditScene');
        Route::post('/edit', 'IpSceneDialogueController@editScene');
        Route::post('/delete', 'IpSceneDialogueController@deleteScene');
    });
});

/*台词路由*/
Route::group(['prefix' => 'ipdialogue'], function () {
    Route::get('/list/verified/{page}/{ipid}', 'IpSceneDialogueController@getVerifiedDialogueList')->where('page', '[0-9]+');
    Route::get('/list/verified/{from}-{to}/{ipid}', 'IpSceneDialogueController@getVerifiedDialogueContent');
    Route::get('/{id}', 'IpSceneDialogueController@getDialogueDetail');

    Route::group(['middleware'=>'auth'], function(){
        Route::get('/create/{ipid}', 'IpSceneDialogueController@displayCreateDialogue');
        Route::post('/create', 'IpSceneDialogueController@addDialogue');
        Route::get('/edit/{id}', 'IpSceneDialogueController@displayEditDialogue');
        Route::post('/edit', 'IpSceneDialogueController@updateDialogue');
        Route::post('/delete', 'IpSceneDialogueController@deleteDialogue');
    });
});
/* 用户路由 */
Route::group(['prefix' => 'user'], function () {
    Route::get('/list/{default}/{page}/{pid?}', 'UserController@showUserList')
        ->where('page', '[0-9]+');
    Route::get('/list/default/{from}-{to}/{pid}', 'UserController@loadListData');
    Route::get('/list/follow/{from}-{to}/{pid}', 'UserController@loadFollowListData');
    Route::get('/list/fans/{from}-{to}/{pid}', 'UserController@loadFansListData');
    Route::get('/list/samelikelist/{from}-{to}/{pid}', 'UserController@loadsameListData');
    Route::get('/list/likeuserlist-{obj}/{from}-{to}/{pid}', 'UserController@loadLikeUserList');
    Route::get('/list/contributor/{from}-{to}/{pid}', 'UserController@getContributorUserList');
    Route::get('/list/master/{from}-{to}/{pid?}', 'UserController@getMasterUserList');
    Route::post('/user/receive', 'UserController@receiveMoney');
    Route::post('/relation', 'UserController@addUserRelation');
    Route::post('/follow-switch', 'UserController@FollowSwitch');
    Route::get('/product/{id}', ['uses'=>'UserController@showProductInfo', 'middleware'=>'log:product']);
    Route::get('/showuserinfo/{id}', 'UserController@showEditUser');
    Route::post('/edituser', 'UserController@editUserInfo');
    Route::get('/showuserprefrence/{type}', ['uses' => 'UserPreferenceController@showUserPrefrence','middleware' => ['auth']]);
    Route::post('/adduserprefrence', 'UserPreferenceController@addUserPrefrence');
});

/* 私信路由 */
Route::group(['prefix' => 'private'], function () {
    Route::get('/list/default/{page}/{pid}', ['uses' => 'UserController@getletterList', 'middleware' => ['auth']])
        ->where('page', '[0-9]+');
    Route::get('/list/default/{from}-{to}/{pid}', 'UserController@getletterListData');

    Route::get('list/dialog/{page}/{userid}',['uses' => 'UserController@getletterListDialog', 'middleware' => ['auth']])
        ->where('page', '[0-9]+');
    Route::get('/list/dialog/{from}-{to}/{userid?}', 'UserController@getletterListDialogData');
    Route::get('/load-chat/{from}-{listCount}', 'userController@loadChatHistory');

    Route::get('/{id}', 'UserController@getletterInfo');
    Route::post('/privateletter', 'UserController@sendPrivateLetter');
    Route::post('/privateststus', 'UserController@updatePrivate');
});
/*通知中心(代替私信)*/
Route::group(['prefix' => 'notice', 'middleware' => 'auth'], function () {
    Route::get('/', 'NoticeController@index')
        ->where('page', '[0-9]+');
    Route::get('/list/{type}/{page}/{pid?}', 'NoticeController@getNoticeList')
        ->where('page', '[0-9]+');
    Route::get('/list/{type}/{from}-{to}/{pid?}', 'NoticeController@getNoticeListData')
        ->where(['from'=>'[0-9]+' ,'to'=>'[0-9]+']);
    Route::get('/list/default/{page}', 'NoticeController@index')
        ->where('page', '[0-9]+');
    Route::get('/list/default/{from}-{to}', 'NoticeController@loadUserLetterData')
        ->where(['from'=>'[0-9]+' ,'to'=>'[0-9]+']);
});
/* 消息路由 */
Route::group(['prefix' => 'message', 'middleware' => 'auth'], function () {
    Route::get('/list/default/{page}/{title?}', 'MessageController@showMessagePage')
        ->where('page', '[0-9]+');
    Route::get('/list/default/{from}-{to}/{title?}', 'MessageController@showMessageData')
        ->where(['from'=>'[0-9]+' ,'to'=>'[0-9]+']);
    Route::get('/chat-with/{userId}/{title?}', 'MessageController@showChatPage');
    Route::get('/msg-history/{userId}-{from}-{msgCt}/{title?}', 'MessageController@loadHistoryMessage');

    Route::post('/add', 'MessageController@addNewMessage');
});


/*图片上传*/
Route::post('/image/upload', 'Component\PicController@uploadImage');

/*次元路由*/
Route::group(['prefix' => 'dimension'], function () {
    Route::get('/tags','DimensionController@getDimTags');
    Route::get('/list/{listname}/{page}/{id?}', 'DimensionController@showDimensionList')
        ->where('page', '[0-9]+');
    Route::get('/list/{listname}/{from}-{to}/{id?}', 'DimensionController@loadListData');
    Route::get('/postenter/{pid}', ['uses' => 'DimensionController@postEnter', 'middleware' => ['auth']]);
    Route::get('/create/{id?}', ['uses' => 'DimensionController@displayCreateDimension'
        , 'middleware' => ['auth']]);
    Route::post('/enter-switch', ['uses'=>'DimensionController@switchEnter', 'middleware'=>'auth']);
    Route::get('/{id}', 'DimensionController@dimensionInfo');
    Route::post('/create', 'DimensionController@addDimension');
    Route::post('/delete', 'DimensionController@deleteDimensionPublish');
    Route::get('/edit/{id}', 'DimensionController@showDimensionEdit');
    Route::post('/edit', 'DimensionController@editDimension');


});
Route::group(['prefix' => 'dimpub'], function () {
    Route::get('/list/diminfo/{page}/{id}', ['uses'=>'DimensionController@showDimensionInfo','middleware'=>'log:dimension'])
        ->where('page', '[0-9]+');
    Route::get('/list/diminfo/{from}-{to}/{id}', 'DimensionController@loadListDimensionData');
    Route::group(['middleware'=>'auth'], function(){
        Route::get('/publishcreate/{id}', 'DimensionController@displayCreateDimensionPublish');
        Route::post('/publishcreate', 'DimensionController@addDimensionPublish');
        Route::post('/delete', 'DimensionController@deleteDimensionPublish');
        Route::get('/edit/{id}', 'DimensionController@showDimensionPublishEdit');
        Route::post('/publishedit', 'DimensionController@editDimensionPublish');
    });
    Route::get('/{id}', 'DimensionController@dimensionInfo');
});

//, 'middleware' => 'auth'
Route::group(['prefix' => 'game/thirteen', 'namespace'=>'Game'], function(){
    Route::get('/updateStatus', 'GameThirteenController@getGameUserStatusId');
    Route::post('/setReady', 'GameThirteenController@ready');
    Route::get('/checkReady', 'GameThirteenController@isReady');
    Route::get('/checkAllPrepared/{roomid}', 'GameThirteenController@checkAllPrepared');
    Route::get('/getMyCards/{statusid}', 'GameThirteenController@getMyCards');
    Route::get('/getStrategy', 'GameThirteenController@getStrategy');
    Route::get('/getGameData/{roomid}', 'GameThirteenController@getGameData');
    Route::post('/submitCards', 'GameThirteenController@submitCards');
    Route::post('/readyForNextTurn', 'GameThirteenController@readyForNextTurn');
    Route::get('/checkNextTurn/{roomid}', 'GameThirteenController@checkAllForNextTurn');
    Route::get('/leaveRoom/{roomid}', 'GameThirteenController@leaveGame');
    Route::get('/datalist', 'GameDataEditController@index');
});

Route::get('/pic/local/{folder}/{picname}', 'component\PicController@localPic');

Route::group(['prefix' => 'game13', 'namespace'=>'Game\Game13'], function(){
    Route::get('/test', 'Game13Controller@test');
    Route::get('/test2', 'Game13Controller@test2');
    Route::get('/fuwentest', 'Game13Controller@fuwenTest');
    Route::get('/pic/preloadPic', 'Game13Controller@getPreloadPic');
    Route::get('/pic/background/{orientation}', 'Game13Controller@getBackgroundPic');
    Route::get('/pic/fuwen', 'Game13Controller@getFuwenPic');
    Route::get('/pic/userhead', 'Game13Controller@getUserHeadPic');
    Route::get('/pic/user_head_cover', 'Game13Controller@getUserHeadCoverPic');
    Route::get('/pic/front_hero_card/{level}', 'Game13Controller@getHeroCardFrontPic');
    Route::get('/pic/{pic}/{ext}', 'Game13Controller@getGame13Pic');
    Route::post('/view/userhead', 'Game13Controller@userHead');
    Route::get('/view/entry/userinfo', 'Game13Controller@displayEntryUserInfo');
    Route::post('/view/herocard', 'Game13Controller@heroCard');
    Route::get('/view/board', 'Game13Controller@displayPlayBoard');
    Route::get('/wip/test', 'Game13WIPController@testFunction');
    Route::get('/wip/healthcheck/{gameId}/{gameUserId}', 'Game13WIPController@healthCheck');
    Route::get('/wip/gameinfo/{userid}', 'Game13WIPController@getUserOnprocessInfo');

    Route::get('/loadEntryData', 'Game13Controller@loadEntryData');

    Route::get('/testPlay', 'Game13WIPController@testPlay');
    Route::get('/trigger/{game13UserId}', 'Game13WIPController@triggerGame');
    Route::get('/check/{game13UserId}', 'Game13WIPController@isGameStarted');
    Route::post('/wip/play', 'Game13WIPController@play');
    // Route::post('/wip/play', 'Game13WIPController@fullPlayTest');
    Route::post('/wip/submitcards', 'Game13WIPController@submitCards');
    Route::get('/wip/nextstage/{gameId}/{gameUserId}', 'Game13WIPController@gotoNextStage');
    Route::get('/wip/next/{gameId}/{gameUserId}/{ticket}', 'Game13WIPController@next');
    Route::get('/wip/next/{gameId}/{gameUserId}', 'Game13WIPController@next');
    Route::post('/wip/test', 'Game13WIPController@testPost');

    Route::get('/prepare', 'Game13WIPController@displayPrepareDataPage');
    Route::post('/prepare', 'Game13WIPController@prepareTestData2');
});


//队列
Route::group(['prefix' => 'queue'], function(){
	Route::get('/start','QueueController@show');
});
//发布我的作品
Route::group(['middleware' => ['auth','activity']],function(){
    Route::controller('/pub', 'ProductionPublishController');
});
//活动
Route::group(['prefix' => 'act'],function(){

    Route::get('/getlist', 'ActivityController@getList');
    Route::get('/getshowjoin/{id}', function($id){return redirect('/activity/list/join/0/'.$id);});
    Route::get('/getshowrank/{id}', function($id){return redirect('/activity/list/rank/0/'.$id);});


    Route::group(['prefix' => 'list'], function () {
        Route::get('get_list_data/{from}-{to}', 'ActivityController@get_list_data');
        Route::get('get_ranking_list_data/{from}-{to}/{act_id}', 'ActivityController@get_ranking_list_data');
        Route::get('get_join_list_data/{from}-{to}/{act_id}', 'ActivityController@get_join_list_data');
    });
});
Route::group(['prefix' => 'activity'],function(){
    Route::get('/list', function(){ return redirect('/activity/list/default/0');});
    Route::get('/list/default/{page}', 'ActivityController@getActivityList')->where('page', '[0-9]+');
    Route::get('/list/default/{from}-{to}', 'ActivityController@getActivityListData');

    Route::get('/list/join/{page}/{pid}', 'ActivityController@getJoinPartnerList')->where('page', '[0-9]+');
    Route::get('/list/join/{from}-{to}/{pid}', 'ActivityController@getJoinPartnerListData');

    Route::get('/list/rank/{page}/{pid}', 'ActivityController@getRankPartnerList')->where('page', '[0-9]+');
    Route::get('/list/rank/{from}-{to}/{pid}', 'ActivityController@getRankPartnerListData');
});
//图片
Route::controller('/img','ImageController');
//作品
Route::group(['prefix'=>'/prod'], function(){
    Route::get('/iprelatedcollrec/{ipid}','ProductionController@getRecColl');
    Route::get('/iprelatedperirec/{ipid}','ProductionController@getRecPeri');
    Route::get('/iprelateddiscrec/{ipid}','ProductionController@getRecDisc');
    Route::get('/list/coll-{order}/{page}/{ipid}','ProductionController@getColleagueList')
        ->where(['order'=>'\btime|like\b', 'page'=>'[0-9]+', 'ipid'=>'[0-9]+']);
    Route::get('/list/peri-{order}/{page}/{ipid}','ProductionController@getPeripheralList')
        ->where(['order'=>'\btime|like\b', 'page'=>'[0-9]+', 'ipid'=>'[0-9]+']);
    Route::get('/list/disc-{order}/{page}/{ipid}','ProductionController@getDiscussionList')
        ->where(['order'=>'\btime|like\b', 'page'=>'[0-9]+', 'ipid'=>'[0-9]+']);
    Route::get('/list/{pagetype}-{order}/{from}-{to}/{ipid}','ProductionController@getIpRelatedListData')
        ->where(['pagetype'=>'\bcoll|peri|disc\b', 'order'=>'\btime|like\b',
                'from'=>'[0-9]+', 'to'=>'[0-9]+', 'ipid'=>'[0-9]+']);

});

//Picklist资源的路由
Route::controller('/picklist', 'PickListController');
//管理网站
Route::group(['prefix'=>'/admin', 'middleware'=>['admin']], function(){
    Route::controller('ip', 'Admin\IpController');
    Route::controller('rc', 'Admin\RecommendController');
    Route::controller('ck', 'Admin\CheckController');
    Route::controller('res', 'Admin\ResourceController');
    Route::controller('user', 'Admin\UserController');
    Route::controller('act', 'Admin\ActivityController');
    Route::controller('sp', 'Admin\SpecialController');
	Route::controller('dc', 'Admin\DimensionController');
	Route::controller('ctrl', 'Admin\SystemController');
    Route::controller('tk', 'Admin\TaskController');
    Route::controller('st', 'Admin\StatisticalController');
    Route::controller('schedule', 'Admin\ScheduleController');
    Route::get('/runTaskSchedule/{date}','QueueController@mainIndex');
    Route::get('/runTaskCountVote/{date}','QueueController@mainCountVoteResult');
    Route::get('/runTaskAssignSolution/{date}','QueueController@mainAssignSolution');
});
//功能定制部分（临时活动等）
Route::group(['prefix'=>'custom'], function(){
    Route::controller('ido21', 'Custom\Ido21Controller');
});
//签到路由
Route::controller('signin','SignInController');
//页面处理路由
Route::controller('page','PageController');

@extends('layouts.submit')
@section('title',  '设定技能')
@section('formrange')
@include('partview.headerbar',['left'=>'back', 'center'=>'pageTitle', 'right'=>'home','pageTitle'=>'设定技能'])

<div class="am-container">
    <div>
        <form class="am-form" method="post" action="/uset/saveSkill">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <fieldset class="am-form-set">
                <div class="am-form-group" id="ruleContainer" >
                    <input type="hidden" name="rulesList" id="rulesList" />
                    <input type="hidden" name="uid" id="uid" value="{{$uid}}" />
                    <input type="hidden" name="selectedSkills" id="selectedSkills" value="{{$selectedSkills}}">
                </div>
                <label onclick="ruleClick(this,'/json/userskill')" class="am-btn am-radius am-tenders am-btn-warning" 
                      style="font-size:1.2rem" id="addRule">添加新技能</label>
            </fieldset>
        </form>
    </div>
    <button class="am-btn am-btn-warning am-btn-block" style="padding-top: 1.2rem;padding-bottom: 1.2rem;" onclick="$YN_VALIDATOR.submitForm()">保存</button>
    <div class="am-modal am-modal-no-btn" tabindex="-1" id="select-rule-modal">
        <div class="am-modal-dialog" style="padding:1rem">
            <div class="am-fl" style="font-size:1.4rem;font-weight:bolder;width:100%;text-align: left;border-bottom: 0.1rem #e2e2e2 solid;padding-bottom: 0.5rem;margin-bottom: 0.5rem;" >选择技能
              <a href="javascript: void(0)" class="am-close am-close-spin am-fr" style="text-align: right;margin-right: -0.3rem;font-size: 1.4rem;" data-am-modal-close>&times;</a>
            </div>

            <div id="select-rule-modal-content" style="text-align: left;"></div>
          </div>
        </div>
        <div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirm-rule">
          <div class="am-modal-dialog">
            <div class="am-modal-hd">确认删除</div>
            <div class="am-modal-bd" id="my-confirm-rule-content">
              你，确定要删除这个技能吗？
            </div>
            <div class="am-modal-footer">
              <span class="am-modal-btn" data-am-modal-cancel>取消</span>
              <span class="am-modal-btn" data-am-modal-confirm>确定</span>
            </div>
          </div>
        </div>
    </div>
        
</div>

<script type="text/javascript" src="/js/ym_rule.js"></script>
@section('scriptrange')
  var rule_skill = new Rule();
  rule_skill.setting.skillShow = 'spread';
  var selectedSkills= $('#selectedSkills').val();
  rule_skill.setting.defaultValue = selectedSkills;
  rule_skill.addExistTag();
@stop
@stop

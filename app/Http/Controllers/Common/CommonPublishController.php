<?php

namespace App\Http\Controllers\Common;

use App\Common\CommonUtils;
use App\Http\Controllers\Component\PicController;
use App\Http\Controllers\Controller;
use Input;

class CommonPublishController extends Controller
{
    /*
    以下是关于发布功能的说明：
    组件：
    - CommonPublishController 发布控制器，已经加入app.php, 可以使用Publish::methodName的方式调用其静态方法
    - layouts.publish         发布的页面框架。所有发布页面都应该为该view的子页面。可以参考common.discusscreate
    - common.publishpane      发布的悬浮按钮及弹出窗口的view。

    发布功能支持的控件都以$TP_XX的方式命名。

    创建一个Form发布体的过程
    1. 初始化一个表单
    $form = Publish::form(<表单提交URL>, [表单的标题]);
    2. 添加表单的控件
    $form->addComp(array(<参数>))  <-- 可以写多个
    3. 结束表单
    $form->end(array([提交的button名]))
    如果没有设定任何button,则使用默认的"提交"作为名字
    在接收控制器中可以使用Input::get(<name>)的方式获取值。
    [模板：
    $form = Publish::form('/../..', '..');
    $form->addComp(array(...));
    $form->addComp(array(...));
    $form->addComp(array(...));
    ...
    $form->end([...]);
    ]

    控件参数的解释
    1. name  必须提供。是表单中各个控件的name属性
    2. type  可选，提供的是控件的类型。默认为text, 即文本类型
    3. validators  可选，默认为空的array, 是系统提供的验证器。在后续会详细说明。
    4. label 可选，默认为空。提供的是每个控件前的介绍。在一般情况下，label为string类型。但是对于radio和check类型，label可以设为array， 表示每个radio或checkbox后显示的信息。
    5. placeholder    可选，默认为空。提供的是文本类控件(text, textarea, 等)的占位说明
    6. selectables    可选，默认为空的array。 提供的是radio, checkbox, list, combo控件的可选值。对于list 和combo, option里value和text是一样的。
    7. defaultValue  可选， 默认为空的array。 提供控件的初始值。对于选择性控件，表示初始选择的选项。
    8. errorMessage  可选， 默认为空。表示每个控件绑定了验证器后验证失败所报的错误信息。默认每个验证器都有缺省的错误信息。如果控件没有提供errorMessage, 则显示缺省错误信息。

    验证器：
    系统提供了$VAL_XX默认验证器。
    1. required  判断值为空。没有参数
    2. integer   判断值是否为整数。 没有参数
    3. number    判断值是否为数字。 没有参数
    4. phone     判断值是否为mobile。 全为数字，长度11。没有参数
    5. email     判断值是否为email。 没有参数
    6. limit     判断值长度是否在范围之内。参数为min和max。max可选。
    7. phoneOrMail  phone和email的结合。必须满足其中之一。

    验证器可以传入多个。当传入多个时必须所有验证满足才可。
    如果有参数，可以通过Publish::createOneValidator(<验证器名>, [参数array()])方法返回validator
    例如 文本框要求输入长度为6-20之间的整数
    $form->addComp(array('name'=>'limitNum', 'label'=>'整数长度限制',
    'validators'=>array(Publish::$VAL_INTEGER,
    Publish::createOneValidator(Publish::$VAL_LIMIT, array(6, 20))
    )
    )
    );
    验证器是可以扩展的。
    通过在view的scriptrange区域加入
    $YN_VALIDATOR.extendValidator(<validator名字>, <验证的function>, <缺省错误信息>)
    然后就可以在'validators'的array中添加该validator名字。
     */
    public static $VAL_REQUIRED    = 'required';
    public static $VAL_INTEGER     = 'integer';
    public static $VAL_NUMBER      = 'number';
    public static $VAL_PHONE       = 'phone';
    public static $VAL_EMAIL       = 'email';
    public static $VAL_LIMIT       = 'limit';
    public static $VAL_PHONEORMAIL = 'phoneOrMail';
    public static $VAL_PASSWORDCONFIRM = 'passwordEqual';

    public static $TP_TEXT        = 'text';
    public static $TP_HIDDEN      = 'hidden';
    public static $TP_PASSWORD    = 'password';
    public static $TP_TEXTAREA    = 'textarea';
    public static $TP_CHECK       = 'checkbox';
    public static $TP_RADIO       = 'radio';
    public static $TP_LIST        = 'list';
    public static $TP_COMBO       = 'combo';
    public static $TP_PIC         = 'pic';
    public static $TP_VIDEO       = 'video';
    public static $TP_DATE        = 'date';
    public static $TP_CITY        = 'city';
    public static $TP_URL         = 'url';
    public static $TP_DYNAMICATTR = 'attr';
    public static $TP_TAG         = 'tag';
    public static $TP_RULE        = 'rule';
    public static $TP_ATTACHMENT  = 'attachment';

    private static $IMG_UPLOAD_SINGLE_CTRL   = '_uploadImageSingleCtrl';
    private static $IMG_UPLOAD_MULTIPLE_CTRL = '_uploadImageMultipleCtrl';
    private static $IMG_UPLOAD_FORM          = '_uploadImageForm';

    //用户在上传图片之前很可能作了很多修改，有很多已经存在的图片用户在客户端会将其删除
    //需要调用该方法删除那些垃圾图片
    public static function removeUnexistImages($picName)
    {
        $pics     = Input::get($picName);
        $picsfull = Input::get($picName . '_full');
        if (strlen($picsfull) == 0) {
            return;
        }
        $fullarray = explode($picsfull, ';');
        $actarray  = explode($pics, ';');
        foreach ($fullarray as $oneimg) {
            if (strlen($oneimg) > 0 && !in_array($oneimg, $actarray)) {
                PicController::delOneImage($oneimg);
            }
        }
    }
    public static function createOneValidator($validatorName, $params = array())
    {
        $sz = sizeof($params);
        if ($sz > 0) {
            for ($i = 0; $i < $sz; $i++) {
                $validatorName .= '!' . $params[$i];
            }
        }
        return $validatorName;
    }
    /**
     * 初始化一个表单
     * 表单自带 _token, 不需要手动添加
     *
     * @param  $action 提交表单的URL
     * @param  $title  即表单的标题 - legend
     * @return \Illuminate\Http\Response
     */
    public static function form($action, $title = '', $visible = true)
    {
        $csrf_token = csrf_token();
        $display    = ($visible ? '' : 'style="display:none"');
        echo
            '<script type="text/javascript">$.ymFunc.setTitle("'.$title.'")</script>'.
            '<div class="am-g" id="mform_container">
 			<div class=" col-md-8 col-sm-centered">
 				    <form class="am-form" method="post" id="formLogin" action="' . $action . '" ' . $display . '>
        				 <input type="hidden" name="_token" value="' . $csrf_token . '">
        				 <fieldset class="am-form-set">';
        return new CommonPublishController;
    }

    private $hasImageCtrl   = false;
    private $hasDynamicAttr = false;
    private $hasAttachTag   = false;
    private $hasRule        = false;
    private $hasAttachCtrl  = false;
    public function end($submitButtons = '', $btnType = "button")
    {
        $htmlstr = '</fieldset>
		';
        $htmlstr .= '</form></div>';

        $btnArr = array();
        if (is_string($submitButtons)) {
            $btnArr[0] = ['name' => ($submitButtons == '' ? '提交' : $submitButtons), 'func' => '$YN_VALIDATOR.submitForm()'];
        } elseif (is_array($submitButtons)) {
            foreach ($submitButtons as $key => $btn) {
                if (is_array($btn)) {
                    $btnArr[$key]         = array();
                    $btnArr[$key]['name'] = array_key_exists('name', $btn) ? $btn['name'] : '提交';
                    $btnArr[$key]['func'] = array_key_exists('func', $btn) ? $btn['func'] : '$YN_VALIDATOR.submitForm()';
                } else {
                    $btnArr[$key] = ['name' => $btn, 'func' => '$YN_VALIDATOR.submitForm()'];
                }
            }
        }

        $htmlstr .= $this->getBtnHtml($btnArr, $btnType);
        //动态属性
        if ($this->hasDynamicAttr) {
            $htmlstr .= '
			<div class="am-modal am-modal-no-btn" tabindex="-1" id="select-attr-modal">
			  <div class="am-modal-dialog">
			    <div class="am-modal-hd">选择属性
			      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
			    </div>
			    <div class="am-modal-bd" id="select-attr-modal-content"></div>
			  </div>
			</div>
			<div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirm-attr">
			  <div class="am-modal-dialog">
			    <div class="am-modal-hd">确认删除</div>
			    <div class="am-modal-bd" id="my-confirm-attr-content">
			      你，确定要删除这条记录吗？
			    </div>
			    <div class="am-modal-footer">
			      <span class="am-modal-btn" data-am-modal-cancel>取消</span>
			      <span class="am-modal-btn" data-am-modal-confirm>确定</span>
			    </div>
			  </div>
			</div>
			';
        }
        // tags
        if ($this->hasAttachTag) {
            $htmlstr .= '
			<div class="am-modal am-modal-no-btn" tabindex="-1" id="select-tag-modal">
			  <div class="am-modal-dialog">
			    <div class="am-modal-hd">选择标签
			      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
			    </div>
			    <div class="am-modal-bd" id="select-tag-modal-content"></div>
			  </div>
			</div>
			<div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirm-tag">
			  <div class="am-modal-dialog">
			    <div class="am-modal-hd">确认删除</div>
			    <div class="am-modal-bd" id="my-confirm-tag-content">
			      你，确定要删除这条记录吗？
			    </div>
			    <div class="am-modal-footer">
			      <span class="am-modal-btn" data-am-modal-cancel>取消</span>
			      <span class="am-modal-btn" data-am-modal-confirm>确定</span>
			    </div>
			  </div>
			</div>
			'.'<div class="am-modal am-modal-alert" tabindex="-1" id="my-alert" style="width:70%;left:15%;margin-left:0;">
		  <div class="am-modal-dialog">
		    <div class="am-modal-hd">提示</div>
		    <div class="am-modal-bd">

		    </div>
		    <div class="am-modal-footer">
		      <span class="am-modal-btn">确定</span>
		    </div>
		  </div>
		</div>';
        }
        //规则
        if ($this->hasRule) {
            $htmlstr .= '
			<div class="am-modal am-modal-no-btn" tabindex="-1" id="select-rule-modal">
			  <div class="am-modal-dialog">
			    <div class="am-modal-hd">选择规则
			      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
			    </div>
			    <div class="am-modal-bd" id="select-rule-modal-content"></div>
			  </div>
			</div>
			<div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirm-rule">
			  <div class="am-modal-dialog">
			    <div class="am-modal-hd">确认删除</div>
			    <div class="am-modal-bd" id="my-confirm-rule-content">
			      你，确定要删除这条规则吗？
			    </div>
			    <div class="am-modal-footer">
			      <span class="am-modal-btn" data-am-modal-cancel>取消</span>
			      <span class="am-modal-btn" data-am-modal-confirm>确定</span>
			    </div>
			  </div>
			</div>
			';
        }
        $htmlstr .= '</div>
		</div>';
        if ($this->hasImageCtrl) {
            $token = csrf_token();
            $htmlstr .= $this->generateAliOssForm();
        }
        if ($this->hasAttachCtrl) {
            $token = csrf_token();
            $htmlstr .= $this->generateAttachmentAliOssForm();
            $htmlstr .= '<div class="am-modal am-modal-confirm" tabindex="-1" id="my-confirm-attach">
			  <div class="am-modal-dialog">
			    <div class="am-modal-hd">确认删除</div>
			    <div class="am-modal-bd" id="my-confirm-attach-content">
			      你，确定要删除这条规则吗？
			    </div>
			    <div class="am-modal-footer">
			      <span class="am-modal-btn" data-am-modal-cancel>取消</span>
			      <span class="am-modal-btn" data-am-modal-confirm>确定</span>
			    </div>
			  </div>
			</div>';
        }
        $htmlstr .= '</div>';
        echo $htmlstr;
    }
    private function getBtnHtml($btnArr, $btnType)
    {
        $htmlstr = "";
        if ($btnType == 'button') {
            foreach ($btnArr as $key => $button) {
                $htmlstr .= '<button class="am-btn am-btn-warning am-btn-block" style="display:none" onclick ="' . $button['func'] . '">' . $button['name'] . '</button>
                ';}
        } elseif ($btnType == 'navbar') {
            $htmlstr .= '
            <div style="height:2rem" >&nbsp;</div>
           <div data-am-widget="navbar" class="am-navbar am-cf am-navbar-default">
                <ul class="am-navbar-nav am-cf am-avg-sm-1">
               ';
            foreach ($btnArr as $key => $button) {
                $htmlstr .= '<li onclick="' . $button['func'] . '">
                    <a href="javascript:void(0)">
                        <span class="am-navbar-label">' . $button['name'] . '</span>
                    </a>
                </li>';}
            $htmlstr .= '
                </ul>
            </div>';
        }

        return $htmlstr;
    }
    private function addComboAndList($name, $type, $selectables, $selectlabels, $label, $isRequired, $defaultValue, $errorMessage, $editable)
    {
        if (!is_array($selectables)) {
            $selectables = array($selectables);
        }
        if (sizeof($selectlabels) == 0) {
            $selectlabels = $selectables;
        }
        $outputstr = ' <div class="am-form-group">
		';
        $thetype = (($type == self::$TP_COMBO) ? '' : 'multiple');
        $compId  = '';
        if (strlen($label) > 0) {
            $compId = CommonUtils::createRandomId($name);
            $outputstr .= '<label for="' . $compId . '">' . $label . '</label>
			';
        }
        $outputstr .= '<select name="' . $name . '" ' . $thetype . ' class="am-form-field ym-publish-field"';
        if (!$editable) {
            $outputstr .= 'readonly="true" ';
        }
        if (strlen($compId) > 0) {
            $outputstr .= 'id="' . $compId . '" ';
        }
        if (strlen($errorMessage) > 0) {
            $outputstr .= 'errorMessage="' . $errorMessage . '" ';
        }
        if ($isRequired) {
            $outputstr .= 'validate="' . self::$VAL_REQUIRED . '" ';
        }

        $outputstr .= '>
		';
        $count = 0;
        foreach ($selectables as $onesel) {
            $outputstr .= '<option value="' . $onesel . '" ';
            if (in_array($onesel, $defaultValue)) {
                $outputstr .= ' selected ';
            }
            $outputstr .= '>' . $selectlabels[$count] . '</option>
			';
            $count++;
        }
        $outputstr .= '</select>
		</div>
		';
        echo $outputstr;
    }
    public function addSelectCity($name , $label, $provinceId, $cityId){
        $compId = Commonutils::createRandomId($name);
        $outputstr = '<div class="am-form-group"><label for="' . $compId . '">' . $label . '</label>';
        $outputstr .='<input type="hidden" name="province" id="province" value="'.$provinceId.'"/>';
        $outputstr .='<input type="hidden" name="city" id="city" value="'.$cityId.'"/>';
        $outputstr .='<input type="text" name="'.$name.'" id="pro" value=""/>';
        $outputstr .='<select style="display:none" name="citySelect" id="citySelect" class="am-form-field ym-publish-field"> </select>';
        $outputstr .='</div> ';
        echo $outputstr;
    }
    private function addCheckboxAndRatio($name, $type, $selectables, $selectlabels, $label, $isRequired, $defaultValue, $errorMessage, $editable)
    {
        if (!is_array($selectables)) {
            $selectables = array($selectables);
        }

        if (sizeof($selectlabels) == 0) {
            $selectlabels = $label;
        }
        $outputstr = ' <div class="am-form-group">
		';
        $thetype   = (($type == self::$TP_CHECK) ? 'checkbox' : 'radio');
        $idx       = 0;
        $lastlabel = '';
        foreach ($selectables as $onesel) {
            $outputstr .= '<label class="am-' . $thetype . '-inline">
			               <input type="' . $thetype . '" name="' . $name . '"value="' . $onesel . '"  class="ym-publish-field" ';
            if (in_array($onesel, $defaultValue)) {
                $outputstr .= 'checked ';
            }
            if (strlen($errorMessage) > 0) {
                $outputstr .= 'errorMessage="' . $errorMessage . '" ';
            }
            if (!$editable) {
                $outputstr .= 'readonly="true" ';
            }
            if ($isRequired) {
                $outputstr .= 'validate="' . self::$VAL_REQUIRED . '" ';
            }
            $outputstr .= ' >';
            if (is_array($selectlabels)) {
                if ($idx < sizeof($selectlabels)) {
                    $outputstr .= $selectlabels[$idx];
                    $lastlabel = $selectlabels[$idx];
                } else {
                    $outputstr .= $lastlabel;
                }
            } else {
                $outputstr .= $selectlabels;
            }
            $outputstr .= '
			</label>
			';
            $idx++;
        }
        $outputstr .= '</div>
		';
        echo $outputstr;
    }
    private function addInputText($name, $texttype, $validatorStr, $label, $placeholder, $defaultValue, $errorMessage, $editable)
    {
        if ($texttype == 'hidden') {
            $outputstr = '<input type="hidden" name="' . $name . '" ';
            if (strlen($defaultValue) > 0) {
                $outputstr .= 'value="' . $defaultValue . '" ';
            }
            $outputstr .= ' class="am-form-field ym-publish-field" >
				';
            echo $outputstr;
            return;
        }
        $outputstr = '<div class="am-form-group">
			';
        $compId = 0;
        if (strlen($label) > 0) {
            $compId = CommonUtils::createRandomId($name);
            $outputstr .= '<label for="' . $compId . '">' . $label . '</label>
			';
            if ($texttype == self::$TP_TEXTAREA) {
                $outputstr .= '<textarea rows="7" ';
            } else {
                $outputstr .= '<input type="' . $texttype . '" ';
            }
            $outputstr .= 'id="' . $compId . '" ';
        } else {
            if ($texttype == self::$TP_TEXTAREA) {
                $outputstr .= '<textarea rows="5" ';
            } else {
                $outputstr .= '<input type="' . $texttype . '" ';
            }
        }

        if (!$editable) {
            $outputstr .= 'readonly="true" ';
        }
        if (strlen($validatorStr) > 0) {
            $outputstr .= 'validate="' . $validatorStr . '" ';
        }
        if (strlen($placeholder) > 0) {
            $outputstr .= 'placeholder="' . $placeholder . '" ';
        }
        if (strlen($errorMessage) > 0) {
            $outputstr .= 'errorMessage="' . $errorMessage . '" ';
        }
        $outputstr .= 'name="' . $name . '" class="am-form-field ym-publish-field" ';
        if (strlen($defaultValue) > 0) {
            if ($texttype == self::$TP_TEXTAREA) {
                $outputstr .= '>' . $defaultValue . '</textarea>';
            } else {
                $outputstr .= 'value="' . $defaultValue . '" >';
            }
        } else {
            if ($texttype == self::$TP_TEXTAREA) {
                $outputstr .= '></textarea>';
            } else {
                $outputstr .= ' >';
            }
        }
        $outputstr .= '
			</div>
		';

        echo $outputstr;
    }
    private function addUploadImageCtrlSingle($name, $type, $validatorStr, $label, $defaultValue, $errorMessage, $limit)
    {
        $objid = CommonUtils::createRandomId($name);
        $str   = '';
        if (strlen($label) > 0) {
            $str .= '<label>' . $label . '</label>
			';
        }

        $str .= '<div class="ym_img_upload_control_single ym_img_upload_control_single_init">
				';

        $str .= '<div id="' . $objid . '_button"><a href="javascript:doChoose(\'' . $name . '\', ' . $limit . ')">图片</a></div>
				<input type="hidden" name="' . $name . '" id="' . $objid . '" class="ym-publish-field"/>
				<input type="hidden" name="' . $name . '_full" id="' . $objid . '_full" class="ym-publish-field"/>
			<div id="' . $objid . '_del_btn" class="am-icon-trash ym-ft-20  ym-c-green ym_img_tick" style="display:none;"></div>
		</div>';

        $this->hasImageCtrl = true;
        echo $str;
    }
    private function addUploadImageCtrl($name, $type, $validatorStr, $label, $defaultValue, $errorMessage, $limit = 0)
    {

        $objid = CommonUtils::createRandomId($name);
        $str   = '';
        if (strlen($label) > 0) {
            $str .= '<label>' . $label . '</label>
			';
        }
        $count = 0;
        if (!empty($defaultValue)) {
            $valueArr = array_filter(explode(';', $defaultValue));
            $count    = count($valueArr);
        }
        $display = '';
        if($count == $limit){
        	$display = 'display:none;';
        }
        $defaultValue = str_replace('http://umeiii.img-cn-hangzhou.aliyuncs.com/','',$defaultValue);
        $defaultValue1 = strrchr($defaultValue,'@');
        $defaultValue = str_replace($defaultValue1,'',$defaultValue);
        $str .= '
		<div class="am-form-group" id="_container">
			<input type="hidden" name="' . $name . '" id="' . $name . '_value"
			nameSeed="' . CommonUtils::createRandomId($name) . '" nameIndex ="0" maxCount="' . $limit . '" maxSize="0" imgCount="' . $count . '" value="' . $defaultValue . ';"/>
			<ul id="' . $name . '_thumbnaillist" class="am-avg-sm-4 am-thumbnails">';
        if (!empty($defaultValue)) {
            foreach ($valueArr as $k => $v) {
                if ($v) {
                	$v = str_replace('http://umeiii.img-cn-hangzhou.aliyuncs.com/','',$v);
                	$v1 = strrchr($v,'@');
                	$v = str_replace($v1,'',$v);
                    $str .= '<li style="padding-bottom:0"> <img id="'. $name .'_' . $v . '" imgname="' . str_replace('http://umeiii.img-cn-hangzhou.aliyuncs.com/','',$v) . '"
        				class="am-thumbnail ym-flag-image" style="margin-bottom:5px" src="http://umeiii.img-cn-hangzhou.aliyuncs.com/' . $v . '@64h_64w_1e_1c"';

        			$str .= 'onclick="showImageDetail(\''.$name.'\',\'' . $v . '\')"></li>';
                }
            }
        }
        $str .= '<li style="padding-bottom:0">
			 <img id="' . $name . '_addbtn" class="am-thumbnail"  style="margin-bottom:5px;'.$display.'"
			  src="/imgs/addbtn.png" onclick="addImage(\'' . $name . '\')" />
			 </li>
			</ul>
		</div>';

        $this->hasImageCtrl = true;
        echo $str;
    }
    private function addUploadAttachmentCtrl($name, $type, $validatorStr, $label, $defaultValue, $errorMessage, $uploadlimit, $uploadSize)
    {
        $objid = CommonUtils::createRandomId($name);
        $str   = '';
        if (strlen($label) > 0) {
            $str .= '<label>' . $label . '</label>
			';
        }
        $count = 0;
        if (!empty($defaultValue)) {
            $valueArr = explode(';', $defaultValue);
            $count    = count($valueArr);
        }
        $str .= '
		<div class="am-form-group" id="_container">
			<input type="hidden" name="' . $name . '" id="' . $name . '_value"
			nameSeed="' . CommonUtils::createRandomId($name) . '" nameIndex ="0" maxCount="1" attachCount="0" maxSize="' . $uploadSize . '"  value="' . $defaultValue . '"/>
			<input type="hidden" name="image" id="image_value" imgCount="0"
			nameSeed="' . CommonUtils::createRandomId('image') . '" nameIndex ="0" maxCount="' . $uploadlimit . '"  value="' . $defaultValue . '"/>
			<ul id="' . $name . '_thumbnaillist" class="am-avg-sm-4 am-thumbnails">';
        if (!empty($defaultValue)) {
            foreach ($valueArr as $k => $v) {
                if ($v) {
                    $str .= '<li style="padding-bottom:0"> <img id="image_' . $v . '" imgname="' . $v . '"
        				class="am-thumbnail ym-flag-image" style="margin-bottom:5px" src="http://umeiii.img-cn-hangzhou.aliyuncs.com/' . $v . '@64h_64w_1e_1c"
        						onclick="deleteAttach(this)"></li>';
                }
            }
        }
        $str .= '<li style="padding-bottom:0">
			 <img id="' . $name . '_addbtn" class="am-thumbnail"  style="margin-bottom:5px"
			  src="/imgs/addbtn.png" onclick="addAttach(\'' . $name . '\')" />
			 </li>
			</ul>
		</div>';

        $this->hasAttachCtrl = true;
        echo $str;
    }
    private function addDynamicAttr($name, $label)
    {
        $str = '';

        $str .= '
		<div class="am-form-group" id="dynamicAttrContiner"></div>
		<input type="hidden" name="attrsList" id="attrsList" />
		<label onclick="dynamicAttrbtnClick(this)" class="am-btn am-btn-secondary am-radius">添加属性</label>
		';
        if (strlen($label) > 0) {
            $str .= '<label>' . $label . '</label>
			';
        }
        $this->hasDynamicAttr = true;
        echo $str;
    }
    private function addAttachTag($name, $label, $defaultValue, $jsonUrl = '')
    {
        $str = '';
        $str .= '<div class="am-form-group" id="tagContainer" style="margin-bottom:0.2rem" ></div>
			<input type="hidden" name="tagsList" id="tagsList" value="' . $defaultValue . '" />
			<label onclick="attachTagClick(this,\'' . $jsonUrl . '\',\'' . $defaultValue . '\')" class="am-btn am-btn-secondary am-radius">添加标签</label>
		';
        if (strlen($label) > 0) {
            $str .= '<label>' . $label . '</label>
			';
        }
        $this->hasAttachTag = true;
        echo $str;
    }
    private function addRule($name, $label, $defaultValue, $jsonUrl)
    {
        $str = '';
        $str .= '
			<div class="am-form-group" id="ruleContainer" style="margin-bottom:0.2rem" ></div>
			<input type="hidden" name="rulesList" id="rulesList" value="' . $defaultValue . '" />
			<label onclick="ruleClick(this,\'' . $jsonUrl . '\')" class="am-btn am-btn-secondary am-radius am-tenders" id="addRule">'.$label.'</label>
		';
//         if (strlen($label) > 0) {
//             $str .= '<label>' . $label . '</label>
// 			';
//         }
        $this->hasRule = true;
        echo $str;
    }
    public function addComp($arr)
    {
        $this->add($arr['name'],
            CommonUtils::getValueFromArray($arr, 'type', self::$TP_TEXT),
            CommonUtils::getValueFromArray($arr, 'validators', array()),
            CommonUtils::getValueFromArray($arr, 'label'),
            CommonUtils::getValueFromArray($arr, 'placeholder'),
            CommonUtils::getValueFromArray($arr, 'selectlabels', array()),
            CommonUtils::getValueFromArray($arr, 'selectables', array()),
            CommonUtils::getValueFromArray($arr, 'selectitems', null),
            CommonUtils::getValueFromArray($arr, 'defaultValue', array()),
            CommonUtils::getValueFromArray($arr, 'errorMessage'),
            CommonUtils::getValueFromArray($arr, 'imguploadLimit', 0),
            Commonutils::getValueFromArray($arr, 'editable', true),
            Commonutils::getValueFromArray($arr, 'jsonUrl', ''),
            Commonutils::getValueFromArray($arr, 'uploadSize', '10')
        );
    }
    public function addInputDate($name, $type, $label, $defaultValue)
    {
        $compId    = CommonUtils::createRandomId($name);
        $outputstr = '<div class="am-form-group"><label for="' . $compId . '">' . $label . '</label>
			';
        $outputstr .= '<input name="' . $name . '" type="' . $type . '" class="am-form-field ym-publish-field" ';
        $defaultDate = '';
        if (strlen($defaultValue) > 0) {
            $outputstr .= ' value="' . $defaultValue . '"';
        }
        $outputstr .= 'id="' . $compId . '" /></div>';
        echo $outputstr;
    }
    public function addInputUrl($name, $type, $label, $defaultValue, $placeHolder)
    {
        $compId    = CommonUtils::createRandomId($name);
        $outputstr = '<div class="am-form-group"><label for="' . $compId . '">' . $label . '</label>';
        $outputstr .= '<input name="' . $name . '" class="am-form-field ym-publish-field" type="' . $type . '" placeholder="' . $placeHolder . '"';
        $defaultDate = '';
        if (strlen($defaultValue) > 0) {
            $outputstr .= ' value="' . $defaultValue . '"';
        }
        $outputstr .= 'id="' . $compId . '" /></div>';
        echo $outputstr;
    }
    private function add($name, $type = self::TP_TEXT,
        $validators = array(), $label = '', $placeholder = '', $selectlabels = array(),
        $selectables = array(), $selectItems = null, $defaultValue = array(), $errorMessage = '', $imguploadLimit = 0, $editable = true, $jsonUrl = '', $uploadSize = '') {
        $validatorStr = '';
        $szValid      = sizeof($validators);
        if ($szValid > 0) {
            for ($i = 0; $i < $szValid; $i++) {
                $validatorStr .= $validators[$i];
                if ($i < $szValid - 1) {
                    $validatorStr .= ',';
                }
            }
        }
        if (!is_array($defaultValue)) {
            $defaultValue = array($defaultValue);
        }
        switch ($type) {
            case self::$TP_TEXT:
                $this->addInputText($name, 'text', $validatorStr, $label, $placeholder, (sizeof($defaultValue) > 0 ? $defaultValue[0] : ''), $errorMessage, $editable);
                break;
            case self::$TP_HIDDEN:
                $this->addInputText($name, 'hidden', $validatorStr, $label, $placeholder, (sizeof($defaultValue) > 0 ? $defaultValue[0] : ''), $errorMessage, $editable);
                break;
            case self::$TP_PASSWORD:
                $this->addInputText($name, 'password', $validatorStr, $label, $placeholder, (sizeof($defaultValue) > 0 ? $defaultValue[0] : ''), $errorMessage, $editable);
                break;
            case self::$TP_TEXTAREA:
                $this->addInputText($name, $type, $validatorStr, $label, $placeholder, (sizeof($defaultValue) > 0 ? $defaultValue[0] : ''), $errorMessage, $editable);
                break;
            case self::$TP_CHECK:
            case self::$TP_RADIO:
                $this->addCheckboxAndRatio($name, $type, $selectables, $selectlabels, $label, strpos($validatorStr, self::$VAL_REQUIRED) >= 0, $defaultValue, $errorMessage, $editable);
                break;
            case self::$TP_LIST:
            case self::$TP_COMBO:
                if (!is_null($selectItems)) {
                    $selectables  = array();
                    $selectlabels = array();
                    foreach ($selectItems as $key => $value) {
                        array_push($selectables, $key);
                        array_push($selectlabels, $value);
                    }
                }
                $this->addComboAndList($name, $type, $selectables, $selectlabels, $label, strpos($validatorStr, self::$VAL_REQUIRED) >= 0, $defaultValue, $errorMessage, $editable);
                break;
            case self::$TP_PIC:
                $this->addUploadImageCtrl($name, $type, $validatorStr, $label, (sizeof($defaultValue) > 0 ? $defaultValue[0] : ''), $errorMessage, $imguploadLimit);
                break;
            case self::$TP_VIDEO:
                break;
            case self::$TP_DYNAMICATTR:
                $this->addDynamicAttr($name, $label);
                break;
            case self::$TP_TAG:
                $this->addAttachTag($name, $label, (sizeof($defaultValue) > 0 ? $defaultValue[0] : ''), $jsonUrl);
                break;
	    case self::$TP_CITY:
                $this->addSelectCity($name, $label, (sizeof($defaultValue)>0 ? $defaultValue['province'] :''), (sizeof($defaultValue)>1 ? $defaultValue['city'] : ''));
                break;
            case self::$TP_DATE:
                $this->addInputDate($name, $type, $label, (sizeof($defaultValue) > 0 ? $defaultValue[0] : ''));
                break;
            case self::$TP_URL:
                $this->addInputUrl($name, $type, $label, (sizeof($defaultValue) > 0 ? $defaultValue[0] : ''), $placeholder);
                break;
            case self::$TP_RULE:
                $this->addRule($name, $label, (sizeof($defaultValue) > 0 ? $defaultValue[0] : ''), $jsonUrl);
                break;
            case self::$TP_ATTACHMENT:
                $this->addUploadAttachmentCtrl($name, $type, $validatorStr, $label, (sizeof($defaultValue) > 0 ? $defaultValue[0] : ''), $errorMessage, $imguploadLimit, $uploadSize);
                break;
        }
    }
    //生成alioss需要的Form
    private function generateAliOssForm()
    {
        $accessId  = CommonUtils::getAliOssAccessId();
        $policy    = CommonUtils::getAliOSSPostPolicy();
        $signature = Commonutils::getAliOSSSignature($policy);
        $postUrl   = CommonUtils::getAliUrl('post');
        $showUrl   = CommonUtils::getAliUrl('show');
        $domStr    = '';
        $domStr .= '
		<iframe style="display:none" name="hiddenFrame"></iframe>
		<form style="display:none" id="_uploadImageForm" target ="hiddenFrame"
			action= "' . $postUrl . '" method="post" enctype="multipart/form-data" >
		   <input type="hidden" id="objName" />
		    <input type="hidden" value="testfileA.jpg" name="key" />
		    <input type="hidden" value="image/jpeg" name="content-Type" />
			<input type="hidden" value="' . $accessId . '" name="OSSAccessKeyId" />
			<input type="hidden" value="' . $policy . '" name="policy" />
			<input type="hidden" value="' . $signature . '" name="signature" />
			<input type="file" id="_uploadImageFile" onchange="imageChange(this,false)" class="oss-upload-file" name="file" accept="image/*" />
			<input type="hidden" value="" name="success_action_redirect" />
  			<input type="hidden" value="201" name="success_action_status">
		</form>
		<div class="am-modal am-modal-no-btn" tabindex="-1" id="your-modal">
		  <div class="am-modal-dialog" style="position:relative;">
			<a id="delete-img" onclick="deleteName()"><i class="am-icon-remove ym-c-red"></i></a>
		    <div class="am-modal-bd">
		      <img id="showImg" style="width:100%;" src="" />

		    </div>
		  </div>
		</div>
		<script>
			function getImgBaseUrl(imgName)
			{
				return "' . $showUrl . '/"+imgName;
			}
		</script>
		';
        return $domStr;
    }
    
    public function getAliOssForm()
    {
    	$accessId  = CommonUtils::getAliOssAccessId();
    	$policy    = CommonUtils::getAliOSSPostPolicy();
    	$signature = Commonutils::getAliOSSSignature($policy);
    	$postUrl   = CommonUtils::getAliUrl('post');
    	$showUrl   = CommonUtils::getAliUrl('show');
    	$domStr    = '';
    	$domStr .= '
		<iframe style="display:none" name="hiddenFrame"></iframe>
		<form style="display:none" id="_uploadImageForm" target ="hiddenFrame"
			action= "' . $postUrl . '" method="post" enctype="multipart/form-data" >
		   <input type="hidden" id="objName" />
		    <input type="hidden" value="testfileA.jpg" name="key" />
		    <input type="hidden" value="image/jpeg" name="content-Type" />
			<input type="hidden" value="' . $accessId . '" name="OSSAccessKeyId" />
			<input type="hidden" value="' . $policy . '" name="policy" />
			<input type="hidden" value="' . $signature . '" name="signature" />
			<input type="file" id="_uploadImageFile" onchange="imageUpload(this,false)" class="oss-upload-file" name="file" accept="image/*" />
			<input type="hidden" value="" name="success_action_redirect" />
  			<input type="hidden" value="201" name="success_action_status">
		</form>
		<script>
			function getImgBaseUrl(imgName)
			{
				return "' . $showUrl . '/"+imgName;
			}
		</script>
		';
    	return $domStr;
    }
    private function generateAttachmentAliOssForm()
    {
        $accessId  = CommonUtils::getAliOssAccessId();
        $policy    = CommonUtils::getAliOSSPostPolicy();
        $signature = Commonutils::getAliOSSSignature($policy);
        $postUrl   = CommonUtils::getAliUrl('post');
        $showUrl   = CommonUtils::getAliUrl('show');
        $domStr    = '';
        $domStr .= '
		<iframe style="display:none" name="hiddenAttachFrame"></iframe>
		<form style="display:none" id="_uploadAttachForm" target ="hiddenAttachFrame"
			action= "' . $postUrl . '" method="post" enctype="multipart/form-data" >
		   <input type="hidden" id="attactFileName" />
		    <input type="hidden" value="testfileA.jpg" name="key" />
		    <input type="hidden" value="image/jpeg" name="content-Type" />
			<input type="hidden" value="' . $accessId . '" name="OSSAccessKeyId" />
			<input type="hidden" value="' . $policy . '" name="policy" />
			<input type="hidden" value="' . $signature . '" name="signature" />
			<input type="file" id="_uploadAttachFile" onchange="attachChange(this,false)" class="oss-upload-file" name="file" accept="aplication/zip" />
			<input type="hidden" value="" name="success_action_redirect" />
  			<input type="hidden" value="201" name="success_action_status">
		</form>
		<div class="am-modal am-modal-no-btn" tabindex="-1" id="your-modal">
		  <div class="am-modal-dialog" style="position:relative;">
			<a id="delete-img" onclick="deleteAttach()"><i class="am-icon-remove ym-c-red"></i></a>
		    <div class="am-modal-bd">
		      <label id="showFileName"></label>

		    </div>
		  </div>
		</div>
		<script>
			function getAttachUrl(attachName)
			{
				return attachName;
			}
			function getImgBaseUrl(imgName)
			{
				return "' . $showUrl . '/"+imgName;
			}
		</script>
		';
        return $domStr;
    }

}

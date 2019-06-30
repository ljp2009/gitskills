@foreach($certificationlist as $certification)
<div class="am-container">
	<div class="am-g" style="padding-top:1rem;font-size:1.5rem;margin-bottom:1rem">
        <div class="am-u-sm-12">
            {{$certification['skillName']}}
        </div>
    </div>
    <div class="am-g" style="margin-bottom:1rem">
        <div class="am-u-sm-12" style="text-align:left;">
            <label class="am-badge" style="font-size:1.2rem">{{$certification['skillLevelName']}}</label>
            <label class="am-badge" style="font-size:1.2rem">{{$certification['certifiResult']}}</label>
        </div>
    </div>
    <div class="am-g" style="border-top:solid 1px #eee;border-bottom:solid 2px #eee;padding:0.5rem 0">
        <div class="am-u-sm-7" style="color:rgb(255,102,0);font-size:1rem;margin-top:0.2rem">
            <span style="font-size:1.0rem;margin-top:0rem">&nbsp;{{$certification['createdDate']}}</span>
        </div>
        <div class="am-u-sm-5" style="text-align:right;">
            <a class='am-btn am-btn-primary' onclick="editCertifi({{$certification['id']}})"
               style='padding:0.2rem 1rem;font-size:1.2rem;margin-top:-0.2rem '

                @if($certification['status'] ==1 || $certification['result'] == 1)
                    disabled="true"
                @endif
                >
                编辑
            </a>
            <a class='am-btn am-btn-default' onclick="deleteCertifi({{$certification['id']}}, {{$certification['status']}})"
               style='padding:0.2rem 1rem;font-size:1.2rem;margin-top:-0.2rem' 
               @if($certification['status'] ==1)
                    disabled="true"
               @endif
               >
                删除
            </a>
        </div>
    </div>
</div>


@endforeach

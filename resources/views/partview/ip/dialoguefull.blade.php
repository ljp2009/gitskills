@extends('layouts.block')
@section('serverLoad')
<!--经典台词标题部分-->
  <div class="am-container ym-ft-15 ym-c-bblack ym-r-header" >
	  经典台词
	  <a href="##" class="am-fr">
		  <i class="am-icon-angle-right am-icon-sm ym-c-black"></i>
	  </a>
  <hr />		
  </div>
   <div class="ym_lzdiv" viewpath="/ipdialogue/listall/verified/{{$ipid}}/0" style="min-height:4rem"></div>

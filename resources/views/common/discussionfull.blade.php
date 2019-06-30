@extends('layouts.block')
@section('serverLoad')
  <div class="am-g ym-r-header">
  @if ($isshort)
	短评论
  @else
	长评论
  @endif
  </div>
  @if ($isshort)
  <div class="ym_lzdiv" viewpath="/common/discuss/detail/short/{{resourcename}}/{resourceid}/0" style="min-height:4rem"></div>
  @else
	<div class="ym_lzdiv" viewpath="/common/discuss/detail/long/{{resourcename}}/{resourceid}/0" style="min-height:4rem"></div>
  @endif
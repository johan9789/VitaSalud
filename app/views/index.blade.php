@extends('layout')

@section('body')

<br> 

<div id="contenedor" style="height: 600px;">
	{{ Form::radio('radio', '', true, ['id' => 'tab-1', 'class' => 'tab-selector-1']) }}
    <label for="tab-1" class="tab-label-1">{{ HTML::image('assets/img/contenedor/imagen-1.jpg') }}</label>
    {{ Form::radio('radio', '', false, ['id' => 'tab-2', 'class' => 'tab-selector-2']) }}
    <label for="tab-2" class="tab-label-2">{{ HTML::image('assets/img/contenedor/imagen-2.jpg') }}</label>
    {{ Form::radio('radio', '', false, ['id' => 'tab-3', 'class' => 'tab-selector-3']) }}
    <label for="tab-3" class="tab-label-3">{{ HTML::image('assets/img/contenedor/imagen-3.jpg') }}</label>
    {{ Form::radio('radio', '', false, ['id' => 'tab-4', 'class' => 'tab-selector-4']) }}
    <label for="tab-4" class="tab-label-4">{{ HTML::image('assets/img/contenedor/imagen-4.jpg') }}</label>
    {{ Form::radio('radio', '', false, ['id' => 'tab-5', 'class' => 'tab-selector-5']) }}
    <label for="tab-5" class="tab-label-5" style="margin-right:0;">{{ HTML::image('assets/img/contenedor/imagen-5.jpg') }}</label>
    <div class="content">
        <div class="content-1">{{ HTML::image('assets/img/contenedor/imagen-1.jpg') }}</div>
        <div class="content-2">{{ HTML::image('assets/img/contenedor/imagen-2.jpg') }}</div>
        <div class="content-3">{{ HTML::image('assets/img/contenedor/imagen-3.jpg') }}</div>
        <div class="content-4">{{ HTML::image('assets/img/contenedor/imagen-4.jpg') }}</div>
        <div class="content-5">{{ HTML::image('assets/img/contenedor/imagen-5.jpg') }}</div>
    </div>
</div>

<br>

@stop
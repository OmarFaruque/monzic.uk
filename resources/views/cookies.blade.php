@php
    // $user = auth('user')->user();
@endphp
@extends('templates.page')


@push('meta')
    <title>Cookies - {{ config('app.name') }}</title>

@endpush

@push('css')
 {{-- Extra css files here --}}

 
        {{-- Cookies --}}
        <link rel='stylesheet' id='elementor-post-85263458-css' href='/uploads/elementor/css/post-8526345836cd.css?ver={{config('app.version')}}' type='text/css' media='all' />
        
@endpush




@section('content')

<div data-elementor-type="wp-page" data-elementor-id="85263458" class="elementor elementor-85263458" data-elementor-post-type="page">
    <section class="elementor-section elementor-top-section elementor-element elementor-element-5273aa6b elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="5273aa6b" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
    <div class="elementor-container elementor-column-gap-default">
<div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-6098b231" data-id="6098b231" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-38a6e150 elementor-align-center elementor-mobile-align-justify elementor-widget elementor-widget-button" data-id="38a6e150" data-element_type="widget" data-widget_type="button.default">
<div class="elementor-widget-container">
<div class="elementor-button-wrapper">
<a class="elementor-button elementor-button-link elementor-size-xs" href="/privacy-policy">
    <span class="elementor-button-content-wrapper">
                <span class="elementor-button-text">Privacy Policy</span>
</span>
</a>
</div>
</div>
</div>
</div>
</div>
<div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-3c2fd090" data-id="3c2fd090" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-e1959a8 elementor-align-center elementor-mobile-align-justify elementor-widget elementor-widget-button" data-id="e1959a8" data-element_type="widget" data-widget_type="button.default">
<div class="elementor-widget-container">
<div class="elementor-button-wrapper">
<a class="elementor-button elementor-button-link elementor-size-xs" href="/customer-terms-of-business">
    <span class="elementor-button-content-wrapper">
                <span class="elementor-button-text">Customer Terms of Business</span>
</span>
</a>
</div>
</div>
</div>
</div>
</div>
<div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-7e677f8" data-id="7e677f8" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-63020312 elementor-align-center elementor-mobile-align-justify elementor-widget elementor-widget-button" data-id="63020312" data-element_type="widget" data-widget_type="button.default">
<div class="elementor-widget-container">
<div class="elementor-button-wrapper">
<a class="elementor-button elementor-button-link elementor-size-xs" href="#">
    <span class="elementor-button-content-wrapper">
                <span class="elementor-button-text">Cookies</span>
</span>
</a>
</div>
</div>
</div>
</div>
</div>
<div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-38183896" data-id="38183896" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-75f02409 elementor-align-center elementor-mobile-align-justify elementor-widget elementor-widget-button" data-id="75f02409" data-element_type="widget" data-widget_type="button.default">
<div class="elementor-widget-container">
<div class="elementor-button-wrapper">
<a class="elementor-button elementor-button-link elementor-size-xs" href="/website-terms-of-use">
    <span class="elementor-button-content-wrapper">
                <span class="elementor-button-text">Website Terms of Use</span>
</span>
</a>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
<section class="elementor-section elementor-top-section elementor-element elementor-element-83db457 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="83db457" data-element_type="section">
    <div class="elementor-container elementor-column-gap-default">
<div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-51ba411" data-id="51ba411" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-0d898cb elementor-widget elementor-widget-text-editor" data-id="0d898cb" data-element_type="widget" data-widget_type="text-editor.default">
<div class="elementor-widget-container">
<style>/*! elementor - v3.23.0 - 05-08-2024 */
.elementor-widget-text-editor.elementor-drop-cap-view-stacked .elementor-drop-cap{background-color:var(--gtheme-color-dark);color:#fff}.elementor-widget-text-editor.elementor-drop-cap-view-framed .elementor-drop-cap{color:var(--gtheme-color-dark);border:3px solid;background-color:transparent}.elementor-widget-text-editor:not(.elementor-drop-cap-view-default) .elementor-drop-cap{margin-top:8px}.elementor-widget-text-editor:not(.elementor-drop-cap-view-default) .elementor-drop-cap-letter{width:1em;height:1em}.elementor-widget-text-editor .elementor-drop-cap{float:left;text-align:center;line-height:1;font-size:50px}.elementor-widget-text-editor .elementor-drop-cap-letter{display:inline-block}</style>				




{!! $pageContent !!}


</div>
</div>
</div>
</div>
</div>
</section>
</div>




@endsection('content')



@push('js')

{{-- extra jss files here --}}

@endpush

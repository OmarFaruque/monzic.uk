@php
    // $user = auth('user')->user();
@endphp
@extends('templates.page')


@push('meta')
    <title>Customer Terms of Buisness - {{ config('app.name') }}</title>

@endpush

@push('css')
 {{-- Extra css files here --}}

 
        {{-- Customer Terms of Business --}}
        <link rel='stylesheet' id='elementor-post-85263472-css' href='/uploads/elementor/css/post-85263472fad4.css?ver={{config('app.version')}}' type='text/css' media='all' />
        
@endpush




@section('content')
    

<div data-elementor-type="wp-page" data-elementor-id="85263472" class="elementor elementor-85263472" data-elementor-post-type="page">
    <section class="elementor-section elementor-top-section elementor-element elementor-element-5d602214 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="5d602214" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
    <div class="elementor-container elementor-column-gap-default">
<div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-6e13c2fe" data-id="6e13c2fe" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-3b8b8d4e elementor-align-center elementor-mobile-align-justify elementor-widget elementor-widget-button" data-id="3b8b8d4e" data-element_type="widget" data-widget_type="button.default">
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
<div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-6186330e" data-id="6186330e" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-71a0e4ff elementor-align-center elementor-mobile-align-justify elementor-widget elementor-widget-button" data-id="71a0e4ff" data-element_type="widget" data-widget_type="button.default">
<div class="elementor-widget-container">
<div class="elementor-button-wrapper">
<a class="elementor-button elementor-button-link elementor-size-xs" href="#">
    <span class="elementor-button-content-wrapper">
                <span class="elementor-button-text">Customer Terms of Business</span>
</span>
</a>
</div>
</div>
</div>
</div>
</div>
<div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-6bde803c" data-id="6bde803c" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-4004d91c elementor-align-center elementor-mobile-align-justify elementor-widget elementor-widget-button" data-id="4004d91c" data-element_type="widget" data-widget_type="button.default">
<div class="elementor-widget-container">
<div class="elementor-button-wrapper">
<a class="elementor-button elementor-button-link elementor-size-xs" href="/cookies">
    <span class="elementor-button-content-wrapper">
                <span class="elementor-button-text">Cookies</span>
</span>
</a>
</div>
</div>
</div>
</div>
</div>
<div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-5eeb1805" data-id="5eeb1805" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-509146ae elementor-align-center elementor-mobile-align-justify elementor-widget elementor-widget-button" data-id="509146ae" data-element_type="widget" data-widget_type="button.default">
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
<section class="elementor-section elementor-top-section elementor-element elementor-element-a8cff31 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="a8cff31" data-element_type="section">
    <div class="elementor-container elementor-column-gap-default">
<div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-3624390" data-id="3624390" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-44e3fd4 elementor-widget elementor-widget-text-editor" data-id="44e3fd4" data-element_type="widget" data-widget_type="text-editor.default">
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

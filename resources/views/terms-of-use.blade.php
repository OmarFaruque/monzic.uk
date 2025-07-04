@php
    // $user = auth('user')->user();
@endphp
@extends('templates.page')


@push('meta')
    <title>Website Terms of Use - {{ config('app.name') }}</title>

@endpush

@push('css')
 {{-- Extra css files here --}}
 
        {{-- Terms of Use --}}
        <link rel='stylesheet' id='elementor-post-85263453-css' href='/uploads/elementor/css/post-8526345320dd.css?ver={{config('app.version')}}' type='text/css' media='all' />

@endpush




@section('content')


<div data-elementor-type="wp-page" data-elementor-id="85263453" class="elementor elementor-85263453" data-elementor-post-type="page">
    <section class="elementor-section elementor-top-section elementor-element elementor-element-cba9184 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="cba9184" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
    <div class="elementor-container elementor-column-gap-default">
<div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-78b570d" data-id="78b570d" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-63cd302 elementor-align-center elementor-mobile-align-justify elementor-widget elementor-widget-button" data-id="63cd302" data-element_type="widget" data-widget_type="button.default">
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
<div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-73d11f4" data-id="73d11f4" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-4ccf407 elementor-align-center elementor-mobile-align-justify elementor-widget elementor-widget-button" data-id="4ccf407" data-element_type="widget" data-widget_type="button.default">
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
<div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-e65d54b" data-id="e65d54b" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-6ffb094 elementor-align-center elementor-mobile-align-justify elementor-widget elementor-widget-button" data-id="6ffb094" data-element_type="widget" data-widget_type="button.default">
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
<div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-9e38344" data-id="9e38344" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-e4cbd0e elementor-align-center elementor-mobile-align-justify elementor-widget elementor-widget-button" data-id="e4cbd0e" data-element_type="widget" data-widget_type="button.default">
<div class="elementor-widget-container">
<div class="elementor-button-wrapper">
<a class="elementor-button elementor-button-link elementor-size-xs" href="#">
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
<section class="elementor-section elementor-top-section elementor-element elementor-element-aa92b03 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="aa92b03" data-element_type="section">
    <div class="elementor-container elementor-column-gap-default">
<div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-6878388" data-id="6878388" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-8783a7d elementor-widget elementor-widget-text-editor" data-id="8783a7d" data-element_type="widget" data-widget_type="text-editor.default">
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

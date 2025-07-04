@php
    // $user = auth('user')->user();
@endphp
@extends('templates.page')


@push('meta')
    <title>Privacy Policy - {{ config('app.name') }}</title>

@endpush

@push('css')
 {{-- Extra css files here --}}

 
        {{-- Privacy Policy --}}
        <link rel='stylesheet' id='elementor-post-3-css' href='/uploads/elementor/css/post-30b43.css?ver={{config('app.version')}}' type='text/css' media='all' />
        
@endpush




@section('content')
    


<div data-elementor-type="wp-page" data-elementor-id="3" class="elementor elementor-3" data-elementor-post-type="page">
    <section class="elementor-section elementor-top-section elementor-element elementor-element-5a8ec00e elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="5a8ec00e" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
    <div class="elementor-container elementor-column-gap-default">
<div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-7918be13" data-id="7918be13" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-4b9afc78 elementor-align-center elementor-mobile-align-justify elementor-widget elementor-widget-button" data-id="4b9afc78" data-element_type="widget" data-widget_type="button.default">
<div class="elementor-widget-container">
<div class="elementor-button-wrapper">
<a class="elementor-button elementor-button-link elementor-size-xs" href="#">
    <span class="elementor-button-content-wrapper">
                <span class="elementor-button-text">Privacy Policy</span>
</span>
</a>
</div>
</div>
</div>
</div>
</div>
<div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-5a7d98f5" data-id="5a7d98f5" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-7101c962 elementor-align-center elementor-mobile-align-justify elementor-widget elementor-widget-button" data-id="7101c962" data-element_type="widget" data-widget_type="button.default">
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
<div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-23f3e6bc" data-id="23f3e6bc" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-133d1101 elementor-align-center elementor-mobile-align-justify elementor-widget elementor-widget-button" data-id="133d1101" data-element_type="widget" data-widget_type="button.default">
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
<div class="elementor-column elementor-col-25 elementor-top-column elementor-element elementor-element-627c6223" data-id="627c6223" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-6d64606f elementor-align-center elementor-mobile-align-justify elementor-widget elementor-widget-button" data-id="6d64606f" data-element_type="widget" data-widget_type="button.default">
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
<section class="elementor-section elementor-top-section elementor-element elementor-element-13708306 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="13708306" data-element_type="section">
    <div class="elementor-container elementor-column-gap-default">
<div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-7e579b03" data-id="7e579b03" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-64341bb5 elementor-widget elementor-widget-text-editor" data-id="64341bb5" data-element_type="widget" data-widget_type="text-editor.default">
<div class="elementor-widget-container">
<style>/*! elementor - v3.23.0 - 05-08-2024 */
.elementor-widget-text-editor.elementor-drop-cap-view-stacked .elementor-drop-cap{background-color:var(--gtheme-color-dark);color:#fff}.elementor-widget-text-editor.elementor-drop-cap-view-framed .elementor-drop-cap{color:var(--gtheme-color-dark);border:3px solid;background-color:transparent}.elementor-widget-text-editor:not(.elementor-drop-cap-view-default) .elementor-drop-cap{margin-top:8px}.elementor-widget-text-editor:not(.elementor-drop-cap-view-default) .elementor-drop-cap-letter{width:1em;height:1em}.elementor-widget-text-editor .elementor-drop-cap{float:left;text-align:center;line-height:1;font-size:50px}.elementor-widget-text-editor .elementor-drop-cap-letter{display:inline-block}</style>				<p></p>



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

@php
    // $user = auth('user')->user();
@endphp
@extends('templates.page')


@push('meta')
    <title>{{ config('app.name') }}</title>

@endpush

@push('css')
 {{-- Extra css files here --}}
    
     {{-- HOME PAGE --}}

     <link rel='stylesheet'   href='/plugins/elementor/assets/css/frontend-lite.mind5d5.css?ver={{config('app.version')}}' type='text/css' media='all' />

     <link rel='stylesheet'   href='/uploads/elementor/css/global5eb8.css?ver=1730215372' type='text/css' media='all' />


     <link rel='stylesheet' id='elementor-post-85265199-css'
     href='/uploads/elementor/css/post-852651995eb8.css?ver=1730215372' type='text/css' media='all' />


     <style>
        .top_container{
            padding: 0px !important;
            max-width: 100% !important;max-width: ;
        }
        .home_strip_1{
            font-family: "Cabin", Sans-serif;
            font-size: 16px;
            font-weight: 500;
            fill: #FFFFFF;
            color: #FFFFFF;
            background-color: #328C9C;
            border-radius: 8px 8px 8px 8px;
            padding: 15px 15px 15px 15px;
        }
        .home_strip_2{
            font-family: "Cabin", Sans-serif;
            font-size: 16px;
            font-weight: 500;
            fill: #FFFFFF;
            color: #FFFFFF;
            background-color: var(--gtheme-color-dark);
            border-radius: 8px 8px 8px 8px;
            padding: 15px 15px 15px 15px;
        }
     </style>
     <style>
    @media (max-width: 430px) {
        #Registration {
            font-size: 20px !important;
            padding-right: 10px !important;
        }
    }
</style>

@endpush




@section('content')
    
<div data-elementor-type="wp-page" data-elementor-id="85265199" class="elementor elementor-85265199" data-elementor-post-type="page" >
	
<section class="elementor-section elementor-top-section elementor-element elementor-element-6d9d4e8 elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="6d9d4e8" data-element_type="section" data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;,&quot;background_background&quot;:&quot;classic&quot;}">
    <div class="elementor-container elementor-column-gap-default">
<div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-fd52522" data-id="fd52522" data-element_type="column">
<div class="elementor-widget-wrap">
        </div>
</div>
<div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-93144e0" data-id="93144e0" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-d3d7d57 elementor-widget elementor-widget-heading" data-id="d3d7d57" data-element_type="widget" data-widget_type="heading.default">
<div class="elementor-widget-container">
<style>/*! elementor - v3.23.0 - 05-08-2024 */
.elementor-heading-title{padding:0;margin:0;line-height:1}.elementor-widget-heading .elementor-heading-title[class*=elementor-size-]>a{color:inherit;font-size:inherit;line-height:inherit}.elementor-widget-heading .elementor-heading-title.elementor-size-small{font-size:15px}.elementor-widget-heading .elementor-heading-title.elementor-size-medium{font-size:19px}.elementor-widget-heading .elementor-heading-title.elementor-size-large{font-size:29px}.elementor-widget-heading .elementor-heading-title.elementor-size-xl{font-size:39px}.elementor-widget-heading .elementor-heading-title.elementor-size-xxl{font-size:59px}</style><h3 class="elementor-heading-title elementor-size-default">{{ $pagstn['home_title_top_text'] }}</h3>		</div>
</div>
<div class="elementor-element elementor-element-33c5489 elementor-hidden-mobile elementor-widget elementor-widget-heading" data-id="33c5489" data-element_type="widget" data-widget_type="heading.default">
<div class="elementor-widget-container">
<h2 class="elementor-heading-title elementor-size-default">{{ $pagstn['home_title_bottom_text'] }}</h2>		</div>
</div>
</div>
</div>
<div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-478a60e" data-id="478a60e" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-94c6403 elementor-hidden-desktop elementor-hidden-tablet elementor-widget elementor-widget-heading" data-id="94c6403" data-element_type="widget" data-widget_type="heading.default">
<div class="elementor-widget-container">
<h4 class="elementor-heading-title elementor-size-default">{{ $pagstn['home_title_bottom_text'] }}</h4>		</div>
</div>
</div>
</div>
</div>
</section>
<section class="elementor-section elementor-top-section elementor-element elementor-element-345a5a2 elementor-section-stretched elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="345a5a2" data-element_type="section" data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;,&quot;background_background&quot;:&quot;classic&quot;}">
    <div class="elementor-container elementor-column-gap-default">
<div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-bc96db8" data-id="bc96db8" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-42029da elementor-hidden-desktop elementor-hidden-tablet elementor-widget elementor-widget-heading" data-id="42029da" data-element_type="widget" data-widget_type="heading.default">
<div class="elementor-widget-container">
{{-- <h5 class="elementor-heading-title elementor-size-default"></h5>		 --}}
</div>
</div>
</div>
</div>
<div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-e0588ed" data-id="e0588ed" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-3265752 elementor-widget elementor-widget-html" data-id="3265752" data-element_type="widget" data-widget_type="html.default">
<div class="elementor-widget-container">
<form id="homeform" method="get" action="/order/get-quote/" onsubmit="validateRegNumber(event)">
<div class="reg-wrapper">


<div class="gb-icon">GB</div>
<label for="Registration" class="visuallyhidden" aria-label="Registration">Registration</label>
<input id="Registration" autocomplete="off" placeholder="ENTER HERE" name="reg_no" required type="text" maxlength="8" autocorrect="off" value="">
</div>
<input type="hidden" name="cstm_hour" />
<input type="hidden" name="cstm_day" />
</form>		</div>
</div>
<div class="elementor-element elementor-element-4ee47c6 elementor-align-center elementor-widget-mobile__width-inherit elementor-widget elementor-widget-button" data-id="4ee47c6" data-element_type="widget" data-widget_type="button.default">
<div class="elementor-widget-container">
<div class="elementor-button-wrapper">
<a class="elementor-button elementor-button-link elementor-size-md" href="#" id="homeformbtn">
    <span class="elementor-button-content-wrapper">
                <span class="elementor-button-text">{{ $pagstn['home_submit'] }}</span>
</span>
</a>
</div>
</div>
</div>
</div>
</div>
<div class="elementor-column elementor-col-33 elementor-top-column elementor-element elementor-element-05f51de" data-id="05f51de" data-element_type="column">
<div class="elementor-widget-wrap">
        </div>
</div>
</div>
</section>
<section class="elementor-section elementor-top-section elementor-element elementor-element-e951831 elementor-section-stretched elementor-section-full_width elementor-section-height-default elementor-section-height-default" data-id="e951831" data-element_type="section" data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;,&quot;background_background&quot;:&quot;classic&quot;}">
        <div class="elementor-background-overlay"></div>
        <div class="elementor-container elementor-column-gap-default">
<div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-143f022" data-id="143f022" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <section class="elementor-section elementor-inner-section elementor-element elementor-element-94637f3 elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="94637f3" data-element_type="section">
    <div class="elementor-container elementor-column-gap-default">
<div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-319d845" data-id="319d845" data-element_type="column" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
<div class="elementor-widget-wrap elementor-element-populated">
<div class="elementor-background-overlay"></div>
    <div class="elementor-element elementor-element-3b3e478 elementor-widget__width-initial elementor-widget elementor-widget-heading" data-id="3b3e478" data-element_type="widget" data-widget_type="heading.default">
<div class="elementor-widget-container">
<h3 class="elementor-heading-title elementor-size-default">{{ $pagstn['home_second_box_header'] }}</h3>		</div>
</div>
<div class="elementor-element elementor-element-b10122a elementor-widget__width-initial elementor-widget elementor-widget-text-editor" data-id="b10122a" data-element_type="widget" data-widget_type="text-editor.default">
<div class="elementor-widget-container">
<style>/*! elementor - v3.23.0 - 05-08-2024 */
.elementor-widget-text-editor.elementor-drop-cap-view-stacked .elementor-drop-cap{background-color:var(--gtheme-color-dark);color:#fff}.elementor-widget-text-editor.elementor-drop-cap-view-framed .elementor-drop-cap{color:var(--gtheme-color-dark);border:3px solid;background-color:transparent}.elementor-widget-text-editor:not(.elementor-drop-cap-view-default) .elementor-drop-cap{margin-top:8px}.elementor-widget-text-editor:not(.elementor-drop-cap-view-default) .elementor-drop-cap-letter{width:1em;height:1em}.elementor-widget-text-editor .elementor-drop-cap{float:left;text-align:center;line-height:1;font-size:50px}.elementor-widget-text-editor .elementor-drop-cap-letter{display:inline-block}</style>				<p><span style="/color: #364344;">{{ $pagstn['home_second_box_text'] }}</span></p>						</div>
</div>
<div class="elementor-element elementor-element-b59790d elementor-icon-list--layout-traditional elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list" data-id="b59790d" data-element_type="widget" data-widget_type="icon-list.default">
<div class="elementor-widget-container">
<link rel="stylesheet" href="/plugins/elementor/assets/css/widget-icon-list.min.css?ver={{config('app.version')}}">		<ul class="elementor-icon-list-items">
    
    @php
        $bullets = explode("\n", $pagstn['home_second_box_bullet']);
    @endphp
    @foreach($bullets as $bullet)
    @if(!empty(trim($bullet)))

        <li class="elementor-icon-list-item">
            <span class="elementor-icon-list-icon">
        <i aria-hidden="true" class="fas fa-check-circle"></i>						</span>
        <span class="elementor-icon-list-text">{{ trim($bullet)}}</span>
        </li>

    @endif
    @endforeach

    </ul>
</div>
</div>
<div class="elementor-element elementor-element-cb184d4 elementor-align-center elementor-widget__width-initial elementor-widget elementor-widget-button" data-id="cb184d4" data-element_type="widget" data-widget_type="button.default">
<div class="elementor-widget-container">
<div class="elementor-button-wrapper">
<a class="elementor-button elementor-button-link elementor-size-sm " href="order/get-quote" id="button-learner">
    <span class="elementor-button-content-wrapper">
                <span class="elementor-button-text">{{ $pagstn['home_second_box_btn'] }}</span>
</span>
</a>
</div>
</div>
</div>
</div>
</div>
<div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-4ca1cbc" data-id="4ca1cbc" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-c060ee9 elementor-hidden-mobile elementor-widget elementor-widget-image" data-id="c060ee9" data-element_type="widget" data-widget_type="image.default">
<div class="elementor-widget-container">
<style>/*! elementor - v3.23.0 - 05-08-2024 */
.elementor-widget-image{text-align:center}.elementor-widget-image a{display:inline-block}.elementor-widget-image a img[src$=".svg"]{width:48px}.elementor-widget-image img{vertical-align:middle;display:inline-block}</style>										<img  width="500" height="500" src="/img/theme-transparent.png" class="attachment-large size-large wp-image-85278134" alt=""  sizes="(max-width: 500px) 100vw, 500px" />													</div>
</div>
</div>
</div>
</div>
</section>
</div>
</div>
</div>
</section>
<section class="elementor-section elementor-top-section elementor-element elementor-element-61d5739 elementor-section-full_width elementor-section-stretched elementor-section-height-default elementor-section-height-default" data-id="61d5739" data-element_type="section" data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;}">
    <div class="elementor-container elementor-column-gap-default">
<div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-24bcd8f" data-id="24bcd8f" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <section class="elementor-section elementor-inner-section elementor-element elementor-element-6cb2379 elementor-reverse-mobile elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="6cb2379" data-element_type="section">
    <div class="elementor-container elementor-column-gap-default">
<div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-2eb9dd0" data-id="2eb9dd0" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-4dc9ad2 elementor-hidden-mobile elementor-widget elementor-widget-image" data-id="4dc9ad2" data-element_type="widget" data-widget_type="image.default">
<div class="elementor-widget-container">
                                <img  width="643" height="554" src="/uploads/2023/09/icon5.png" class="attachment-large size-large wp-image-85263549" alt=""  sizes="(max-width: 643px) 100vw, 643px" />													</div>
</div>
</div>
</div>
<div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-f3aaba3" data-id="f3aaba3" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-527db00 elementor-widget__width-initial elementor-widget elementor-widget-heading" data-id="527db00" data-element_type="widget" data-widget_type="heading.default">
<div class="elementor-widget-container">
<h3 class="elementor-heading-title elementor-size-default"><span class="background">{{ $pagstn['car_box1_header'] }}</span></h3>		</div>
</div>
<div class="elementor-element elementor-element-a696d6d elementor-widget__width-initial elementor-widget elementor-widget-text-editor" data-id="a696d6d" data-element_type="widget" data-widget_type="text-editor.default">
<div class="elementor-widget-container">
        <p>{{ $pagstn['car_box1_text'] }}</p>						</div>
</div>
<div class="elementor-element elementor-element-3d0248c elementor-align-center elementor-tablet-align-right elementor-widget__width-initial elementor-widget elementor-widget-button" data-id="3d0248c" data-element_type="widget" data-widget_type="button.default">
<div class="elementor-widget-container">
<div class="elementor-button-wrapper">
<a class="elementor-button elementor-button-link elementor-size-sm" href="order/get-quote" id="button-temporary">
    <span class="elementor-button-content-wrapper">
                <span class="elementor-button-text">{{ $pagstn['car_box1_btn'] }}</span>
</span>
</a>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
<section class="elementor-section elementor-inner-section elementor-element elementor-element-ab6dcfb elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="ab6dcfb" data-element_type="section">
    <div class="elementor-container elementor-column-gap-default">
<div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-b963d5f" data-id="b963d5f" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-c8bc30f elementor-widget__width-initial elementor-widget elementor-widget-heading" data-id="c8bc30f" data-element_type="widget" data-widget_type="heading.default">
<div class="elementor-widget-container">
<h3 class="elementor-heading-title elementor-size-default"><span class="background">{{ $pagstn['car_box2_header'] }}</span></h3>		</div>
</div>
<div class="elementor-element elementor-element-9bed63d elementor-widget__width-initial elementor-widget elementor-widget-text-editor" data-id="9bed63d" data-element_type="widget" data-widget_type="text-editor.default">
<div class="elementor-widget-container">
        <div class="flex max-w-full flex-col flex-grow"><div class="min-h-8 text-message flex w-full flex-col items-end gap-2 whitespace-normal break-words [.text-message+&amp;]:mt-5" dir="auto" data-message-author-role="assistant" data-message-id="0bfd850d-810c-4165-a4e3-d1062e7e5934" data-message-model-slug="gpt-4o"><div class="flex w-full flex-col gap-1 empty:hidden first:pt-[3px]"><div class="markdown prose w-full break-words dark:prose-invert light"><p>{{ $pagstn['car_box2_text'] }}</p></div></div></div></div>						</div>
</div>
<div class="elementor-element elementor-element-2b1136f elementor-align-center elementor-tablet-align-right elementor-widget__width-initial elementor-widget elementor-widget-button" data-id="2b1136f" data-element_type="widget" data-widget_type="button.default">
<div class="elementor-widget-container">
<div class="elementor-button-wrapper">
<a class="elementor-button elementor-button-link elementor-size-sm" href="order/get-quote" id="button-temporary">
    <span class="elementor-button-content-wrapper">
                <span class="elementor-button-text">{{ $pagstn['car_box2_btn'] }}</span>
</span>
</a>
</div>
</div>
</div>
</div>
</div>
<div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-4a39c75" data-id="4a39c75" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-0b5f89d elementor-hidden-mobile elementor-widget elementor-widget-image" data-id="0b5f89d" data-element_type="widget" data-widget_type="image.default">
<div class="elementor-widget-container">
                                <img   width="643" height="554" src="/uploads/2023/09/icon6.png" class="attachment-large size-large wp-image-85263550" alt=""  sizes="(max-width: 643px) 100vw, 643px" />													</div>
</div>
</div>
</div>
</div>
</section>
</div>
</div>
</div>
</section>



<section class="elementor-section elementor-top-section elementor-element elementor-element-6cd4647 elementor-section-stretched elementor-section-full_width elementor-section-height-default elementor-section-height-default" data-id="6cd4647" data-element_type="section" data-settings="{&quot;stretch_section&quot;:&quot;section-stretched&quot;,&quot;background_background&quot;:&quot;classic&quot;}">
        <div class="elementor-background-overlay"></div>
        <div class="elementor-container elementor-column-gap-default">
<div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-11f26f5" data-id="11f26f5" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <section class="elementor-section elementor-inner-section elementor-element elementor-element-af4f63a elementor-section-content-middle elementor-section-boxed elementor-section-height-default elementor-section-height-default" data-id="af4f63a" data-element_type="section">
    <div class="elementor-container elementor-column-gap-default">
<div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-1121a38" data-id="1121a38" data-element_type="column">
<div class="elementor-widget-wrap elementor-element-populated">
    <div class="elementor-element elementor-element-04c264a elementor-widget elementor-widget-heading" data-id="04c264a" data-element_type="widget" data-widget_type="heading.default">
<div class="elementor-widget-container">
<h4 class="elementor-heading-title elementor-size-default">
    </h4>		</div>
</div>
<div class="elementor-element elementor-element-8155e4c elementor-widget elementor-widget-text-editor" data-id="8155e4c" data-element_type="widget" data-widget_type="text-editor.default">
<div class="elementor-widget-container">
        <p>
        {{-- HERE     --}}
        </p>						</div>
</div>
</div>
</div>
<div class="elementor-column elementor-col-50 elementor-inner-column elementor-element elementor-element-a48c3d0" data-id="a48c3d0" data-element_type="column">
<div class="elementor-widget-wrap">
        </div>
</div>
</div>
</section>
</div>
</div>
</div>
</section>



</div>
</div>
</div>
</section>

</div>

@if($show_home_notice == "yes" && ($choosen_page_notice == "home" || $choosen_page_notice == "both"))
<!-- Modal -->
<div class="modal fade" id="noticeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="noticeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content" style="border-color: var(--gtheme-color)">
        <div class="modal-header">
          <h5 class="modal-title" id="noticeModalLabel">Important Notice</h5>
        </div>
        <div class="modal-body">

            {!! $home_notice !!}
        </div>
        <div class="modal-footer">
          <button id="closeNoticeBtn" type="button" class="btn btn-primary">Close</button>
        </div>
      </div>
    </div>
  </div>
@endif

  

@endsection('content')



@push('js')

{{-- extra jss files here --}}

<script src="/plugins/elementor/assets/js/accordion.8799675460c73eb48972.bundle.min.js?ver={{config('app.version')}}"></script>
<script src="/plugins/elementor/assets/js/frontend.mind5d5.js?ver={{config('app.version')}}"></script>

<script>

@if($show_home_notice == "yes" && ($choosen_page_notice == "home" || $choosen_page_notice == "both"))
document.addEventListener("DOMContentLoaded", function () {
    const modal = new bootstrap.Modal(document.getElementById('noticeModal'));
    const closeBtn = document.getElementById('closeNoticeBtn');

    const noticeKey = 'noticeDismissedAt';
    const durationHours = 2; // Change to 24 for once a day

    function shouldShowNotice() {
        const dismissedAt = localStorage.getItem(noticeKey);
        if (!dismissedAt) return true;


        const dismissedTime = new Date(dismissedAt);
        const now = new Date();
        const diffInMs = now - dismissedTime;
        const diffInHours = diffInMs / (1000 * 60 * 60);
        return diffInHours >= durationHours;
    }

    if (shouldShowNotice()) {
        modal.show();
    }

    closeBtn.addEventListener('click', function () {
        localStorage.setItem(noticeKey, new Date().toISOString());
        modal.hide();
    });
});
@endif

</script>


@endpush

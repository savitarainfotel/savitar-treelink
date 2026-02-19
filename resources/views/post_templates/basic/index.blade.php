@extends('post_templates.layout')
@section('content')

    <div class="bg-animation basic-bg-animation">
        <div class="min-vh-100 bg-white d-flex justify-content-center align-items-center">
            <div class="post-container quick-bio-wrapper position-relative animated fadeIn">

                <div class="position-relative p-3">
                    @if(@$postOptions->cover_image)
                        <svg width="0" height="0" class="d-block">
                            <defs>
                                <clipPath id="myCurve" clipPathUnits="objectBoundingBox">
                                    <path d="M 0,1 L 0,0 L 1,0 L 1,1 C .65 .8, .35 .8, 0 1 Z"></path>
                                </clipPath>
                            </defs>
                        </svg>
                        <div class="bio-cover-image-wrapper wow fadeInDown">
                            <img alt="Cover" src="{{ asset('storage/post/logo/'.$postOptions->cover_image) }}">
                        </div>
                    @endif

                    <div
                        class="d-flex position-absolute pb-0 sm-pb-24 justify-content-between {{ (@$postOptions->bio_share) ? "" : "invisible" }}">
                        <button class="button icon-group bg-white text-dark-1 dropdown-toggle size-35 shadow-3"
                                type="button"
                                data-bs-toggle="modal" data-bs-target="#share_social">
                            <i class="fa-regular fa-up-right-from-square font-14"></i>
                        </button>
                    </div>
                    <div class="bio-picture-wrapper bg-white">
                        <div class="bio-link-image mx-auto wow bounceIn" data-wow-delay="50ms">
                            <img class="" src="{{ asset('storage/post/logo/'.$post->image) }}" alt="{{ $post->title }}"
                                 role="presentation">
                        </div>
                    </div>

                    <h1 class="bio-title font-22 mt-16 text-center fw-bold wow fadeInDown"
                        data-wow-delay="200ms">{{ $post->title }}</h1>
                    <p class="bio-name font-18 mt-12 lh-lg text-center wow fadeInDown"
                       data-wow-delay="300ms">{{ $post->content }}</p>

                    @if ($bioLinks->count() > 0)
                        @php
                            $i = 0;
                            $limit = $post->user->plan_settings->biolink_limit;
                        @endphp
                        <div class="mt-24">
                        @foreach ($bioLinks as $bioLink)
                            @if ($bioLink->type == "header")
                                <!--HEADER-->
                                    <div
                                        class="quick-bio-link-heading mb-16 mt-32 text-center font-16 fw-bold wow fadeInDown"
                                        data-wow-delay="{{ 400 * $i }}ms">{{ $bioLink->settings->title }}</div>
                            @endif

                                @if ($bioLink->type == "link" && ($limit == -1 || $i < $limit))
                                <!--LINKS-->
                                    <div
                                        class="quick-bio-item wow fadeInDown @if ($bioLink->settings->highlight == 1) shake @endif"
                                        data-wow-delay="{{ 400 * $i }}ms">
                                        <a href="{{ $bioLink->settings->url }}"
                                           class="item-wrapper bg-white -outlined text-dark-1 shadow-3">
                                            @if ($bioLink->settings->logo != "")
                                                <div class="quick-bio-link-image">
                                                    <img
                                                        src="{{ asset('storage/post/biolink/'.$bioLink->settings->logo) }}"
                                                        alt="{{ $bioLink->settings->title }}">
                                                </div>
                                            @endif
                                            <div class="text-center">{{ $bioLink->settings->title }}</div>
                                        </a>
                                    </div>
                                    @php
                                        $i++;
                                    @endphp
                                @endif
                            @endforeach
                        </div>

                        <div class="d-flex align-items-center justify-content-center flex-wrap mt-32 pt-16">
                            @foreach ($bioLinks as $bioLink)
                                @if ($bioLink->type == "social")
                                    <div class="quick-social-icon mx-10 mb-10">
                                        @if ($bioLink->settings->title == "email")
                                            <a href="mailto:{{ $bioLink->settings->url }}"
                                               class="icon-group -outlined -light shadow-3 rounded-3 wow fadeInDown"
                                               data-wow-delay="{{ 400 * $i }}ms"><i
                                                    class="fa-regular fa-envelope"></i></a>
                                        @elseif ($bioLink->settings->title == "phone")
                                            <a href="tel:{{ $bioLink->settings->url }}"
                                               class="icon-group -outlined -light shadow-3 rounded-3 wow fadeInDown"
                                               data-wow-delay="{{ 400 * $i }}ms"><i
                                                    class="fa-regular fa-phone"></i></a>
                                        @elseif ($bioLink->settings->title == "whatsapp")
                                            <a href="https://api.whatsapp.com/send?phone={{ $bioLink->settings->url }}"
                                               class="icon-group -outlined -light shadow-3 rounded-3 wow fadeInDown"
                                               data-wow-delay="{{ 400 * $i }}ms"><i
                                                    class="fab fa-whatsapp"></i></a>
                                        @elseif ($bioLink->settings->title == "telegram")
                                            <a href="https://telegram.me/{{ $bioLink->settings->url }}"
                                               class="icon-group -outlined -light shadow-3 rounded-3 wow fadeInDown"
                                               data-wow-delay="{{ 400 * $i }}ms"><i
                                                    class="fab fa-telegram"></i></a>
                                        @elseif ($bioLink->settings->title == "threads")
                                            <a href="{{ $bioLink->settings->url }}"
                                               class="icon-group -outlined -light shadow-3 rounded-3 wow fadeInDown"
                                               data-wow-delay="{{ 400 * $i }}ms"><svg fill="currentColor"
                                                                                      viewBox="0 0 512 512"
                                                                                      style="fill: currentcolor; height: 1em; overflow: visible; width: 1em;"><path
                                                        d="M331.5 235.7c2.2 .9 4.2 1.9 6.3 2.8c29.2 14.1 50.6 35.2 61.8 61.4c15.7 36.5 17.2 95.8-30.3 143.2c-36.2 36.2-80.3 52.5-142.6 53h-.3c-70.2-.5-124.1-24.1-160.4-70.2c-32.3-41-48.9-98.1-49.5-169.6V256v-.2C17 184.3 33.6 127.2 65.9 86.2C102.2 40.1 156.2 16.5 226.4 16h.3c70.3 .5 124.9 24 162.3 69.9c18.4 22.7 32 50 40.6 81.7l-40.4 10.8c-7.1-25.8-17.8-47.8-32.2-65.4c-29.2-35.8-73-54.2-130.5-54.6c-57 .5-100.1 18.8-128.2 54.4C72.1 146.1 58.5 194.3 58 256c.5 61.7 14.1 109.9 40.3 143.3c28 35.6 71.2 53.9 128.2 54.4c51.4-.4 85.4-12.6 113.7-40.9c32.3-32.2 31.7-71.8 21.4-95.9c-6.1-14.2-17.1-26-31.9-34.9c-3.7 26.9-11.8 48.3-24.7 64.8c-17.1 21.8-41.4 33.6-72.7 35.3c-23.6 1.3-46.3-4.4-63.9-16c-20.8-13.8-33-34.8-34.3-59.3c-2.5-48.3 35.7-83 95.2-86.4c21.1-1.2 40.9-.3 59.2 2.8c-2.4-14.8-7.3-26.6-14.6-35.2c-10-11.7-25.6-17.7-46.2-17.8H227c-16.6 0-39 4.6-53.3 26.3l-34.4-23.6c19.2-29.1 50.3-45.1 87.8-45.1h.8c62.6 .4 99.9 39.5 103.7 107.7l-.2 .2zm-156 68.8c1.3 25.1 28.4 36.8 54.6 35.3c25.6-1.4 54.6-11.4 59.5-73.2c-13.2-2.9-27.8-4.4-43.4-4.4c-4.8 0-9.6 .1-14.4 .4c-42.9 2.4-57.2 23.2-56.2 41.8l-.1 .1z"/></svg></a>
                                        @elseif ($bioLink->settings->title == "x")
                                            <a href="{{ $bioLink->settings->url }}"
                                               class="icon-group -outlined -light shadow-3 rounded-3 wow fadeInDown"
                                               data-wow-delay="{{ 400 * $i }}ms"><svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"
                                                                                      style="fill: currentcolor; height: 1em; overflow: visible; width: 1em;"><path
                                                        d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z"/></svg></a>
                                        @else
                                            <a href="{{ $bioLink->settings->url }}"
                                               class="icon-group -outlined -light shadow-3 rounded-3 wow fadeInDown"
                                               data-wow-delay="{{ 400 * $i }}ms"><i
                                                    class="fa-brands fa-{{ $bioLink->settings->title }}"></i></a>
                                        @endif

                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
                @php
                    $show = true;
                    if($post->user->plan_settings->hide_branding){
                        $show = (@$postOptions->bio_credit) ? true : false;
                    }
                @endphp
                @if($show)
                    <div class="quick-bio-logo text-center">
                        <a target="_blank" aria-label="Biolink" rel="noopener nofollow" href="{{ url('/') }}">
                            <img class="h-30-px" src="{{ asset('storage/brand/'.$settings->media->dark_logo) }}"
                                 alt="{{ asset($settings->media->dark_logo) }}{{ $settings->site_title }}">
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

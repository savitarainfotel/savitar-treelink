@extends($activeTheme.'layouts.app')
@section('title', ___('Edit Biolink'))
@section('content')
    <section class="our-bio-link mt-24">
        <div class="row">
            <div class="col-sm-4 d-none d-sm-block" id="stickySidebar">
                <div class="overflow-hidden phone-frame">
                    <iframe id="bioframe" class="bg-white w-100 h-100" src="{{ url('/') }}/{{ $post->slug }}"
                            title="{{ $post->title }}"></iframe>
                </div>
            </div>

            <div class="col-sm-8 col-12">
                <ul class="nav nav-tabs" id="bioLinkTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="links-tab" data-bs-toggle="tab" data-bs-target="#links"
                                type="button" role="tab" aria-controls="links"
                                aria-selected="true">{{ ___('Links') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="stats-tab" data-bs-toggle="tab" data-bs-target="#design"
                                type="button" role="tab" aria-controls="stats"
                                aria-selected="false">{{ ___('Design') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="stats-tab" data-bs-toggle="tab" data-bs-target="#settings"
                                type="button" role="tab" aria-controls="stats"
                                aria-selected="false">{{ ___('Settings') }}</button>
                    </li>
                </ul>
                <div class="tab-content mt-32" id="bioLinkTabContent">
                    {{-- Links --}}
                    <div class="tab-pane fade show active" id="links" role="tabpanel" aria-labelledby="links-tab">
                        <div class="add-link">
                            <button class="button -primary-l border-dashed rounded-3 push-left w-100 h-48-px mb-24"
                                    type="button" data-bs-toggle="modal" data-bs-target="#add_link">
                                <i class="fa-regular fa-plus push-this mr-5"></i> {{ ___('Add Link') }}
                            </button>
                            <button class="button -info-l border-dashed rounded-3 push-left w-100 h-48-px"
                                    type="button" data-bs-toggle="modal" data-bs-target="#add_header">
                                <i class="fa-regular fa-plus push-this mr-5"></i> {{ ___('Add Header') }}
                            </button>
                        </div>

                        <div class="bio-link-wrapper mt-32 position-relative" id="quick-blocks">
                            <div class="quick-reorder-body" data-action="{{ route('biolinks.reorder', $post->id) }}">
                                @foreach ($bioLinks as $bioLink)
                                    @if ($bioLink->type == "header")
                                        <!--HEADER-->
                                        <div class="quick-reorder-element bioitems bio-link-header card py-16 px-16"
                                             data-type="header" id="blockid_{{$bioLink->id}}" data-id="{{$bioLink->id}}"
                                             data-active="{{ $bioLink->active }}"
                                             data-settings='@json($bioLink->settings)'>
                                            <div class="d-flex align-items-center">
                                                <div class="quick-reorder-icon drag-handle cursor-grab">
                                                    <i class="fa-solid fa-grip-dots-vertical font-30 text-grey-2"></i>
                                                </div>
                                                <div
                                                    class="text-dark-1 font-14 fw-bold text-one-line word-break flex-grow-1">
                                                    <span
                                                        class="quick-header-title ml-15">{{ $bioLink->settings->title }}</span>
                                                    <span
                                                        class="quick-active-status {{ ($bioLink->active == 1) ? "d-none" : "" }}">
                                                            <i class="fa-solid fa-eye-slash text-info"></i>
                                                        </span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <div class="cursor-pointer" data-bs-toggle="modal"
                                                         data-bs-target="#edit_header"><i
                                                            class="pr-16 fa-solid fa-edit text-grey-2"></i></div>
                                                    <div class="cursor-pointer item-js-delete"
                                                         data-ajax-action="{{ route('biolinks.deleteLink', $post->id) }}">
                                                        <i class="fa-solid fa-trash text-grey-2"></i></div>
                                                </div>

                                            </div>
                                        </div>
                                    @endif

                                    @if ($bioLink->type == "link")
                                        <!--LINKS-->
                                        <div class="quick-reorder-element bioitems bio-link-item card py-16 px-16"
                                             data-type="link" id="blockid_{{$bioLink->id}}" data-id="{{$bioLink->id}}"
                                             data-active="{{ $bioLink->active }}"
                                             data-settings='@json($bioLink->settings)'>
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="d-flex justify-content-between flex-grow-1 gap-3 align-items-center pr-40">
                                                    <div class="d-flex align-items-center">
                                                        <div class="quick-reorder-icon drag-handle cursor-grab">
                                                            <i class="fa-solid fa-grip-dots-vertical font-30 text-grey-2"></i>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <span class="quick-link-img">
                                                            @if ($bioLink->settings->logo != "")
                                                                    <span class="ml-15">
                                                                        <img
                                                                            src="{{ asset('storage/post/biolink/'.$bioLink->settings->logo) }}"
                                                                            alt="{{ $bioLink->settings->title }}">
                                                                    </span>
                                                                @endif
                                                             </span>

                                                            <div class="ml-15">
                                                                <div
                                                                    class="text-dark-1 font-14 fw-bold text-one-line word-break">
                                                                    <span
                                                                        class="quick-link-title">{{ $bioLink->settings->title }}</span>
                                                                    <span
                                                                        class="quick-active-status {{ ($bioLink->active == 1) ? "d-none" : "" }}">
                                                                            <i class="fa-solid fa-eye-slash text-info"></i>
                                                                        </span>
                                                                </div>
                                                                <div
                                                                    class="text font-14 text-one-line word-break quick-link-url">
                                                                    {{ $bioLink->settings->url }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center d-none">
                                                        <i class="fa-solid fa-eye text-grey-2"></i>
                                                        <span class="count ml-8">{{ $bioLink->click }}</span>
                                                    </div>
                                                </div>
                                                <div class="cursor-pointer pr-16" data-bs-toggle="modal"
                                                     data-bs-target="#edit_link"><i
                                                        class="fa-solid fa-edit text-grey-2"></i></div>
                                                <div class="cursor-pointer item-js-delete"
                                                     data-ajax-action="{{ route('biolinks.deleteLink', $post->id) }}"><i
                                                        class="fa-solid fa-trash text-grey-2"></i></div>
                                            </div>
                                        </div>
                                    @endif

                                @endforeach
                            </div>
                        </div>
                        <!--SOCIALS-->
                        <div class="add-social-link mt-32">
                            <h2 class="mb-16 font-20">{{ ___('Socials') }}</h2>
                            <div id="quick-blocks-social">
                                <div class="quick-reorder-body"
                                     data-action="{{ route('biolinks.reorder', $post->id) }}">
                                    @foreach ($bioLinks as $bioLink)
                                        @if ($bioLink->type == "social")
                                            <div class="quick-reorder-element bioitems bio-social-item d-flex flex-wrap"
                                                 data-type="social" id="blockid_{{ $bioLink->id }}"
                                                 data-id="{{ $bioLink->id }}" data-active="{{ $bioLink->active }}"
                                                 data-settings='@json($bioLink->settings)'>
                                                <div class="w-100">
                                                    <div class="mb-16">
                                                        <div
                                                            class="d-flex justify-content-between bg-white py-15 shadow-3 rounded-3">
                                                            <div class="d-flex align-items-center ">
                                                                <div
                                                                    class="quick-reorder-icon drag-handle cursor-grab  ml-16">
                                                                    <i class="fa-solid fa-grip-dots-vertical font-30 text-grey-2"></i>
                                                                </div>
                                                                <span class="pl-16 pr-20">
                                                                            @if ($bioLink->settings->title == "email")
                                                                        <i class="fa-regular fa-envelope"></i>
                                                                    @elseif ($bioLink->settings->title == "phone")
                                                                        <i class="fa-regular fa-phone text-success"></i>
                                                                    @elseif ($bioLink->settings->title == "threads")
                                                                        <i class="text-{{ $bioLink->settings->title }}">
                                                                            <svg fill="currentColor" viewBox="0 0 512 512" style="fill: currentcolor; height: 1em; overflow: visible; width: 1em;"><path d="M331.5 235.7c2.2 .9 4.2 1.9 6.3 2.8c29.2 14.1 50.6 35.2 61.8 61.4c15.7 36.5 17.2 95.8-30.3 143.2c-36.2 36.2-80.3 52.5-142.6 53h-.3c-70.2-.5-124.1-24.1-160.4-70.2c-32.3-41-48.9-98.1-49.5-169.6V256v-.2C17 184.3 33.6 127.2 65.9 86.2C102.2 40.1 156.2 16.5 226.4 16h.3c70.3 .5 124.9 24 162.3 69.9c18.4 22.7 32 50 40.6 81.7l-40.4 10.8c-7.1-25.8-17.8-47.8-32.2-65.4c-29.2-35.8-73-54.2-130.5-54.6c-57 .5-100.1 18.8-128.2 54.4C72.1 146.1 58.5 194.3 58 256c.5 61.7 14.1 109.9 40.3 143.3c28 35.6 71.2 53.9 128.2 54.4c51.4-.4 85.4-12.6 113.7-40.9c32.3-32.2 31.7-71.8 21.4-95.9c-6.1-14.2-17.1-26-31.9-34.9c-3.7 26.9-11.8 48.3-24.7 64.8c-17.1 21.8-41.4 33.6-72.7 35.3c-23.6 1.3-46.3-4.4-63.9-16c-20.8-13.8-33-34.8-34.3-59.3c-2.5-48.3 35.7-83 95.2-86.4c21.1-1.2 40.9-.3 59.2 2.8c-2.4-14.8-7.3-26.6-14.6-35.2c-10-11.7-25.6-17.7-46.2-17.8H227c-16.6 0-39 4.6-53.3 26.3l-34.4-23.6c19.2-29.1 50.3-45.1 87.8-45.1h.8c62.6 .4 99.9 39.5 103.7 107.7l-.2 .2zm-156 68.8c1.3 25.1 28.4 36.8 54.6 35.3c25.6-1.4 54.6-11.4 59.5-73.2c-13.2-2.9-27.8-4.4-43.4-4.4c-4.8 0-9.6 .1-14.4 .4c-42.9 2.4-57.2 23.2-56.2 41.8l-.1 .1z"></path></svg>
                                                                        </i>
                                                                    @elseif ($bioLink->settings->title == "x")
                                                                        <i class="text-{{ $bioLink->settings->title }}"><svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" style="fill: currentcolor; height: 1em; overflow: visible; width: 1em;"><path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z"></path></svg></i>
                                                                    @else
                                                                        <i class="fa-brands fa-{{ $bioLink->settings->title }} text-{{ $bioLink->settings->title }}"></i>
                                                                    @endif
                                                                        </span>
                                                                <div class="text-capitalize font-16">
                                                                    {{ $bioLink->settings->title }}
                                                                    <span
                                                                        class="quick-active-status {{ ($bioLink->active == 1) ? "d-none" : "" }}">
                                                                                <i class="fa-solid fa-eye-slash text-info"></i>
                                                                            </span>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center ">
                                                                <div
                                                                    class="font-16 text-grey-1 text-one-line word-break pl-32 pr-24 quick-link-url">
                                                                    {{ $bioLink->settings->url }}
                                                                </div>
                                                                <div class="cursor-pointer pr-16" data-bs-toggle="modal"
                                                                     data-bs-target="#edit_social_link"><i
                                                                        class="fa-solid fa-edit text-grey-2"></i></div>
                                                                <div class="cursor-pointer pr-16 item-js-delete"
                                                                     data-ajax-action="{{ route('biolinks.deleteLink', $post->id) }}">
                                                                    <i class="fa-solid fa-trash text-grey-2"></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <button class="button -info-l border-dashed rounded-3 push-left w-100 h-48-px"
                                    type="button" data-bs-toggle="modal" data-bs-target="#social_link">
                                <i class="fa-regular fa-plus push-this mr-5"></i> {{ ___('Add Socials') }}
                            </button>
                        </div>
                    </div>

                    {{-- Design --}}
                    <div class="tab-pane fade" id="design" role="tabpanel" aria-labelledby="design-tab">
                        <style>.theme-preset-active{box-shadow:0 0 0 2px #fff, 0 0 0 4px currentColor;}</style>
                        @php
                            $tc = isset($postOptions->theme_customization) ? (array) $postOptions->theme_customization : [];
                            $tcBtn = $tc['button_color'] ?? '#ec4899';
                            $tcBtnText = $tc['button_text_color'] ?? '#FFFFFF';
                            $tcText = $tc['text_color'] ?? '#FFFFFF';
                            $tcBgType = $tc['background_type'] ?? 'solid';
                            $tcBgSolid = $tc['background_solid_color'] ?? '#ffffff';
                            $tcBgGradient = $tc['background_gradient'] ?? '';
                            $tcBgImage = $tc['background_image'] ?? '';
                            $tcFont = $tc['font_family'] ?? '';
                            $themePresets = ['#ec4899', '#92400e', '#2563eb', '#dc2626', '#a78bfa'];
                        @endphp
                        <form class="ajax_submit_form" action="{{ route('biolinks.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="bg-white px-30 py-30 shadow-3 rounded-3">
                                <h3 class="fw-bold font-20 mb-16">{{ ___('Themes') }}</h3>
                                <div class="row g-3">
                                    @foreach($templates as $template)
                                        <div class="col-md-4 col-6">
                                            <label for="{{ $template['name'] }}" class="design-box d-block">
                                                <input id="{{ $template['name'] }}" type="radio" name="biotheme"
                                                       value="{{ $template['name'] }}"
                                                       hidden {{ (@$postOptions->biotheme == $template['name']) ? "checked" : "" }}>
                                                <div class="theme-bg-box">
                                                    <img class="theme-bg-box-image" src="{{ $template['image'] }}"
                                                         alt="{{ $template['name'] }}">
                                                </div>
                                            </label>
                                            <div
                                                class="text-dark-1 font-14 text-center mt-8 text-capitalize">{{ str_replace('-', ' ', $template['name']) }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="bg-white px-30 py-30 shadow-3 rounded-3 mt-30">
                                <h3 class="fw-bold font-20 mb-16">{{ ___('Color Theme') }}</h3>
                                <div class="d-flex align-items-center gap-2 mb-16 flex-wrap">
                                    <span class="font-14 text-dark-1">{{ ___('Color Theme') }}</span>
                                    <div class="d-flex align-items-center gap-2 ms-auto">
                                        @foreach($themePresets as $idx => $hex)
                                            <button type="button" class="theme-preset-btn border rounded-circle p-0 d-inline-flex align-items-center justify-content-center {{ $tcBtn == $hex ? 'theme-preset-active' : '' }}" data-hex="{{ $hex }}" style="width:28px;height:28px;background-color:{{ $hex }};" title="{{ $hex }}"></button>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="row g-3 mb-16">
                                    <div class="col-md-4">
                                        <label class="form-label font-14">{{ ___('Button Color') }}</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="color" class="form-control form-control-color p-1" name="theme_button_color" id="theme_button_color" value="{{ $tcBtn }}" style="width:40px;height:40px;">
                                            <input type="text" class="form-control text-field font-14" value="{{ $tcBtn }}" id="theme_button_color_hex" maxlength="7">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label font-14">{{ ___('Button Text Color') }}</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="color" class="form-control form-control-color p-1" name="theme_button_text_color" id="theme_button_text_color" value="{{ $tcBtnText }}" style="width:40px;height:40px;">
                                            <input type="text" class="form-control text-field font-14" value="{{ $tcBtnText }}" id="theme_button_text_color_hex" maxlength="7">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label font-14">{{ ___('Text Color') }}</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="color" class="form-control form-control-color p-1" name="theme_text_color" id="theme_text_color" value="{{ $tcText }}" style="width:40px;height:40px;">
                                            <input type="text" class="form-control text-field font-14" value="{{ $tcText }}" id="theme_text_color_hex" maxlength="7">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white px-30 py-30 shadow-3 rounded-3 mt-30">
                                <h3 class="fw-bold font-20 mb-16">{{ ___('Background Settings') }}</h3>
                                <div class="mb-16">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="theme_background_type" id="bg_type_solid" value="solid" {{ $tcBgType == 'solid' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="bg_type_solid">{{ ___('Solid Color') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="theme_background_type" id="bg_type_gradient" value="gradient" {{ $tcBgType == 'gradient' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="bg_type_gradient">{{ ___('Gradient') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="theme_background_type" id="bg_type_image" value="image" {{ $tcBgType == 'image' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="bg_type_image">{{ ___('Image') }}</label>
                                    </div>
                                </div>
                                <div class="mb-16 theme-bg-solid-wrap" style="display:{{ $tcBgType == 'solid' ? 'block' : 'none' }};">
                                    <label class="form-label font-14">{{ ___('Solid Color') }}</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="color" class="form-control form-control-color p-1" name="theme_background_solid_color" id="theme_background_solid_color" value="{{ $tcBgSolid }}" style="width:40px;height:40px;">
                                        <input type="text" class="form-control text-field font-14" value="{{ $tcBgSolid }}" maxlength="7">
                                    </div>
                                </div>
                                <div class="mb-16 theme-bg-gradient-wrap" style="display:{{ $tcBgType == 'gradient' ? 'block' : 'none' }};">
                                    <label class="form-label font-14">{{ ___('Gradient') }}</label>
                                    <input type="text" class="form-control text-field font-14" name="theme_background_gradient" placeholder="e.g. linear-gradient(135deg, #667eea 0%, #764ba2 100%)" value="{{ $tcBgGradient }}">
                                </div>
                                <div class="theme-bg-image-wrap" style="display:{{ $tcBgType == 'image' ? 'block' : 'none' }};">
                                    <label class="form-label font-14">{{ ___('Background Image') }}</label>
                                    <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                                        <input type="text" class="form-control text-field font-14 flex-grow-1" id="theme_background_image_path" value="{{ $tcBgImage ? 'storage/post/logo/'.$tcBgImage : '' }}" readonly placeholder="{{ ___('No image') }}">
                                        <input type="file" name="background_image" id="theme_background_image_file" accept="image/jpg,image/jpeg,image/png" class="d-none">
                                        <button type="button" class="button -primary-l rounded-3" id="theme_browse_bg">{{ ___('Browse') }} <i class="fa-regular fa-image ms-1"></i></button>
                                        <button type="button" class="button -outlined rounded-3 text-danger" id="theme_remove_bg" title="{{ ___('Remove') }}"><i class="fa-regular fa-xmark"></i></button>
                                    </div>
                                    <input type="hidden" name="remove_background_image" id="remove_background_image" value="0">
                                    @if($tcBgImage)
                                        <div class="mt-2 theme-bg-preview-wrap">
                                            <img id="theme_bg_preview_img" src="{{ asset('storage/post/logo/'.$tcBgImage) }}" alt="Preview" class="rounded-3" style="max-height:80px;max-width:160px;object-fit:cover;">
                                        </div>
                                    @else
                                        <div class="mt-2 theme-bg-preview-wrap d-none"><img id="theme_bg_preview_img" src="" alt="Preview" class="rounded-3" style="max-height:80px;max-width:160px;object-fit:cover;"></div>
                                    @endif
                                </div>
                            </div>
                            <div class="bg-white px-30 py-30 shadow-3 rounded-3 mt-30">
                                <h3 class="fw-bold font-20 mb-16">{{ ___('Font Family') }}</h3>
                                <input type="text" class="form-control text-field font-14" name="theme_font_family" id="theme_font_family" value="{{ $tcFont }}" placeholder="{{ ___('e.g. Inter, sans-serif') }}" list="theme_font_list">
                                <datalist id="theme_font_list">
                                    <option value="Inter, sans-serif">
                                    <option value="Poppins, sans-serif">
                                    <option value="Open Sans, sans-serif">
                                    <option value="Roboto, sans-serif">
                                    <option value="Lato, sans-serif">
                                    <option value="Montserrat, sans-serif">
                                    <option value="Source Sans Pro, sans-serif">
                                    <option value="system-ui, sans-serif">
                                </datalist>
                            </div>
                            <div class="bg-white px-30 py-30 shadow-3 rounded-3 mt-30">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h3 class="fw-bold text-dark-1 font-18 mb-0">
                                        {{ ___('Show Quick Bio Credit') }}
                                        @if(!request()->user()->plan_settings->hide_branding)
                                            <small
                                                class="badge bg-danger text-white font-10">{{ ___('Premium') }}</small>
                                        @endif
                                    </h3>
                                    @php
                                        $checked = "checked";
                                        $disabled = "disabled";
                                        if(request()->user()->plan_settings->hide_branding){
                                            $checked = (@$postOptions->bio_credit) ? "checked" : "";
                                            $disabled = "";
                                        }
                                    @endphp
                                    <div class="form-check form-switch">
                                        <input class="form-check-input cursor-pointer" name="bio_credit" type="checkbox"
                                               role="switch" {{ $checked }} {{ $disabled }}>
                                    </div>
                                </div>
                                <p class="mt-8">{{ ___('We appreciate you showing our logo credit in the footer, but feel free to hide it.') }}</p>
                                <div class="separator-1px-op-l my-32"></div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <h3 class="fw-bold text-dark-1 font-18 mb-0">{{ ___('Display Share button') }}</h3>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input cursor-pointer" name="bio_share" type="checkbox"
                                               role="switch" {{ (@$postOptions->bio_share) ? "checked" : "" }}>
                                    </div>
                                </div>
                                <p class="mt-8">{{ ___('We appreciate you showing our logo credit in the footer, but feel free to hide it.') }}</p>
                            </div>
                            <input type="hidden" name="type" value="design">
                            <button type="submit"
                                    class="button -lg -primary rounded-pill w-100 mt-32">{{ ___('Save') }}</button>
                        </form>
                    </div>

                    {{-- Settings --}}
                    <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                        <form class="ajax_submit_form" action="{{ route('biolinks.update', $post->id) }}" method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="bg-white px-30 py-30 shadow-3 rounded-3">
                                <h2 class="font-20 mb-16">{{ ___('Profile') }}</h2>
                                <div class="bio-link-upload-img mb-16">
                                    <div class="avatar-upload my-0 mw-100">
                                        <div class="avatar-edit">
                                            <input id="uploadcover" type="file" name="cover"
                                                   onchange="readURL(this,'uploadedCover')"
                                                   accept="image/jpg, image/jpeg, image/png" hidden/>
                                            <label for="uploadcover"></label>
                                        </div>
                                        <div class="avatar-preview w-100 text-center">
                                            @if(@$postOptions->cover_image == "")
                                                <img src="{{ asset('storage/post/default-cover.png') }}"
                                                     id="uploadedCover" class="rounded-3"/>
                                            @else
                                                <img src="{{ asset('storage/post/logo/'.$postOptions->cover_image) }}"
                                                     id="uploadedCover" class="rounded-3"/>
                                            @endif


                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="d-flex flex-wrap-reverse flex-sm-nowrap justify-content-between align-items-center">
                                    <div class="d-flex flex-wrap justify-content-between flex-column w-100">
                                        <input class="form-control text-field mb-16 h-40-px" type="text" name="name"
                                               placeholder="{{ ___('Title') }}" value="{{ $post->title }}">
                                        <input class="form-control text-field h-40-px" type="text" name="bio"
                                               placeholder="{{ ___('Content') }}" value="{{ $post->content }}">
                                    </div>
                                    <div class="mx-auto sm-mb-32">
                                        <div class="bio-link-upload-img ml-24">
                                            <div class="avatar-upload my-0">
                                                <div class="avatar-edit">
                                                    <input id="upload3" type="file" name="logo"
                                                           onchange="readURL(this,'uploadedAvatar3')"
                                                           accept="image/jpg, image/jpeg, image/png" hidden/>
                                                    <label for="upload3"></label>
                                                </div>
                                                <div class="avatar-preview text-center">
                                                    @if($post->image == "")
                                                        <img src="{{ asset('storage/post/default.png') }}"
                                                             id="uploadedAvatar3" class="rounded-3"/>
                                                    @else
                                                        <img src="{{ asset('storage/post/logo/'.$post->image) }}"
                                                             id="uploadedAvatar3" class="rounded-3"/>
                                                    @endif


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white px-30 py-30 shadow-3 rounded-3 mt-30">
                                <div class="position-relative">
                                    <h3 class="fw-bold text-dark-1 font-18 mb-16">{{ ___('Username') }}</h3>
                                    <input type="text" name="slug" class="form-control text-field overflow-hidden"
                                           placeholder="{{ ___('Slug') }}" value="{{ $post->slug }}">
                                </div>
                                <div class="separator-1px-op-l my-32"></div>
                                <div class="d-flex flex-column">
                                    <h3 class="fw-bold text-dark-1 font-18">{{ ___('SEO') }}</h3>
                                    <span
                                        class="mb-16 text-muted">{{ ___('Choose the title and description to appear on search engines and social posts.') }}</span>
                                    <input type="text" class="form-control text-field mb-16" name="seo_title"
                                           placeholder="{{ ___('Title') }}" value="{{ @$postOptions->seo_title }}">
                                    <input type="text" class="form-control text-field" name="seo_desc"
                                           placeholder="{{ ___('Short Description') }}"
                                           value="{{ @$postOptions->seo_desc }}">
                                </div>
                            </div>
                            <input type="hidden" name="type" value="settings">
                            <button type="submit"
                                    class="button -lg -primary rounded-pill w-100 mt-32">{{ ___('Save') }}</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!--ADD Header Modal Start-->
    <div class="animated fadeIn modal" id="add_header" aria-labelledby="addHeaderModalLable" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addHeaderModalLable">{{ ___('Add header') }}</h5>
                    <button type="button" class="icon-group -close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-regular fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <form class="add_block_form" action="{{ route('biolinks.addheader', $post->id) }}" method="POST">
                        <div
                            class="d-flex flex-wrap-reverse flex-sm-nowrap justify-content-between align-items-center my-32 ">
                            <div class="d-flex flex-wrap justify-content-between flex-column w-100">
                                <input class="form-control text-field" type="text" name="title"
                                       placeholder="{{ ___('Title') }}">
                            </div>
                        </div>
                        <div class="position-relative">
                            <button class="button -primary w-100 -lg" type="submit">{{ ___('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--ADD Header Modal End-->

    <!--Edit Header Modal Start-->
    <div class="animated fadeIn modal" id="edit_header" aria-labelledby="editHeaderModalLable" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editHeaderModalLable">{{ ___('Edit header') }}</h5>
                    <button type="button" class="icon-group -close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-regular fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <form class="edit_block_form" action="{{ route('biolinks.editHeader', $post->id) }}" method="POST">
                        <input type="hidden" name="id" value="">
                        <div class="d-flex flex-wrap justify-content-between w-100 my-32">
                            <input class="form-control text-field" type="text" name="title"
                                   placeholder="{{ ___('Title') }}">
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-16">
                            <p class="fw-semibold font-16 mb-0">{{ ___('Hide') }}</p>
                            <div class="form-check form-switch">
                                <input class="form-check-input cursor-pointer" name="active" type="checkbox"
                                       role="switch" id="HideSwitch">
                            </div>
                        </div>
                        <div class="position-relative mt-32">
                            <button class="button -primary w-100 -lg" type="submit">{{ ___('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Edit Header Modal End-->

    <!--ADD Link Modal Start-->
    <div class="animated fadeIn modal" id="add_link" aria-labelledby="addLinkModalLable" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addLinkModalLable">{{ ___('Add Link') }}</h5>
                    <button type="button" class="icon-group -close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-regular fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <form class="add_block_form" action="{{ route('biolinks.addlink', $post->id) }}" method="POST"
                          enctype="multipart/form-data">
                        <div
                            class="d-flex flex-wrap-reverse flex-sm-nowrap justify-content-between align-items-center my-32">

                            <div class="d-flex flex-wrap justify-content-between flex-column w-100">
                                <input class="form-control text-field mb-16" type="text" name="title"
                                       placeholder="{{ ___('Title') }}">
                                <input class="form-control text-field" type="text" name="url"
                                       placeholder="{{ ___('URL') }}">
                            </div>
                            <div class="mx-auto sm-mb-32">
                                <div class="bio-link-upload-img ml-24">
                                    <div class="avatar-upload my-0">
                                        <div class="avatar-edit">
                                            <input id="upload1" type="file" name="logo"
                                                   onchange="readURL(this,'uploadedAvatar1')"
                                                   accept="image/jpg, image/jpeg, image/png" hidden/>
                                            <label for="upload1"></label>
                                        </div>
                                        <div class="avatar-preview text-center">
                                            <img src="{{ asset('storage/post/default.png') }}" id="uploadedAvatar1"
                                                 class="rounded-3"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="fw-semibold font-16 mb-0">{{ ___('Highlight this link') }}</p>
                            <div class="form-check form-switch">
                                <input class="form-check-input cursor-pointer" type="checkbox" name="highlight"
                                       role="switch">
                            </div>
                        </div>
                        <div class="position-relative mt-32">
                            <button class="button -primary w-100 -lg" type="submit">{{ ___('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--ADD Link Modal End-->

    <!--Edit Link Modal Start-->
    <div class="animated fadeIn modal" id="edit_link" aria-labelledby="editLinkModalLable" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLinkModalLable">{{ ___('Edit Link') }}</h5>
                    <button type="button" class="icon-group -close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-regular fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <form class="edit_block_form" action="{{ route('biolinks.editLink', $post->id) }}" method="POST"
                          enctype="multipart/form-data">
                        <input type="hidden" name="id" value="">
                        <div
                            class="d-flex flex-wrap-reverse flex-sm-nowrap justify-content-between align-items-center my-32 ">
                            <div class="d-flex flex-wrap justify-content-between flex-column w-100">
                                <input class="form-control text-field mb-16" type="text" name="title"
                                       placeholder="{{ ___('Title') }}">
                                <input class="form-control text-field" type="text" name="url"
                                       placeholder="{{ ___('URL') }}">
                            </div>
                            <div class="mx-auto sm-mb-32">
                                <div class="bio-link-upload-img ml-24">
                                    <div class="avatar-upload my-0">
                                        <div class="avatar-edit">
                                            <input id="upload2" type="file" name="logo"
                                                   onchange="readURL(this,'uploadedAvatar2')"
                                                   accept="image/jpg, image/jpeg, image/png" hidden/>
                                            <label for="upload2"></label>
                                        </div>
                                        <div class="avatar-preview text-center">
                                            <img src="{{ asset('storage/post/default.png') }}" id="uploadedAvatar2"
                                                 class="rounded-3"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="fw-semibold font-16 mb-0">{{ ___('Highlight this link') }}</p>
                            <div class="form-check form-switch">
                                <input class="form-check-input cursor-pointer" name="highlight" type="checkbox"
                                       role="switch" id="highlightSwitch">
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-16">
                            <p class="fw-semibold font-16 mb-0">{{ ___('Hide') }}</p>
                            <div class="form-check form-switch">
                                <input class="form-check-input cursor-pointer" name="active" type="checkbox"
                                       role="switch" id="HideSwitch">
                            </div>
                        </div>
                        <div class="position-relative mt-32">
                            <button class="button -primary w-100 -lg" type="submit">{{ ___('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Edit Link Modal End-->


    <!--ADD Social Link Modal Start-->
    <div class="animated fadeIn modal" id="social_link" aria-labelledby="socialLinkModalLable" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="socialLinkModalLable">{{ ___('Social Link') }}</h5>
                    <button type="button" class="icon-group -close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-regular fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <div class="social-link-wrapper my-32">
                        <div
                            class="social-link-item d-flex align-items-center border text-border-1 rounded px-16 py-10 mb-8 justify-content-between cursor-pointer"
                            data-bs-target="#addSocialModal" data-bs-toggle="modal"
                            data-type="email"
                            data-title="{{ ___('Email') }}"
                            data-placeholder="your@domain.com">
                            <div class="d-flex align-items-center justify-content-center h-20-px">
                                <span class="pr-16 fa-regular fa-envelope"></span>{{ ___('Email') }}</div>
                            <a class="text-decoration -underline text-primary">{{ ___('Add') }}</a>
                        </div>
                        <div
                            class="social-link-item d-flex align-items-center border text-border-1 rounded px-16 py-10 mb-8 justify-content-between cursor-pointer"
                            data-bs-target="#addSocialModal" data-bs-toggle="modal"
                            data-type="phone"
                            data-title="{{ ___('Phone') }}"
                            data-placeholder="+00000000000">
                            <div class="d-flex align-items-center justify-content-center h-20-px">
                                <span class="pr-16 fa-regular fa-phone text-success"></span>{{ ___('Phone') }}</div>
                            <a class="text-decoration -underline text-primary">{{ ___('Add') }}</a>
                        </div>
                        <div
                            class="social-link-item d-flex align-items-center border text-border-1 rounded px-16 py-10 mb-8 justify-content-between cursor-pointer"
                            data-bs-target="#addSocialModal" data-bs-toggle="modal"
                            data-type="instagram"
                            data-title="{{ ___('Instagram') }}"
                            data-placeholder="https://instagram.com/username">
                            <div class="d-flex align-items-center justify-content-center h-20-px">
                                <span class="pr-16 fa-brands fa-instagram text-instagram"></span>{{ ___('Instagram') }}
                            </div>
                            <a class="text-decoration -underline text-primary">{{ ___('Add') }}</a>
                        </div>
                        <div
                            class="social-link-item d-flex align-items-center border text-border-1 rounded px-16 py-10 mb-8 justify-content-between cursor-pointer"
                            data-bs-target="#addSocialModal" data-bs-toggle="modal"
                            data-type="threads"
                            data-title="{{ ___('Threads') }}"
                            data-placeholder="https://threads.net/username">
                            <div class="d-flex align-items-center justify-content-center h-20-px">
                                <span class="pr-16 text-thread" style="color: #14171a">
                                    <svg fill="currentColor"
                                                                                            viewBox="0 0 512 512"
                                                                                            style="fill: currentcolor; height: 1em; overflow: visible; width: 1em;"><path
                                            d="M331.5 235.7c2.2 .9 4.2 1.9 6.3 2.8c29.2 14.1 50.6 35.2 61.8 61.4c15.7 36.5 17.2 95.8-30.3 143.2c-36.2 36.2-80.3 52.5-142.6 53h-.3c-70.2-.5-124.1-24.1-160.4-70.2c-32.3-41-48.9-98.1-49.5-169.6V256v-.2C17 184.3 33.6 127.2 65.9 86.2C102.2 40.1 156.2 16.5 226.4 16h.3c70.3 .5 124.9 24 162.3 69.9c18.4 22.7 32 50 40.6 81.7l-40.4 10.8c-7.1-25.8-17.8-47.8-32.2-65.4c-29.2-35.8-73-54.2-130.5-54.6c-57 .5-100.1 18.8-128.2 54.4C72.1 146.1 58.5 194.3 58 256c.5 61.7 14.1 109.9 40.3 143.3c28 35.6 71.2 53.9 128.2 54.4c51.4-.4 85.4-12.6 113.7-40.9c32.3-32.2 31.7-71.8 21.4-95.9c-6.1-14.2-17.1-26-31.9-34.9c-3.7 26.9-11.8 48.3-24.7 64.8c-17.1 21.8-41.4 33.6-72.7 35.3c-23.6 1.3-46.3-4.4-63.9-16c-20.8-13.8-33-34.8-34.3-59.3c-2.5-48.3 35.7-83 95.2-86.4c21.1-1.2 40.9-.3 59.2 2.8c-2.4-14.8-7.3-26.6-14.6-35.2c-10-11.7-25.6-17.7-46.2-17.8H227c-16.6 0-39 4.6-53.3 26.3l-34.4-23.6c19.2-29.1 50.3-45.1 87.8-45.1h.8c62.6 .4 99.9 39.5 103.7 107.7l-.2 .2zm-156 68.8c1.3 25.1 28.4 36.8 54.6 35.3c25.6-1.4 54.6-11.4 59.5-73.2c-13.2-2.9-27.8-4.4-43.4-4.4c-4.8 0-9.6 .1-14.4 .4c-42.9 2.4-57.2 23.2-56.2 41.8l-.1 .1z"/></svg>
                                </span>{{ ___('Threads') }}
                            </div>
                            <a class="text-decoration -underline text-primary">{{ ___('Add') }}</a>
                        </div>
                        <div
                            class="social-link-item d-flex align-items-center border text-border-1 rounded px-16 py-10 mb-8 justify-content-between cursor-pointer"
                            data-bs-target="#addSocialModal" data-bs-toggle="modal"
                            data-type="facebook"
                            data-title="{{ ___('Facebook') }}"
                            data-placeholder="https://facebook.com/pageurl">
                            <div class="d-flex align-items-center justify-content-center h-20-px">
                                <span class="pr-16 fa-brands fa-facebook text-facebook"></span>{{ ___('Facebook') }}
                            </div>
                            <a class="text-decoration -underline text-primary">{{ ___('Add') }}</a>
                        </div>
                        <div
                            class="social-link-item d-flex align-items-center border text-border-1 rounded px-16 py-10 mb-8 justify-content-between cursor-pointer"
                            data-bs-target="#addSocialModal" data-bs-toggle="modal"
                            data-type="youtube"
                            data-title="{{ ___('Youtube') }}"
                            data-placeholder="https://youtube.com/channel/channelurl">
                            <div class="d-flex align-items-center justify-content-center h-20-px">
                                <span class="pr-16 fa-brands fa-youtube text-youtube"></span>{{ ___('Youtube') }}</div>
                            <a class="text-decoration -underline text-primary">{{ ___('Add') }}</a>
                        </div>
                        <div
                            class="social-link-item d-flex align-items-center border text-border-1 rounded px-16 py-10 mb-8 justify-content-between cursor-pointer"
                            data-bs-target="#addSocialModal" data-bs-toggle="modal"
                            data-type="x"
                            data-title="{{ ___('X (Twitter)') }}"
                            data-placeholder="https://x.com/username">
                            <div class="d-flex align-items-center justify-content-center h-20-px">
                                <span class="pr-16 text-twitter">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"
                                         style="fill: currentcolor; height: 1em; overflow: visible; width: 1em;"><path
                                            d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z"/></svg>
                                </span>
                                {{ ___('X (Twitter)') }}
                            </div>
                            <a class="text-decoration -underline text-primary">{{ ___('Add') }}</a>
                        </div>
                        <div
                            class="social-link-item d-flex align-items-center border text-border-1 rounded px-16 py-10 mb-8 justify-content-between cursor-pointer"
                            data-bs-target="#addSocialModal" data-bs-toggle="modal"
                            data-type="whatsapp"
                            data-title="{{ ___('WhatsApp') }}"
                            data-placeholder="+00000000000">
                            <div class="d-flex align-items-center justify-content-center h-20-px">
                                <span class="pr-16 fab fa-whatsapp text-whatsapp"
                                      style="color: #25d366"></span>{{ ___('WhatsApp') }}</div>
                            <a class="text-decoration -underline text-primary">{{ ___('Add') }}</a>
                        </div>
                        <div
                            class="social-link-item d-flex align-items-center border text-border-1 rounded px-16 py-10 mb-8 justify-content-between cursor-pointer"
                            data-bs-target="#addSocialModal" data-bs-toggle="modal"
                            data-type="telegram"
                            data-title="{{ ___('Telegram') }}"
                            data-placeholder="+00000000000">
                            <div class="d-flex align-items-center justify-content-center h-20-px">
                                <span class="pr-16 fab fa-telegram text-telegram"
                                      style="color: #0088cc"></span>{{ ___('Telegram') }}</div>
                            <a class="text-decoration -underline text-primary">{{ ___('Add') }}</a>
                        </div>
                        <div
                            class="social-link-item d-flex align-items-center border text-border-1 rounded px-16 py-10 mb-8 justify-content-between cursor-pointer"
                            data-bs-target="#addSocialModal" data-bs-toggle="modal"
                            data-type="tiktok"
                            data-title="{{ ___('TikTok') }}"
                            data-placeholder="https://tiktok.com/username">
                            <div class="d-flex align-items-center justify-content-center h-20-px">
                                <span class="pr-16 fab fa-tiktok text-tiktok"
                                      style="color: #010101"></span>{{ ___('TikTok') }}</div>
                            <a class="text-decoration -underline text-primary">{{ ___('Add') }}</a>
                        </div>
                        <div
                            class="social-link-item d-flex align-items-center border text-border-1 rounded px-16 py-10 mb-8 justify-content-between cursor-pointer"
                            data-bs-target="#addSocialModal" data-bs-toggle="modal"
                            data-type="snapchat"
                            data-title="{{ ___('Snapchat') }}"
                            data-placeholder="https://snapchat.com/add/username">
                            <div class="d-flex align-items-center justify-content-center h-20-px">
                                <span class="pr-16 fab fa-snapchat text-snapchat"
                                      style="color: #030303"></span>{{ ___('Snapchat') }}</div>
                            <a class="text-decoration -underline text-primary">{{ ___('Add') }}</a>
                        </div>
                        <div
                            class="social-link-item d-flex align-items-center border text-border-1 rounded px-16 py-10 mb-8 justify-content-between cursor-pointer"
                            data-bs-target="#addSocialModal" data-bs-toggle="modal"
                            data-type="spotify"
                            data-title="{{ ___('Spotify') }}"
                            data-placeholder="https://spotify.com/username">
                            <div class="d-flex align-items-center justify-content-center h-20-px">
                                <span class="pr-16 fab fa-spotify text-spotify"
                                      style="color: #1db954"></span>{{ ___('Spotify') }}</div>
                            <a class="text-decoration -underline text-primary">{{ ___('Add') }}</a>
                        </div>
                        <div
                            class="social-link-item d-flex align-items-center border text-border-1 rounded px-16 py-10 mb-8 justify-content-between cursor-pointer"
                            data-bs-target="#addSocialModal" data-bs-toggle="modal"
                            data-type="linkedin"
                            data-title="{{ ___('LinkedIn') }}"
                            data-placeholder="https://linkedin.com/username">
                            <div class="d-flex align-items-center justify-content-center h-20-px">
                                <span class="pr-16 fab fa-linkedin text-linkedin"
                                      style="color: #0a66c2"></span>{{ ___('LinkedIn') }}</div>
                            <a class="text-decoration -underline text-primary">{{ ___('Add') }}</a>
                        </div>
                        <div
                            class="social-link-item d-flex align-items-center border text-border-1 rounded px-16 py-10 mb-8 justify-content-between cursor-pointer"
                            data-bs-target="#addSocialModal" data-bs-toggle="modal"
                            data-type="soundcloud"
                            data-title="{{ ___('Soundcloud') }}"
                            data-placeholder="https://soundcloud.com/username">
                            <div class="d-flex align-items-center justify-content-center h-20-px">
                                <span class="pr-16 fab fa-soundcloud text-soundcloud"
                                      style="color: #ff8800"></span>{{ ___('Soundcloud') }}</div>
                            <a class="text-decoration -underline text-primary">{{ ___('Add') }}</a>
                        </div>
                        <div
                            class="social-link-item d-flex align-items-center border text-border-1 rounded px-16 py-10 mb-8 justify-content-between cursor-pointer"
                            data-bs-target="#addSocialModal" data-bs-toggle="modal"
                            data-type="vimeo"
                            data-title="{{ ___('Vimeo') }}"
                            data-placeholder="https://vimeo.com/username">
                            <div class="d-flex align-items-center justify-content-center h-20-px">
                                <span class="pr-16 fab fa-vimeo text-vimeo"
                                      style="color: #1ab7ea"></span>{{ ___('Vimeo') }}</div>
                            <a class="text-decoration -underline text-primary">{{ ___('Add') }}</a>
                        </div>
                        <div
                            class="social-link-item d-flex align-items-center border text-border-1 rounded px-16 py-10 mb-8 justify-content-between cursor-pointer"
                            data-bs-target="#addSocialModal" data-bs-toggle="modal"
                            data-type="behance"
                            data-title="{{ ___('Behance') }}"
                            data-placeholder="https://www.behance.net/username">
                            <div class="d-flex align-items-center justify-content-center h-20-px">
                                <span class="pr-16 fab fa-behance text-behance"
                                      style="color: #1769ff"></span>{{ ___('Behance') }}</div>
                            <a class="text-decoration -underline text-primary">{{ ___('Add') }}</a>
                        </div>
                        <div
                            class="social-link-item d-flex align-items-center border text-border-1 rounded px-16 py-10 mb-8 justify-content-between cursor-pointer"
                            data-bs-target="#addSocialModal" data-bs-toggle="modal"
                            data-type="dribbble"
                            data-title="{{ ___('Dribbble') }}"
                            data-placeholder="https://dribbble.com/username">
                            <div class="d-flex align-items-center justify-content-center h-20-px">
                                <span class="pr-16 fa-brands fa-dribbble text-dribble"></span>{{ ___('Dribbble') }}
                            </div>
                            <a class="text-decoration -underline text-primary">{{ ___('Add') }}</a>
                        </div>
                        <div
                            class="social-link-item d-flex align-items-center border text-border-1 rounded px-16 py-10 mb-8 justify-content-between cursor-pointer"
                            data-bs-target="#addSocialModal" data-bs-toggle="modal"
                            data-type="pinterest"
                            data-title="{{ ___('Pinterest') }}"
                            data-placeholder="https://pinterest.com/username">
                            <div class="d-flex align-items-center justify-content-center h-20-px">
                                <span class="pr-16 fa-brands fa-pinterest text-pinterest"
                                      style="color: #e60023"></span>{{ ___('Pinterest') }}</div>
                            <a class="text-decoration -underline text-primary">{{ ___('Add') }}</a>
                        </div>
                        <div
                            class="social-link-item d-flex align-items-center border text-border-1 rounded px-16 py-10 mb-8 justify-content-between cursor-pointer"
                            data-bs-target="#addSocialModal" data-bs-toggle="modal"
                            data-type="reddit"
                            data-title="{{ ___('Reddit') }}"
                            data-placeholder="https://reddit.com/username">
                            <div class="d-flex align-items-center justify-content-center h-20-px">
                                <span class="pr-16 fa-brands fa-reddit text-reddit"
                                      style="color: #ff4500"></span>{{ ___('Reddit') }}</div>
                            <a class="text-decoration -underline text-primary">{{ ___('Add') }}</a>
                        </div>
                        <div
                            class="social-link-item d-flex align-items-center border text-border-1 rounded px-16 py-10 mb-8 justify-content-between cursor-pointer"
                            data-bs-target="#addSocialModal" data-bs-toggle="modal"
                            data-type="discord"
                            data-title="{{ ___('Discord') }}"
                            data-placeholder="https://discord.gg/username">
                            <div class="d-flex align-items-center justify-content-center h-20-px">
                                <span class="pr-16 fa-brands fa-discord text-discord"
                                      style="color: #5865f2"></span>{{ ___('Discord') }}</div>
                            <a class="text-decoration -underline text-primary">{{ ___('Add') }}</a>
                        </div>
                        <div
                            class="social-link-item d-flex align-items-center border text-border-1 rounded px-16 py-10 mb-8 justify-content-between cursor-pointer"
                            data-bs-target="#addSocialModal" data-bs-toggle="modal"
                            data-type="twitch"
                            data-title="{{ ___('Twitch') }}"
                            data-placeholder="https://www.twitch.tv/username">
                            <div class="d-flex align-items-center justify-content-center h-20-px">
                                <span class="pr-16 fa-brands fa-twitch text-twitch"
                                      style="color: #9146ff"></span>{{ ___('Twitch') }}</div>
                            <a class="text-decoration -underline text-primary">{{ ___('Add') }}</a>
                        </div>
                        <div
                            class="social-link-item d-flex align-items-center border text-border-1 rounded px-16 py-10 mb-8 justify-content-between cursor-pointer"
                            data-bs-target="#addSocialModal" data-bs-toggle="modal"
                            data-type="github"
                            data-title="{{ ___('Github') }}"
                            data-placeholder="https://github.com/username">
                            <div class="d-flex align-items-center justify-content-center h-20-px">
                                <span class="pr-16 fa-brands fa-github text-github"></span>{{ ___('Github') }}</div>
                            <a class="text-decoration -underline text-primary">{{ ___('Add') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--*****ADD Social Media ID Start******-->

    <div class="animated fadeIn modal" id="addSocialModal" aria-labelledby="addSocialHeaderLable" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="icon-group -close" data-bs-target="#social_link"
                            data-bs-toggle="modal">
                        <i class="fa-regular fa-arrow-left"></i>
                    </button>
                    <h5 class="modal-title" id="addSocialHeaderLable">{{ ___('Heading') }}</h5>
                    <button type="button" class="icon-group -close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-regular fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <form class="add_block_form" action="{{ route('biolinks.addsocial', $post->id) }}" method="POST">
                        <div
                            class="d-flex flex-wrap-reverse flex-sm-nowrap justify-content-between align-items-center my-32 ">
                            <div class="d-flex flex-wrap justify-content-between flex-column w-100">
                                <input class="form-control text-field" name="url" type="text"
                                       placeholder="{{ ___('URL') }}">
                                <input class="form-control text-field" name="title" type="hidden" value="">
                            </div>
                        </div>
                        <div class="position-relative">
                            <button class="button -primary w-100 -lg" type="submit">{{ ___('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--*****ADD Social Media ID End******-->

    <!--*****Edit Social Media ID Start******-->
    <div class="animated fadeIn modal" id="edit_social_link" aria-labelledby="editSocialHeaderLable"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="icon-group -close" data-bs-target="#social_link"
                            data-bs-toggle="modal">
                        <i class="fa-regular fa-arrow-left"></i>
                    </button>
                    <h5 class="modal-title text-capitalize" id="editSocialHeaderLable">{{ ___('Heading') }}</h5>
                    <button type="button" class="icon-group -close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-regular fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body pt-0">
                    <form class="edit_block_form" action="{{ route('biolinks.editSocial', $post->id) }}" method="POST">
                        <input type="hidden" name="id" value="">
                        <div class="d-flex flex-wrap justify-content-between flex-column w-100 my-32">
                            <input class="form-control text-field" name="url" type="text"
                                   placeholder="{{ ___('URL') }}">
                            <input class="form-control text-field" name="title" type="hidden" value="">
                        </div>
                        <div class="d-flex align-items-center justify-content-between mt-16">
                            <p class="fw-semibold font-16 mb-0">{{ ___('Hide') }}</p>
                            <div class="form-check form-switch">
                                <input class="form-check-input cursor-pointer" name="active" type="checkbox"
                                       role="switch" id="HideSwitch">
                            </div>
                        </div>
                        <div class="position-relative mt-32">
                            <button class="button -primary w-100 -lg" type="submit">{{ ___('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--*****Edit Social Media ID End******-->

    <div id="quick-block-templates" hidden>
        <!--HEADER-->
        <div class="quick-reorder-element bioitems bio-link-header card py-16 px-16" data-type="header">
            <div class="d-flex align-items-center cursor-pointer">
                <div class="quick-reorder-icon drag-handle cursor-grab">
                    <i class="fa-solid fa-grip-dots-vertical font-30 text-grey-2"></i>
                </div>
                <div class="text-dark-1 font-14 fw-bold text-one-line word-break flex-grow-1">
                    <span class="quick-header-title ml-15">
                    <!--Dynamic Title Goes Here-->
                    </span>
                    <span class="quick-active-status d-none">
                        <i class="fa-solid fa-eye-slash text-info"></i>
                    </span>
                </div>
                <div class="d-flex align-items-center">
                    <div class="cursor-pointer" data-bs-toggle="modal" data-bs-target="#edit_header"><i
                            class="pr-16 fa-solid fa-edit text-grey-2"></i></div>
                    <div class="cursor-pointer item-js-delete" data-ajax-action="#"><i
                            class="fa-solid fa-trash text-grey-2"></i></div>
                </div>
            </div>
        </div>
        <!--LINKS-->
        <div class="quick-reorder-element bioitems bio-link-item card py-16 px-16" data-type="link">
            <div class="d-flex align-items-center cursor-pointer">
                <div class="d-flex justify-content-between flex-grow-1 gap-3 align-items-center pr-40">
                    <div class="d-flex align-items-center">
                        <div class="quick-reorder-icon drag-handle cursor-grab">
                            <i class="fa-solid fa-grip-dots-vertical font-30 text-grey-2"></i>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="quick-link-img">
                                <!--Dynamic Image Goes Here-->
                            </span>
                            <div class="ml-15">
                                <div class="text-dark-1 font-14 fw-bold text-one-line word-break">
                                    <span class="quick-link-title">
                                    <!--Dynamic Title Goes Here-->
                                    </span>
                                    <span class="quick-active-status d-none">
                                        <i class="fa-solid fa-eye-slash text-info"></i>
                                    </span>
                                </div>
                                <div class="text font-14 text-one-line word-break quick-link-url">
                                    <!--Dynamic URL Goes Here-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center d-none">
                        <i class="fa-solid fa-eye text-grey-2"></i>
                        <span class="count ml-8">0</span>
                    </div>
                </div>
                <div class="cursor-pointer pr-16" data-bs-toggle="modal" data-bs-target="#edit_link"><i
                        class="fa-solid fa-edit text-grey-2"></i></div>
                <div class="cursor-pointer item-js-delete" data-ajax-action="#"><i
                        class="fa-solid fa-trash text-grey-2"></i></div>
            </div>
        </div>
        <!--SOCIALS-->
        <div class="quick-reorder-element bioitems bio-social-item d-flex flex-wrap" data-type="social">
            <div class="w-100">
                <div class="mb-16">
                    <div class="d-flex cursor-pointer justify-content-between bg-white py-15 shadow-3 rounded-3">
                        <div class="d-flex align-items-center ">
                            <div class="quick-reorder-icon drag-handle cursor-grab  ml-16">
                                <i class="fa-solid fa-grip-dots-vertical font-30 text-grey-2"></i>
                            </div>
                            <span class="pl-16 pr-20"><i class="quick-link-icon"></i></span>
                            <div class="text-capitalize font-16">
                                <span class="quick-link-title">
                                    <!--Dynamic Title Goes Here-->
                                </span>
                                <span class="quick-active-status d-none">
                                    <i class="fa-solid fa-eye-slash text-info"></i>
                                </span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center ">
                            <div class="font-16 text-grey-1 text-one-line word-break pl-32 pr-24 quick-link-url">
                                <!--Dynamic URL Goes Here-->
                            </div>
                            <div class="cursor-pointer pr-16" data-bs-toggle="modal" data-bs-target="#edit_social_link">
                                <i class="fa-solid fa-edit text-grey-2"></i></div>
                            <div class="cursor-pointer pr-16 item-js-delete" data-ajax-action="#"><i
                                    class="fa-solid fa-trash text-grey-2"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts_at_top')
        <script>
            "use strict";
            var linkImagePath = @json(asset('storage/post/biolink/'));
            var defaultImageUrl = @json(asset('storage/post/default.png'));
        </script>
    @endpush
    @push('scripts_vendor')
        <script>
            "use strict";
            $(document).on('click', ".bioitems", function () {
                var $id = $(this).data("id"),
                    $type = $(this).data("type"),
                    $active = $(this).data("active"),
                    $settings = $(this).data("settings");
                if ($type == "header") {
                    var $item = $("#edit_header");
                } else if ($type == "link") {
                    var $item = $("#edit_link");
                    $item.find("input[name=url]").val($settings.url);
                    if ($settings.logo != "") {
                        $item.find("img").attr('src', linkImagePath + '/' + $settings.logo);
                    } else {
                        $item.find("img").attr('src', defaultImageUrl);
                    }
                    if ($settings.highlight == 0) {
                        $item.find("#highlightSwitch").prop('checked', false);
                    } else {
                        $item.find("#highlightSwitch").prop('checked', true);
                    }
                } else if ($type == "social") {
                    var $item = $("#edit_social_link");
                    $item.find("#editSocialHeaderLable").text($settings.title);
                    $item.find("input[name=url]").val($settings.url);

                }
                $item.find("input[name=id]").val($id);
                $item.find("input[name=title]").val($settings.title);
                console.log($active);
                if ($active == 0) {
                    $item.find("#HideSwitch").prop('checked', true);
                } else {
                    $item.find("#HideSwitch").prop('checked', false);
                }
            });

            $(document).on('click', ".social-link-item", function () {
                var $field_title = $(this).data("title"),
                    $field_type = $(this).data("type"),
                    $field_placeholder = $(this).data("placeholder");
                $("#addSocialHeaderLable").text($field_title);
                $("#addSocialModal input[name=title]").val($field_type);
                $("#addSocialModal input[name=url]").attr('placeholder', $field_placeholder);
            });

            /* Add Block Ajax Form */
            $(document).on("submit", ".add_block_form", function (e) {
                e.stopPropagation();
                e.preventDefault();
                var $form = $(this),
                    action = $form.attr('action'),
                    loader = $form.find('[type="submit"]'),
                    options = {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: action,
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                Snackbar.show({
                                    text: response.message,
                                    pos: 'bottom-center',
                                    showAction: false,
                                    actionText: "Dismiss",
                                    duration: 3000,
                                    textColor: '#fff',
                                    backgroundColor: '#383838'
                                });
                                if ($form.parents(".modal").length) {
                                    $form.trigger("reset");
                                    if (response.type == "link") {
                                        $form.find("img").attr('src', defaultImageUrl);
                                    }
                                    $form.parents(".modal").modal('hide');
                                }
                                add_block(response.type, response.id, response.settings);
                                $('#bioframe').attr('src', function (i, val) {
                                    return val;
                                });
                            } else {
                                Snackbar.show({
                                    text: response.message,
                                    pos: 'bottom-center',
                                    showAction: false,
                                    actionText: "Dismiss",
                                    duration: 3000,
                                    textColor: '#fff',
                                    backgroundColor: '#ee5252'
                                });
                            }
                            loader.removeClass('quick-loader').prop('disabled', false);
                        }
                    };
                loader.addClass('quick-loader').prop('disabled', true);
                $form.ajaxSubmit(options);
            });

            /* Edit Block Ajax Form */
            $(document).on("submit", ".edit_block_form", function (e) {
                e.stopPropagation();
                e.preventDefault();
                var $form = $(this),
                    action = $form.attr('action'),
                    loader = $form.find('[type="submit"]'),
                    options = {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: action,
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                Snackbar.show({
                                    text: response.message,
                                    pos: 'bottom-center',
                                    showAction: false,
                                    actionText: "Dismiss",
                                    duration: 3000,
                                    textColor: '#fff',
                                    backgroundColor: '#383838'
                                });
                                var $item = $('#blockid_' + response.id);
                                $item.data('active', response.active);
                                $item.data('settings', response.settings);
                                if (response.type == "header") {
                                    $item.find('.quick-header-title').text(response.settings.title);
                                } else if (response.type == "link") {
                                    $item.find('.quick-link-title').text(response.settings.title);
                                    $item.find('.quick-link-url').text(response.settings.url);

                                    if (response.settings.logo != "") {
                                        $item.find('.quick-link-img').html('<span class="ml-15"><img src="' + linkImagePath + '/' + response.settings.logo + '"/></span>');
                                    } else {
                                        $item.find('.quick-link-img').html("");
                                    }

                                    $form.find('img').attr('src', defaultImageUrl);
                                    $form.find('.form-check-input').prop('checked', false);
                                } else if (response.type == "social") {
                                    $item.find('.quick-link-url').text(response.settings.url);
                                }

                                if (response.active == 1) {
                                    $item.find('.quick-active-status').addClass('d-none');
                                } else {
                                    $item.find('.quick-active-status').removeClass('d-none');
                                }

                                if ($form.parents(".modal").length) {
                                    $form.trigger("reset");
                                    $form.parents(".modal").modal('hide');
                                }
                                $('#bioframe').attr('src', function (i, val) {
                                    return val;
                                });
                            } else {
                                Snackbar.show({
                                    text: response.message,
                                    pos: 'bottom-center',
                                    showAction: false,
                                    actionText: "Dismiss",
                                    duration: 3000,
                                    textColor: '#fff',
                                    backgroundColor: '#ee5252'
                                });
                            }
                            loader.removeClass('quick-loader').prop('disabled', false);
                        }
                    };
                loader.addClass('quick-loader').prop('disabled', true);
                $form.ajaxSubmit(options);
            });

            /* Delete single Block */
            $(document).on('click', '.item-js-delete', function (e) {
                e.stopPropagation();
                e.preventDefault();
                var $this = $(this),
                    action = $this.data('ajax-action'),
                    $item = $this.closest('.bioitems'),
                    data = {action: action, id: $item.data('id')};
                if (confirm(lang.are_you_sure)) {
                    $this.addClass('quick-loader').prop('disabled', true);
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: action,
                        type: 'POST',
                        data: data,
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                Snackbar.show({
                                    text: response.message,
                                    pos: 'bottom-center',
                                    showAction: false,
                                    actionText: "Dismiss",
                                    duration: 3000,
                                    textColor: '#fff',
                                    backgroundColor: '#383838'
                                });
                                $item.remove();
                                $('#bioframe').attr('src', function (i, val) {
                                    return val;
                                });
                            } else {
                                Snackbar.show({
                                    text: response.message,
                                    pos: 'bottom-center',
                                    showAction: false,
                                    actionText: "Dismiss",
                                    duration: 3000,
                                    textColor: '#fff',
                                    backgroundColor: '#ee5252'
                                });
                            }

                            $this.removeClass('quick-loader').prop('disabled', false);
                        }
                    });
                }
            });

            /* sortable */
            let $sortable = $('.quick-reorder-body');
            $sortable.each(function () {
                let $this = $(this);
                $this.sortable({
                    axis: 'y',
                    handle: '.quick-reorder-icon',
                    update: function (event, ui) {
                        var data = [];
                        $this.children('div').each(function () {
                            data.push($(this).data('id'));
                        });
                        $.ajax({
                            type: 'POST',
                            url: $this.data('action'),
                            dataType: 'json',
                            data: {position: data},
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (response) {
                                Snackbar.show({
                                    text: response.message,
                                    pos: 'bottom-center',
                                    showAction: false,
                                    actionText: "Dismiss",
                                    duration: 3000,
                                    textColor: '#fff',
                                    backgroundColor: '#383838'
                                });
                            }
                        });
                    }
                });
            });

            /**
             * Add new block.
             *
             * @param type
             * @param id
             * @param settings
             * @returns {*|jQuery}
             */
            function add_block(type, id, settings) {
                var $new_field = $('#quick-block-templates > div[data-type=' + type + ']').clone();
                var deleteRoute = '{{ route("biolinks.deleteLink", $post->id) }}';

                $new_field
                    .hide()
                    .attr('id', 'blockid_' + id)
                    .data('id', id)
                    .data('active', 1)
                    .data('settings', settings);

                $new_field.find('.item-js-delete').data('ajax-action', deleteRoute);
                if (type === "header") {
                    $new_field.find('.quick-header-title').text(settings.title);
                    // Add new field to the list.
                    $('#quick-blocks .quick-reorder-body').append($new_field);
                } else if (type === "link") {
                    $new_field.find('.quick-link-title').text(settings.title);
                    $new_field.find('.quick-link-url').text(settings.url);
                    if (settings.logo != "") {
                        $new_field.find('.quick-link-img').prepend('<span class="ml-15"><img src="' + linkImagePath + '/' + settings.logo + '"/></span>');
                    }
                    // Add new field to the list.
                    $('#quick-blocks .quick-reorder-body').append($new_field);
                } else if (type === "social") {
                    $new_field.find('.quick-link-title').text(settings.title);
                    $new_field.find('.quick-link-url').text(settings.url);

                    if (settings.title == "email") {
                        $new_field.find('.quick-link-icon').addClass('fa-regular fa-envelope');
                    } else if (settings.title == "phone") {
                        $new_field.find('.quick-link-icon').addClass('fa-regular fa-phone text-success');
                    } else if (settings.title == "threads") {
                        $new_field.find('.quick-link-icon').addClass(`text-${settings.title}`).html('<svg fill="currentColor" viewBox="0 0 512 512" style="fill: currentcolor; height: 1em; overflow: visible; width: 1em;"><path d="M331.5 235.7c2.2 .9 4.2 1.9 6.3 2.8c29.2 14.1 50.6 35.2 61.8 61.4c15.7 36.5 17.2 95.8-30.3 143.2c-36.2 36.2-80.3 52.5-142.6 53h-.3c-70.2-.5-124.1-24.1-160.4-70.2c-32.3-41-48.9-98.1-49.5-169.6V256v-.2C17 184.3 33.6 127.2 65.9 86.2C102.2 40.1 156.2 16.5 226.4 16h.3c70.3 .5 124.9 24 162.3 69.9c18.4 22.7 32 50 40.6 81.7l-40.4 10.8c-7.1-25.8-17.8-47.8-32.2-65.4c-29.2-35.8-73-54.2-130.5-54.6c-57 .5-100.1 18.8-128.2 54.4C72.1 146.1 58.5 194.3 58 256c.5 61.7 14.1 109.9 40.3 143.3c28 35.6 71.2 53.9 128.2 54.4c51.4-.4 85.4-12.6 113.7-40.9c32.3-32.2 31.7-71.8 21.4-95.9c-6.1-14.2-17.1-26-31.9-34.9c-3.7 26.9-11.8 48.3-24.7 64.8c-17.1 21.8-41.4 33.6-72.7 35.3c-23.6 1.3-46.3-4.4-63.9-16c-20.8-13.8-33-34.8-34.3-59.3c-2.5-48.3 35.7-83 95.2-86.4c21.1-1.2 40.9-.3 59.2 2.8c-2.4-14.8-7.3-26.6-14.6-35.2c-10-11.7-25.6-17.7-46.2-17.8H227c-16.6 0-39 4.6-53.3 26.3l-34.4-23.6c19.2-29.1 50.3-45.1 87.8-45.1h.8c62.6 .4 99.9 39.5 103.7 107.7l-.2 .2zm-156 68.8c1.3 25.1 28.4 36.8 54.6 35.3c25.6-1.4 54.6-11.4 59.5-73.2c-13.2-2.9-27.8-4.4-43.4-4.4c-4.8 0-9.6 .1-14.4 .4c-42.9 2.4-57.2 23.2-56.2 41.8l-.1 .1z"></path></svg>');
                    } else if (settings.title == "x") {
                        $new_field.find('.quick-link-icon').addClass(`text-${settings.title}`).html('<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16" style="fill: currentcolor; height: 1em; overflow: visible; width: 1em;"><path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865z"></path></svg>');
                    } else {
                        $new_field.find('.quick-link-icon').addClass(`fa-brands fa-${settings.title} text-${settings.title}`);
                    }
                    // Add new field to the list.
                    $('#quick-blocks-social .quick-reorder-body').append($new_field);
                }

                $new_field.fadeIn('fast');
                return $new_field;
            }

            /* Theme customization: color sync */
            function syncColorToHex(colorId, hexId) {
                var hex = $('#' + colorId).val();
                $('#' + hexId).val(hex);
            }

            function syncHexToColor(hexId, colorId) {
                var hex = $('#' + hexId).val();
                if (/^#[0-9A-Fa-f]{6}$/.test(hex)) {
                    $('#' + colorId).val(hex);
                }
            }

            $('#theme_button_color').on('input', function () { syncColorToHex('theme_button_color', 'theme_button_color_hex'); });
            $('#theme_button_color_hex').on('input', function () { syncHexToColor('theme_button_color_hex', 'theme_button_color'); });
            $('#theme_button_text_color').on('input', function () { syncColorToHex('theme_button_text_color', 'theme_button_text_color_hex'); });
            $('#theme_button_text_color_hex').on('input', function () { syncHexToColor('theme_button_text_color_hex', 'theme_button_text_color'); });
            $('#theme_text_color').on('input', function () { syncColorToHex('theme_text_color', 'theme_text_color_hex'); });
            $('#theme_text_color_hex').on('input', function () { syncHexToColor('theme_text_color_hex', 'theme_text_color'); });

            $('.theme-preset-btn').on('click', function () {
                var hex = $(this).data('hex');
                $('.theme-preset-btn').removeClass('theme-preset-active');
                $(this).addClass('theme-preset-active');
                $('#theme_button_color').val(hex);
                $('#theme_button_color_hex').val(hex);
                $('#theme_button_text_color').val("#ffffff" )
                $('#theme_text_color').val("#ffffff")
                $('#theme_button_text_color_hex').val("#ffffff")
                $('#theme_text_color_hex').val("#ffffff")
            });

            $('input[name="theme_background_type"]').on('change', function () {
                var v = $(this).val();
                $('.theme-bg-solid-wrap').toggle(v === 'solid');
                $('.theme-bg-gradient-wrap').toggle(v === 'gradient');
                $('.theme-bg-image-wrap').toggle(v === 'image');
            });

            $('#theme_browse_bg').on('click', function () { $('#theme_background_image_file').click(); });
            $('#theme_background_image_file').on('change', function () {
                if (this.files && this.files[0]) {
                    if (typeof readURL === 'function') {
                        readURL(this, 'theme_bg_preview_img');
                    }
                    $('.theme-bg-preview-wrap').removeClass('d-none');
                    $('#theme_background_image_path').val(this.files[0].name);
                    $('#remove_background_image').val('0');
                }
            });
            $('#theme_remove_bg').on('click', function () {
                $('#theme_background_image_file').val('');
                $('#theme_background_image_path').val('');
                $('#remove_background_image').val('1');
                $('.theme-bg-preview-wrap').addClass('d-none');
                $('#theme_bg_preview_img').attr('src', '');
            });
        </script>
    @endpush
@endsection

<div class="slidePanel-content">
    <header class="slidePanel-header">
        <div class="slidePanel-overlay-panel">
            <div class="slidePanel-heading">
                <h2>{{ ___('Edit') .' '. $gateway->name }}</h2>
            </div>
            <div class="slidePanel-actions">
                <button id="post_sidePanel_data" class="btn btn-icon btn-primary" title="{{ ___('Save') }}">
                    <i class="icon-feather-check"></i>
                </button>
                <button class="btn btn-default btn-icon slidePanel-close" title="{{ ___('Close') }}">
                    <i class="icon-feather-x"></i>
                </button>
            </div>
        </div>
    </header>
    <div class="slidePanel-inner">
        <form action="{{ route('admin.gateways.update', $gateway->id) }}" method="post" id="sidePanel_form">
            @csrf
            <div class="d-flex align-items-start justify-content-between gap-4">
                <div>
                    <label for="upload" class="btn btn-primary mb-2" tabindex="0">
                        <i class="fas fa-upload"></i>
                        <span class="d-none d-sm-block ms-2">{{ ___('Change Logo') }}</span>
                        <input name="avatar" type="file" id="upload" hidden
                               onchange="readURL(this,'uploadedLogo')"
                               accept="image/png, image/jpeg">
                    </label>
                    <p class="form-text mb-0">{{ ___('Allowed JPG, JPEG or PNG.') }}</p>
                </div>
                <img src="{{ asset('storage/payments/'.$gateway->logo) }}" alt="logo"
                     class="d-block rounded" width="150" id="uploadedLogo">
            </div>
            <hr>
            <div class="mb-3">
                {{quick_switch(___('Active'), 'status', $gateway->status == '1')}}
            </div>
            <div class="mb-3">
                <label class="form-label">{{ ___('Name') }} *</label>
                <input type="text" name="name" class="form-control" value="{{ $gateway->name }}"
                       required>
            </div>
            <div class="mb-3">
                <label class="form-label">{{ ___('Gateway fees') }} (%) *</label>
                <div class="input-group">
                    <input type="number" name="fees" class="form-control" placeholder="0"
                           value="{{ $gateway->fees }}">
                    <span class="input-group-text"><i class="icon-feather-percent"></i></span>
                </div>
            </div>
            @if (!is_null($gateway->test_mode))
                <div class="mb-3">
                    {{quick_switch(___('Test Mode'), 'test_mode', $gateway->test_mode == '1')}}
                </div>
            @endif

            @if(in_array($gateway->key, ['paypal', 'stripe']))
                <div class="mb-3">
                    <label class="form-label text-capitalize">{{ ___('Payment Mode') }}</label>
                    <select name="payment_mode" id="payment_mode" class="form-control">
                        <option value="one_time"
                                @selected(@$gateway->payment_mode == 'one_time')>{{___('One Time')}}</option>
                        <option value="recurring"
                                @selected(@$gateway->payment_mode == 'recurring')>{{___('Recurring')}}</option>
                        <option value="both"
                                @selected(@$gateway->payment_mode == 'both')>{{___('Both')}}</option>
                    </select>
                    <small class="text-muted">{!! ___('Webhook setup is required for the recurring payment.') !!}</small>
                </div>
            @endif

            @foreach ($gateway->credentials as $key => $value)
                <div class="mb-3">
                    <label class="form-label text-capitalize">{{ $gateway->name }}
                        {{ str_replace('_', ' ', $key) }}</label>
                    @if($gateway->key == 'wire_transfer')
                        <textarea name="credentials[{{ $key }}]" rows="3"
                                  class="tiny-editor form-control">{{ demo_mode() ? '' : $value }}</textarea>
                    @else
                        <input type="text" name="credentials[{{ $key }}]"
                               value="{{ demo_mode() ? '' : $value }}" class="form-control">
                    @endif
                </div>
            @endforeach

            @switch($gateway->key)
                @case('paypal')
                    <div class="mb-3">
                        <label class="form-label" for="paypal_webhook">{{___('WebHook Url')}}</label>
                        <input type="text" id="paypal_webhook" class="form-control"
                               value="{{url('webhook/'.$gateway->key)}}" disabled>
                        <small class="text-muted">{!! ___('Select the :EVENTS events for webhook.', ['EVENTS' => '<code>PAYMENT.SALE.COMPLETED</code>']) !!}</small>
                    </div>
                    <p>{{___('Get the API details from')}} <a
                                href="https://developer.paypal.com/developer/applications/create"
                                target="_blank">{{___('here')}} <i class="far fa-external-link"></i></a></p>
                    @break

                @case('stripe')
                    <div class="mb-3">
                        <label class="form-label" class="form-label" for="paypal_webhook">{{___('WebHook Url')}}</label>
                        <input type="text" id="paypal_webhook" class="form-control"
                               value="{{url('webhook/'.$gateway->key)}}" disabled>
                        <small class="text-muted">{!! ___('Select the :EVENTS events for webhook.', ['EVENTS' => '<code>checkout.session.completed</code>, <code>invoice.paid</code>, <code>invoice.upcoming</code>']) !!}</small>
                    </div>
                    <p>{{___('Get the API details from')}} <a href="https://dashboard.stripe.com/apikeys"
                                                              target="_blank">{{___('here')}} <i
                                    class="far fa-external-link"></i></a></p>
                    @break

                @case('mollie')
                    <p>{{___('Get the API details from')}} <a href="https://www.mollie.com/dashboard"
                                                              target="_blank">{{___('here')}} <i
                                    class="far fa-external-link"></i></a></p>
                    @break

                @case('razorpay')
                    <p>{{___('Get the API details from')}} <a href="https://dashboard.razorpay.com/app/keys"
                                                              target="_blank">{{___('here')}} <i
                                    class="far fa-external-link"></i></a></p>
                    @break

                @case('paddle')
                    <p>{{___('Get the API details from')}} <a
                                href="https://vendors.paddle.com/"
                                target="_blank">{{___('here')}} <i class="far fa-external-link"></i></a></p>
                    @break
            @endswitch
        </form>
    </div>
</div>

@if($gateway->key == 'wire_transfer')
    <script src="{{ asset('admin/assets/plugins/tinymce/tinymce.min.js') }}"></script>
    <script>
        if ($('.tiny-editor').length) {
            tinymce.init({
                selector: '.tiny-editor',
                height: 350,
                resize: true,
                plugins: 'quickbars image advlist lists code table codesample autolink link wordcount fullscreen help searchreplace media',
                toolbar: [
                    "bold italic underline strikethrough | alignleft aligncenter alignright  | link image media",
                    "removeformat | table | bullist numlist | code fullscreen"
                ],
                menubar: "",
                // link
                relative_urls: false,
                remove_script_host: false,
                convert_urls: false,
                link_assume_external_targets: true,
                // images
                image_advtab: true,
                extended_valid_elements: 'i[*]',
                content_style: 'body { font-size:16px }',
                smart_paste: false,
                setup: function (editor) {
                    editor.on('change', function () {
                        tinymce.triggerSave();
                    });
                }
            });
        }
    </script>
@endif

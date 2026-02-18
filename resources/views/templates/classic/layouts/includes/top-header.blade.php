<x-demo-frame />
@if(session()->get('quick_admin_user_id'))
    <div class="alert alert-info mb-0 py-5">
        <div class="d-flex justify-content-between">
            <span>{!! ___('You are logged in as :user_name.', ['user_name' => '<strong>'.request()->user()->name.'</strong>']) !!}</span>
            <a href="{{ route('logout') }}" class="text-decoration-underline"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ ___('Exit') }}
            </a>
        </div>
    </div>
@endif

@if (session()->has('flash_alert_message'))
    <apithy-flash-message :type="{{ json_encode(session()->get('flash_alert_type')) }}"
                          :header="{{ json_encode(session()->get('flash_alert_header')) }}"
                          :message="{{ json_encode(session()->get('flash_alert_message')) }}">
    </apithy-flash-message>
@endif

@if(count($errors))
    <article class="message is-danger">
        <div class="message-body">
            <ul class="list">
                @foreach ($errors->all() as $error)
                    <li>{!!  $error !!}</li>
                @endforeach
            </ul>
        </div>
    </article>
@endif
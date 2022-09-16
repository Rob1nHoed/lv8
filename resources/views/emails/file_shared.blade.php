@component('mail::message')
    
    {{ $details['user_name'] }} has sended you a file.
    
    {{ $details['description'] }}
    
    @component('mail::button', ['url' => $details['link']])
        View File
    @endcomponent
    
    Thanks,<br>
    {{ config('app.name') }}

@endcomponent                  

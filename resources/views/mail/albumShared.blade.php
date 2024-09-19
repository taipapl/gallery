<x-mail::message>
# Introduction

@lang('You have been invited to view an album.')

<x-mail::button :url="$url">
@lang('See Album')
</x-mail::button>


@if('profile_url')
<x-mail::button :url="$profile_url">
@lang('See Profile')
</x-mail::button>
@endif



@lang('Thanks'),<br>
{{ config('app.name') }}
</x-mail::message>

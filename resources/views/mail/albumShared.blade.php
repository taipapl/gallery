<x-mail::message>
# Introduction

@lang('You have been invited to view an album.')

<x-mail::button :url="$url">
@lang('See Album')
</x-mail::button>

@lang('Thanks'),<br>
{{ config('app.name') }}
</x-mail::message>

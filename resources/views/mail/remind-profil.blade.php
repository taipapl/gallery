<x-mail::message>
# Introduction

@lang('User wants remind you about his profile')

<x-mail::button :url="$url">
@lang('See Profil')
</x-mail::button>

@lang('Thanks'),<br>
{{ config('app.name') }}
</x-mail::message>

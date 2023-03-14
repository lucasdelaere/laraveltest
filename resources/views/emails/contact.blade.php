<x-mail::message>
<h1>Message from website www.laravelblog.com</h1>
    <x-mail::panel>
        {{-- request is a laravel function that looks for any incoming request on this page --}}
        <p>Name:{{request()->name}}</p>
        <p>Name:{{request()->email}}</p>
        <p>Name:{{request()->message}}</p>
    </x-mail::panel>
    {{-- colors: primary, success en error --}}
<x-mail::button :url="''" color="success">
Verify
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
    <!-- change app.name in .env to your firm's name -->
</x-mail::message>

@props(['heading'])
<h1>{{$heading}}</h1>
@if(session('status'))
    <div class="alert {{session('status')[1]}}"> <!-- class alert hier belangrijk -->
        <a href="" class="close" data-dismiss="alert" aria-label="close" title="close">x</a>
        <strong>Success!</strong> {{session('status')[0]}}
    </div>
@endif

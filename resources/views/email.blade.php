@extends('bell::includes.layout')

@section('content')
    {!!  $content ?? '' !!}
    <p>
        Best Regards,<br>
        {{ config('app.name') }} Support Team
    </p>
@endsection

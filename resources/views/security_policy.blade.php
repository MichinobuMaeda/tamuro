@extends('layouts.app')
@inject('vh', 'App\Services\ViewHelperService')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          @include('layouts.logo')
          {{ __('Security policy') }}
        </div>
        <div class="card-body">
          @component('parts.multi_line_message')
            Description of security policy ...
          @endcomponent
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

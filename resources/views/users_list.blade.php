@extends('layouts.app')
@inject('vh', 'App\Services\ViewHelperService')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-12 col-xl-10">
      <div class="card">
        <div class="card-header">{{ __('Account list') }}</div>

        <div class="card-body">
          <table class="table table-striped table-sm">
            <thead>
              <tr>
              <th>
                  <a href="{{ route('users.list.orderBy', ['orderBy' => 'id', 'orderDir' => ($orderBy =='id' && $orderDir == 'asc' ? 'desc' : 'asc')]) }}">
                    <i class="fas fa-sort"></i>
                    {{ __('ID') }}
                  </a>
                </th>
                <th>
                  <a href="{{ route('users.list.orderBy', ['orderBy' => 'name', 'orderDir' => ($orderBy =='name' && $orderDir == 'asc' ? 'desc' : 'asc')]) }}">
                    <i class="fas fa-sort"></i>
                    {{ __('Display name') }}
                  </a>
                </th>
                <th>
                  <a href="{{ route('users.list.orderBy', ['orderBy' => 'email', 'orderDir' => ($orderBy =='email' && $orderDir == 'asc' ? 'desc' : 'asc')]) }}">
                    <i class="fas fa-sort"></i>
                    {{ __('E-mail') }}
                  </a>
                </th>
                <th>
                  <a href="{{ route('users.list.orderBy', ['orderBy' => 'invited_at', 'orderDir' => ($orderBy =='invited_at' && $orderDir == 'asc' ? 'desc' : 'asc')]) }}">
                    <i class="fas fa-sort"></i>
                    {{ __('Invitation') }}
                  </a>
                </th>
                <th>
                  <a href="{{ route('users.list.orderBy', ['orderBy' => 'entered_at', 'orderDir' => ($orderBy =='entered_at' && $orderDir == 'asc' ? 'desc' : 'asc')]) }}">
                    <i class="fas fa-sort"></i>
                    {{ __('Entrance') }}
                  </a>
                </th>
                <th>
                </th>
              </tr>
            </thead>
            <tbody>
            @foreach ($users as $user)
              <tr>
                <td>
                  {{ $user->id }}
                </td>
                <td>
                  {{ $user->name }}
                </td>
                <td>
                @if (preg_match("/.+@.+\..+/", $user->email))
                  {{ $user->email }}
                @endif
                </td>
                <td>
                  @if (($user->invited_at))
                  <a href="{{ route('get.invitation', ['user'=>$user->id, 'sendBy' => 'message']) }}">
                    {{ $vh->formatTimestamp($user->invited_at) }}
                  </a>
                  @endif
                </td>
                <td>
                  {{ $vh->formatTimestamp($user->entered_at) }}
                </td>
                <td>
                  <form id="{{ $user->id }}" method="POST" action="{{ route('post.invitation', ['user'=>$user->id]) }}"> 
                    @csrf
                    <button type="submit" name="sendBy" value="message" class='btn btn-sm' style='background-color:transparent;'>
                      <i class="far fa-comment"></i>
                    </button>
                    @if (preg_match("/.+@.+\..+/", $user->email))
                    &nbsp;
                    <button type="submit" name="sendBy" value="email" class='btn btn-sm' style='background-color:transparent;'>
                      <i class="fas fa-envelope"></i>
                    </button>
                    @endif
                  </form>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
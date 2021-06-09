@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        {{ $title }}
                        <div class="text-right my-2">
                            <a class="btn btn-primary" href="{{ route('parkings.create') }}" >{{ __('Create') }}</a>
                        </div>
                    </div>

                    <div class="card-body">

                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">@lang('Title')</th>
                                <th scope="col">@lang('Region')</th>
                                <th scope="col">@lang('Address')</th>
                                <th scope="col">@lang('Timezone')</th>
                                <th scope="col">@lang('Legal entities')</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($parkings as $parking)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>
                                        <a href="{{ route('parkings.show', ['parking'=>$parking->id]) }}">{{ $parking->title }}</a>
                                    </td>
                                    <td>{{ $parking->code }}</td>
                                    <td>{{ $parking->address }}</td>
                                    <td>{{ $parking->timezone }}</td>
                                    <td>
                                        @if($parking->legals->isNotEmpty())
                                            <a href="{{ route('legals.parkings.view', ['parking'=>$parking->id,
                                                                'legal'=>$parking->legals->pluck('id')->first()]) }}">
                                                {{ $parking->legals->pluck('name')->first() }}
                                            </a>
                                            <br>
                                            <a href="{{ route('legals.parkings.all', ['parking'=>$parking->id]) }}">{{ __('show all') }}</a>
                                        @else
                                            <p>{{ __('No Legal entity') }}</p>
                                        @endif
                                    </td>

                                    <td class="text-right">
                                        <div class="btn-group">
{{--                                            @can('user_update')--}}
                                                <a class="btn btn-primary" href="{{ route('parkings.edit', ['parking'=>$parking->id]) }}">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                </a>
{{--                                            @endcan--}}

{{--                                            @can(['delete_self', 'user_delete'], $user)--}}
                                                <a class="btn btn-danger" onclick="if( confirm('Delete it?') ) { event.preventDefault();
                                                    document.getElementById('deleteUser{{ $parking->id }}').submit(); return true }">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </a>
{{--                                            @endcan--}}

                                        </div>

{{--                                        @can(['delete_self', 'user_delete'], $user)--}}
                                            <form id="deleteUser{{ $parking->id }}"
                                                  method="POST"
                                                  action="{{ route('parkings.destroy', ['parking'=>$parking->id]) }}">
                                                @csrf
                                                @method('DELETE')
                                            </form>
{{--                                        @endcan--}}

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

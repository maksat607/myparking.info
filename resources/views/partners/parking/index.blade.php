@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ $title }}
                        <form class="form-inline justify-content-between" method="POST" action="{{ route('partner.parkings.add') }}">
                            @csrf

                            <div class="form-group flex-fill">
                                <label for="parkingSearch" class="sr-only">{{ __('Parking search') }}</label>
                                <select id="parkingSearch" class="get-parking-ajax @error('parking') is-invalid @enderror" name="parking"></select>
                            </div>
                            <button type="submit" class="btn btn-primary ml-2">{{ __('Add') }}</button>

                        </form>
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
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($partnerParkings as $parking)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>
                                        {{ $parking->title }}
                                    </td>
                                    <td>{{ $parking->code }}</td>
                                    <td>{{ $parking->address }}</td>
                                    <td>{{ $parking->timezone }}</td>

                                    <td class="text-right">
                                        <div class="btn-group">

                                            {{--                                            @can(['delete_self', 'user_delete'], $user)--}}
                                            <a class="btn btn-danger" onclick="if( confirm('Delete it?') ) { event.preventDefault();
                                                document.getElementById('removeParking{{ $parking->id }}').submit(); return true }">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </a>
                                            {{--                                            @endcan--}}

                                        </div>

                                        {{--                                        @can(['delete_self', 'user_delete'], $user)--}}
                                        <form id="removeParking{{ $parking->id }}"
                                              method="POST"
                                              action="{{ route('partner.parkings.remove', ['parking'=>$parking->id]) }}">
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

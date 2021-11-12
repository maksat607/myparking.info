@extends('layouts.app')

@section('content')
    <section class="setting">
        <div class="wrapper">
            <h2 class="setting__title">{{ $title }}</h2>
            <div class="setting__bar s-between">
                <input type="text" class="input setting__search" placeholder="Поиск по столбцам">
                <a class="btn blue-btn" href="{{ route('parkings.create') }}">{{ __('Create') }}</a>
            </div>
            <div class="table-response">
                <table class="setting__table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">@lang('Title')</th>
                            <th scope="col">@lang('Region')</th>
                            <th scope="col">@lang('Address')</th>
{{--                            <th scope="col">@lang('Timezone')</th>--}}
                            <th scope="col">@lang('Legal entities')</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($parkings as $parking)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>
                                    <a
                                        href="{{ route('parkings.edit', ['parking' => $parking->id]) }}">{{ $parking->title }}</a>
                                </td>
                                <td>{{ $parking->code }}</td>
                                <td>{{ $parking->address }}</td>
{{--                                <td>{{ $parking->timezone }}</td>--}}
                                <td>

                                    @if ($parking->legals->isNotEmpty())
                                        <a
                                            href="{{ route('legals.parkings.view', ['parking' => $parking->id, 'legal' => $parking->legals->pluck('id')->first()]) }}">
                                            {{ $parking->legals->pluck('name')->first() }}
                                        </a>
                                        @if($parking->legals->count() > 1)
                                        <br>
                                        <a
                                            href="{{ route('legals.parkings.all', ['parking' => $parking->id]) }}">{{ __('show all') }}</a>
                                        @endif
                                    @else
                                        <p>{{ __('No Legal entity') }}</p>
                                    @endif
                                </td>

                                <td class="text-right">
                                    <div class="btn-group">
                                        {{-- @can('user_update') --}}
                                        <a class="tbale-btn"
                                            href="{{ route('parkings.edit', ['parking' => $parking->id]) }}">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                        </a>
                                        {{-- @endcan --}}

                                        {{-- @can(['delete_self', 'user_delete'], $user) --}}
                                        <a class="tbale-btn"
                                            onclick="if( confirm('Delete it?') ) { event.preventDefault();
                                                    document.getElementById('deleteUser{{ $parking->id }}').submit(); return true }">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                        </a>
                                        {{-- @endcan --}}

                                    </div>

                                    {{-- @can(['delete_self', 'user_delete'], $user) --}}
                                    <form id="deleteUser{{ $parking->id }}" method="POST"
                                        action="{{ route('parkings.destroy', ['parking' => $parking->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    {{-- @endcan --}}

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

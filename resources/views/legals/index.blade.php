@extends('layouts.app')

@section('content')
    <div class="container page-head-wrap">
        <div class="page-head">
            <div class="page-head__top d-flex align-items-center">
                <h1>{{ $title }}</h1>
                <label class="field-style blue">
                    <span>Поиск</span>
                    <input type="text" id="keyword" placeholder="Поиск по столбцам">
                </label>
                <div class="ml-auto d-flex">
                    <a href="{{ route('legals.create') }}" class="btn btn-white">Добавить</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container" id="searchable">
        <div class="inner-page">
            @if ($legals->isNotEmpty())
            <table class="table">
                <thead>
                <tr>
                    <th></th>
                    <th>Название</th>
                    <th>ИНН</th>
                    <th>ОГРН</th>
                    <th>КПП</th>
                    <th>Стоянки <span style="color: darkred">новый блок</span></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($legals as $legal)
                <tr @if(!$legal->status)class="disabled-tr"@endif>
                    <td class="tr-id">{{ $loop->iteration }}</td>
                    <td>
                        <div class="first-info d-flex align-items-center">
                            <span class="status-dot status-success">&bull;</span>
                            <span>{{ $legal->name }}</span>
                        </div>
                    </td>
                    <td>{{ $legal->inn }}</td>
                    <td>{{ $legal->reg_number }}</td>
                    <td>{{ $legal->kpp }}</td>
                    <td>Лужнецкая эстакада ТТК <span style="color: darkred">новый блок</span></td>
                    <td>
                        <div class="car-dd">
                            <div class="car-close-dd"></div>
                            <div class="car-dd-body">
                                @can('legal_update')
                                <a href="{{ route('legals.edit', ['legal' => $legal->id]) }}" class="link">
                                    <svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13.2929 0.292893C13.6834 -0.0976311 14.3166 -0.0976311 14.7071 0.292893L18.7071 4.29289C19.0976 4.68342 19.0976 5.31658 18.7071 5.70711L5.70711 18.7071C5.51957 18.8946 5.26522 19 5 19H1C0.447715 19 0 18.5523 0 18V14C0 13.7348 0.105357 13.4804 0.292893 13.2929L10.2927 3.2931L13.2929 0.292893ZM11 5.41421L2 14.4142V17H4.58579L13.5858 8L11 5.41421ZM15 6.58579L16.5858 5L14 2.41421L12.4142 4L15 6.58579Z" fill="#536E9B"></path>
                                    </svg>
                                </a>
                                @endcan
                                @can('legal_delete')
                                <a href="#" onclick="if( confirm('Delete it?') ) { event.preventDefault();
                                    document.getElementById('deleteLegal{{ $legal->id }}').submit(); return true }" class="link">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g opacity="0.6">
                                            <path d="M7 4C7 2.89543 7.89543 2 9 2H15C16.1046 2 17 2.89543 17 4V6H18.9897C18.9959 5.99994 19.0021 5.99994 19.0083 6H21C21.5523 6 22 6.44772 22 7C22 7.55228 21.5523 8 21 8H19.9311L19.0638 20.1425C18.989 21.1891 18.1182 22 17.0689 22H6.93112C5.88184 22 5.01096 21.1891 4.9362 20.1425L4.06888 8H3C2.44772 8 2 7.55228 2 7C2 6.44772 2.44772 6 3 6H4.99174C4.99795 5.99994 5.00414 5.99994 5.01032 6H7V4ZM9 6H15V4H9V6ZM6.07398 8L6.93112 20H17.0689L17.926 8H6.07398ZM10 10C10.5523 10 11 10.4477 11 11V17C11 17.5523 10.5523 18 10 18C9.44772 18 9 17.5523 9 17V11C9 10.4477 9.44772 10 10 10ZM14 10C14.5523 10 15 10.4477 15 11V17C15 17.5523 14.5523 18 14 18C13.4477 18 13 17.5523 13 17V11C13 10.4477 13.4477 10 14 10Z" fill="#EB5757"></path>
                                        </g>
                                    </svg>
                                </a>
                                <form id="deleteLegal{{ $legal->id }}" method="POST"
                                      action="{{ route('legals.destroy', ['legal' => $legal->id]) }}">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                @endcan
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            @else
                <p>{{ __('Legal entities have not yet been created. Create at least one record.') }}</p>
            @endif
        </div>
    </div>
@endsection

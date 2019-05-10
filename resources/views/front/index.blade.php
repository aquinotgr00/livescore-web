@extends('_layouts/wrapper')

@section('content')

    <div class="row">
        <div class="col-md-2 pl-md-0">
            <div class="list-group">
                <a class="list-group-item list-group-item-action d-block text-left" href="#">
                    Liga 1
                </a>
                <a class="list-group-item list-group-item-action d-block text-left" href="#">
                    Liga 2
                </a>
                <a class="list-group-item list-group-item-action d-block text-left" href="#">
                    Liga 3
                </a>
            </div>
        </div>

        <div class="col-md-10">
            @if (isset($error))
                @if (isset($error['request']))
                    {{$error['request']}}<br/>
                @elseif (isset($error['response']))
                    {{$error['response']}}<br/>
                @endif
                Server timed out. Please refresh several times.
            @elseif(!isset($error))
                <div class="row mx-0 mb-3">
                    @foreach ($days as $day)
                        @php $today = date('M d'); @endphp
                        <div class="col p-0">
                            @if ($day==$today)
                                <a href="/" class="btn btn-outline-dark w-100 active">Today</a>
                            @elseif ($day!==$today)
                                <a href="#" class="btn btn-outline-dark w-100">{{$day}}</a>
                            @endif
                        </div>
                    @endforeach
                </div>
                <table class="table">
                @foreach ($data as $value => $item)
                    @if ($value == 0 || $item->strLeague !== $data[$value-1]->strLeague)
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-left" colspan="4">
                                    Country
                                    <a href="#">{{$item->strLeague}}</a>
                                </th>
                                <th class="text-right">
                                    <a href="#">{{$item->strDate}}</a>
                                </th>
                            </tr>
                        </thead>
                    @endif
                        <tr>
                            <td class="text-left">{{$item->strTime}}</td>
                            <td class="text-right">{{$item->strHomeTeam}}</td>
                            <td>{{$item->intHomeScore ?: '?'}} - {{$item->intAwayScore ?: '?'}}</td>
                            <td class="text-left">{{$item->strAwayTeam}}</td>
                            <td class="text-right"><span>*</span></td>
                        </tr>
                @endforeach
                </table>
            @endif
        </div>
    </div>
@endsection
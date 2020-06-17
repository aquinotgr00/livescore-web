@extends('_layouts/wrapper')

@section('content')
    <div class="row mx-0">
        <div class="col-md-2 px-0 pt-1 border-right">
            <ul class="list-group text-left countries-nav" style="list-style:none;">
                <li class="border-bottom pl-4 py-2 {{ Request::is('country-match/england') || Request::is('country-match-league/england/*') ? 'active' : '' }}">
                    <a href="{{ route('homepage.get-country-leagues', 'england') }}">
                        England
                    </a>
                </li>
                <li class="border-bottom pl-4 py-2 {{ Request::is('country-match/italy') || Request::is('country-match-league/italy/*') ? 'active' : '' }}">
                    <a href="{{ route('homepage.get-country-leagues', 'italy') }}">
                        Italy
                    </a>
                </li>
                <li class="border-bottom pl-4 py-2 {{ Request::is('country-match/spain') || Request::is('country-match-league/spain/*') ? 'active' : '' }}">
                    <a href="{{ route('homepage.get-country-leagues', 'spain') }}">
                        Spain
                    </a>
                </li>
                <li class="border-bottom pl-4 py-2 {{ Request::is('country-match/germany') || Request::is('country-match-league/germany/*') ? 'active' : '' }}">
                    <a href="{{ route('homepage.get-country-leagues', 'germany') }}">
                        Germany
                    </a>
                </li>
                <li class="border-bottom pl-4 py-2 {{ Request::is('country-match/france') || Request::is('country-match-league/france/*') ? 'active' : '' }}">
                    <a href="{{ route('homepage.get-country-leagues', 'france') }}">
                        France
                    </a>
                </li>
                <li class="border-bottom pl-4 py-2 {{ Request::is('country-match/netherlands') || Request::is('country-match-league/netherlands/*') ? 'active' : '' }}">
                    <a href="{{ route('homepage.get-country-leagues', 'netherlands') }}">
                        Netherlands
                    </a>
                </li>
                <li class="border-bottom pl-4 py-2 {{ Request::is('country-match/belgium') || Request::is('country-match-league/belgium/*') ? 'active' : '' }}">
                    <a href="{{ route('homepage.get-country-leagues', 'belgium') }}">
                        Belgium
                    </a>
                </li>
                <li class="border-bottom pl-4 py-2 {{ Request::is('country-match/portugal') || Request::is('country-match-league/portugal/*') ? 'active' : '' }}">
                    <a href="{{ route('homepage.get-country-leagues', 'portugal') }}">
                        Portugal
                    </a>
                </li>
                <li class="border-bottom pl-4 py-2 {{ Request::is('country-match/scotland') || Request::is('country-match-league/scotland/*') ? 'active' : '' }}">
                    <a href="{{ route('homepage.get-country-leagues', 'scotland') }}">
                        Scotland
                    </a>
                </li>
            </ul>
            <br>
            <div class="border" style="min-height:250px;">
                <img class="img-fluid rounded mb-12 mb-lg-0" src="{{ asset('img/banner1.png') }}" alt="">
            </div>
        </div>
        <div class="col-md-7 pt-1">


            @if (isset($days))
                <div class="row mx-0 mb-3">
                    @foreach ($days as $day)
                        @php $today = date('M d'); @endphp
                        <div class="col p-0">
                            @if ($day['display']==$today)
                                <a href="/" class="btn w-100 border-bottom week-nav {{ request()->is('/') ? 'active' : '' }}">
                                    Today
                                </a>
                            @elseif ($day['display']!==$today)
                                <a href="{{ route('homepage.get-matches-by-date', $day['format']) }}"
                                    class="btn w-100 border-bottom week-nav {{ request()->is('match/'.$day['format'].'') ? 'active' : '' }}"
                                >
                                    {{$day['display']}}
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @elseif (isset($leagueIds))
                <div class="row mx-0 mb-3 row-leagues">
                    <div class="col-md-5 form-group px-0 mb-0 mt-2 rounded-0">
                        <select class="form-control rounded-0 lv-league-select" name="league" data-country="{{$country}}" id="league">
                            @foreach ($leagueIds as $key => $league)
                                @if ($key == 0)
                                    <option {{ Request::is('country-match/*') ? 'selected="selected"' : '' }}
                                        value="{{ $league->idLeague }}">
                                            {{ $league->strLeague }}
                                    </option>
                                @elseif (!request()->is('/country-match/'. $country. '/'))
                                    <option {{ request()->is('country-match-league/'.$country.'/'.$league->idLeague) ? 'selected="selected"' : '' }}
                                        value="{{ $league->idLeague }}">
                                            {{ $league->strLeague }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    {{-- @foreach ($leagueIds as $key => $league)
                        <div class="col p-0 col-leagues">
                            @if ($key == 0)
                                <a href="{{ route('homepage.get-country-leagues', $country) }}" class="btn btn-outline-dark w-100 {{ Request::is('country-match/*') ? 'active' : '' }}">
                                    {{$league->strLeague}}
                                </a>
                            @elseif (!request()->is('/country-match/'. $country. '/'))
                                <a href="{{ route('homepage.get-matches-by-country', ['country' => $country, 'league' => $league->idLeague]) }}"
                                    class="btn btn-outline-dark w-100 {{ request()->is('country-match-league/'.$country.'/'.$league->idLeague) ? 'active' : '' }}"
                                >
                                    {{$league->strLeague}}
                                </a>
                            @endif
                        </div>
                    @endforeach --}}
                </div>
            @endif

            @if (isset($error))
                {{$error}}
            @elseif(!isset($error))
                <table class="table">

                    @if(Request::is('country-match/*') || Request::is('country-match-league/*'))
                        @foreach ($data as $value => $item)
                            @if ($value == 0 || $item->dateEvent !== $data[$value-1]->dateEvent)
                                <thead class="lv-thead-blue">
                                    <tr>
                                        <th class="text-left lv-thead-bold" colspan="3">
                                            <a href="#">Round: {{$item->intRound}}</a>
                                        </th>
                                        <th class="text-right lv-thead-bold" colspan="2">
                                            <a href="#">{{$item->strDate ?: $item->dateEvent}}</a>
                                        </th>
                                    </tr>
                                </thead>
                            @endif
                                <tr>
                                    <td class="text-left">{{date("h:i",strtotime($item->strTime))}}</td>
                                    <td class="text-right">{{$item->strHomeTeam}}</td>
                                    <td>
                                        {{$item->intHomeScore == null ? ' ':$item->intHomeScore}} - 
                                        {{$item->intAwayScore == null ? ' ':$item->intAwayScore}}
                                    </td>
                                    <td class="text-left">{{$item->strAwayTeam}}</td>
                                    <td class="text-right"><span>*</span></td>
                                </tr>
                        @endforeach
                    @elseif(!Request::is('country-match/*'))
                        @foreach ($data as $value => $item)
                            @if ($value == 0 || $item->strLeague !== $data[$value-1]->strLeague || $item->dateEvent !== $data[$value-1]->dateEvent)
                                <thead class="lv-thead-blue">
                                    <tr>
                                        <th class="text-left lv-thead-bold" colspan="3">
                                            <a href="#">{{$item->strLeague}} - Round: {{$item->intRound}}</a>
                                        </th>
                                        <th class="text-right lv-thead-bold" colspan="2">
                                            <a href="#">{{$item->strDate ?: $item->dateEvent}}</a>
                                        </th>
                                    </tr>
                                </thead>
                            @endif
                                <tr>
                                    <td class="text-left">{{date("h:i",strtotime($item->strTime))}}</td>
                                    <td class="text-right">{{$item->strHomeTeam}}</td>
                                    <td>
                                        {{$item->intHomeScore == null ? ' ':$item->intHomeScore}} - 
                                        {{$item->intAwayScore == null ? ' ':$item->intAwayScore}}
                                    </td>
                                    <td class="text-left">{{$item->strAwayTeam}}</td>
                                    <td class="text-right"><span>*</span></td>
                                </tr>
                        @endforeach
                    @endif

                </table>
            @endif


        </div>
        <div class="col-md-3 px-0">
            <div class="border" style="min-height:350px;">
                <img class="img-fluid rounded mb-12 mb-lg-0" src="{{ asset('img/banner2.png') }}" alt="">
            </div>
            <div class="border mt-2" style="min-height:350px;">
                <img class="img-fluid rounded mb-12 mb-lg-0" src="{{ asset('img/banner3.png') }}" alt="">
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('select[name=league]').change(function () {
                console.log($(this).data('country'));
                window.location = '/country-match-league/' + $(this).data('country') + '/' + $(this).val();
            })
        })
    </script>
@endsection
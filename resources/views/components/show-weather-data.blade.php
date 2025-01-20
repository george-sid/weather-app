@if($values)
<!-- the result of database it shows 2 table one table for hourly and one for daily-->
    <div class="title-section">
        <h2>Weather data hourly</h2>
    </div>
    <table id="hourlyTable" class="table table-bordered" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2; text-align: center;">
                <th>Location</th>
                <th>Weather API sitename</th>
                <th>Date</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            @foreach($values['values'] as $value)
                @if($value->step == 'hourly')
                    <tr class="main-row" style="text-align: center;">
                        <td>{{ $value->location->name }}</td>
                        <td><a href="{{ $value->weatherApi->website_url }}">{{ $value->weatherApi->name }}</td></a>
                        <td>{{ $value->date }}</td>
                        <td>
                            <button class="btn btn-info toggleDetails" style="cursor: pointer; padding: 5px 10px;">+</button>
                        </td>
                    </tr>
                    <tr class="child-row" style="display:none;">
                        <td colspan="4" style="text-align: left; padding: 20px;">
                            <table class="table table-bordered" style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr style="background-color: #f9f9f9; text-align: center;">
                                        <th>Time</th>
                                        <th>Temperature</th>
                                        <th>Precipitation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($value->time as $index => $time)
                                        <tr style="text-align: center;">
                                            <td>{{ $time }}</td>
                                            <td>{{ $value->temperature[$index] }}°C</td>
                                            <td>{{ $value->precipitation[$index] }} mm</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    <div class="title-section" style="margin-top:20px">
        <h2>Weather data daily</h2>
    </div>
    <table id="dailyTable" class="table table-bordered" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2; text-align: center;">
                <th>Location</th>
                <th>Weather API sitename</th>
                <th>Date</th>
                <th>Temperature </th>
                <th>Precipitation </th>
            </tr>
        </thead>
        <tbody>
            @foreach($values['values'] as $value)
                @if($value->step == 'daily')
                    <tr style="text-align: center;">
                    <td>{{ $value->location->name }}</td>
                    <td><a href="{{ $value->weatherApi->website_url }}">{{ $value->weatherApi->name }}</td></a>
                        <td>{{ $value->date }}</td>
                        <td>{{ $value->temperature[0] }} {{ $value->temperature_unit }}</td>
                        <td>{{ $value->precipitation[0] }} mm</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

@endif

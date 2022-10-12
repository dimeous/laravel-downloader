@extends('base')

@section('content')

    <div>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">URL</th>
                <th scope="col">Resource url</th>
                <th scope="col">Status</th>
                <th scope="col">Timestamp</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($downloads as $download)
                <tr>
                    <td>{{ $download->id }}</td>
                    <td>{{ $download->url }}</td>
                    <td>@if ($download->resource_url) <a href="{{$download->resource_url}}">Link to resource</a> @endif</td>
                    <td>{{ $download->status }}</td>
                    <td>{{ $download->created_at }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


@endsection

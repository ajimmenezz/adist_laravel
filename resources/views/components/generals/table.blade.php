<table id="{{ $id }}" class="table table-bordered text-nowrap border-bottom mb-4 w-100 {{ $classes }}">
    <thead>
        <tr>
            @foreach ($headers as $item)
                <th class="{{ implode(' ', $item['classes']) }}">{{ $item['label'] }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

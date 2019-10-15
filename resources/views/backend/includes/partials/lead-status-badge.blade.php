@switch ($status)

    @case('new')
        <span class="badge badge-info">{{ $status }}</span>
        @break

    @case('append_failed')
    @case('destination_failed')
        <span class="badge badge-danger">{{ $status }}</span>
        @break

    @case('appended')
    @case('complete')
        <span class="badge badge-success">{{ $status }}</span>
        @break

    @default
        <span class="badge badge-info">{{ $status }}</span>
        @break

@endswitch

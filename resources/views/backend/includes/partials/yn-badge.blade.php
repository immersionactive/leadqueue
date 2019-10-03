@if ($active)
    <span class="badge badge-success">{{ isset($yes_text) ? $yes_text : 'Yes' }}</span>
@else
    <span class="badge badge-danger">{{ isset($no_text) ? $no_text : 'No' }}</span>
@endif

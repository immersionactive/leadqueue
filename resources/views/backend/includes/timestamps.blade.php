{{-- TODO: also display the trashed() timestamp, if the model uses SoftDeletes. See here: https://laracasts.com/discuss/channels/eloquent/check-if-a-model-uses-soft-deleting --}}

@if ($model->timestamps)
    <div class="float-right text-muted mt-4">
        <strong>Created At:</strong> {{ timezone()->convertToLocal($model->created_at) }} ({{ $model->created_at->diffForHumans() }}),
        <strong>Last Updated:</strong> {{ timezone()->convertToLocal($model->updated_at) }} ({{ $model->updated_at->diffForHumans() }})
    </div>
@endif

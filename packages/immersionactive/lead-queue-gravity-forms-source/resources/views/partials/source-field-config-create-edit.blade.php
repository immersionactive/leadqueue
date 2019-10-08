{{-- Field Name --}}

<div class="form-group row">
    
    {{ html()->label('Field Name')
        ->class('col-md-4 form-control-label')
        ->for('source_field_config[field_name]')
    }}

    <div class="col-md-8">
        {{ html()->text('source_field_config[field_name]', $source_field_config->field_name)
            ->class('form-control')
            ->placeholder('Field Name')
            ->attribute('maxlength', 255)
            ->required()
            ->autofocus()
        }}
    </div>
    
</div>

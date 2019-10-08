{{-- Field Name --}}

<div class="form-group row">
    
    {{ html()->label('Contact Field Name')
        ->class('col-md-4 form-control-label')
        ->for('destination_field_config[contact_field_name]')
    }}

    <div class="col-md-8">
        {{ html()->text('destination_field_config[contact_field_name]', $destination_field_config->contact_field_name)
            ->class('form-control')
            ->placeholder('Contact Field Name')
            ->attribute('maxlength', 255)
            ->required()
            ->autofocus()
        }}
    </div>
    
</div>

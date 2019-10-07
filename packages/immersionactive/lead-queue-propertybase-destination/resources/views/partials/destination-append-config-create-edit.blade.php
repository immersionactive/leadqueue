<div class="form-group row">
    
    {{ html()->label('Propertybase Contact Field Name')
        ->class('col-md-2 form-control-label')
        ->for('destination_append_config[contact_field_name]')
    }}

    <div class="col-md-10">
        {{ html()->text('destination_append_config[contact_field_name]', $destination_append_config->contact_field_name)->required()->class('form-control') }}
    </div>
    
</div>            

{{-- Webflow Site ID --}}

<div class="form-group row">
    
    {{ html()->label('Webflow Site ID')
        ->class('col-md-2 form-control-label')
        ->for('source_config[webflow_site_id]')
    }}

    <div class="col-md-10">
        {{ html()->text('source_config[webflow_site_id]', old('source_config.webflow_site_id'))
            ->class('form-control')
            ->placeholder('Webflow Site ID')
            ->attribute('maxlength', 255)
            ->required()
        }}
    </div>
    
</div>

{{-- Webflow Form Name --}}

<div class="form-group row">
    
    {{ html()->label('Webflow Form Name')
        ->class('col-md-2 form-control-label')
        ->for('source_config[webflow_form_name]')
    }}

    <div class="col-md-10">
        {{ html()->text('source_config[webflow_form_name]', old('source_config.webflow_form_name'))
            ->class('form-control')
            ->placeholder('Webflow Form Name')
            ->attribute('maxlength', 255)
            ->required()
            ->autofocus()
        }}
    </div>
    
</div>

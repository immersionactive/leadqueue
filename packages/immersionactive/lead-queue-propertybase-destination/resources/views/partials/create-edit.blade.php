<p>Please refer to Propertybase&rsquo;s <a href="https://help.propertybase.com/hc/en-us/articles/360003180752-WebToProspect-REST-API" rel="external">official documentation</a> for instructions on enabling WebToProspect and finding the config values listed below.</p>

{{-- API Site Domain --}}

<div class="form-group row">
    
    {{ html()->label('API Site Domain')
        ->class('col-md-2 form-control-label')
        ->for('destination_config[api_site_domain]')
    }}

    <div class="col-md-10">
        {{ html()->text('destination_config[api_site_domain]', $destination_config->api_site_domain)
            ->class('form-control')
            ->placeholder('example.secure.force.com')
            ->attribute('maxlength', 255)
            ->required()
            ->autofocus()
        }}
    </div>
    
</div>

{{-- Token --}}

<div class="form-group row">
    
    {{ html()->label('Token')
        ->class('col-md-2 form-control-label')
        ->for('destination_config[token]')
    }}

    <div class="col-md-10">
        {{ html()->text('destination_config[token]', $destination_config->token)
            ->class('form-control')
            ->placeholder('Token')
            ->attribute('maxlength', 255)
            ->required()
            ->autofocus()
        }}
    </div>
    
</div>

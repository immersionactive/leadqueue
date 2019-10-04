<p>Please refer to Propertybase&rsquo;s <a href="https://help.propertybase.com/hc/en-us/articles/360003180752-WebToProspect-REST-API" rel="external">official documentation</a> for instructions on enabling WebToProspect and finding the config values listed below.</p>

{{-- Account name --}}

<div class="form-group row">
    
    {{ html()->label('Account Name')
        ->class('col-md-2 form-control-label')
        ->for('destination_config[account]')
    }}

    <div class="col-md-10">
        {{ html()->text('destination_config[account]', $destination_config->account)
            ->class('form-control')
            ->placeholder('Account Name')
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

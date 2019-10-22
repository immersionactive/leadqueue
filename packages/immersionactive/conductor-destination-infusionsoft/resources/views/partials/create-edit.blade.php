<p>Infusionsoft API credentials can be obtained via <a href="https://keys.developer.infusionsoft.com/apps/mykeys" rel="external">this link</a>. API credentials are tied to your Infusionsoft user account &ndash; not to any specific Infusionsoft organizaitonal account that you might have access to.</p>

<p><strong>Important!</strong> After creating a new Infusionsoft destination in Conductor, you <em>must</em> authorize it to access a specific Infusionsoft organizational account, by clicking on the "Authorize" button on the next screen.</p>

<p><strong>Also important!</strong> Obtaining a new OAuth2 token for an Infusionsoft organizational account will invalidate any previously issued tokens. Therefore, you should never create more than one Lead Destination for a given Infusionsoft org account.</p>

{{-- Client ID --}}

<div class="form-group row">
    
    {{ html()->label('Client ID')
        ->class('col-md-2 form-control-label')
        ->for('destination_config[client_id]')
    }}

    <div class="col-md-10">
        {{ html()->text('destination_config[client_id]', $destination_config->client_id)
            ->class('form-control')
            ->attribute('maxlength', 255)
            ->required()
            ->autofocus()
        }}
    </div>
    
</div>

{{-- Client Secret --}}

<div class="form-group row">
    
    {{ html()->label('Client Secret')
        ->class('col-md-2 form-control-label')
        ->for('destination_config[client_secret]')
    }}

    <div class="col-md-10">
        {{ html()->text('destination_config[client_secret]', $destination_config->client_secret)
            ->class('form-control')
            ->attribute('maxlength', 255)
            ->required()
            ->autofocus()
        }}
    </div>
    
</div>

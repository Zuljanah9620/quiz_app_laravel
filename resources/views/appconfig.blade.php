@extends('layouts.admin')

@section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('App Config') }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger border-left-danger" role="alert">
            <ul class="pl-4 my-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">

        <div class="col-lg-8 order-lg-1">

            <div class="card shadow mb-4">

                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-dark">Detail</h6>
                </div>

                <div class="card-body">

                    <form method="POST" id="configForm" autocomplete="off">
                        <span id="form_result"></span>
                        @csrf
                        <h6 class="text-muted ">Admob Settings</h6>
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="name">Native Ad ID<span class="small text-danger">*You should define in Androidmanifest.xml</span></label>
                                        @if(isset($app_config['native_ad_id']))
                                        @if(!is_null($app_config['native_ad_id']))
                                        <input type="text" id="native_ad_id" class="form-control" name="native_ad_id" placeholder="Native Ad ID" value="{{ $app_config->native_ad_id }}">
                                        @endif
                                        @else
                                        <input type="text" id="native_ad_id" class="form-control" name="native_ad_id" placeholder="Native Ad ID">
                                        @endif
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="name">Interstitial Ad ID<span class="small text-danger">*</span></label>
                                        @if(isset($app_config->interstitial_ad_id))
                                        <input type="text" id="interstitial_ad_id" class="form-control" name="interstitial_ad_id" placeholder="Interstitial Ad ID" value="{{ old('interstitial_ad_id', $app_config->interstitial_ad_id) }}">
                                        @else
                                        <input type="text" id="interstitial_ad_id" class="form-control" name="interstitial_ad_id" placeholder="Interstitial Ad ID">
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="name">Banner Ad ID<span class="small text-danger">*</span></label>
                                        @if(isset($app_config->banner_ad_id))
                                        <input type="text" id="banner_ad_id" class="form-control" name="banner_ad_id" placeholder="Banner Ad ID" value="{{ old('banner_ad_id', $app_config->banner_ad_id) }}">
                                        @else
                                        <input type="text" id="banner_ad_id" class="form-control" name="banner_ad_id" placeholder="Banner Ad ID">
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="name">Reward Ad ID<span class="small text-danger">*</span></label>
                                        @if(isset($app_config->reward_ad_id))
                                        <input type="text" id="reward_ad_id" class="form-control" name="reward_ad_id" placeholder="Reward Ad ID" value="{{ old('reward_ad_id', $app_config->reward_ad_id) }}">
                                        @else
                                        <input type="text" id="reward_ad_id" class="form-control" name="reward_ad_id" placeholder="Reward Ad ID">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            
                        </div>
                        <hr/>
                        <br/>
                        <h6 class="text-muted ">App Settings</h6>
                        <div class="pl-lg-4">

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="name">Package Name<span class="small text-danger">*</span></label>
                                        @if(isset($app_config->package_name))
                                        <input type="text" id="package_name" class="form-control" name="package_name" placeholder="Package Name" value="{{ old('package_name', $app_config->package_name) }}">
                                        @else
                                        <input type="text" id="package_name" class="form-control" name="package_name" placeholder="Package Name">
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="name">Contact Mail<span class="small text-danger">*</span></label>
                                        @if(isset($app_config->contact_mail))
                                        <input type="text" id="contact_mail" class="form-control" name="contact_mail" placeholder="Contact Mail" value="{{ old('contact_mail', $app_config->contact_mail) }}">
                                        @else
                                        <input type="text" id="contact_mail" class="form-control" name="contact_mail" placeholder="Contact Mail">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <hr/>
                        <br/>
                        <h6 class="text-muted ">Firebase Settings</h6>
                        <div class="pl-lg-4">
                            
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group focused">
                                        <label class="form-control-label" for="name">Firebase Server Key<span class="small text-danger">*</span></label>
                                        @if(isset($app_config->firebase_server_key))
                                        <input type="text" id="firebase_server_key" class="form-control" name="firebase_server_key" placeholder="Firebase Server Key" value="{{ old('firebase_server_key', $app_config->firebase_server_key) }}">
                                        @else
                                        <input type="text" id="firebase_server_key" class="form-control" name="firebase_server_key" placeholder="Firebase Server Key">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Button -->
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col text-center">
                                    <button type="submit" class="btn btn-success">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>

            </div>

        </div>

    </div>

    <script type="text/javascript">
        $('#configForm').on('submit', function(event){
                event.preventDefault();
                var action_url = "{{ route('appconfig.update') }}";
                var formData = new FormData(configForm);

                $.ajax({
                    url: action_url,
                    method:"POST",
                    data:formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success:function(data)
                    {
                        var html = '';
                        if(data.errors)
                        {
                            html = '<div class="alert alert-danger">';
                            for(var count = 0; count < data.errors.length; count++)
                            {
                                html += '<p>' + data.errors[count] + '</p>';
                            }
                            html += '</div>';
                        }
                        if(data.success)
                        {
                            html = '<div class="alert alert-success">' + data.success + '</div>';
                            $('#sample_form')[0].reset();
                            $('#data_table').DataTable().ajax.reload();
                        }
                        if(data.error)
                        {
                            html = '<div class="alert alert-danger">' + data.error + '</div>'
                        }
                        $('#form_result').html(html);
                        setTimeout(function(){
                            $('#storeModal').modal('hide');
                        }, 900);
                    }
                });
            });
    </script>

@endsection

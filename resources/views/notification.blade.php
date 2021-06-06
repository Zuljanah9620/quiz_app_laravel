@extends('layouts.admin')
@section('styles')
<style>
    /* The device with borders */
.smartphone {
  position: relative;
  width: 360px;
  height: 640px;
  margin: auto;
  border: 16px black solid;
  border-top-width: 60px;
  border-bottom-width: 60px;
  border-radius: 36px;
}

/* The horizontal line on the top of the device */
.smartphone:before {
  content: '';
  display: block;
  width: 60px;
  height: 5px;
  position: absolute;
  top: -30px;
  left: 50%;
  transform: translate(-50%, -50%);
  background: #333;
  border-radius: 10px;
}

/* The circle on the bottom of the device */
.smartphone:after {
  content: '';
  display: block;
  width: 35px;
  height: 35px;
  position: absolute;
  left: 50%;
  bottom: -65px;
  transform: translate(-50%, -50%);
  background: #333;
  border-radius: 50%;
}
</style>
@endsection
@section('main-content')
<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">{{ __('Notification') }}</h1>

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
    <div class="col-lg-4 order-lg-2">
                <div class="card-body">
                    <div class="smartphone">
                        <div class="content">
                            <input type="text" id="notification_preview_title" class="form-control" name="notification_preview_title" placeholder="Notification Title" readonly="true">
                            <textarea type="text" id="notification_preview_body" class="form-control" name="notification_preview_body" placeholder="Notification Body" readonly="true"></textarea>
                        </div>
                    </div>
                </div>
    </div>
    <div class="col-lg-8 order-lg-1">

        <div class="card shadow mb-4">

            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-dark">Send Notification</h6>
            </div>

            <div class="card-body">

                <form method="post" id="notificationForm" autocomplete="off">
                    <span id="form_result"></span>
                    @csrf
                    <h6 class="text-muted ">Notification Info</h6>
                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group focused">
                                    <label class="form-control-label" for="name">Notification Title<span class="small text-danger">*</span></label>
                                    <input type="text" id="notification_title" class="form-control" name="notification_title" placeholder="Notification Title" oninput="notification_preview_title.value = notification_title.value; return true;">
                                </div>
                            </div>

                        </div>                    
                    </div>
                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group focused">
                                    <label class="form-control-label" for="name">Notification Body<span class="small text-danger">*</span></label>
                                    <textarea type="text" id="notification_body" class="form-control" name="notification_body" placeholder="Notification Body" oninput="notification_preview_body.value = notification_body.value; return true;"></textarea>
                                </div>
                            </div>

                        </div>                    
                    </div>
                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group focused">
                                    <label class="form-control-label" for="name">Notification Icon<span class="small text-danger">*</span></label>
                                    <select name="notification_icon" id="notification_icon" class="form-control">
                                        <option value="notification_default">default</option>
                                        <option value="notification_primary">primary</option>
                                        <option value="notification_success">success</option>
                                        <option value="notification_warning">warning</option>
                                        <option value="notification_danger">danger</option>
                                    </select>
                                </div>
                            </div>

                        </div>                    
                    </div>
                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group focused">
                                    <label class="form-control-label" for="name">Notification Image Url<span class="small text-danger"> (optional)</span></label>
                                    <input type="text" id="notification_image" class="form-control" name="notification_image" placeholder="https://example.com/image.png">
                                </div>
                            </div>

                        </div>                    
                    </div>
                    <hr/>
                    <br/>

                    <!-- Button -->
                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col text-center">
                                <button type="button" name="send_button" id="send_button" class="btn btn-success">Send</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>

        </div>

    </div>

    
</div>


<div id="confirmModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Confirmation</h2>
            </div>
            <h4 align="center" style="margin:0;">Are you sure you want to send notificaton?</h4><br/>
            <div class="modal-body">
               
            </div>
        <div class="modal-footer">
            <button type="button" name="ok_button" id="ok_button" class="btn btn-danger">SEND</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
    </div>
</div>
</div>



@endsection
@section('scripts')
<script type="text/javascript">
    $('#ok_button').click(function(event){
        event.preventDefault();
        var action_url = "{{ route('notification.send') }}";
        var formData = new FormData(notificationForm);

        $.ajax({
            url: action_url,
            beforeSend:function(){
                $('#ok_button').text('SENDING...');
            },
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
                    $('#notificationForm')[0].reset();
                }
                if(data.error)
                {
                    html = '<div class="alert alert-danger">' + data.error + '</div>'
                }
                setTimeout(function(){
                    $('#form_result').html(html);
                    $('#ok_button').text('SEND');
                    $('#confirmModal').modal('hide');
                }, 1000);
            },
            error:function() {
                html = '<div class="alert alert-danger">Something went wrong. Please check your Firebase Server Key</div>' 
                setTimeout(function(){
                    $('#confirmModal').modal('hide');
                    $('#form_result').html(html);
                    $('#ok_button').text('SEND');
                }, 1000);
            }
        });
    });
    $('#send_button').click(function() {
        $('#confirmModal').modal('show');
    });

</script>
@endsection

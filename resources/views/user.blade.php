@extends('layouts.admin')
@section('styles')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('plugins/jquery.dataTables.css') }}">
@endsection
@section('main-content')

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Panel Users') }}</h1>

    <div class="row">
        <div class="col-lg-12 order-lg-1">

        <div class="card shadow mb-4">
            <br />
            <div align="right">
                <button type="button" name="create_record" id="create_record" class="btn btn-success btn-sm">Add User</button>
            </div>
            <br />
            <div class="table-responsive">
                <table id="data_table" class="table table-bordered table-striped display responsive nowrap" width="99%">
                    <thead>
                        <tr>
                            <th class="text-center">Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Role</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <br />
            <br />
        </div>
    </div>
    <div id="editModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit User</h4>
                </div>
                <div class="modal-body">
                    <span id="edit_form_result"></span>
                    <form method="post" id="edit_form" class="form-horizontal">
                        @csrf
                        <div class="form-group">
                            <label class="control-label col-md-4" >Name : </label>
                            <div class="col-md-8">
                                <input type="text" name="edit_user_name" id="edit_user_name" class="form-control" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Role : </label>
                            <div class="col-md-8">
                                <select name="edit_user_role" id="edit_user_role" class="user-select-auto">
                                    <option>admin</option>
                                    <option>user</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Status : </label>
                            <div class="col-md-8">
                                <select name="edit_user_status" id="edit_user_status" class="user-select-auto">
                                    <option>active</option>
                                    <option>passive</option>
                                </select>
                            </div>
                        </div>
                        <br />
                        <div class="form-group" align="center">
                            <input type="hidden" name="edit_hidden_id" id="edit_hidden_id" />
                            <input type="submit" name="edit_action_button" id="edit_action_button" class="btn btn-warning" value="Edit" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div id="storeModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add</h4>
            </div>
            <div class="modal-body">
                <span id="form_result" class="form_result"></span>
                <form method="post" id="sample_form" class="form-horizontal">
                    @csrf
                    <div class="form-group">
                        <label class="control-label col-md-4" >Name : </label>
                        <div class="col-md-8">
                            <input type="text" name="user_name" id="user_name" class="form-control" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">Email : </label>
                        <div class="col-md-8">
                            <input type="email" name="user_email" id="user_email" class="form-control" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">Role : </label>
                        <div class="col-md-8">
                            <select name="user_role" id="user_role" class="form-control">
                                <option>admin</option>
                                <option>user</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">Status : </label>
                        <div class="col-md-8">
                            <select name="user_status" id="user_status" class="form-control">
                                <option>active</option>
                                <option>passive</option>
                            </select>
                        </div>
                    </div>
                    <br />
                    <div class="form-group" align="center">
                        <input type="hidden" name="action" id="action" value="Add" />
                        <input type="hidden" name="hidden_id" id="hidden_id" />
                        <input type="submit" name="action_button" id="action_button" class="btn btn-success" value="Add" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</div>
        
    @section('scripts')
    <!-- Script -->
    <script type="text/javascript" charset="utf8" src="{{ asset('plugins/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){

          // DataTable
          $('#data_table').DataTable({
             processing: true,
             serverSide: true,
             ajax: { url: "{{route('user.getUsers')}}" },
             columns: [
                { data: 'name', className: 'dt-body-center', },
                { data: 'email', className: 'dt-body-center', },
                { data: 'role', className: 'dt-body-center', 
                    render: function( data, type, full, meta ) {
                        if(data === 'admin') {
                            return '<span class="btn btn-dark btn-sm">'+ data +'</span>';
                        }
                        return '<span class="btn btn-secondary btn-sm">'+ data +'</span>';
                    }
                },
                { data: 'status', className: 'dt-body-center',
                    render: function( data, type, full, meta ) {
                        if(data === 'active') {
                            return '<span class="btn btn-success btn-sm">'+ data +'</span>';
                        }
                        return '<span class="btn btn-danger btn-sm">'+ data +'</span>';
                    }
                },
                { 
                    data: 'action',
                    className: 'dt-body-center',
                    orderable: false,
                },
             ]
          });

          $(document).on('click', '.edit', function(){
                var id = $(this).attr('id');
                $('#edit_form_result').html('');
                $.ajax({
                    url :"user/"+id+"/edit",
                    dataType:"json",
                    success:function(data)
                    {
                        $('#edit_user_name').val(data.result.name);
                        $('#edit_user_role').val(data.result.role);
                        $('#edit_user_status').val(data.result.status);
                        $('#edit_hidden_id').val(id);
                        $('.modal-title').text('Edit');
                        $('#editModal').modal('show');
                    }
                })
            });

            $('#edit_form').on('submit', function(event){
                event.preventDefault();
                var action_url = "{{ route('user.update') }}";
                var formData = new FormData(edit_form);

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
                            $('#data_table').DataTable().ajax.reload();
                        }
                        if(data.error)
                        {
                            html = '<div class="alert alert-danger">' + data.error + '</div>'
                        }
                        $('#edit_form_result').html(html);
                        setTimeout(function(){
                            $('#editModal').modal('hide');
                        }, 900);
                    }
                });
            });

        $('#create_record').click(function(){
            $('.modal-title').text('Add');
            $('#action_button').val('Add');
            $('#action').val('Add');
            $('#form_result').html('');
            $('#storeModal').modal('show');
        });

        $('#sample_form').on('submit', function(event){
            event.preventDefault();
            var action_url = "{{ route('user.store') }}";
            var formData = new FormData(sample_form);

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


        });
    </script>
    @endsection
@endsection

@extends('layouts.admin')
@section('styles')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('plugins/jquery.dataTables.css') }}">
@endsection
@section('main-content')

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Categories') }}</h1>

    @if (session('success'))
    <div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if (session('status'))
        <div class="alert alert-success border-left-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12 order-lg-1">

        <div class="card shadow mb-4">
            <br />
            <div align="right">
                <button type="button" name="create_record" id="create_record" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Add Category</button>
            </div>
            <br />
            <div class="table-responsive">
                <table id="data_table" class="table table-bordered table-striped display responsive nowrap" width="99%">
                    <thead>
                        <tr>
                            <th class="text-center">Name</th>
                            <th class="text-center">Image</th>
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
                    <h4 class="modal-title">Edit Category</h4>
                </div>
                <div class="modal-body">
                    <span id="edit_form_result"></span>
                    <form method="post" id="edit_form" class="form-horizontal" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label class="control-label col-md-4" >Category Name : </label>
                            <div class="col-md-8">
                                <input type="text" name="edit_category_name" id="edit_category_name" class="form-control" required/>
                            </div>
                        </div>
                        <!--<div class="form-group">
                            <label class="control-label col-md-4">Language Code : </label>
                            <div class="col-md-8">
                                
                            </div>
                        </div>-->
                        <div class="form-group">
                            <label class="control-label col-md-4">Status : </label>
                            <div class="col-md-8">
                                <select name="edit_category_status" id="edit_category_status" class="user-select-auto">
                                    <option>active</option>
                                    <option>passive</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Category Image File:</label>
                            <div class="col-md-8">
                                <input type="file" name="edit_category_image" placeholder="Choose image" id="edit_category_image">
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
                    <h4 class="modal-title">Update Category</h4>
                </div>
                <div class="modal-body">
                    <span id="form_result"></span>
                    <form method="post" id="sample_form" class="form-horizontal" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label class="control-label col-md-4" >Category Name : </label>
                            <div class="col-md-8">
                                <input type="text" name="category_name" id="category_name" class="form-control" required/>
                            </div>
                        </div>
                        <!--<div class="form-group">
                            <label class="control-label col-md-4">Language Code : </label>
                            <div class="col-md-8">
                                <select name="lang_code" id="lang_code" class="form-control">
                                    
                                </select>
                            </div>
                        </div>-->
                        <div class="form-group">
                            <label class="control-label col-md-4">Status : </label>
                            <div class="col-md-8">
                                <select name="category_status" id="category_status" class="form-control">
                                    <option>active</option>
                                    <option>passive</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Category Image File:</label>
                            <div class="col-md-8">
                                <input type="file" name="category_image" placeholder="Choose image" id="category_image">
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

    <div id="confirmModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Confirmation</h2>
                </div>
                <div class="modal-body">
                    <h4 align="center" style="margin:0;">Are you sure you want to remove this data?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" name="ok_button" id="ok_button" class="btn btn-danger">OK</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
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
             ajax: { url: "{{route('category.getCategories')}}" },
             columns: [
                { data: 'name', className: 'dt-center', },
                { data: 'image_url', className: 'dt-body-center',
                    render: function( data, type, full, meta ) {
                            if(data === '') {
                                return 'No image Avaliable';
                            }
                            return "<img src=\"" + data + "\" height=\"60px\"/>";
                        } 
                },
                /*{ data: 'lang_code', className: 'dt-center', 
                    render: function( data, type, full, meta ) {
                            if(data === '') {
                                return 'No image Avaliable';
                            }
                            return "<img src=\"/flags/" + data + ".png\" width=\"10%\"/>";
                        }
                },*/
                { data: 'status', className: 'dt-center', 
                    render: function( data, type, full, meta ) {
                            if(data === 'active') {
                                return '<span class="btn btn-success btn-sm">'+ data +'</span>';
                            }
                            return '<span class="btn btn-danger btn-sm">'+ data +'</span>';
                        } 
                },
                { 
                    data: 'action',
                    className: 'dt-center',
                    orderable: false,
                },
             ]

          });

          $(document).on('click', '.edit', function(){
                var id = $(this).attr('id');
                $('#edit_form_result').html('');
                $.ajax({
                    url :"category/"+id+"/edit",
                    dataType:"json",
                    success:function(data)
                    {
                        $('#edit_category_name').val(data.result.name);
                        $('#edit_lang_code').val(data.result.lang_code);
                        $('#edit_category_status').val(data.result.status);
                        $('#edit_hidden_id').val(id);
                        $('.modal-title').text('Edit');
                        $('#editModal').modal('show');
                    }
                })
            });

            $('#edit_form').on('submit', function(event){
                event.preventDefault();
                var action_url = "{{ route('category.update') }}";
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
                $('#form_result').html('');
                $('#storeModal').modal('show');
            });

            $('#sample_form').on('submit', function(event){
                event.preventDefault();
                var action_url = "{{ route('category.store') }}";
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


            let categor_id;

            $(document).on('click', '.delete', function(){
                categor_id = $(this).attr('id');
                $('#confirmModal').modal('show');
            });


            $('#ok_button').click(function(){
                $.ajax({
                    url:"category/destroy/"+categor_id,
                    beforeSend:function(){
                        $('#ok_button').text('Deleting...');
                    },
                    success:function(data)
                    {
                        setTimeout(function(){
                            $('#confirmModal').modal('hide');
                            $('#data_table').DataTable().ajax.reload();
                            alert('Data Deleted');
                            $('#ok_button').text('OK');
                        }, 1000);
                    }
                })
            });


        });
    </script>
    @endsection
@endsection

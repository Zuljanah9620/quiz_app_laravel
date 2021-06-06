@extends('layouts.admin')
@section('styles')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('plugins/jquery.dataTables.css') }}">
@endsection
@section('main-content')

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">{{ $category_name }} {{ __('Questions') }}</h1>

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
                <button type="button" name="create_csv_file" id="create_csv_file" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Add CSV File</button>
                <button type="button" name="create_record" id="create_record" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Add Question</button>
            </div>
            <br />
            <div class="table-responsive">
                <table id="data_table" class="table table-bordered table-striped display responsive nowrap" width="99%">
                    <thead>
                        <tr>
                            <th class="text-center">Question</th>
                            <th class="text-center">Level</th>
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

    <div id="formModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Question</h4>
                </div>
                <div class="modal-body">
                    <span id="form_result"></span>
                    <form method="post" id="sample_form" class="form-horizontal" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label class="control-label col-md-4" >Question : </label>
                            <div class="col-md-8">
                                <textarea name="question" id="question" cols="40" rows="5" class="form-control" required></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">True Answer : </label>
                            <div class="col-md-8">
                                <input type="text" name="true_answer" id="true_answer" class="form-control" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">False Answer 1: </label>
                            <div class="col-md-8">
                                <input type="text" name="false_answer1" id="false_answer1" class="form-control" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">False Answer 2: </label>
                            <div class="col-md-8">
                                <input type="text" name="false_answer2" id="false_answer2" class="form-control" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">False Answer 3: </label>
                            <div class="col-md-8">
                                <input type="text" name="false_answer3" id="false_answer3" class="form-control" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Level : </label>
                            <div class="col-md-8">
                                <select name="level" id="level" class="form-control">
                                    <option>easy</option>
                                    <option>medium</option>
                                    <option>hard</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Status : </label>
                            <div class="col-md-8">
                                <select name="status" id="status" class="form-control">
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

    <div id="csvModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-csv-title">Add CSV File</h4>
                </div>
                <div class="modal-body">
                    <span id="csv_form_result"></span>
                    <form method="post" id="csv_form" class="form-horizontal" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label class="control-label col-md-4">CSV File : </label>
                            <div class="col-md-8">
                                <input type="file" name="csv_file" placeholder="Choose CSV file" id="csv_file">
                            </div>
                        </div>
                        <br />
                        <div class="form-group" align="center">
                            <input type="hidden" name="csv_hidden_id" id="csv_hidden_id" value="Add CSV" />
                            <input type="submit" name="action_csv_button" id="action_csv_button" class="btn btn-success" value="Add CSV" />
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


    <div id="detailModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">Detail</h2>

                    
                </div>
                <div class="modal-body">

                            <div class="form-group">
                                <label class="control-label col-md-4" >Question : </label>
                                <div class="col-md-8">
                                    <textarea name="question_show" id="question_show" cols="40" rows="5" class="form-control" readonly="true"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">True Answer : </label>
                                <div class="col-md-8">
                                    <input type="text" name="true_answer_show" id="true_answer_show" class="form-control" readonly="true" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">False Answer 1: </label>
                                <div class="col-md-8">
                                    <input type="text" name="false_answer1_show" id="false_answer1_show" class="form-control" readonly="true" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">False Answer 2: </label>
                                <div class="col-md-8">
                                    <input type="text" name="false_answer2_show" id="false_answer2_show" class="form-control" readonly="true" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">False Answer 3: </label>
                                <div class="col-md-8">
                                    <input type="text" name="false_answer3_show" id="false_answer3_show" class="form-control" readonly="true" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Level : </label>
                                <div class="col-md-8">
                                    <div class="col-md-8">
                                        <input type="text" name="level_show" id="level_show" class="form-control" readonly="true" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-md-4">Status : </label>
                                <div class="col-md-8">
                                    <div class="col-md-8">
                                        <input type="text" name="status_show" id="status_show" class="form-control" readonly="true" />
                                    </div>
                                </div>
                            </div>

                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
            ajax: { url: "{{ url('/question/getQuestions', $category_id)}}" },
            columns: [
            { data: 'question', className: 'dt-body-center', },
            { data: 'level', className: 'dt-body-center',
            render: function( data, type, full, meta ) {
                if(data === 'easy') {
                    return '<span class="badge badge-success">'+ data +'</span>';
                }
                if(data === 'medium') {
                    return '<span class="badge badge-warning">'+ data +'</span>';
                }
                return '<span class="badge badge-danger">'+ data +'</span>';
            } 
        },
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
        className: 'dt-body-center',
        orderable: false,
    },
    ]

});

        $(document).on('click', '.edit', function(){
            var id = $(this).attr('id');
            $('#form_result').html('');
            $.ajax({
                url :id+"/edit",
                dataType:"json",
                success:function(data)
                {
                    $('#question').val(data.result.question);
                    $('#true_answer').val(data.result.true_answer);
                    $('#false_answer1').val(data.result.false_answer1);
                    $('#false_answer2').val(data.result.false_answer2);
                    $('#false_answer3').val(data.result.false_answer3);
                    $('#level').val(data.result.level);
                    $('#status').val(data.result.status);
                    $('#hidden_id').val(id);
                    $('.modal-title').text('Edit Question');
                    $('#action_button').val('Edit');
                    $('#action').val('Edit');
                    $('#formModal').modal('show');
                }
            })
        });

        $(document).on('click', '.detail', function(){
            var id = $(this).attr('id');
            $.ajax({
                url :id+"/show",
                dataType:"json",
                success:function(data)
                {
                    $('#question_show').val(data.result.question);
                    $('#true_answer_show').val(data.result.true_answer);
                    $('#false_answer1_show').val(data.result.false_answer1);
                    $('#false_answer2_show').val(data.result.false_answer2);
                    $('#false_answer3_show').val(data.result.false_answer3);
                    $('#level_show').val(data.result.level);
                    $('#status_show').val(data.result.status);
                    $('#detailModal').modal('show');
                }
            })
        });

        $('#create_record').click(function(){
            $('#form_result').html('');
            $('.modal-title').text('Add Question');
            $('#action_button').val('Add');
            $('#action').val('Add');
            $('#formModal').modal('show');
        });

        $('#create_csv_file').click(function(){
            $('#csv_form_result').html('');
            $('#csvModal').modal('show');
        });

        $('#csv_form').on('submit', function(event){
            event.preventDefault();
            var action_url = "{{ route('question.store_csv') }}";

            var formData = new FormData(csv_form);
            formData.append('id', {{ $category_id }});

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
                        $('#csv_form')[0].reset();
                        $('#data_table').DataTable().ajax.reload();
                    }
                    if(data.error)
                    {
                        html = '<div class="alert alert-danger">' + data.error + '</div>'
                    }
                    $('#csv_form_result').html(html);
                    setTimeout(function(){
                        $('#csvModal').modal('hide');
                    }, 900);
                },
                error:function() {
                    html = '<div class="alert alert-danger">Something went wrong. Please check your CSV File</div>';
                    $('#csv_form_result').html(html);
                    setTimeout(function(){
                        $('#csvModal').modal('hide');
                    }, 2000);
                }
            });
        });

        $('#sample_form').on('submit', function(event){
            event.preventDefault();
            var action_url = "";

            if($('#action').val() == 'Add')
            {
                action_url = "{{ route('question.store') }}";
            }

            if($('#action').val() == 'Edit')
            {
                action_url = "{{ route('question.update') }}";
            }


            var formData = new FormData(sample_form);
            formData.append('id', {{ $category_id }});

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
                        $('#formModal').modal('hide');
                    }, 900);
                }
            });
        });


        let question_id;

        $(document).on('click', '.delete', function(){
            question_id = $(this).attr('id');
            $('#confirmModal').modal('show');
        });


        $('#ok_button').click(function(){
            $.ajax({
                url:"/question/destroy/"+question_id ,
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

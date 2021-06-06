@extends('layouts.admin')
@section('styles')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('plugins/jquery.dataTables.css') }}">
@endsection
@section('main-content')

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">{{ __('Reports') }}</h1>

<div class="row">
    <div class="col-lg-12 order-lg-1">

        <div class="card shadow mb-4">
            <br />
            <br />
            <div class="table-responsive">
                <table id="data_table" class="table table-bordered table-striped display responsive nowrap" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">Category</th>
                            <th class="text-center">Question</th>
                            <th class="text-center">Note</th>
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
                                <select name="level" id="level" class="user-select-auto">
                                    <option>easy</option>
                                    <option>medium</option>
                                    <option>hard</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Status : </label>
                            <div class="col-md-8">
                                <select name="status" id="status" class="user-select-auto">
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
            ajax: { url: "{{ route('report.getReports')}}" },
            columns: [
                { data: 'question_id', className: 'dt-body-center', },
                { data: 'question', className: 'dt-body-center', },
                { data: 'note', className: 'dt-body-center', },
                { data: 'status', className: 'dt-body-center',
                    render: function( data, type, full, meta ) {
                        if(data === 'pending') {
                            return '<span class="badge badge-warning">'+ data +'</span>';
                        }
                        if(data === 'progress') {
                            return '<span class="badge badge-info">'+ data +'</span>';
                        }
                        return '<span class="badge badge-sucess">'+ data +'</span>';
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


        $('#sample_form').on('submit', function(event){
            event.preventDefault();
            var action_url = "{{ route('report.update') }}";
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
                        $('#formModal').modal('hide');
                    }, 900);
                }
            });
        });


        let report_id;

        $(document).on('click', '.delete', function(){
            report_id = $(this).attr('id');
            $('#confirmModal').modal('show');
        });


        $('#ok_button').click(function(){
            $.ajax({
                url:"/report/destroy/"+report_id ,
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

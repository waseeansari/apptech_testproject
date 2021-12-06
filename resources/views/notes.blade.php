@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <button type="button" id="addNote" class="btn btn-primary float-right">Add Note</button>
            <div class="clearfix">&nbsp;</div>
            <!-- Add/Edit Note Modal -->
            <div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="noteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="noteModalLabel">Note Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="saveNoteForm" name="saveNoteForm" action="{{ url('') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate="">
                                @csrf
                                <input type="hidden" name="note_id" id="note_id">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div id="noteModalAlert" class="alert alert-danger alert-dismissible d-none">
                                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                            <strong>Alert!</strong> <span></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="note" class="form-label">Note:</label>
                                        <textarea type="text" id="note" name="note" class="form-control" placeholder="Enter your note here ..." required=""></textarea>
                                    </div>
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="image" class="form-label">Image</label>
                                        <input type="file" id="image" name="image" class="form-control">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" id="saveNote" class="btn btn-primary">Save Note</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @include('flash::message')
            <div class="clearfix">&nbsp;</div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @if($notes->count())
                <div class="row">
                    @foreach($notes as $note)
                        <div class='col-sm-4 col-xs-6 col-md-3 col-lg-3'>
                            <div class="card notes-card mb-4">
                                <div class="card-body notes-card-body">
                                    <div class="card-img-actions notes-card-img-actions">
                                        @if(!empty($note->image))
                                            <img src="{{asset($note->image)}}" class="card-img notes-card-img img-fluid" width="100" height="100" alt="">
                                        @else
                                            <img src="{{ asset("assets/img/placeholder.png")  }}" class="card-img notes-card-img img-fluid" width="100" height="100" alt="">
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body bg-light text-center">
                                    <div class="mb-2">
                                        <p class="card-text">{{$note->note}}</p>
                                    </div>
                                    <button type="button" class="btn btn-primary btn-sm editNote" data-id="{{$note->id}}">Edit</button>
                                    <button type="button" class="btn btn-danger btn-sm deleteNote" data-id="{{$note->id}}">Delete</button>
                                </div>
                            </div>
                        </div> <!-- col-6 / end -->
                    @endforeach
                </div>
            @else
                <div class="card">
                    <div class="card-body">
                        No posts added.
                    </div>
                </div>
            @endif
        </div>
    </div>
</div> <!-- container / end -->

<style type="text/css">
.error{
    color:red;
}
.notes-card {
    position: relative;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid rgba(0, 0, 0, .125);
    border-radius: .1875rem
}
.notes-card-body {
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding: 1.25rem;
    text-align: center
}
.notes-card-img-actions {
    position: relative
}

.notes-card-img {
    width: 100px;
    max-height:100px;
}
</style>
<script type="text/javascript">
    $(function () {
        let ajaxurl = "{!! route('notes.index') !!}";
        let app_response_messages = $('.app-response-messages');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#addNote').click(function () {
            $('#note_id').val('');
            $('#saveNoteForm').trigger("reset");
            $('#noteModal').modal('show');
        });

        $('body').on('click', '.editNote', function () {
            var note_id = $(this).data('id');
            $.get(ajaxurl +'/' + note_id +'/edit', function (data) {
                if(data.status == false){
                    app_response_messages.find('.alert-danger').removeClass('d-none').find('span').html(data.message);
                    return false;
                }
                $('#noteModal').modal('show');
                $('#note_id').val(data.id);
                $('#note').val(data.note);
                //$('#image').val(data.image);
                //$('#status').val(data.status);
            });
        });

        $('#saveNote').click(function (e) {
            e.preventDefault();
            $("#saveNoteForm").submit().removeClass('was-validated').addClass('was-validated');
        });

        if ($("#saveNoteForm").length > 0) {
            $("#saveNoteForm").validate({
                rules: {
                    note: {
                        required: true,
                        maxlength: 500
                    },
                    image: {
                        extension: "jpg|jpeg|png"
                    },
                },
                messages: {
                    note: {
                        required: "Please enter your note",
                        maxlength: "Name should less than or equal to 500 characters."
                    },
                    image: {
                    }
                },
                errorElement : 'div',
                submitHandler: function() {
                    app_response_messages.find('.alert').addClass('d-none');
                    $('#saveNote').html('Processing...').attr('disabled','disabled');
                    $.ajax({
                        url: ajaxurl ,
                        type: "POST",
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: "json",//xml, json, script, or html
                        data: new FormData($('#saveNoteForm')[0]),
                        success: function( response ) {
                            $('#saveNote').html('Save Note').removeAttr('disabled');
                            if(response.status == false){
                                app_response_messages.find('.alert-danger').removeClass('d-none').find('span').html(response.message);
                                return false;
                            }

                            app_response_messages.find('.alert-success').removeClass('d-none').find('span').html(response.message);
                            $('#saveNoteForm').trigger("reset");
                            //$('#noteModal').modal('hide');
                            window.location.reload();
                        },
                        error :function( data ) {
                            if (data.status === 422) {
                                var response = $.parseJSON(data.responseText);
                                var html_error = "";
                                html_error += "<strong>"+response.message+"</strong><br>";
                                $.each( response.errors, function( key, value ) {
                                    html_error += "<span>"+value+"</span><br>";
                                    $('#'+key).removeClass('error valid is-invalid').addClass('is-invalid');
                                });
                                $('#noteModalAlert').removeClass('d-none').find('span').html(html_error);
                            }
                            $('#saveNote').html('Save Note').removeAttr('disabled');
                        }
                    })
                },
            })
        }

        $('body').on('click', '.deleteNote', function () {
            var note_id = $(this).data("id");
            Swal.fire({
                title: 'Confirmation !',
                text: "Are you sure you want to delete this note !",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then(function (success) {
                if (success.value) {
                    $.ajax({
                        type: "DELETE",
                        url: ajaxurl +'/' + note_id,
                        success: function (response) {
                            if(response.status == false){
                                app_response_messages.find('.alert-danger').removeClass('d-none').find('span').html(response.message);
                                return false;
                            }

                            app_response_messages.find('.alert-success').removeClass('d-none').find('span').html(response.message);
                            window.location.reload();
                            setTimeout(function(){
                                app_response_messages.find('.alert').addClass('d-none');
                            },3000);
                        },
                        error: function (data) {
                            app_response_messages.find('.alert-danger').removeClass('d-none').find('span').html(data);
                        }
                    });
                }
            });
        });
    })
</script>
@endsection

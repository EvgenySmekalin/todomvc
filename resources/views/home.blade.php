@extends('layouts.app')
<style>
    .change-list-name,
    .delete-list {
        margin-top: 4px;
        display: none;
    }
    .user-list:hover .change-list-name,
    .user-list:hover .delete-list {
        display: block;
    }
</style>

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h3>User's lists</h3>
            @if ($userLists->isEmpty())
                <p>No any lists</p>
            @else
                {{--<div class="list-group">--}}
                @foreach ($userLists as $userList)
                    <div class="row user-list">
                        <div class="col-sm-8"><a href="{{ url('edit') . '/'. $userList->id . '/all' }}" class="list-group-item">{{ $userList->name }}</a></div>
                        <div class="col-sm-2 change-list-name"><button type="button" class="btn btn-sm" data-target="#myModal" onclick="changeListName(this, {{ $userList->id }})">Change name</button></div>
                        <div class="col-sm-2 delete-list"><button type="button" class="btn btn-danger btn-sm" onclick="deleteList({{ $userList->id }})">Delete list</button></div>
                    </div>
                @endforeach
                {{--</div>--}}
            @endif
        </div>
    </div>
</div>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Change list name</h4>
            </div>
            <div class="modal-body">
                <form action="change-name" method="get" id="change-name">
                    <input type="hidden" id="list-id" name="list-id" value="">
                    <div class="form-group">
                        <label for="list-name">List Name:</label>
                        <input type="text" class="form-control" id="list-name" name="list-name">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="submitForm()">Submit</button>
            </div>
        </div>

    </div>
</div>

<script>
    function changeListName(elem, listId) {
        var listName    = document.getElementById('list-name');
        var listIdInput = document.getElementById('list-id');

        listName.value     = elem.parentNode.parentNode.getElementsByClassName('list-group-item')[0].innerHTML;
        listIdInput.value = listId;

        $("#myModal").modal();
    }

    function submitForm() {
        var form    = document.getElementById('change-name');
        form.submit();
    }

    function deleteList(listId) {
        var check = confirm('Do you wan to remove this list with all it items?');
        if (check) {
            var form = document.createElement('form');
            form.action = 'delete-list';
            form.method = 'get';

            var input = document.createElement('input');
            input.name = 'list-id';
            input.value = listId;

            form.appendChild(input);

            document.getElementsByTagName('body')[0].appendChild(form);
            form.submit();
        }
    }
</script>
@endsection




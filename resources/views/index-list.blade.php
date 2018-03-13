@extends('layouts.app')

<!-- Push a style dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
@endpush

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
                <div id="todomvc">
                    <section id="todoapp">
                        <header id="header">
                            @if (isset($listName))
                                <h2>{{ $listName }}</h2>
                            @else
                                <h1>todos</h1>
                            @endif
                            <input id="new-todo" placeholder="What needs to be done?" autofocus>
                        </header>
                        <section id="main">
                            <input id="toggle-all" type="checkbox">
                            <label for="toggle-all">Mark all as complete</label>
                            <ul id="todo-list"></ul>
                        </section>
                        <footer id="footer"></footer>
                    </section>
                    <footer id="info">
                        <p>Double-click to edit a todo</p>
                        @auth
                            @if (!isset($listName))
                                <button type="button" class="btn btn-success btn-sm" data-target="#myModal" data-toggle="modal">Save</button>
                            @endif
                        @endauth
                    </footer>
                </div>
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
                    <h4 class="modal-title">Save new list</h4>
                </div>
                <div class="modal-body">
                    <form action="add-list" method="get" id="add-list">
                        <div class="form-group">
                            <input type="text" class="form-control" id="list-name" name="list-name" value="todos" placeholder="List Name">
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
        var TODOS_LIST = {!! json_encode($list) !!};

        function submitForm() {
            var form    = document.getElementById('add-list');
            form.submit();
        }
    </script>
@endsection



<!-- Push a script dynamically from a view -->
@push('scripts')
    <script src=" {{ asset('js/index.js') }}"></script>
@endpush

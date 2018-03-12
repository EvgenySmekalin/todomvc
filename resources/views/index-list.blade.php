@extends('layouts.app')

<!-- Push a style dynamically from a view -->
@push('styles')
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div id="todomvc">
                    <section id="todoapp">
                        <header id="header">
                            <h1>todos</h1>
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
                        <p>Created by <a href="http://sindresorhus.com">Sindre Sorhus</a></p>
                        <p>Part of <a href="http://todomvc.com">TodoMVC</a></p>
                    </footer>
                </div>

            </div>
        </div>
    </div>
    <input type="hidden" id="list-id" name="list-id" value="{{ $id }}">
@endsection

<script>
    var TODOS_LIST = {!! json_encode($list) !!};
</script>

<!-- Push a script dynamically from a view -->
@push('scripts')
    <script src=" {{ asset('js/index.js') }}"></script>
@endpush

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">Laravel</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item {{ (strpos(Route::current()->uri(),'students') !== false) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('students.index') }}">Students</a>
                </li>
                <li class="nav-item {{ (strpos(Route::current()->uri(),'teachers') !== false) ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('teachers.index') }}">Teachers</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

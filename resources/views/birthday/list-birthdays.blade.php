<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body>
    @auth
    <h1 class="text-center">You are logged in as {{$user->name}}</h1>

    <div class="container">
        <h2 class="text-center">All Birthday Reminders</h2>
        <a class="btn btn-primary" href="{{URL::current()}}">All</a>
        <a class="btn btn-primary" href="{{ route('list-birthdays', ['search' => $search, 'sort' => 'date', 'order' => 'asc']) }}">Sort by Date Ascending</a>
        <a class="btn btn-primary" href="{{ route('list-birthdays', ['search' => $search, 'sort' => 'date', 'order' => 'desc']) }}">Sort by Date Descending</a>
        <a class="btn btn-primary" href="{{ route('list-birthdays', ['search' => $search, 'sort' => 'alphabetical', 'order' => 'asc']) }}">Sort by Alphabetically Ascending</a>
        <a class="btn btn-primary" href="{{ route('list-birthdays', ['search' => $search, 'sort' => 'alphabetical', 'order' => 'desc']) }}">Sort by Alphabetically Descending</a>
        <form method="get" action="/list-birthdays">
            <div class="input-group">
                <input class="form-control" name="search" placeholder="Search...">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
        @foreach ($birthdays as $birthday)
        <div class="card">
            <div class="card-header">
                {{$birthday->name}}
            </div>
            <div class="card-body">
                <h5 class="card-title">{{$birthday->title}}</h5>
                <h5>Description</h5>
                    <div class="border border-dark border-4 p-4">
                        <p class="card-text">{{$birthday->body}}</p>
                    </div>
                <p class="card-text">Phone number: {{$birthday->phone_number}}</p>
                <p class="card-text">Birthday date: {{$birthday->birthday_date}}</p>
                @if ($birthday->group_id)
                <p class="card-text">Group: {{$birthday->group->name}}</p>
                @else
                <p class="card-text">Group: N/A</p>
                @endif
                <a class="btn btn-primary" href="/edit-birthday/{{$birthday->id}}" role="button">Edit</a>
                <form action="/delete-birthday/{{$birthday->id}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-primary">Delete</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="container">
        <h1>Register</h1>
        <form action="/register" method="POST">
            @csrf
            <div class="form-group">
                <label for="exampleInputName1">Name</label>
                <input name="name" type="name" class="form-control" id="exampleInputName1" aria-describedby="nameHelp" placeholder="Enter name">
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input name="email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input name="password" type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
    <div class="container">
        <h1>Log in</h1>
        <form action="/login" method="POST">
            @csrf
            <div class="form-group">
                <label for="exampleInputName1">Name</label>
                <input name="loginName" type="name" class="form-control" id="exampleInputName1" aria-describedby="nameHelp" placeholder="Enter name">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input name="loginPassword" type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
    @endauth
</body>
</html>
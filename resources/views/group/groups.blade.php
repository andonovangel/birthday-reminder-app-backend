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
    <h1 class="text-center">You are logged in</h1>
    <div class="container">
        <form action="/create-group" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input name="name" type="text" class="form-control" placeholder="Person name">
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <input name="description" type="text" class="form-control" placeholder="Description">
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
        <form action="/logout" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">Log out</button>
        </form>
    </div>

    <div class="container">
        <h2 class="text-center">All Groups Reminders</h2>
        @foreach ($groups as $group)
        <div class="card">
            <div class="card-header">
                Description: {{$group['description']}}
            </div>
            <div class="card-body">
                <h5 class="card-title">{{$group['name']}} by {{$group->user->name}}</h5>
                <h4>Birthdays:</h4>
                <ul class="list-group">
                    @foreach ($birthdays as $birthday)
                    @if ($birthday['group_id'] == $group['id'])
                    <li class="list-group-item">
                        {{$birthday['name']}}
                        <a class="btn btn-danger" href="/remove-from-group/{{$birthday->id}}" role="button">Remove</a>
                    </li>
                    @endif
                    @endforeach
                </ul>
                <a class="btn btn-primary" href="/edit-group/{{$group->id}}" role="button">Edit</a>
                <form action="/delete-group/{{$group->id}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
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
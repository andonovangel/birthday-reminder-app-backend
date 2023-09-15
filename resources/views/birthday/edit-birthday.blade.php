<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <title>Edit Birthday {{$birthday->id}}</title>
</head>
<body>
    <div class="container">
        <form action="/edit-birthday/{{$birthday->id}}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input name="name" type="text" class="form-control" value="{{$birthday->name}}" placeholder="Person name">
            </div>
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input name="title" type="text" class="form-control" value="{{$birthday->title}}" placeholder="Title">
            </div>
            <div class="mb-3">
                <label class="form-label">Phone number</label>
                <input name="phone_number" type="text" class="form-control" value="{{$birthday->phone_number}}" placeholder="Phone number">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Description</label>
                <textarea name="body" class="form-control" rows="3">{{$birthday->body}}</textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Birthday date</label>
                <input name="birthday_date" type="datetime-local" class="form-control" value="{{$birthday->birthday_date}}">
            </div>
            <div class="mb-3">
                <select name="group_id" class="form-select" aria-label="Default select example">
                    @if ($birthday->group)
                    <option value={{$birthday->group->id}} selected>{{$birthday->group->name}}</option>
                    @else
                    <option selected>Add to group</option>
                    @endif
                    @foreach ($groups as $group)
                    <option value={{$group['id']}}>{{$group['id']}} | {{$group['name']}}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Edit</button>
        </form>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Add Task</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
    ​
    <div class="container">
        <h2>Update Task</h2>
        <form action="{{ route('user.tasks.update', $task->id) }}" method="post">
            @csrf
            <div class="form-group">
                <label class="control-label col-sm-2">Name of Task:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" value="{{ $task->name }}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-sm-2">Description:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="description" value="{{ $task->description }}">
                </div>
            </div>
            

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                <button class="btn btn-primary" type="submit">Update</button>
                </div>
            </div>
        </form>
    </div>
    ​
</body>

</html>
​

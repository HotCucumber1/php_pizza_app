<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>UserPage</title>
</head>
<body style="background-color: lightslategrey">
    <form action="/update_user?user_id=<?= htmlentities($user->getUserId()) ?>" method="post" enctype="multipart/form-data" role="form" style="margin-inline: auto;
                                                              margin-top: 100px;
                                                              width: 600px;
                                                              padding: 20px;
                                                              border-radius: 10px;
                                                              background-color: lightgreen;">
        <h1>User</h1>
        <div class="form-group">
            <label for="name">Name:</label>
            <input name="name" id="name" type="text" class="form-control" value="<?= htmlentities($user->getFirstName()) ?>">
        </div>
        <div class="form-group">
            <label for="last_name">Last_name:</label>
            <input name="last_name" id="last_name" type="text" class="form-control" value="<?= htmlentities($user->getLastName()) ?>">
        </div>
        <div class="form-group">
            <label for="middle_name">Middle_name:</label>
            <input name="middle_name" id="middle_name" type="text" class="form-control" value="<?= htmlentities($user->getMiddleName()) ?>">
        </div>
        <div class="form-group">
            <label for="gender">Gender:</label>
            <input name="gender" id="gender" type="text" class="form-control" value="<?= htmlentities($user->getGender()) ?>">
        </div>
        <div class="form-group">
            <label for="birth_date">Birth date:</label>
            <input name="birth_date" id="birth_date" type="date" class="form-control" value="<?= htmlentities(explode(' ', $user->getBirthDate())[0]) ?>">
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input name="email" id="email" type="email" class="form-control" value="<?= htmlentities($user->getEmail()) ?>">
        </div>
        <div class="form-group">
            <label for="phone">Phone:</label>
            <input name="phone" id="phone" type="number" class="form-control" value="<?= htmlentities($user->getPhone()) ?>">
        </div>
        <div class="form-group">
            <label for="avatar_path">Avatar:</label>
            <input type="hidden" name="MAX_FILE_SIZE" value="30000000"/>
            <input name="avatar_path" id="avatar_path" type="file" accept="image/png, image/jpeg, image/gif" class="form-control">
            <label for="avatar" style="display: block">Current avatar:</label>
            <img id="avatar" src="<?= htmlentities($user->getAvatarPath()) ?>" alt="Avatar" style="height: 100px; width: 100px; border-radius: 5px">
        </div>
        <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Update</button>
        <a href="/delete_user?user_id=<?= htmlentities($user->getUserId()) ?>" class="btn btn-primary" style="margin-top: 20px;">Delete this user</a>
    </form>
</body>
</html>

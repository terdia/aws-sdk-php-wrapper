<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>s3 File Management</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
          integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb"
          crossorigin="anonymous">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>S3 File Management</h1>
            <hr />
            <div class="float-sm-none float-right">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="custom-file">
                        <input  type="file"
                               class="custom-file-input" id="customFile" name="file">
                        <label style="border: 1px solid darkgrey; width: 100%; padding: 0.38rem;
                        text-align: right;"
                               class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary mb-2">Upload</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <?php if(isset($msg) && $msg): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <?= $msg ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
    
            <?php if(isset($path) && $path): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    File uploaded to: <?= $path ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            
            <?php if(isset($bucket_contents)): ?>
            <table class="table">
                <thead class="thead-dark">
                <tr><th>Path</th> <th>Filename</th> <th>Download</th><th>Delete</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($bucket_contents as $bucket_content): ?>
                        <tr>
                            <td><?= $bucket_content['Key'] ?></td>
                            <td><?= $storage->getObjectName($bucket_content['Key']) ?></td>
                            <td> <a href="<?= $storage->getUrl($bucket_content['Key']) ?>">Download</a> </td>
                            <td><a href="?delete=<?= $bucket_content['Key'] ?>">Delete</a> </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
                <?php else: ?>
                <p class="leads">You have not uploaded any item.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
</body>
</html>







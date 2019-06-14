Well, you can create symbolic link using

php artisan storage:link
and access files using

<img src="{{ asset('public/myfolder1/image.jpg') }}" />
But sometime you can't create symbolic link if you're on shared hosting. You want to protect some files behind some access control logic, there is the alternative of having a special route that reads and serves the image. For example.

Route::get('storage/{filename}', function ($filename)
{
    $path = storage_path($filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});
Now you can access your files like this.

http://example.com/storage/public/myfolder1/image.jpg
<img src="{{ asset('storage/public/myfolder1/image.jpg') }} />
Note: I'd suggest to not store paths in the db for flexibility. Please just store file name and do the following thing in the code.

Route::get('storage/{filename}', function ($filename)
{
    // Add folder path here instead of storing in the database.
    $path = storage_path('public/myfolder1' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});
and access it using

http://example.com/storage/image.jpg
Hope that helps :)
<form method="POST" action="/files" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file">
    <button type="submit">Upload File</button>
</form>
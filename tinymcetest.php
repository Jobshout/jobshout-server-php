<script type="text/javascript" src="tinymce/tinymce.min.js"></script>
	<script type="text/javascript">
tinymce.init({
    selector: '#absurls',
    plugins: 'link image code',
    relative_urls: false
});

tinymce.init({
    selector: '#abshosturls',
    plugins: 'link image code',
    relative_urls: false,
    remove_script_host: false
});

tinymce.init({
    selector: '#relurls',
    plugins: 'link image code',
    relative_urls: true
});

tinymce.init({
    selector: '#relurlstopage',
    plugins: 'link image code',
    relative_urls: true,
    document_base_url: 'http://www.tinymce.com/tryit/'
});

tinymce.init({
    selector: "#nourlconvert",
    plugins: 'link image code',
    convert_urls: false
});
</script>

<form method="post" action="somepage">
        <h2>TinyMCE with absolute URLs on links and images</h2>
        <textarea id="absurls" name="absurls" cols="85" rows="10"></textarea>

        <h2>TinyMCE with absolute URLs and including domain on links and images</h2>
        <textarea id="abshosturls" name="abshosturls" cols="85" rows="10"></textarea>

        <h2>TinyMCE with relative URLs on links and images</h2>
        <textarea id="relurls" name="relurls" cols="85" rows="10"></textarea>

        <h2>TinyMCE with relative URLs on links and images to a specific page</h2>
        <textarea id="relurlstopage" name="relurlstopage" cols="85" rows="10"></textarea>

        <h2>TinyMCE with no url conversion</h2>
        <textarea id="nourlconvert" name="nourlconvert" cols="85" rows="10"></textarea>
</form>
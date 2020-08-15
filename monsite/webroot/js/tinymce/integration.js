tinymce.init({
    selector: '#inputcontent',
    height: 500,


    plugins: [
        "image",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table paste imagetools wordcount", "inlinepopus"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | image",
    content_css: '//www.tiny.cloud/css/codepen.min.css',
    relative_urls: false,
    images_upload_url: '/Site-Formation/cockpit/medias/upload',


    images_upload_handler: function (blobInfo, success, failure) {


        var xhr, formData;


        xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        xhr.open('POST', '/Site-Formation/cockpit/medias/upload');

        xhr.onload = function () {
            var json;

            if (xhr.status != 200) {
                failure('HTTP Error: ' + xhr.status);
                return;
            }
            alert('Une erreur est survenue !\n\nCode :' + xhr.status + '\nTexte : ' + xhr.responseText); 
            json = JSON.parse(xhr.responseText);

            if (!json || typeof json.location != 'string') {
               
                failure('Invalid JSON: ' + xhr.responseText);
                return;
            }
           
            success(json.location);
        };

        formData = new FormData();

        formData.append('file', blobInfo.blob(), blobInfo.filename());

        xhr.send(formData);

    },
    file_picker_callback: function (callback, value, meta) {
        tinymce.activeEditor.windowManager.openUrl({
            title: 'File Manager',
            url: '/Site-Formation/cockpit/medias/index',
            onMessage: function (api, data) {
                if (data.mceAction === 'customAction') {
                    callback(data.url);
                    api.close();
                }
            }
    });
},


});
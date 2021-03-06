// Copyright (c) 2015, Fujana Solutions - Moritz Maleck. All rights reserved.
// For licensing, see LICENSE.md

CKEDITOR.plugins.add( 'imageuploader', {
    init: function( editor ) {
        editor.config.filebrowserBrowseUrl = CKEDITOR.getUrl('') + 'plugins/imageuploader/imgbrowser.php';
        editor.config.filebrowserUploadUrl = CKEDITOR.getUrl('') + 'plugins/imageuploader/imgupload.php';
    }
});

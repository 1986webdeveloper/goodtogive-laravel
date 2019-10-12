/*=========================================================================================
    File Name: dropzone.js
    Description: dropzone
    --------------------------------------------------------------------------------------
    Item Name: Modern Admin - Clean Bootstrap 4 Dashboard HTML Template
   Version: 3.0
    Author: PIXINVENT
    Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/
/*
Dropzone.options.dpzSingleFile = {
    paramName: "file", // The name that will be used to transfer the file
    maxFiles: 1,
    init: function() {
        this.on("maxfilesexceeded", function(file) {
            this.removeAllFiles();
            this.addFile(file);
        });
    }
};*/

/********************************************
*               Multiple Files              *
********************************************/
Dropzone.options.projectFileUpload = { // The camelized version of the ID of the form element

  // The configuration we've talked about above
  autoProcessQueue: false,
  uploadMultiple: true,
  parallelUploads: 100,
  maxFiles: 100,

  // The setting up of the dropzone
  init: function() {
    var myDropzone = this;

    // First change the button to actually tell Dropzone to process the queue.
    this.element.querySelector("a[href='#finish']").addEventListener("click", function(e) {
      // Make sure that the form isn't actually being sent.
      e.preventDefault();
      e.stopPropagation();
      myDropzone.processQueue();
    });

    // Listen to the sendingmultiple event. In this case, it's the sendingmultiple event instead
    // of the sending event because uploadMultiple is set to true.
    this.on("sendingmultiple", function() {
      // Gets triggered when the form is actually being sent.
      // Hide the success button or the complete form.
    });
    this.on("successmultiple", function(files, response) {
      // Gets triggered when the files have successfully been sent.
      // Redirect user or notify of success.
    });
    this.on("errormultiple", function(files, response) {
      // Gets triggered when there was an error sending the files.
      // Maybe show form again, and notify user of error
    });
  }
 
}
/*
Dropzone.options.dpzMultipleFiles = {
    autoProcessQueue: false,
    paramName: "file2", // The name that will be used to transfer the file
    maxFilesize: 0.5, // MB
    clickable: true,
    renameFile: function(file) {
        var dt = new Date();
        var time = dt.getTime();
        return time+file.name;
    },
    init:function () {

        var myDropzone = this;

        // Update selector to match your button
        $("a[href='#finish']").click(function (e) {
            e.preventDefault();
            myDropzone.processQueue();
        });

    },
    acceptedFiles: ".jpeg,.jpg,.png,.gif",
    addRemoveLinks: true,
    timeout: 5000,
    success: function(file, response) 
    {
        console.log('test'+response);
    },
    error: function(file, response)
    {
        return false;
    }
};*/
// console.log(Dropzone.options.dpzMultipleFiles);
/********************************************************
*               Use Button To Select Files              *
********************************************************/
//  new Dropzone(document.body, { // Make the whole body a dropzone
//      url: "/post/", // Set the url
// //     previewsContainer: "#dpz-btn-select-files", // Define the container to display the previews
// //     clickable: "#select-files" // Define the element that should be used as click trigger to select files.
//  });


/****************************************************************
*               Limit File Size and No. Of Files                *
****************************************************************/
/*Dropzone.options.dpzFileLimits = {
    paramName: "file", // The name that will be used to transfer the file
    maxFilesize: 0.5, // MB
    maxFiles: 5,
    maxThumbnailFilesize: 1, // MB
}
*/

/********************************************
*               Accepted Files              *
********************************************/
/*Dropzone.options.dpAcceptFiles = {
    paramName: "file", // The name that will be used to transfer the file
    maxFilesize: 1, // MB
    acceptedFiles: 'image/*'
}
*/

/************************************************
*               Remove Thumbnail                *
************************************************/
/*Dropzone.options.dpzRemoveThumb = {
    paramName: "file", // The name that will be used to transfer the file
    maxFilesize: 1, // MB
    addRemoveLinks: true,
    dictRemoveFile: " Trash"
}
*/
/*****************************************************
*               Remove All Thumbnails                *
*****************************************************/
// Dropzone.options.dpzRemoveAllThumb = {
//     paramName: "file", // The name that will be used to transfer the file
//     maxFilesize: 1, // MB
//     init: function() {

//         // Using a closure.
//         var _this = this;

//         // Setup the observer for the button.
//         $("#clear-dropzone").on("click", function() {
//             // Using "_this" here, because "this" doesn't point to the dropzone anymore
//             _this.removeAllFiles();
//             // If you want to cancel uploads as well, you
//             // could also call _this.removeAllFiles(true);
//         });
//     }
// };
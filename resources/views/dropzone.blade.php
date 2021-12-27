
<html>
 <head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Image Upload in Laravel using Dropzone</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.css" /> --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" integrity="sha512-jU/7UFiaW5UBGODEopEqnbIAHOI8fO6T99m7Tsmqs2gkdujByJfkCbbfPSN4Wlqlb9TGnsuC0YgUgWkRBK7B9A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js" integrity="sha512-oQq8uth41D+gIH/NJvSJvVB85MFk1eWpMK6glnkg6I7EdMqC1XVkW7RxLheXwmFdG03qScCM7gKS/Cx3FYt7Tg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
     <script src="https://raw.githack.com/SortableJS/Sortable/master/Sortable.js"></script>
 </head>
 <body>
  <div class="container-fluid ">
      <br />
    <h3 align="center">Image Upload in Laravel using Dropzone</h3>
    <br />
        <div class="row">
      <div class=" col-md-6 panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Select Image</h3>
        </div>
        <div class="panel-body">
          <form id="dropzoneForm" class="dropzone" action="{{ route('dropzone.upload') }}">
            @csrf
          </form>
          <div align="center">
            <button type="button" class="btn btn-info" id="submit-all">Upload</button>
          </div>
        </div>
      </div>
      <div class="col-md-6">  <p>
          Try to drag and drop items within lists or between lists.
        </p>
        <ul class="list-group" id="demo1">
          <li class="list-group-item">Cras justo odio</li>
          <li class="list-group-item">Dapibus ac facilisis in</li>
          <li class="list-group-item">Morbi leo risus</li>
          <li class="list-group-item">Porta ac consectetur ac</li>
          <li class="list-group-item">Vestibulum at eros</li>
        </ul>
        <ul class="list-group mt-4" id="demo2">
          <li class="list-group-item">Cras justo odio</li>
          <li class="list-group-item">Dapibus ac facilisis in</li>
          <li class="list-group-item">Morbi leo risus</li>
          <li class="list-group-item">Porta ac consectetur ac</li>
          <li class="list-group-item">Vestibulum at eros</li>
        </ul>
      </div></div>
      <br />
      
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Uploaded Image</h3>
        </div>
        <div class="panel-body" id="uploaded_image">
          
        </div>
      </div>
    </div>
 </body>
</html>

<script type="text/javascript">

  Dropzone.options.dropzoneForm = {
    autoProcessQueue : false,
    parallelUploads:5,

            uploadMultiple:true,
    acceptedFiles : ".png,.jpg,.gif,.bmp,.jpeg",
    accept: function(file, done) {
    if (file.size == 0) {
      done("Empty files will not be uploaded.");
    }
    else { done(); }
  },
renameFile: function(file) {
  console.log("filerename",file);
            var dt = new Date();
            var time = dt.getTime();
           return time+file.name;
        },
    init:function(){
      var submitButton = document.querySelector("#submit-all");
      myDropzone = this;

      submitButton.addEventListener('click', function(){
        myDropzone.processQueue();
      });
      this.on(
            "addedfile", function(file) {
              caption = file.caption == undefined ? "" : file.caption;
              file._captionLabel = Dropzone.createElement("<p>File Info:</p>")
              file._captionBox = Dropzone.createElement("<input id='"+file.filename+"' type='text' name='caption' value="+caption+" >");
              file.previewElement.appendChild(file._captionLabel);
              file.previewElement.appendChild(file._captionBox);
        }),
this.on("sendingmultiple", function (file, xhr, formData) {
    console.log("sendingmultiple",file);
});
this.on("sending", function (file, xhr, formData) {
           console.log("filename",file.name);
               formData.append('description[]',file._captionBox.value);
               console.log("file._captionBox.value",file._captionBox.value);
         /**  
            this.options.renameFilename = function(file){
                //keeping the file extension.
                var ext = file.split('.').pop();
                return file.name = filename + '.' + ext;
            };*/
        });
      this.on("complete", function(){
        if(this.getQueuedFiles().length == 0 && this.getUploadingFiles().length == 0)
        {
          var _this = this;
          _this.removeAllFiles();
        }
        load_images();
      });

    }

  };

  load_images();

  function load_images()
  {
    $.ajax({
      url:"{{ route('dropzone.fetch') }}",
      success:function(data)
      {
        $('#uploaded_image').html(data);
      }
    })
  }

  $(document).on('click', '.remove_image', function(){
    var name = $(this).attr('id');
    $.ajax({
      url:"{{ route('dropzone.delete') }}",
      data:{name : name},
      success:function(data){
        load_images();
      }
    })
  });
/**
  $(".dropzone").sortable({
    items:'.dz-preview',
    cursor: 'grab',
    opacity: 0.5,
    containment: '.dropzone',
    distance: 20,
    tolerance: 'pointer',
    stop: function () {
      var queue = myDropzone.getAcceptedFiles();
      newQueue = [];
      $('#dropzoneForm .dz-preview .dz-filename [data-dz-name]').each(function (count, el) {           
            var name = el.innerHTML;
            queue.forEach(function(file) {
                if (file.name === name) {
                    newQueue.push(file);
                }
            });
      });
      myDropzone.files = newQueue;
    }
});
*/
new Sortable(document.getElementById('dropzoneForm'), {
  draggable: '.dz-preview',
  onSort: function (/**Event*/evt) {
		// same properties as onEnd
    //console.log('evt.to',evt.to);    // target list
		//console.log('evt.from',evt.from);  // previous list
    //console.log('evt.oldIndex',evt.oldIndex);  // element's old index within old parent
	//	console.log('evt.newIndex',evt.newIndex);
    
      var queue = myDropzone.getAcceptedFiles();
      newQueue = [];
      $('#dropzoneForm .dz-preview .dz-filename [data-dz-name]').each(function (count, el) {           
            var name = el.innerHTML;
            queue.forEach(function(file) {
                if (file.name === name) {
                    newQueue.push(file);
                }
            });
      });
      myDropzone.files = newQueue;
	},
});

Sortable.create(demo1, {
  animation: 100,
  group: 'list-1',
  draggable: '.list-group-item',
  handle: '.list-group-item',
  sort: true,
  filter: '.sortable-disabled',
  chosenClass: 'active'
});

Sortable.create(demo2, {
  group: 'list-1',
  handle: '.list-group-item'
});


</script>
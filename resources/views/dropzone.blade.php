
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
<link href="{{ asset('dist/css/select2.min.css') }}" rel="stylesheet">
<script  type="text/javascript" src="{{ asset('dist/js/select2.full.min.js') }}"></script>   
 </head>
 <body>
 <style>

  .draggable1 {
  background: #000;
  color: #fff;
  display: inline-block;
  margin: 5px;
  padding: 5px;
  user-select: none;
}

  .dropzone2 {
 border: 1px solid #000;
  height: 40px;
  margin: 5px;
  width: 200px;
  }</style>
  <div class="container-fluid ">
      <br />
    <h3 align="center">Multi Drag&Drop Files and Drag & drop name</h3>
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
      <div class="col-md-6">  <ol>
        <li>Select the images you want to upload.</li> 
        <li>Selct multiple channels from dropdown</li> 
        <li>Drag and drop divs under the droped images</li> 
         
         
        </ol>

<label for="genres" class="col-md-4 control-label">Channels</label>
              
<select name="genres[]" id="genres" class="form-control select2" multiple="multiple">
{{-- @foreach($genres as $id => $genre)
                        @if(in_array($id, $arrVal))
                        <option value="{{ $id }}" selected="true">{{ $genre }}</option>
                        @else
                        <option value="{{ $id }}">{{ $genre }}</option>
                        @endif 
                    @endforeach --}}
                </select>
                <div class="dynamic-fields">
<div class="dropzone2"><div class="draggable1" draggable="true" >This div is draggable2</div></div>
</div>
        

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

$('.select2').select2({
placeholder: 'This is my placeholder',
  allowClear: true,
  ajax: {
        url: '/dataforselect2',
        dataType: 'json',
        delay: 250,
        data: function(params) {
            return {
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
    });

$('#genres').on('select2:select', function(e) {
  var cacheEle = $('.dynamic-fields');
  var data = e.params.data;
  console.log(data.id);
  console.log(data.text);
   let divtoAdd='<div class="dropzone2" id='+data.id+'><div class="draggable1" draggable="true"  >' + data.text + '</div></div>'
        
          cacheEle.append(divtoAdd);

});


$('#genres').on('select2:unselect', function(e) {
  var cacheEle = $('.dynamic-fields');
  var data = e.params.data;
  console.log(data.id);
  console.log(data.text);
  var removethedropzonediv = cacheEle.find(".dropzone2"+"#" + data.id);
  console.log("removethedropzonediv",removethedropzonediv);
  removethedropzonediv.remove();
  //cacheEle
});
document.addEventListener('DOMContentLoaded', () => {
  var dragged;

  /* events fired on the draggable target */
  document.addEventListener("drag", function( event ) {
console.log("drag triggrred");
  }, false);

  document.addEventListener("dragstart", function( event ) {
      // store a ref. on the dragged elem
      dragged = event.target;
      console.log('dragged',dragged);
      console.log('draggedvalue',dragged.textContent);
       // event.dataTransfer.setData("Text", event.target.id);
         event.dataTransfer.setData("Text", event.target.textContent);
      // make it half transparent
      event.target.style.opacity = .5;
  }, false);

  document.addEventListener("dragend", function( event ) {
      // reset the transparency
      event.target.style.opacity = "";
  }, false);

  /* events fired on the drop targets */
  document.addEventListener("dragover", function( event ) {
      // prevent default to allow drop
      event.preventDefault();
  }, false);

  document.addEventListener("dragenter", function( event ) {
      // highlight potential drop target when the draggable element enters it
      if ( event.target.className == "dropzone2" ) {
          event.target.style.background = "purple";
      }

  }, false);

  document.addEventListener("dragleave", function( event ) {
      // reset background of potential drop target when the draggable element leaves it
      if ( event.target.className == "dropzone2" ) {
          event.target.style.background = "grey";
      }

  }, false);

  document.addEventListener("drop", function( event ) {
      // prevent default action (open as link for some elements)
      event.preventDefault();
      // move dragged elem to the selected drop target
      if ( event.target.className == "dropzone2" ) {
          event.target.style.background = "";
let nxtSibling=event.target.nextSibling;
let divValue=event.target;
console.log("divValue",divValue.children);
//	var txt = "Nick";
   let data = event.dataTransfer.getData("Text");
//	nxtSibling.value = txt;
	nxtSibling.value = data;
          console.log("eventtsrget",nxtSibling);
          dragged.parentNode.removeChild( dragged );
          event.target.appendChild( dragged );

      }
    
  }, false);
});
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
              file._captionLabel = Dropzone.createElement('<div class="dropzone2"></div>')
              file._captionBox = Dropzone.createElement("<input id='"+file.filename+"' type='hidden' name='description[]' value="+caption+" >");
              file.previewElement.appendChild(file._captionLabel);
              file.previewElement.appendChild(file._captionBox);
        }),
this.on("sendingmultiple", function (file, xhr, formData) {
    console.log("sendingmultiple",file);
});
this.on("sending", function (file, xhr, formData) {
           console.log("filename",file.name);
               formData.append('filenames[]',file.name);
              // console.log("file._captionBox.value",file._captionBox.value);
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
      },
      cache: false
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
  handle: '.dz-details',
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



</script>
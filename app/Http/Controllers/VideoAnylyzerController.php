<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Channel;
use FFmpeg\Coordinate\Dimension;

use FFmpeg\Format\Video\X264;
use FFMpeg; 
class VideoAnylyzerController extends Controller
{
    //https://blog.webnersolutions.com/compress-videos-using-ffmpeg-laravel-shell_exec-method
    public function index(){
      
        $media = FFMpeg::open('ss.mp4');
        $inputs = array(
            'video_1.mp4',
            'video_2.mp4',
            'video_3.mp4',
            'video_4.mp4',
        );
        // $advancedMedia = $ffmpeg->openAdvanced($inputs)
        
  
        // echo $durationInSeconds;
        // echo '<br>';
        // echo $durationInMiliseconds;    echo '<br>';
        // $churl = ['http://channelstore.streamlytv.com/live/CMTV/interlink.m3u8','fdghsh.m3u8','http://radioitaliatv-lh.akamaihd.net/i/radioitaliatv_1@329645/master.m3u8',''];
       $churl = Channel::all()->pluck('url');
        foreach ($churl as $url) {
            try {
                if ($url==null) {
                    echo 'url not found';
                }
                
                echo $url; echo '<br>';
                $ffprobe = FFMpeg\FFProbe::create();
                $da= $ffprobe
        ->streams($url) // extracts streams informations
        ->videos()    
        ->first()              // returns the first video stream
       ->all();  
          print_r($da); echo '<br>'; echo '<br>';
        //  echo 'isvalid'.$ffprobe->isValid($url);
            } catch (FFMpeg\Exception\RuntimeException $e) {
                echo "error:".$url; echo '<br>';
            }
            echo PHP_EOL;
        }
        //return view('admin.videoanylyzer.index', compact('da'));
    //     try {  
    //           //print_r($churl);
    //          $myarr=['http://channelstore.streamlytv.com/live/CMTV/interlink.m3u8','http://channelstore.streamlytv.com/live/AETV/interlink.m3u8','fdghsh.m3u8'];
    //         $ffprobe = FFMpeg\FFProbe::create();
    //         foreach ($churl as $url) {
    //             echo $url; echo '<br>';
    //             $da= $ffprobe
    //     ->streams($url) // extracts streams informations
    //     ->videos()                 // filters video streams
    //     ->first()  ;                   // returns the first video stream
    //    // ->get('width');  
    //       print_r($da);echo '<br>'; echo '<br>';
    //     //  echo 'isvalid'.$ffprobe->isValid($url); 
    //         }
            
    //     } catch (FFMpeg\Exception\RuntimeException $e) {
    //         echo "error";
    //     }
           

    // FFMpeg::open('ss.mp4')
    //     ->getStreams()
    //     ->videos()
    //     ->first()
    //     ->getDimensions();
}

public function probeAudioFile()
 {
    //   $churl = ['http://channelstore.streamlytv.com/live/CMTV/interlink.m3u8','fdghsh.m3u8','http://radioitaliatv-lh.akamaihd.net/i/radioitaliatv_1@329645/master.m3u8','https://content.jwplatform.com/manifests/yp34SRmf.m3u8'];
     $input='https://content.jwplatform.com/manifests/yp34SRm';
     $ffprobe = FFMpeg\FFProbe::create();
     $output = array();
     $format = $ffprobe->format($input)->all();
     $fileName=$format['filename'];
     $format_name=$format['format_name'];
    //  $format = $ffprobe->format($input)->get('format_name');
    // $channels = $ffprobe->streams($input)->videos()->first()->all();
    // $bits = $ffprobe->streams($input)->videos()->first()->get('bits_per_sample');
     //$sample_rate = $ffprobe->streams($input)->videos()->first()->get('sample_rate');
     array_push($output, $format,$fileName,$format_name);
     return view('admin.videoanylyzer.index', compact('output'));
    // return $output;
 }

  /**
  * A special endpoint for video uploads. This starts the process of conversion into web formats
  */
  function action_video()
  {
      try {
          set_time_limit(0);
          ini_set('memory_limit', '256M');
      } catch (\Exception $e) {
          // Nothing!
      }
      \Package::load(array('log'));
      // Get the size limit as defined by PHP
      $this->sizeLimit = min($this->toBytes(ini_get('upload_max_filesize')), $this->toBytes(ini_get('post_max_size')));
      // If you want to use resume feature for uploader, specify the folder to save parts.
      $this->chunksFolder = APPPATH . 'tmp/chunks';
      // Call handleUpload() with the name of the folder
      $path = \Input::get('path', urldecode(\Input::post('path', 'uploads')));
      $path = trim($path, '/') . '/';
      $result = $this->handleUpload(DOCROOT . $path);
      // We may have been passed details about the owner of this video field
      $model_class = \Input::get('model', urldecode(\Input::post('model', null)));
      $item_id = \Input::get('item_id', urldecode(\Input::post('item_id', null)));
      $field_name = \Input::get('fieldName', urldecode(\Input::post('fieldName', null)));
      // Set the data for the response
      $result['uploadName'] = $this->getUploadName();
      $result['originalName'] = $this->getOriginalName();
      $result['path'] = $path . $result['uploadName'];
      $result['fieldName'] = $field_name;
      // We need to work out where the PID file for this conversion will sit...
      $video_path = $result['path'];
      $video_pid = 'videoconvert_' . md5(DOCROOT . $video_path);
      $result['pid'] = $video_pid;
      $pid_dir = APPPATH . 'run';
      if (!is_dir($pid_dir)) {
          $dir_result = @mkdir($pid_dir, 0775, true);
      }
      // Get the dimensions. These are important...
      $config = \Config::get('cmf.ffmpeg');
      $logger = new Logger('WebVideoConverter');
      $logger->pushHandler(new NullHandler());
      $full_video_path = DOCROOT . $video_path;
      // Set up the FFMpeg instances
      $ffprobe = new \FFMpeg\FFProbe($config['ffprobe_binary'], $logger);
      // Probe the video for info
      $format_info = json_decode($ffprobe->probeFormat($full_video_path));
      $video_streams = json_decode($ffprobe->probeStreams($full_video_path));
      $video_info = null;
      foreach ($video_streams as $num => $stream) {
          if ($stream->codec_type == 'video') {
              $video_info = $stream;
              break;
          }
      }
      // Serve up an error if we can't find a video stream
      if ($video_info === null) {
          $this->headers = array("Content-Type: text/plain");
          return \Response::forge(json_encode(array('error' => 'This is not a supported video type')), $this->status, $this->headers);
      }
      // Now add some useful video stats to the result
      $result['width'] = intval(isset($video_info->width) ? $video_info->width : $config['default_size']['width']);
      $result['height'] = intval(isset($video_info->height) ? $video_info->height : $config['default_size']['height']);
      $result['duration'] = $format_info->duration;
      // Execute the oil conversion process as a separate 'thread'
      $oil_path = realpath(APPPATH . '../../oil');
      exec("php {$oil_path} r ffmpeg:webvideo -file='{$video_path}' > /dev/null 2>&1 & echo \$! >> /dev/null");
      $this->headers = array("Content-Type: text/plain");
      return \Response::forge(json_encode($result), $this->status, $this->headers);
  }

  public static function getProperties($path)
  {
      if (is_file($path)) {
          // init
          $ffprobe = FFProbe::create();
          $streams = $ffprobe->streams($path);
          // extracts streams informations
          // get video data
          $video = $streams->videos()->first();
          // filters video first streams
          $dimensions = $video->getDimensions();
          // get audio data
          $audio = $streams->audios()->first();
          // filters audio first streams
          return ['screen_width' => $dimensions->getWidth(), 'screen_height' => $dimensions->getHeight(), 'video_bitrate' => $video->get('bit_rate'), 'audio_bitrate' => $audio->get('bit_rate')];
      }
      throw new ErrorException('Not found file: ' . $path);
  }

 /**
  * {@inheritdoc}
  */
 public function read($filename)
 {
     $meta = new ValueBag();
     try {
         $format = $this->ffprobe->format($filename);
         if ($format->has('format_name')) {
             $meta->set('media.format_name', new MetaValue($format->get('format_name')));
         }
         if ($format->has('format_long_name')) {
             $meta->set('media.format_long_name', new MetaValue($format->get('format_long_name')));
         }
         if ($format->has('duration')) {
             $meta->set('media.duration', new MetaValue($format->get('duration')));
         }
         if ($format->has('bit_rate')) {
             $meta->set('media.bit_rate', new MetaValue($format->get('bit_rate')));
         }
         if ($format->has('width')) {
             $meta->set('media.width', new MetaValue($format->get('width')));
         }
         if ($format->has('height')) {
             $meta->set('media.height', new MetaValue($format->get('height')));
         }
         if ($format->has('nb_streams')) {
             $meta->set('media.number_of_streams', new MetaValue($format->get('nb_streams')));
         }
         $streams = $this->ffprobe->streams($filename);
         foreach ($streams as $stream) {
             $index = $stream->get('index');
             $prefix = 'stream_' . $index;
             $type = 'media';
             if ($stream->isVideo()) {
                 $type = 'video';
             } elseif ($stream->isAudio()) {
                 $type = 'audio';
             }
             if ($stream->has('codec_type')) {
                 $meta->set("{$type}.{$prefix}.codec_type", new MetaValue($stream->get('codec_type')));
             }
             if ($stream->has('codec_name')) {
                 $meta->set("{$type}.{$prefix}.codec_name", new MetaValue($stream->get('codec_name')));
             }
             if ($stream->has('codec_long_name')) {
                 $meta->set("{$type}.{$prefix}.codec_long_name", new MetaValue($stream->get('codec_long_name')));
             }
             if ($stream->has('codec_time_base')) {
                 $meta->set("{$type}.{$prefix}.codec_time_base", new MetaValue($stream->get('codec_time_base')));
             }
             if ($stream->has('codec_tag_string')) {
                 $meta->set("{$type}.{$prefix}.codec_tag", new MetaValue($stream->get('codec_tag_string')));
             }
             if ($stream->has('bit_rate')) {
                 $meta->set("{$type}.{$prefix}.bit_rate", new MetaValue($stream->get('bit_rate')));
             }
             if ($stream->has('display_aspect_ration')) {
                 $meta->set("{$type}.{$prefix}.aspect_ratio", new MetaValue($stream->get('display_aspect_ratio')));
             }
             if ($stream->has('avg_frame_rate')) {
                 $meta->set("{$type}.{$prefix}.frame_rate", new MetaValue($stream->get('avg_frame_rate')));
             }
             if ($stream->has('bits_per_sample')) {
                 $meta->set("{$type}.{$prefix}.bits_per_sample", new MetaValue($stream->get('bits_per_sample')));
             }
             if ($stream->has('channels')) {
                 $meta->set("{$type}.{$prefix}.channels", new MetaValue($stream->get('channels')));
             }
         }
     } catch (\Exception $e) {
     }
     return $meta;
 }
 public function testProbeOnRemoteFile()
 { $fileName='https://content.jwplatform.com/manifests/yp34SRmf.m3u8';
    $ffprobe = FFMpeg\FFProbe::create();
    
     return $ffprobe->streams($fileName)->videos()->first()->all();
     
 }


 private function ingestImageAsset($filepath, $type, $ffprobe_config)
 {
     $msg = "Creating probe, for file: {$filepath}";
     $this->report[] = $msg;
     static::$logger->info($msg);
     try {
         $probe = FFProbe::create($ffprobe_config, static::$logger);
         if (!$probe) {
             throw new \Exception('FFProbe failed to create.');
         } else {
             $msg = "Probe created";
             $this->report[] = $msg;
             static::$logger->info($msg);
         }
     } catch (Exception $e) {
         $msg = "Probe creation failed: " . $e->getMessage();
         $this->report[] = $msg;
         static::$logger->info($msg);
     }
     $assignedProperties = [];
     $msg = "Probing file properties: {$filepath}";
     $this->report[] = $msg;
     static::$logger->info($msg);
     try {
         $props = $probe->format($filepath)->all();
         if ($props) {
             $assignedProperties[] = $this->formatToJsonArray($props);
         }
     } catch (ExecutionFailureException $e) {
         $msg = $e->getMessage();
         static::$logger->addWarning($msg);
         return false;
         // triggers rejection in ingest
     }
     $msg = "Probing exif properties";
     $this->report[] = $msg;
     static::$logger->info($msg);
     $exif = new Exif($filepath);
     $msg = "Encoding derived metadata";
     $this->report[] = $msg;
     static::$logger->info($msg);
     $exif_json = json_encode($exif->toArray(), JSON_UNESCAPED_SLASHES);
     $exif_obj = Util::decode_json($exif_json);
     $msg = "Encoding assignedProperties";
     $this->report[] = $msg;
     static::$logger->info($msg);
     $json_props = implode(",\n", $assignedProperties);
     $data = Util::decode_json($json_props);
     if ($exif_obj) {
         $msg = "Attaching derived exif props";
         $this->report[] = $msg;
         static::$logger->info($msg);
         $data->{'exif'} = $exif_obj;
     }
     $msg = "Returning derived data object";
     $this->report[] = $msg;
     static::$logger->info($msg);
     return $data;
 }
 public function testFprobe()
 { $ffmpeg='/usr/bin/ffprobe';
    $file='https://content.jwplatform.com/manifests/yp34SRmf.m3u8';
    $info = exec("$ffmpeg -show_format  -show_streams  -print_format json -pretty $file",$output,$result);
    return $output;
//  $f= exec("/usr/bin/ffprobe -v error -select_streams v:0 -show_entries stream=width,height,duration,bit_rate -of default=noprint_wrappers=1 http://channelstore.streamlytv.com/live/CMTV/interlink.m3u8");

// var_export($f);
// echo "\n\n";
}
}

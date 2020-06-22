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
      
        
        // $advancedMedia = $ffmpeg->openAdvanced($inputs)
        
  
        // echo $durationInSeconds;
        // echo '<br>';
        // echo $durationInMiliseconds;    echo '<br>';
        $churl = ['http://channelstore.streamlytv.com/live/CMTV/interlink.m3u8','fdghsh.m3u8','https://content.jwplatform.com/manifests/yp34SRm'];
    //    $churl = Channel::all()->pluck('url');
    //     foreach ($churl as $url) {
    //         try {
    //             if ($url==null) {
    //                 echo 'url not found';
    //             }
                
    //             echo $url; echo '<br>';
    //             $ffprobe = FFMpeg\FFProbe::create();
    //             $da= $ffprobe
    //     ->streams($url) // extracts streams informations
    //     ->videos()    
    //     ->first()              // returns the first video stream
    //    ->all();  
    //       print_r($da); echo '<br>'; echo '<br>';
    //     //  echo 'isvalid'.$ffprobe->isValid($url);
    //         } catch (FFMpeg\Exception\RuntimeException $e) {
    //             echo "error:".$url; echo '<br>';
    //         }
    //         echo PHP_EOL;
    //     }
        //return view('admin.videoanylyzer.index', compact('da'));
        try {  
              //print_r($churl);
            //  $myarr=['http://channelstore.streamlytv.com/live/CMTV/interlink.m3u8','http://channelstore.streamlytv.com/live/AETV/interlink.m3u8','fdghsh.m3u8'];
            $ffprobe = FFMpeg\FFProbe::create();
            foreach ($churl as $url) {
                echo $url; echo '<br>';
                $da= $ffprobe
        ->streams($url) // extracts streams informations
        ->videos()                 // filters video streams
        ->first()  ;                   // returns the first video stream
       // ->get('width');  
          print_r($da);echo '<br>'; echo '<br>';
        //  echo 'isvalid'.$ffprobe->isValid($url); 
            }
            
        } catch (FFMpeg\Exception\RuntimeException $e) {
            echo "error";
        }
           

    // FFMpeg::open('ss.mp4')
    //     ->getStreams()
    //     ->videos()
    //     ->first()
    //     ->getDimensions();
}

public function probeAudioFile()
 {
    //   $churl = ['http://channelstore.streamlytv.com/live/CMTV/interlink.m3u8','fdghsh.m3u8','http://radioitaliatv-lh.akamaihd.net/i/radioitaliatv_1@329645/master.m3u8','https://content.jwplatform.com/manifests/yp34SRmf.m3u8'];
     $input='https://content.jwplatform.com/manifests/yp34SRmf.m3u8';
     $ffprobe = FFMpeg\FFProbe::create();
     $output = array();
     $format = $ffprobe->format($input)->all();
     $streams = $ffprobe->streams($input)->videos()->first()->all();
     $fileName=$format['filename'];
     $format_name=$format['format_name'];
    //  $format = $ffprobe->format($input)->get('format_name');
    // $channels = $ffprobe->streams($input)->videos()->first()->all();
    // $bits = $ffprobe->streams($input)->videos()->first()->get('bits_per_sample');
     //$sample_rate = $ffprobe->streams($input)->videos()->first()->get('sample_rate');
     array_push($output, $format,$fileName,$format_name,$streams);
    //  return view('admin.videoanylyzer.index', compact('output'));
    return $output;
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
 public function testProbeOnRemoteFile()
 { $filename='https://content.jwplatform.com/manifests/yp34SRmf.m3u8';
    $ffprobe = FFMpeg\FFProbe::create();
    // $ffprobe->streams($filename)->videos()->first()->all();
    $format = $ffprobe->format($filename)->all();
    $streams = $ffprobe->streams($filename)->videos()->all();
     return [$format,$streams];
     
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


public function test2(){
    $a=[]; $outputs=[];
    // $churl = Channel::all()->pluck('url');
    $churl = ['http://channelstore.streamlytv.com/live/CMTV/interlink.m3u8','fdghsh.m3u8','http://radioitaliatv-lh.akamaihd.net/i/radioitaliatv_1@329645/master.m3u8','https://content.jwplatform.com/manifests/yp34SRmf.m3u8','https://www.youtube.com/watch?v=9ao4FEaDGhQ'];
    foreach ($churl as $url) {
        // $file_headers = @get_headers($url);
        
        // if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
        //     $exists = 'dead link.';
        // }  
    ini_set('default_socket_timeout', 1);

    if(!$fp = @fopen($url, "r")) {
    
       $urlstatus="no";
        $a['url']=$url;
        $a['isStreaming'] =$urlstatus;
        
       
    $a['isHLS'] ='No';
    } else {
        
        $urlstatus="streaming";
        $a['url']=$url;
        $a['isStreaming'] =$urlstatus;
        $m3u8 = file_get_contents($url);
        if(!$m3u8)
        return false;
        $array = preg_split('/$\n?^/m', trim($m3u8), NULL, PREG_SPLIT_NO_EMPTY);
    if($array[0]!= '#EXTM3U'){
     
    $a['isHLS'] ='No';
    }else{
        $a['isHLS'] ='Yes';
            if(strpos($m3u8, 'EXT-X-STREAM-INF') !== FALSE || strpos($m3u8, 'EXT-X-I-FRAME-STREAM-INF') !== FALSE){
                
                $a['hls_manifest_type'] ='MasterPlaylist';
                $hls_manifest_type = "MasterPlaylist";
                $a['variants'] =substr_count($m3u8, 'RESOLUTION');

                if(strpos($m3u8, 'EXT-X-KEY:METHOD=AES-128') !== FALSE) {$a['isEncrypted'] ='Yes';} else{$a['isEncrypted'] ='No';}
            }
            else {
                $a['hls_manifest_type'] ='MediaPlaylist';
                $hls_manifest_type = "MediaPlaylist"; 
                $a['variants'] =substr_count($m3u8, 'RESOLUTION');
                if(strpos($m3u8, 'EXT-X-KEY:METHOD=AES-128') !== FALSE) {$a['isEncrypted'] ='Yes';} else{$a['isEncrypted'] ='No';}
            }
        }
        fclose($fp);
    }
    array_push($outputs, $a);
}


return view('admin.videoanylyzer.index', compact('outputs'));
//   return $output; 
   // echo $this->getHttpResponseCode('http://channelstore.streamlytv.com/live/TVG/interlink.m3u8');
}

    function getHttpResponseCode(string $url): int
{
    $headers = get_headers($url);
    return substr($headers[0], 9, 3);
}
}

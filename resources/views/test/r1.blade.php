@extends('app')

@section('header')
    <meta charset="utf-8">
@endsection

@php
    
    if (! function_exists('getProgressColor')) {
       function getProgressColor($percentage):string {
      
        if ($percentage <= 50) {
    return '#E08087';
  } elseif ($percentage <= 75) {
    return '#F9968B';
  } else {
    return '#8DDCAD';
  }
       }
    }
@endphp

@section('title', 'testSeult')

@section('style')
<style id="fonts" type="text/css">
    @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 400;
        src: url({{ storage_path("fonts/Roboto-Regular.ttf") }}) format("truetype");
    }

    @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 600;
        src: url({{ storage_path("fonts/Roboto-Medium.ttf") }}) format("truetype");
    }

    @font-face {
        font-family: 'Roboto';
        font-style: normal;
        font-weight: 700;
        src: url({{ storage_path("fonts/Gilroy-Bold.ttf") }}) format("truetype");
    }
</style>

   
     <style type="text/css">
* {
  padding: 0;
  margin: 0;
  font-family: Roboto, sans-serif;
}

.wrapper-result {
    position: relative;
  background-color: #fff;
  padding: 11px 4px;
  padding-bottom: 7px;
}

.result-title {
  font-weight: 600;
  margin-bottom: 1.25rem;
  font-size: 1.16rem;
}

.result-user-name {
  font-size: 1.20rem;
  font-weight: 600;
  color: #000;
}

.result-job-name {
  font-size: 0.875rem;
  font-weight: 600;
  color: #000;
}

.more-test {
  padding-top: 1.5rem;
  padding-bottom: 1.5rem ;
  padding-left: 20px /* 40px */;
  padding-right: 20px /* 40px */;
  border-radius: 20px;
  margin-bottom: 10px;
  position: relative;
  background-color: #f4f6f9;
}



.job-desc-title {
  margin-bottom: 10px;
  width: 80%;
  font-size: 1rem;
}

.line-progress-wrapper {
  position: relative;
  width: 100px;
  
  height: 7px;
  background-color: rgb(133 140 148 / 0.4);
  border-radius: 1rem;
}
.progress-text {
  width: 4rem;
}
.line-progress {
  position: absolute;
  left: 0;
  top: 0;
  right: 0;
  height: 100%;
  border-radius: 1rem;
}
.time {
    position: absolute;
    right:  25px;

}
     </style>
@endsection
@section('content') 
<main id="root">
    
    <div style="padding: 0.5rem">
        <div class="wrapper-result">
            <h1 class="result-title"></h1>
            <div class="more-test">
              <div>
                <h2 class="result-user-name">{{$candidate->name}}</h2>
                <h4 class="result-job-name">{{$candidate->specialization}}</h4>
              </div>
            </div>
            @foreach ($data->result as  $value)
            <table class="more-test">
             
                <tr>
                   <td style="width: 100%">
                   
                    <h4 class="job-desc-title">
                       {{$value['title']}}
                    </h4>
                    <div class="result-description-wrapper">
                      <p class="result-job-subtitle">
                        {{$value['result']}}
                      </p>
                    </div>
                  </td>
                  <td>
                    <td>
                        <div class="time">
                            <p style="margin-bottom: 8px; font-size: 14px;">{{Carbon\Carbon::parse($value['finishedTestTime'])->format('Y/m/d h:i:s')}}</p>
                        </div>
                        <div class="progress" >
                     
                          <p class="progress-text" id="progressText">{{$value['resultCount']}} / {{$value['maxBall']}}</p>
                        </div>
                      </td>
                      <td>
                        <div class="percentages">
                           
                          <div class="result-percentage">
                            <div class="line-progress-container">
                              <div class="line-progress-wrapper">
                                <div
                                  class="line-progress"
                                  style="width: {{$value['efficiensy']}}%; background-color: {{ getProgressColor($value['efficiensy']) }}"
                                ></div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span style="display: block; margin-left:10px; "
                          >{{$value['efficiensy']}}%</span
                        >
                      </td>
                    </td>

                  </tr>
                 
            
              
            </table>
            @endforeach
          </div>
    </div>

</main>
<script>
@endsection
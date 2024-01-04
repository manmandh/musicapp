@extends('layouts/main')
@section('ContentWarpper')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>


    <section class="content">
      <div class="container-fluid">
        <h2>Tạo một Playlist của bạn lên FunTune</h2>
        {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

        <form id="uploadForm" action="{{route('admin.storePlaylist')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                        <label for="">Tên Playlist</label>
                        <input type="text" name="title" class="form-control">
                </div>
                
                <div class="form-group">
                        <label for="">Mô tả</label>
                        <input type="text" name="description" class="form-control">
                </div>

                <div>
                        <label for="">Bài hát </label> <br>
                        <select name="playlist_song " id="" class="form-select mt-3 p-2 col-10">
                                {{-- @dd($songs) --}}
                                @foreach ( $songs as  $song)
                                <option value="">{{ $song->title}}</option>  
                                @endforeach
                               
                        
                        </select>
                </div>

                <div><input type="hidden" name="user_id" value="1"></div>
                <div class="mb-3"></div>
                <input type="file" name="music_file" id="inputFile" class="d-none">
                <div class="btn btn-secondary" onclick="clickFile()">Thêm tệp</div>
                <div class="btn btn-secondary" onclick="addFile()">Xác nhân tải lên</div>
                @error('music_file')
                    
                @enderror
                <div id="fileInfo"></div>
                <div id="fileInfo2"></div>
        </form>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <script>
      
    var selectedFile;
      var fileInput=document.getElementById('inputFile');
      var fileInfoDiv=document.getElementById('fileInfo'); 
      var fileInfoDiv2=document.getElementById('fileInfo2'); 
      var listArtists=[];
      function clickFile(){
              fileInput.click();
              fileInput.addEventListener('change',function(){
              selectedFile=fileInput.files[0];
              
              alert('Đã chọn tệp' + selectedFile.name);
              fileInfoHTML='<p> tên tệp:'+selectedFile.name +'</p>'
                            +'<p> kích thước:'+selectedFile.size/1024+'kb' +'</p>';

              fileInfoDiv.innerHTML=fileInfoHTML;
              });
      }
      function addFile(){
          if(selectedFile==null){
              alert('Hãy chọn tệp');

          }
          else{
              // alert('Tải lên tệp thành công ' + selectedFile.name);
              
              document.getElementById('uploadForm').submit();
          }
             

      }
       $(document).ready(function() {
              $("#search-input").on("input", function() {
                      var query = $(this).val();

                      if (query !== "") {
                      $.ajax({
                              type: "POST",
                              headers: {
                               'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                                },

                              url: 'searchArtist', // Đường dẫn tới file xử lý tìm kiếm phía server
                              data: {query: query},
                              success: function(response) {
                                      $("#search-results").html(renderResults(response.artists));
                                      
                                      response.artists.forEach(artist => {
                                              document.getElementById("art"+artist.id).addEventListener("click", function(event) {

                                              if (event.target.classList.contains("btn")) {
                                      // Xử lý khi nút được click
                                      if(!checkExistArtist(artist)){
                                                      listArtists.push(artist);
                                          
                                             $("#listAritst").html(renderList(listArtists));
                                             

                                             listArtists.forEach(art2 => {
                                              document.getElementById("artt"+art2.id).addEventListener("click", function(event) {

                                              if (event.target.classList.contains("btn")) {
                                      // Xử lý khi nút được click
                                      
                             
                                      listArtists = listArtists.filter(artist3 => artist3.id !== art2.id);
                                          console.log(listArtists);
                                          $("#listAritst").html(renderList(listArtists));
                              
                                      
                                      // console.log(listArtists);
                                      }
                                      });

                                      });
                                      }
                                      
                                      
                                      // console.log(listArtists);
                                      }
                                      });

                                      });
                              }
                            
                      });
                      } else {
                      $("#search-results").html("");
                      console.log('đã thực hiện');
                      }
              });
      });
      function checkExistArtist(artist){
              listArtists.forEach(art => {
               
                      if(art.id==artist.id){
                              console.log("Check ne");
                              return true;
                             
                      }
              });
              return false;
            
      }
      function renderResults(artists) {
      var html = '<ul class="list-unstyled">';
      for (var i = 0; i < artists.length; i++) {
              if(!listArtists.includes(artists[i])){
                      html += '<li >' + artists[i].name + '<div id="art'+ artists[i].id +'"  class="btn btn-warning">Thêm</div></li>'
              }
              else html += '<li>' + artists[i].name + '<div id="art'+ artists[i].id +'"  class="btn btn-warning disabled">Thêm</div></li>'
       
      }
      html += '</ul>';
      return html;
}
      function shirtArtist(artist){
              listArtists.forEach(art => {
                      if(art.id==artist.id){
                              listArtists.shift();
                      }
              });
      }
      function renderList(artists) {
      
      var html = '<ul class="list-unstyled">';
      for (var i = 0; i < artists.length; i++) {
      
                      html += '<li>' + artists[i].name + '<div id="artt'+ artists[i].id +'"  class="btn btn-secondary">Bỏ</div></li>'
              
        
       
      }
      html += '</ul>';
      return html;

      
      
  }

      
</script>
@endsection

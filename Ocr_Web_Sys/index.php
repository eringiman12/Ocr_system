<?php
session_start();
// サムネイルフォルダを調べる
$files = glob("./asset/img/tmp/gazo/".$_SESSION["User_Name"]."/*.png");
if (!empty($_GET["Name"])) {
   # code...
   $_SESSION["User_Name"] = $_GET["Name"];
   header('Location: ./index.php');
} else {
   if (empty($_SESSION["User_Name"])) {
      echo "リダイレクト対象";
   } 
}

// $Tmp_Folda_Path = "./asset/img/tmp/gazo/".$_SESSION["User_Name"];
// $Tmp_Folda_Path = "./asset/img/tmp/pdf/".$_SESSION["User_Name"];
folda_create("./asset/img/tmp/gazo/".$_SESSION["User_Name"]);
folda_create("./asset/img/tmp/pdf/".$_SESSION["User_Name"]);
folda_create("./asset/img/tmp/pdf/".$_SESSION["User_Name"]);

function folda_create($folda_path) {
   if(!file_exists($folda_path)){
      //存在したときの処理
      mkdir($folda_path, 0777);
   }
   
}
?>

<!-- 必要な物 -->
<!-- imgmagick -->
<!-- ghostscript -->
<!-- imagick -->
<!-- 環境構築参考URL: https://lazesoftware.com/blog/211030/
上記は、ghostscriptの環境構築方法は乗っていないが普通にインストールし環境構築が出来る
-->

<!DOCTYPE html>
<html>
   
<head>
   <meta charset="UTF-8">
   <link type="text/css" rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/cupertino/jquery-ui.min.css" />
   <link rel="stylesheet" href="./asset/css/index.css">
   <meta charset="UTF-8">
   <script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
   <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>
   
   <script src="https://docs.opencv.org/4.5.1/opencv.js"></script>  
</head>

<body>

   <div class="Main_Box">
      <!-- <img src="" id="result" /> -->
      <!-- <a href="" id="ss" download="html_ss.png">スクリーンショット(document.body全体)をダウンロード</a> -->
      <div class="img_select">
      <!-- <p><?php echo $_SESSION["User_Name"]?></p> -->
         <div class="file_select_box">
            <!-- <p>PDFを選択してください。</p> -->
            <p id="errmsg" style="color:red;"></p>   
            <p id="p1"></p>
            <table>
               <tr>
                  <td class="daimoku">ファイル選択</td>
                   <td class="process">
                     <form action="./Process_File.php" method="post" id="File_Select" enctype="multipart/form-data">
                        <input type="file" name="Select_Files" id="inputfile1" accept="application/pdf" onchange="onChangeFile(event)">
                        <input type="submit" value="送信" style="display: none;" id="hanei">
                     </form>
                  </td>
               </tr>
               <tr>
                  <td class="daimoku">銀行選択</td>
                  <td class="process">
                     <select id="Select_Bank">
                        <option></option>
                        <option  value="12@5">四国銀行</option>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td class="daimoku">処理対象商号名</td>
                  <td class="process">
                     <input type="text" value="" class="text" placeholder="入力文字がcsvファイルになります。">
                  </td>
               </tr>
            </table>
         </div>
         
         <div class="ims_sosa_box">
            <div class="bunkatu_img_box">
               <?php foreach ($files as $key => $value) {?>
                  <div class="Img_cl" onclick="preview('<?php echo $value;?>')">
                     <input type="checkbox" value="" disabled checked>
                     <img src="<?php echo $value;uniqid();?>">
                  </div>
               <?php }?>
            </div>
            <div class="img_sosa" id="Sosa_IMg">
               <table class="ope_table">
                  <tr>
                     <td><button id="zoom_in" onclick="Zoom_In()">拡大</button></td>
                     <td><input type="number" min="1" max="100" value="0" id="kakudai_bairitu"></td>
                  </tr>
                  <tr>
                     <th><button id="zoom_out" onclick="Zoom_Out()">縮小</button></th>
                     <td><input type="number" min="1" max="100" value="0" id="syukusho_bairitu"></td>
                  </tr>
                  <tr>
                     <th><button id="kaiten_left" onclick="left_kaiten()">左回転</button></th>
                     <td><input type="number" min="1" max="100" value="0" id="Left_kaiten"></td>
                  </tr>
                  <tr>
                     <th><button id="kaiten_right"  onclick="right_kaiten()">右回転</button></th>
                     <td><input type="number" min="1" max="100" value="0" id="right_kaiten"></td>
                  </tr>
                  <tr>
                     <th><button id="ido_ue" onclick="top_ido_Move()">上</button></th>
                     <td><input type="number" min="1" max="100" value="0" id="top_ido"></td>
                  </tr>
                  <tr>
                     <th><button id="ido_shita" onclick="bottom_ido_Move()">下</button></th>
                     <td><input type="number" min="1" max="100" value="0" id="bottom_ido"></td>
                  </tr>
                  <tr>
                     <th><button id="ido_right" onclick="left_ido_Move()">左</button></th>
                     <td><input type="number" min="1" max="100" value="0" id="left_ido"></td>
                  </tr>
                  <tr>
                     <th><button id="ido_migi" onclick="right_ido_Move()">右</button></th>
                     <td><input type="number" min="1" max="100" value="0" id="right_ido"></td>
                  </tr>
                  <tr>
                     <th><button id="Nowkigo">現在記号</button></th>
                     <td><input type="text"  value="" id="Now_kigo"></td>
                  </tr>
                  <tr>
                     <th><button id="Nowkigo">記号とXの距離</button></th>
                     <td><input type="text"  value="15" id="X_kyori"></td>
                  </tr>
                  <tr >
                     <th><button id="Nowkigo">回転変化</button></th>
                     <td><input type="text"  value="0" id="kaiten_ch_num"></td>
                  </tr>
                  <tr>
                     <th colspan="2"><button onclick="Chosei_Conplete()">調整完了</button></th>
                  </tr>
                  <tr>
                     <th colspan="2"><button id="ch_jiko">変換実行</button></th>
                  </tr>
               </table>
               <!-- <table class="debug">
                  <tr>
                     <td rowspan="2">1番目</td>
                     <td><input type="number"  value="0" id="x1"></td>
                     <td><input type="number"  value="0" id="x2"></td>
                  </tr>
                  <tr>
                     <td><input type="number"  value="0" id="y1"></td>
                     <td><input type="number"  value="0" id="y2"></td>
                  </tr>
                  <tr>
                     <td rowspan="2">2番目</td>
                     <td><input type="number"  value="0" id="x1_2"></td>
                     <td><input type="number"  value="0" id="x2_2"></td>
                  </tr>
                  <tr>
                     <td><input type="number"  value="0" id="y1_2"></td>
                     <td><input type="number"  value="0" id="y2_2"></td>
                  </tr>
                  
                  <tr>
                     <td rowspan="2">親要素</td>
                     <td><input type="number"  value="0" id="ox1_2"></td>
                     <td><input type="number"  value="0" id="ox2_2"></td>
                  </tr>
                  <tr>
                     <td><input type="number"  value="0" id="oy1_2"></td>
                     <td><input type="number"  value="0" id="oy2_2"></td>
                  </tr>
               </table> -->
            </div>
         </div>
      </div>
      
      
      <div class="maskcre">
         <button onclick="Mask_create()">マスク作成</button>
      </div>

      <div id="pdf_viewer">
         <div id="canvas_container">
            <!-- <canvas id="pdf_renderer"></canvas> -->
            <p id="Select_option">選択されていません</p>
            <!-- <div class="demo"></div>
            <script>
               $('.demo').croppie({
                  url: './asset/img/tmp/gazo/後藤/thumnail-0.png',
               });
               </script>
               <img class="my-image" src="./asset/img/tmp/gazo/後藤/thumnail-0.png" />
               <script>
               $('.my-image').croppie();
            </script> -->
         </div>
      </div>

      <div class="canvus_box" id="a">
         <!-- <a href="" id="ss" download="html_ss.jpg">DL</a> -->
         <!-- <form action="" enctype="multipart/form-data" method="post">
            <canvas id="canvas"></canvas>
            <input type="hidden" value="" name="canvas_file_name" id="canvas_val">
            <input type="hidden" value="" name="canvas_id_name" id="canvas_id_name">
            <input type="submit" style="display: none;" id="canvas_submit">
          </form> -->
         <canvas id="canvas"></canvas>
         
                     <!-- <img src="" id="result" /> -->
      </div>
      <button id="dl_img_file">画像ダウンロード</button>

   </div>

   <script src="./asset/js/Move_pdf.js" type="text/javascript"></script>
</body>
</html>
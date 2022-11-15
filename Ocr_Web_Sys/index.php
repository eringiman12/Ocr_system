<?php
header("Cache-Control:no-cache,no-store,must-revalidate,max-age=0");
header("Pragma:no-cache");
session_start();
// サムネイルフォルダを調べる
$files = glob("./asset/img/tmp/gazo/" . $_SESSION["User_Name"] . "/*.png");
if (!empty($_GET["Name"])) {
   $_SESSION["User_Name"] = $_GET["Name"];
   header('Location: ./index.php');
} else {
   if (empty($_SESSION["User_Name"])) {
      echo "リダイレクト対象";
   }
}

// $Tmp_Folda_Path = "./asset/img/tmp/gazo/".$_SESSION["User_Name"];
// $Tmp_Folda_Path = "./asset/img/tmp/pdf/".$_SESSION["User_Name"];
folda_create("./asset/img/tmp/gazo/" . $_SESSION["User_Name"]);
folda_create("./asset/img/tmp/pdf/" . $_SESSION["User_Name"]);
folda_create("./asset/img/tmp/Complete/" . $_SESSION["User_Name"]);

function folda_create($folda_path)
{
   if (!file_exists($folda_path)) {
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
   <!-- UIkit CSS -->
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/uikit@3.11.1/dist/css/uikit.min.css" />

   <!-- UIkit JS -->
   <script src="https://cdn.jsdelivr.net/npm/uikit@3.11.1/dist/js/uikit.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/uikit@3.11.1/dist/js/uikit-icons.min.js"></script>

   <script src="https://docs.opencv.org/4.5.1/opencv.js"></script>
</head>

<body>
   <div class="Main_Box">
      <div class="img_select">
         <!-- 
         <button class="uk-button uk-button-default uk-margin-small-left" type="button" uk-toggle="target: #offcanvas-usage" uk-tooltip="title: 詳細設定; pos: bottom" uk-icon="cog"></button>
         <div id="offcanvas-usage" class="uk-width-5-6" uk-offcanvas>
            <div class="uk-offcanvas-bar">
               <button class="uk-offcanvas-close" type="button" uk-close></button>
               <ul uk-accordion>
                  <li class="uk-open">
                     <a class="uk-accordion-title" href="#">分割関連</a>
                     <div class="uk-accordion-content">
                        <div class="uk-flex">
                           <div>現在記号</div>
                           <div class="uk-margin-small-left"><input type="text" class="uk-width-1-2" value="" id="Now_kigo"></div>
                        </div>
                     </div>
                  </li>
                  <li>
                     <a class="uk-accordion-title" href="#">マスク関連</a>
                     <div class="uk-accordion-content">
                        <p></p>
                     </div>
                  </li>
                  <li>
                     <a class="uk-accordion-title" href="#">Item 3</a>
                     <div class="uk-accordion-content">
                        <p></p>
                     </div>
                  </li>
               </ul>
            </div>
         </div> -->

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
                        <option value="12@5">maru銀行</option>
                     </select>
                  </td>
               </tr>
               <tr>
                  <td class="daimoku">処理対象商号名</td>
                  <td class="process">
                     <input type="text" value="" class="text" id="shogo_name" placeholder="入力文字がcsvファイルになります。">
                  </td>
               </tr>
            </table>
         </div>

         <div class="ims_sosa_box">
            <div class="bunkatu_img_box uk-width-1-2">
               <?php foreach ($files as $key => $value) { ?>
                  <div class="Img_cl" onclick="preview('<?php echo $value; ?>')">
                     <input type="checkbox" id="<?php echo "thumnail-" . $key . "_checked" ?>" value="" disabled>
                     <img src="<?php echo $value;
                                 uniqid(); ?>">
                  </div>
               <?php } ?>
            </div>
            <div class="img_sosa" id="Sosa_IMg">
               <table class="ope_table">
                  <tr>
                     <td>
                        <button id="zoom_in" onclick="Zoom_In()" class="uk-button uk-button-default uk-button-small" uk-tooltip="title: 拡大; pos: left" uk-icon="plus-circle" uk-icon="plus-circle"></button>
                     </td>
                     <td>
                        <input type="number" min="1" max="100" value="0" id="kakudai_bairitu">
                     </td>
                  </tr>
                  <tr>
                     <th>
                        <button id="zoom_out" onclick="Zoom_Out()" class="uk-button uk-button-default uk-button-small" uk-tooltip="title: 縮小; pos: left" uk-icon="minus-circle"></button>
                     </th>
                     <td>
                        <input type="number" min="1" max="100" value="0" id="syukusho_bairitu">
                     </td>
                  </tr>
                  <tr>
                     <th>
                        <!-- <button id="kaiten_left" onclick="left_kaiten()" class="uk-button uk-button-default uk-button-small" uk-tooltip="title: 左回転; pos: left" uk-icon="reply"></button> -->
                        <button id="kaiten_left" onclick="left_kaiten()" class="uk-button uk-button-default uk-button-small uk-icon uk-icon-image" style="background-image: url(./asset/img/sozai/left.svg); padding:13px" uk-tooltip="title: 左回転; pos: left"></button>
                     </th>
                     <td>
                        <input type="number" min="1" max="100" value="0" id="Left_kaiten">
                     </td>
                  </tr>
                  <tr>
                     <th>
                        <button id="kaiten_right" onclick="right_kaiten()" class="uk-button uk-button-default uk-button-small uk-icon uk-icon-image" style="background-image: url(./asset/img/sozai/right.svg); padding:13px" uk-tooltip="title: 右回転; pos: left"></button>
                     </th>
                     <td>
                        <input type="number" min="1" max="100" value="0" id="right_kaiten">
                     </td>
                  </tr>
                  <tr>
                     <th>
                        <button id="ido_ue" onclick="top_ido_Move()" class="uk-button uk-button-default uk-button-small" uk-tooltip="title: 上移動; pos: left" uk-icon="triangle-up"></button>
                     </th>
                     <td><input type="number" min="1" max="100" value="0" id="top_ido"></td>
                  </tr>
                  <tr>
                     <th><button id="ido_shita" onclick="bottom_ido_Move()" class="uk-button uk-button-default uk-button-small" uk-tooltip="title: 下移動; pos: left" uk-icon="triangle-down"></button></th>
                     <td><input type="number" min="1" max="100" value="0" id="bottom_ido"></td>
                  </tr>
                  <tr>
                     <th><button id="ido_right" onclick="left_ido_Move()" class="uk-button uk-button-default uk-button-small" uk-tooltip="title: 左移動; pos: left" uk-icon="triangle-left"></button></th>
                     <td><input type="number" min="1" max="100" value="0" id="left_ido"></td>
                  </tr>
                  <tr>
                     <th><button id="ido_migi" onclick="right_ido_Move()" class="uk-button uk-button-default uk-button-small" uk-tooltip="title: 右移動; pos: left" uk-icon="triangle-right"></button></th>
                     <td><input type="number" min="1" max="100" value="0" id="right_ido"></td>
                  </tr>
                  <tr>
                     <!-- <th><button id="Nowkigo" class="uk-button uk-button-default uk-button-small">記号とXの距離</button></th> -->
                     <td><input type="hidden" value="15" id="X_kyori"></td>
                  </tr>
                  <tr>
                     <td><input type="hidden" value="0" id="kaiten_ch_num"></td>
                  </tr>
                  <tr>
                     <th colspan="2"><button onclick="Chosei_Conplete()" class="uk-button uk-button-default uk-button-small">調整完了</button></th>
                  </tr>
                  <tr>
                     <form action="./img_henkan.php" method="post" enctype="multipart/form-data" name="myform" id="Img_form">
                        <!-- <input type="hidden" value="" id="img_binary" name="teree"> -->
                        <input type="hidden" value="" id="File_Name" name="File_Name">
                        <th colspan="2"><button id="ch_jiko" class="uk-button uk-button-default uk-button-small">変換実行</button></th>
                     </form>
                  </tr>
               </table>
            </div>
         </div>
      </div>

      <div class="maskcre">
         <button onclick="Mask_create()" class="uk-button uk-button-default uk-button-small">マスク作成</button>
      </div>

      <div id="pdf_viewer" class="uk-width-3-4">
         <div id="canvas_container">
            <p id="Select_option">選択されていません</p>
         </div>
      </div>

      <div class="canvus_box" id="canvus_box">
         <!-- <canvas id="canvas"></canvas> -->
      </div>
      <!-- <form action="./img_henkan.php" method="post" enctype="multipart/form-data" name="myform">
         <input type="hidden" value="" id="img_binary" name="teree">
         <input type="hidden" value="" id="F_Name" name="File_Name">
         <button id="dl_img_file" class="uk-button uk-button-default uk-button-small" uk-icon="download" uk-tooltip="title: 画像ダウンロード; pos: bottom"></button>
      </form> -->
      <!-- <button id="dl_img_file" class="uk-button uk-button-default uk-button-small" uk-icon="download" uk-tooltip="title: 画像ダウンロード; pos: bottom"></button> -->

   </div>

   <script src="./asset/js/Move_pdf.js" type="text/javascript"></script>
</body>

</html>

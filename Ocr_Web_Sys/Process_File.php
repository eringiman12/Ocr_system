<?php
session_start();
$deleted_list  = delete_allfile("./asset/img/tmp/pdf/".$_SESSION["User_Name"]);
$deleted_list  = delete_allfile("./asset/img/tmp/gazo/".$_SESSION["User_Name"]);

$save = "./asset/img/tmp/pdf/".$_SESSION["User_Name"].'/'.$_FILES['Select_Files']['name'];
if(move_uploaded_file($_FILES['Select_Files']['tmp_name'], $save)){
   echo 'アップロード成功！';
}

$cmd = 'magick convert '.$save." ./asset/img/tmp/gazo/".$_SESSION["User_Name"]."/thumnail.png";
echo exec($cmd);
header("Cache-Control:no-cache,no-store,must-revalidate,max-age=0");
header("Pragma:no-cache");
header('Location: ./index.php');


// フォルダ内ファイル削除
function delete_allfile($dirpath=''){
	if ( strcmp($dirpath,'')==0 ){
       die('delete_allfile : error : please set dir_name');
   }
   
   // 削除リストの作成
	$deleted_list = array();
	// 引数のフォルダパス内のファイルを配列に格納
   $dir = dir($dirpath);
	
   while ( ($file=$dir->read()) !== FALSE ){
		if (preg_match('/^\./',$file)){ 
         continue; 
      } else {
			array_push($deleted_list, $file);
			if ( ! unlink("$dirpath/$file") ){ 
            die("delete_allfile : error : can not delete file [{$dirpath}/{$file}]");
         }
		}
	}
	return $deleted_list;
}
function onChangeFile(event) {
    var file = event.target.files[0];
    //  var url = window.URL.createObjectURL(file);
    //  PDFファイルの分割
    File_Bukatu(file);
}

// PDFファイルの分割処理の為の遷移
function File_Bukatu(e) {
    var sendbtn = document.getElementById("hanei");
    sendbtn.click();
}
// ウィンドウズ読み込み時処理
window.onload = function() {
    // canvasエリアのIDを定義
    var Canvas_Box = document.getElementById("canvus_box");
    //  サムネイル画像表示エリアの定義
    var imge_box = document.getElementsByClassName("Img_cl");

    //  表示画像特定ループ
    for (let i = 0; i < imge_box.length; i++) {
        //子要素のIDを取得し比較  (IDが存在しない時)
        if (document.getElementById("thumnail-" + i + "_canvas") == null) {
            Canvas_Box.innerHTML += "<canvas id='" + "thumnail-" + i + "_canvas" + "'></canvas>";
        }
    }
}

function preview(e) {
    //　銀行が選択されているID定義 
    var Select_Bank = document.getElementById("Select_Bank");
    if (Select_Bank.value != "") {
        document.getElementById("Select_option").innerText = "";
        imge_box = document.getElementById("canvas_container");
        //  クリック時のイベントの引数のファイル名をIDにする
        var file_id = e.split("/").reverse()[0].split('.')[0];
        //  子要素をすべて取得
        const child1 = imge_box.children;

        //  要素表示(子要素、id、イベント名)
        Yoso_Hyoji(child1, file_id, e)

        // 表示画像の特定
        var hyoji_img = hyoji();

        // canvasの子要素を配列で取得
        var canvasbox_Child = document.getElementById("canvus_box").children;
        if (canvasbox_Child.length != 0) {
            // 表示・非表示ループ
            for (let i = 0; i < canvasbox_Child.length; i++) {
                // 処理中のcanvasのIDを定義
                var hyoji_canvas = document.getElementById(canvasbox_Child.item(i).id);
                // 表示されている画像IDと紐付いているcanvasの表示・非表示を切り替える
                if (hyoji_canvas.id == hyoji_img.id + "_canvas") {
                    hyoji_canvas.style.display = "";
                } else {
                    hyoji_canvas.style.display = "none";
                }

            }
        }

        Create_Table();

    } else {
        alert("銀行名を選択してください")
    }

}

function Yoso_Hyoji(child1, file_id, e) {
    //  選択した画像を表示にする
    for (let i = 0; i < child1.length; i++) {
        //子要素のIDを取得し比較  
        var display_image = document.getElementById(child1.item(i).id);
        if (file_id == child1.item(i).id) {
            display_image.style.display = "";
        } else {
            if (display_image.id != "Mask_Table") {
                display_image.style.display = "none";
            }
        }
    }

    //  存在しない場合のみ画像要素を作成
    if (document.getElementById(file_id) == null) {
        var img_element = document.createElement('img');
        img_element.src = e;　
        // サムネイルファイル名拡張子抜きをidにする   
        img_element.id = file_id;
        // 画像追加
        imge_box.appendChild(img_element);
    }
}

function Create_Table() {
    document.getElementById("Select_option").innerText = "";
    var mask_table = document.getElementById("Mask_Table");

    if (mask_table != null) {
        mask_table.remove();
    }

    //　行列数の取得   
    var [row, col] = select_num();
    var Maskta = document.getElementById("canvas_container");

    //選択したvalue値をp要素に出力
    var table = document.createElement('table');
    table.id = "Mask_Table";
    Maskta.appendChild(table);
    var mask_table = document.getElementById("Mask_Table");

    for (var i = 0; i < row; i++) {
        // tr要素を生成
        var tr = document.createElement('tr');
        tr.id = "Mask_table_tr" + i;
        // th・td部分のループ
        for (var j = 0; j < col; j++) {
            var td = document.createElement('td');
            // td要素内にテキストを追加
            // td要素をtr要素の子要素に追加
            td.id = "Mask_table_td_row" + i + "_col" + j;
            tr.appendChild(td);
        }
        // tr要素をtable要素の子要素に追加
        mask_table.appendChild(tr);

        //  クリッピングマスクをドラッグ可能にする関数
        $("#Mask_Table").draggable();
        //  マスクのセルのサイズ変更可能にする関数
        $("#Mask_Table td").resizable();
    }
}

function Paturn_Match(Pra, bank_name) {
    // 選択時のPDFの回転   
    hyoji_img = hyoji();
    Pra = Pra / 10
    hyoji_img.style.transform = "rotate(" + Pra + "deg)";

    if (bank_name.indexOf("四国銀行") != -1) {
        bank_name = "しこ銀";
    } else {
        bank_name = "";
    }
    var aa = document.getElementById(hyoji_img.id);
    var [img_sa_width, img_sa_heigh] = Img_size_math(aa);

    console.log("横幅" + img_sa_width + "縦幅" + img_sa_heigh);
    return bank_name;
}

// 表示画像特定
function hyoji() {
    imge_box = document.getElementById("canvas_container");
    //  描画エリアの子要素を配列に格納
    const child1 = imge_box.children;

    //  表示画像特定ループ
    for (let i = 0; i < child1.length; i++) {
        //子要素のIDを取得し比較  
        var display_image = document.getElementById(child1.item(i).id);
        //   マスクID要素がないときの処理
        if (display_image.id != "Mask_Table") {
            //   子要素のIDが非表示でない時の処理
            if (display_image.style.display != "none") {
                // 子要素のIDを代入しループを抜ける
                var hyoji = child1.item(i).id;
                break;
            }
        }
    }

    //  現在表示されている画像IDを定義
    var hyoji_img = document.getElementById(hyoji);
    return hyoji_img;
}

// クロッパーマスクの作成
function Mask_create() {
    // マスクIDを定義
    var mask_table = document.getElementById("Mask_Table");
    if (mask_table != null) {
        //  子要素の数をカウントする
        var childElementCount = mask_table.childElementCount;
        if (childElementCount == 1) {
            //　行列数の取得   
            var [row, col] = select_num();
            //   tr部分のループ
            for (var i = 1; i < row; i++) {
                // tr要素を生成
                var tr = document.createElement('tr');
                tr.id = "Mask_table_tr" + i;
                // th・td部分のループ
                for (var j = 0; j < col; j++) {
                    var td = document.createElement('td');
                    // td要素内にテキストを追加
                    // td要素をtr要素の子要素に追加
                    td.id = "Mask_table_td_row" + i + "_col" + j;
                    tr.appendChild(td);
                }
                // tr要素をtable要素の子要素に追加
                mask_table.appendChild(tr);

                //  クリッピングマスクをドラッグ可能にする関数
                $("#Mask_Table").draggable();
                //  マスクのセルのサイズ変更可能にする関数
                $("#Mask_Table td").resizable();
            }
        }
    } else {
        alert("銀行を選択してください");
    }
}
//　調整完了後スクリーンショット取る
//  スクショ後は、サムネイル横のチェックボタンにチェック入れる
function Chosei_Conplete() {

    // 表示画像のID検索
    hyoji_img = hyoji();
    // 表示画像のID定義
    const img_id = document.getElementById(hyoji_img.id);

    // 座標足していく変数 
    var x_i = 0;
    var y_i = 0;

    //  y座標の初期値変数
    var y1_2 = 0;
    var y2_2 = 0;

    //  画像の設置位置
    var img_y = 0;

    //  処理対象の行列特定
    var [row, col] = select_num();

    //  alert(tr_total_size + 4);
    var tr_last_row = row - 1;
    var tr_last_row_size = document.getElementById("Mask_table_tr" + tr_last_row).clientHeight * col;

    var canvas = document.getElementById(hyoji_img.id + "_canvas");
    //  //  canvas ID定義
    //  const canvas = document.getElementById('canvas');
    // canvas描画
    const context = canvas.getContext('2d');
    //  canvasをクリアする
    context.clearRect(0, 0, 0, 0);


    // 　クリッピングマスクの線の太さ
    var border_size = 2;

    //  クリッピングマスクのID定義
    var Mask_Table = document.getElementById("Mask_Table");
    //  canvusの横幅をクリッピングマスクの最大横幅に設定
    canvas.width = Mask_Table.clientWidth;
    // canvasの高さをクリッピングマスクの高さに変更
    canvas.height = Mask_Table.clientWidth + Mask_Table.clientHeight + border_size * (row + 2) + tr_last_row_size;

    //  画像の横幅、高さの差分を求める
    var [img_sa_width, img_sa_heigh] = Img_size_math(img_id);

    //  console.log("高さ差分：" + img_sa_width + "現在の画像の大きさ：" + img_sa_heigh);

    //  1canvasの背景色を決める
    context.fillStyle = 'white';
    context.fillRect(0, 0, canvas.width, canvas.height);

    var x_kyori = document.getElementById("X_kyori");

    //  回転変化量
    var kaiten_ch_num = document.getElementById("kaiten_ch_num").value;

    //  描画するための行ループ
    for (var l = 0; l < row; l++) {
        // 各x,y座標変数の初期化
        var x_zahyo = 0;
        var y_zahyo = 0;
        var x_i = 0;
        var x1_2 = 0
        var x2_2 = 0;

        //   描画するための列ループ
        for (var i = 0; i < col; i++) {
            //  tdの位置特定
            var [x1_2, x2_2, y1_2, y2_2] = x_y_iti("#Mask_table_td_row" + l + "_col" + i);
            //  画像位置特定
            var [x_zahyo, y_zahyo] = Img_pos_get(img_id, x1_2, y1_2);

            //  計測x座標に一つ前のポインタで計測したx座標にborder2px*2（左右）を加算する
            //   加算値をx座標に代入
            x_zahyo = x_i + x_zahyo;
            //   加算値をy座標に代入
            y_zahyo = y_zahyo + y_i;

            // 切り抜き元画像のx始点位置
            var Moto_x = (x_zahyo - (img_sa_width / 2)) - Number(kaiten_ch_num);
            // 切り抜き元画像のy始点位置
            var Moto_y = (y_zahyo - (img_sa_heigh / 2)) - Number(kaiten_ch_num);
            // 切り抜き元画像の横幅
            var Moto_Width = (x2_2 + border_size - (img_sa_width / 2)) + Number(kaiten_ch_num) / 2;
            // 切り抜き元画像の高さ
            var Moto_Height = (y2_2 + border_size - (img_sa_heigh / 2)) + Number(kaiten_ch_num) / 2;
            // 描画先x始点位置(動的に変更されるように設定した場合位置ずれと大きさが変更される)
            var Byoga_x = x_kyori.value;
            // 描画先y始点位置
            var Byoga_y = img_y + border_size;
            // 描画横幅
            var Byoga_Width = x2_2 + img_sa_width + border_size;
            console.log(Byoga_Width);

            // 描画高さ
            var Byoga_height = y2_2 + img_sa_heigh + border_size;
            // console.log("描画高さ：" + y2_2);

            //　文字列の描画スタイル 
            //　描画文字列のフォント種類とフォントサイズ
            context.font = 0.7 + "vw serif";
            // 描画文字列のカラー
            context.fillStyle = "black";

            // 画像描画(下記にdrawImage関数の使い方)
            //  (描画対象ID,描画元x1の座標,描画元y1の座標,描画元画像幅,描画元x1の座標画像高さ,
            //   描画先画像x位置,描画先画像y位置,描画先画像幅, 描画先画像高さ)
            // context.drawImage(img_id, Moto_x, Moto_y, Moto_Width, Moto_Height, Byoga_x, Byoga_y, x2_2, y2_2);
            // context.drawImage(img_id, x_zahyo - img_sa_width, y_zahyo - img_sa_heigh, x2_2 + border_size + img_sa_width, y2_2 + border_size + img_sa_heigh, 15, img_y + border_size, x2_2, y2_2);
            context.drawImage(img_id, Moto_x, Moto_y, Moto_Width, Moto_Height, Byoga_x, Byoga_y, Byoga_Width, Byoga_height);

            //  加算値を前のポインタ値として加算
            x_i += x2_2 + border_size;
            // 文字列を埋め込む位置座標を加算していく
            img_y += y2_2 + border_size;

            // クリップした画像の先頭に@付与
            context.fillText("IX", 0, img_y, y_zahyo);
            // // // クリップした画像の先頭に@付与
            // context.fillText(" ", 25, img_y, y_zahyo);
        }

        //   y座標の高さポインタ値を格納
        y_i = y_i + y2_2 + border_size;
    }

    checked_Img();
}

function checked_Img() {
    // 表示画像のID検索
    hyoji_img = hyoji();
    var checked = document.getElementById(hyoji_img.id + "_checked");
    checked.disabled = false;
    checked.checked = true;
    checked.disabled = true;
}
// 画像サイズの差分調べる関数
function Img_size_math(img_id) {
    // 現在の画像高さ- 元々の画像の高さ　で現在の画像の高さの差分を求める
    var img_sa_heigh = img_id.clientHeight - img_id.naturalHeight;
    // 現在の画像横幅- 元々の画像の横幅　で現在の画像の横幅の差分を求める
    var img_sa_width = img_id.clientWidth - img_id.naturalWidth;

    return [img_sa_width, img_sa_heigh]
}

// 画像のダウンロード関数
function img_Dl(canvas, img_id_name) {
    //アンカータグを作成
    var a = document.createElement('a');
    //canvasをJPEG変換し、そのBase64文字列をhrefへセット
    a.href = canvas.toDataURL("image/jpeg");
    //ダウンロード時のファイル名を指定
    a.download = img_id_name + ".jpg";
    //クリックイベントを発生させる
    a.click();
}

// レイヤーマスクの位置取得
function x_y_iti(td_ID) {
    var pos = $(td_ID).parent().parent().position();
    // canvas_containerのx1,x2からの相対位置
    var x1_2 = pos.left;
    var x2_2 = $(td_ID).width();
    // canvas_containerのy1,y2からの相対位置
    var y1_2 = pos.top;
    var y2_2 = $(td_ID).height();

    return [x1_2, x2_2, y1_2, y2_2];
}

// 画像の位置取得
function Img_pos_get(img_id, x1_2, y1_2) {
    var img_pos = $(img_id).position();
    //  x座標の始点位置
    //  console.log(x1_2);
    var x_zahyo = Number(x1_2 - Math.round(img_pos.left));
    //  y座標の始点位置
    var y_zahyo = y1_2 - Math.round(img_pos.top);
    return [x_zahyo, y_zahyo]
}

// 銀行テーブル行列取得
function select_num() {
    // セレクトボックスのID
    var row_id = document.getElementById("Select_Bank").value;
    var kugiri = "@";
    // @で分割   
    var array_suuji = row_id.split(kugiri);
    var row = array_suuji[0];
    //　後ろが列   
    var col = array_suuji[1];
    return [row, col];
}


//ーーーーーーーーーー
//  画像の移動
//ーーーーーーーーーー
var d = 0;
var y = 0;
var x = 0;
// 拡大ボタンがクリックされたとき
function Zoom_In() {
    //  表示されている画像を特定1
    hyoji_img = hyoji();
    var bairitu = document.getElementById("kakudai_bairitu").value;
    bairitu = Number(bairitu);
    hyoji_img.width = hyoji_img.width + bairitu;
    hyoji_img.height = hyoji_img.height + bairitu;
}

// 縮小ボタンがクリックされたとき
function Zoom_Out() {
    //  表示されている画像を特定1
    hyoji_img = hyoji();
    var bairitu = document.getElementById("syukusho_bairitu").value;
    bairitu = Number(bairitu);
    hyoji_img.width = hyoji_img.width - bairitu;
}

// 左回転がクリックされたとき
function left_kaiten() {
    //  表示されている画像を特定1
    hyoji_img = hyoji();
    var bairitu = document.getElementById("Left_kaiten").value;
    bairitu = Number(bairitu);
    bairitu = bairitu / 10
    d = d - bairitu;
    hyoji_img.style.transform = "rotate(" + d + "deg)";
    var kaiten_ch_num = document.getElementById("kaiten_ch_num");
    kaiten_ch_num.value += bairitu;
}

// 右回転がクリックされたとき
function right_kaiten() {
    //  表示されている画像を特定1
    hyoji_img = hyoji();
    var bairitu = document.getElementById("right_kaiten").value;
    bairitu = Number(bairitu);
    bairitu_rotate = bairitu / 10
    d = d + bairitu_rotate;

    hyoji_img.style.transform = "rotate(" + d + "deg)";
    var kaiten_ch_num = document.getElementById("kaiten_ch_num");

    //  var Num = Number(kaiten_ch_num.value)
    var Num = Number(kaiten_ch_num.value)
    kaiten_ch_num.value = Num + bairitu;
    //  console.log(Num);
}

// 上ボタンがクリックされたとき
function top_ido_Move() {
    //  表示されている画像を特定1
    hyoji_img = hyoji();
    var bairitu = document.getElementById("top_ido").value;
    bairitu = Number(bairitu);
    y -= bairitu;
    hyoji_img.style.position = "relative";
    hyoji_img.style.top = y + "px";
}

// 上ボタンがクリックされたとき
function bottom_ido_Move() {
    //  表示されている画像を特定1
    hyoji_img = hyoji();
    var bairitu = document.getElementById("bottom_ido").value;

    bairitu = Number(bairitu);
    y += bairitu;
    hyoji_img.style.position = "relative";
    hyoji_img.style.top = y + "px";
}

// 左ボタンがクリックされたとき
function left_ido_Move() {
    //  表示されている画像を特定1
    hyoji_img = hyoji();
    var bairitu = document.getElementById("left_ido").value;
    bairitu = Number(bairitu);
    x -= bairitu;
    hyoji_img.style.position = "relative";
    hyoji_img.style.left = x + "px";
}

// 右ボタンがクリックされたとき
function right_ido_Move() {
    //  表示されている画像を特定1
    hyoji_img = hyoji();
    var bairitu = document.getElementById("right_ido").value;

    bairitu = Number(bairitu);
    x += bairitu;
    hyoji_img.style.position = "relative";
    hyoji_img.style.left = x + "px";
}
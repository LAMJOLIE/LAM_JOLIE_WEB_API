/* Navabarを固定する JS  開始*/
window.addEventListener("scroll",function(){
    var header = document.querySelector("header");
    header.classList.toggle("sticky", window.scrollY > 0);
})
/* Navabarを固定する JS  終了*/


/* Geolocation 開始*/

//ユーザーの現在の位置情報を取得
navigator.geolocation.getCurrentPosition(successCallback, errorCallback);

/* ユーザーの現在の位置情報を取得 */
function successCallback(position) {
    var gl_textA = "緯度：" + position.coords.latitude + "<br>";
    var gl_textB = "経度：" + position.coords.longitude + "<br>";
    // document.getElementById("show_latitude").innerHTML = gl_textA;
    // document.getElementById("show_longitude").innerHTML = gl_textB;
    document.getElementById("lat").value = position.coords.latitude;
    document.getElementById("lng").value = position.coords.longitude;
  }

  /* 位置情報が取得できない場合 */
function errorCallback(error) {
    var err_msg = "";
    switch(error.code)
    {
      case 1:
        err_msg = "位置情報の利用が許可されていません";
        break;
      case 2:
        err_msg = "デバイスの位置が判定できません";
        break;
      case 3:
        err_msg = "タイムアウトしました";
        break;
    }
    document.getElementById("show_latitude").innerHTML = err_msg;
    document.getElementById("show_longitude").innerHTML = err_msg;
    //デバッグ用→　document.getElementById("show_result").innerHTML = error.message;
  }

/* Geolocation 終了*/


/* ページングJS　開始*/ 
const shopLists = document.querySelectorAll(".place-content");
console.log(shopLists.length);
const pagingArea = document.querySelector("#paging");
let nowPage = 1;

for(let i = 1; i <= shopLists.length; i++){
    if(nowPage === i){
        document.querySelector("#page" + i).style.visibility = "visible";
    }else{
        document.querySelector("#page" + i).style.visibility = "hidden";
    }
    let btn = document.createElement("button");
    btn.id = "pageBtn" + i;
    btn.innerHTML = i;
    pagingArea.appendChild(btn);
}

pagingArea.addEventListener("click", (e) => {
    console.log("click");
    console.log(e.target.id);
    for(let i = 1; i <= shopLists.length; i++){
        if(e.target.id == "pageBtn" + i){
            document.querySelector("#page" + nowPage).style.visibility = "hidden";
            document.querySelector("#page" + i).style.visibility = "visible";
            nowPage = i;
            break;
        }
    }
})
/* ページングJS　終了*/ 
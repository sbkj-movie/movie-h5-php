/**
 * 生成由大小写字母和数字组成的随机数
 * @param size  位数
 * @returns {string|string}
 */
function randomUserName(size){
    var seed = new Array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','P','Q','R','S','T','U','V','W','X','Y','Z',
        'a','b','c','d','e','f','g','h','i','j','k','m','n','p','Q','r','s','t','u','v','w','x','y','z',
        '2','3','4','5','6','7','8','9'
    );//数组
    seedlength = seed.length;//数组长度
    var createPassword = '';
    for (i=0;i<size;i++) {
        j = Math.floor(Math.random()*seedlength);
        createPassword += seed[j];
    }
    return createPassword;
}

/**
 * 生成由数字组成的密码
 * @param size 位数
 * @returns {string|string}
 */
function randomPassword(size){
    var seed = new Array(		'1','2','3','4','5','6','7','8','9');//数组
    seedlength = seed.length;//数组长度
    var createPassword = '';
    for (i=0;i<size;i++) {
        j = Math.floor(Math.random()*seedlength);
        createPassword += seed[j];
    }
    return createPassword;
}

//获取当前网站是否有登录的用户
function getUserInfoData(){
    var username = localStorage.getItem("userName");
    var zcid = localStorage.getItem("zcid");
    var hyid = localStorage.getItem("hyid");

    if(username == "" || username == null){
        if(zcid == null && hyid == null){
            var userName = randomUserName(8);//系统生成的用户名
            var password = 123456;//初始密码
            axget('/register', { name: userName,password:password,pid:localStorage.getItem("pid")}).then((res) => {
                if (res.code == "0") {
                    localStorage.setItem("zcid", res.data.uid);//用户id
                    localStorage.setItem("hyid", res.data.uid);//用户id
                    localStorage.setItem("token", res.data.token);//用户token
                    localStorage.setItem("userName", userName);//自动生成的用户名
                    localStorage.setItem("password", password);//自动生成的密码
                    localStorage.setItem("randomAccount","true");
                } else {
                    btn.disabled = false;
                    btn.innerText = "立即注册";
                    mui.toast(res.msg);
                }
            })
        }

    }else{
        return true;
    }
}

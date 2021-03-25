function canShowSite()
{
    const url = "../api.php?operation=canShowFa";
    const request = new Request(url, {
        method:'POST'
    });

    fetch(request)
        .then(request => request.json())
        .then(data =>
        {
            if(!data)
            {
                toLoginPage();
            }
        });


}




function verifyCodeAndLogin()
{
    if(checkInput())
    {
        const url = "../api.php?operation=verifyCustomLogin";
        const request = new Request(url, {
            method:'POST',
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
            },
            body: getCode(),
        });

        fetch(request)
            .then(request => request.json())
            .then(data =>
            {
                console.log(data);
                doVerifyCodeAndLogin(data);
            });
    }
}

function doVerifyCodeAndLogin(data)
{
    if(!data.isLoginUser)
    {
        window.alert("Užívateľ nebol prihlaseny (neboli zadané prihlasovacie údaje)")
    }
    else if(data.isLoginUser && data.verifyCode)
    {
        window.location.replace("../html/home.html");
    }
    else if(data.isLoginUser && !data.verifyCode)
    {
        window.alert("Neplatný kod");
    }
}

function checkInput()
{
    let code = document.getElementById("code").value;

    if(code.length <= 0)
    {
        window.alert("Nebol zadaný kod");
        return false;
    }
    return true;
}

function getCode()
{
    let code = document.getElementById("code").value;

    return JSON.stringify({code:code});
}

function toLoginPage()
{
    window.location.replace("../index.html");
}
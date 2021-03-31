function customLogin()
{
    if(checkCustomInput())
    {
        const url = "api.php?operation=customLogin";//../api.php?operation=customLogin
        const request = new Request(url, {
            method:'POST',
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
            },
            body: getCustomLoginData(),
        });

        fetch(request)
            .then(request => request.json())
            .then(data =>
            {
                console.log(data);
                doCustomLogin(data);

            });
    }
}

function doCustomLogin(data)
{
    if(data.isUserLogin)
    {
        window.location.replace("html/fa.html");
    }
    else
    {
        window.alert("Nepodarilo sa prihlasiť. Skontrolujte prosím svoje údaje");
    }
}

function checkFilledInputs(login,password)
{
    let loginInput = document.getElementById(login).value.trim();
    let passwordInput = document.getElementById(password).value.trim();

    if(loginInput.length <= 0)
        return false;
    if(passwordInput.length <= 0)
        return false;

    return true;
}

function getCustomLoginData()
{
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;

    return JSON.stringify({email:email,password:password});
}

function checkCustomInput()
{
    let correct = checkFilledInputs("email","password");

    if(!correct)
    {
        window.alert("Neboli zadane kompletné prihlasovacie údaje");
    }

    return correct;
}







function ldapLogin()
{
    if(checkLdapInput())
    {
        const url = "apiLdap.php?operation=loginLdap";//../api.php?operation=customLogin
        const request = new Request(url, {
            method:'POST',
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
            },
            body: getLdapLoginData(),
        });

        fetch(request)
            .then(request => request.json())
            .then(data =>
            {
                console.log(data);
                doLdapLogin(data);

            });
    }
}

function doLdapLogin(data)
{
    if(data.isUserLogin)
    {
        window.location.replace("html/home.html");
    }
    else
    {
        window.alert(data.error);
    }
}





function checkLdapInput()
{
    let correct = checkFilledInputs("ldapLogin","ldapPassword");

    if(!correct)
    {
        window.alert("Neboli zadane kompletné prihlasovacie údaje");
    }

    return correct;
}

function getLdapLoginData()
{
    let login = document.getElementById("ldapLogin").value;
    let password = document.getElementById("ldapPassword").value;

    return JSON.stringify({login:login,password:password});
}




function googleLogin()
{
    const url = "apiGoogle.php";//../api.php?operation=customLogin
    const request = new Request(url, {
        method:'POST',
        headers: {
            Accept: "application/json",
            "Content-Type": "application/json",
        },
        body: getLdapLoginData(),
    });

    fetch(request)
        .then(request => request.json())
        .then(data =>
        {
            window.location.href = data;
        });
}


function getLoginUser()
{
    const url = "api.php?operation=getLoginUser";
    const request = new Request(url, {
        method:'POST'
    });

    fetch(request)
        .then(request => request.json())
        .then(data =>
        {
            if(data.isUserLogin)
                toHomePage();
        });
}

function toLoginPage()
{
    window.location.replace("index.html");
}

function toHomePage()
{
    window.location.replace("html/home.html");
}


function createAccount()
{
    // window.location.replace();
    window.location.href = "html/formular.html";
}

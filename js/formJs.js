function getQRCode()
{
    const url = "../api.php?operation=getQRCode";
    const request = new Request(url, {
        method:'POST'
    });

    fetch(request)
        .then(request => request.json())
        .then(data =>
        {
            showQRCodeImage(data.qrCodeUrl,data.secretId);
        });
}


function registerNewUser()
{

    if(isCorrectFormular())
    {
        const url = "../api.php?operation=customRegistration";
        const request = new Request(url, {
            method:'POST',
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
            },
            body: getLoginData(),
        });

        fetch(request)
            .then(request => request.json())
            .then(data =>
            {
                console.log(data);
                doAfterRegistration(data);
            });
    }

}


function doAfterRegistration(registrationData)
{
    if(!registrationData.verifyCode)
        window.alert(registrationData.error);
    else if(!registrationData.successRegistration)
        window.alert(registrationData.error);

    if(registrationData.verifyCode && registrationData.successRegistration)
    {
        window.location.replace("../html/home.html");
    }
}




function isCorrectFormular()
{
    let correct = true;
    if(!isFilledInputs())
    {
        window.alert("Neboli vyplnené všetky polia");
        correct = false;
    }

    if(!isMatchesPassword())
    {
        window.alert("Hesla nie sú rovnaké");
        correct = false;
    }
    return correct;

}


function isMatchesPassword()
{
    let password = document.getElementById("password").value.trim();
    let checkPassword = document.getElementById("checkPassword").value.trim();

    return password === checkPassword;
}

function isFilledInputs()
{
    let name = document.getElementById("name").value.trim();
    if(name.length <= 0)
        return false;
    let surname = document.getElementById("surname").value.trim();
    if(surname.length <= 0)
        return false;
    let email = document.getElementById("email").value.trim();
    if(email.length <= 0)
        return false;
    let password = document.getElementById("password").value.trim();
    if(password.length <= 0)
        return false;
    let checkPassword = document.getElementById("checkPassword").value.trim();
    if(checkPassword.length <= 0)
        return false;
    let code = document.getElementById("code").value.trim();
    if(code.length <= 0)
        return false;

    return true;
}


function getLoginData()
{
    let name = document.getElementById("name").value.trim();
    let surname = document.getElementById("surname").value.trim();
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value.trim();
    let checkPassword = document.getElementById("checkPassword").value.trim();
    let code = document.getElementById("code").value.trim();
    let secretId = document.getElementById("secretId").value;

    return JSON.stringify({name:name,surname:surname,email:email,password:password, checkPassword:checkPassword,
        code:code, secretId:secretId});
}

function showQRCodeImage(url,secretId)
{
    document.getElementById("qrcode").src = url;
    document.getElementById("secretId").value = secretId;
}
function customLogin()
{
    if(checkInput())
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

function checkInput()
{
    let correct = checkFilledInputs("email","password");

    if(!correct)
    {
        window.alert("Neboli zadane kompletné prihlasovacie údaje");
    }

    return correct;
}

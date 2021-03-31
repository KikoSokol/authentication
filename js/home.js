function getLoginUser()
{
    const url = "../api.php?operation=getLoginUser";
    const request = new Request(url, {
        method:'POST'
    });

    fetch(request)
        .then(request => request.json())
        .then(data =>
        {
            if(!data.isUserLogin)
                toLoginPage();
            else
                setInformationAboutUser(data.user);
        });
}


function setInformationAboutUser(user)
{
    document.getElementById("name").innerText = user.name;
    document.getElementById("surname").innerText = user.surname;
    document.getElementById("email").innerText = user.email;
}

function toLoginPage()
{
    window.location.replace("../index.html");
}

function logout()
{
    const url = "../api.php?operation=logout";
    const request = new Request(url, {
        method:'POST'
    });

    fetch(request)
        .then(request => request.json())
        .then(data =>
        {
            if(data)
                window.location.replace("../index.html");
            else
                window.alert("Nepodarilo sa vas odhlasiť");
        });
}

function showStats()
{
    window.location.href = "../html/stats.html";
}
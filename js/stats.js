function getStats()
{
    const url = "../api.php?operation=userStats";
    const request = new Request(url, {
        method:'POST'
    });

    fetch(request)
        .then(request => request.json())
        .then(data =>
        {
            console.log(data);
            if(data.isLoginUser)
            {
                fillSite(data);
            }
            else
            {
                toLoginPage();
            }
        });
}

function fillSite(data)
{
    setInformationAboutUser(data.user);
    fillStatsTable(data.userAllAccess);
    fillCountLoginTable(data.countOfLogin);

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

function fillStatsTable(stats)
{
    let table = document.getElementById("bodyStats");
    table.innerHTML = "";
    stats.forEach(d =>{
        table.append(getRowForTableStats(d));
    })
}

function getRowForTableStats(data)
{
    let tr = document.createElement("tr");
    tr.append(createColumn(data.type));
    tr.append(createColumn(data.timestamp));

    return tr;


}

function createColumn(data)
{
    let td = document.createElement("td");
    td.innerText = data;

    return td;
}

function fillCountLoginTable(data)
{
    let table = document.getElementById("bodyCountLogin");
    table.innerHTML = "";

    let tr = document.createElement("tr");

    let custom = document.createElement("td");
    custom.innerText = data.customLogin;

    let ldap = document.createElement("td");
    ldap.innerText = data.ldapLogin;

    let google = document.createElement("td");
    google.innerText = data.googleLogin

    tr.append(custom);
    tr.append(ldap);
    tr.append(google);

    table.append(tr);

}

function showHome()
{
    window.location.href = "../html/home.html";
}
$(document).ready(function () {

    if (isConnect()) {

        $.ajax({
            type: "POST",
            url: "api/users/check",
            headers: { "jwt": localStorage.getItem('jwt') }
        })
            .done(function (data, text, jqxhr) {

                if (data.status === "OK") {
                    sendAlert("Vous êtes connecté.")
                    showPage('home', 'H o m e  . . . ');

                } else {
                    showLoginPage("Oups, vous avez été déconnecté.")

                }
            })
            .fail(function (jqxhr) {
                showLoginPage("Oups, vous avez été déconnecté.")

            });
    } else {
        //Charge les includeHTML  (notament)
        showLoginPage();
    }

    // when a 'nav Item' was clicked
    $(document).on('click', '.nav-link', function (e) {
        e.preventDefault();

        switch (e.currentTarget.id) {
            case "NavLogin":        // show login screen
                showPage('login', 'Merci de vous identifier');
                break;

            case "NavDashboard":    // show dashboard screen
                showPage('dashboard', 'Mon Tableau de bord . . . ');
                break;

            case "NavHome":        // show Home screen
                showPage('home', 'H o m e . . . ');
                break;


            default:
        }
    });


    // when a 'Report Item' was clicked
    $(document).on('click', '.graphItem', function (e) {

        id = $(e.target).attr("data-id");

        showReport('graphs', 'Mes Graphiques . . . ', id);

    });

    // when a 'Report Item' was clicked
    $(document).on('click', '.reportItem', function (e) {

        id = $(e.target).attr("data-id");

        showReport('reports', 'Mon Tableau de bord . . . ', id);

    });

    // when a 'nav Item' was clicked
    $(document).on('click', '.dropdown-item', function (e) {

        switch (e.currentTarget.id) {
            case "NavLogout": //Logout
                showLoginPage('login', 'Merci de vous identifier');
                break;

            case "NavUserProfile":
                break;

            case "NavUser":        // show Home screen
                showPage('users', 'Utilisateurs . . . ');
                break;

            case "NavRegister":     // show register screen
                showPage('register', 'Vous pouvez vous enregistrer . . . ');
                break;

            case "NavAssociate":        // show Home screen
                showPage('associates', 'Associés . . . ');
                break;

            default:
        }
    });
});

/**
 * Affichage de la page de login 
 * 
 * @param void 
 */
function showLoginPage(msg = null) {

    localStorage['isConnect'] = false;
    localStorage['isAdmin'] = false;
    localStorage['jwt'] = '';

    if (msg) sendAlert(msg);

    showPage('login', 'Merci de vous identifier');
}

/**
 * Affichage de la page principale. 
 * 
 * @param {any} name 
 * @param {any} title
 * @param {any} id
 */
function showReport(name, title, id) {

    // inject to 'page-content' of our app
    if (name == "reports") {
        url = "app/views/reports/reports.html";
    } else if (name == "graphs") {
        url = "app/views/graphs/graphs.html";
    }

    $.get(url, function (file) {

        $("#page-content").html(file);
        //Call Init Procedure named onload<name>Screan() We need a different name for each
        var codeToExecute = 'onload' + jsUcfirst(name) + 'Screen(a)';
        var initPageFunc = new Function('a', codeToExecute);
        initPageFunc(id);

    });

    showNavBar(name, title);

    changePageTitle(title);
}

/**
 * Affichage de la page principale. 
 * 
 * @param {any} name 
 * @param {any} title 
 */
function showPage(name, title) {

    // inject to 'page-content' of our app
    $.get('app/views/' + name + '/' + name + '.html', function (file) {

        $("#page-content").html(file);
        //Call Init Procedure named onload<name>Screan() We need a different name for each
        var codeToExecute = 'onload' + jsUcfirst(name) + 'Screen()';
        var initPageFunc = new Function(codeToExecute);
        initPageFunc();

    });

    showNavBar(name, title);

    changePageTitle(title);
}

/**
 * Affichage de la page principale. 
 * 
 * @param {any} name 
 * @param {any} title 
 */
function showNavBar(name, title) {

    //Charge les includeHTML  (notament)
    if (isConnect()) {

        $("#nav").load('app/views/navbar/homeNavbar.html',
            function (responseTxt, statusTxt, xhr) {
                // show login screen
                if (statusTxt == "success") {
                    //remove Menu selection
                    $('li').removeClass('active');
                    //Set user connect in nav bar
                    $('#ConnectUser').text(jsUcfirst(localStorage['UserName']));

                    //if Admin, add elements to menu
                    if (!isAdmin()) {
                        $("#nav .forAdmin").remove();
                    }
                    $('#Nav' + jsUcfirst(name)).parent('li').addClass('active');
                }
            });
    } else {
        $("#nav").load('app/views/navbar/startupNavbar.html',
            function (responseTxt, statusTxt, xhr) {
                // show login screen
                if (statusTxt == "success") {
                    $('li').removeClass('active');
                    $('#Nav' + jsUcfirst(name)).parent('li').addClass('active');
                }
            });
    }

}

// function to make form values to json format
$.fn.serializeObject = function () {
    var o = {};
    var disabled = this.find(':input:disabled').removeAttr('disabled');
    var a = this.serializeArray();
    disabled.attr('disabled', 'disabled');
    $.each(a, function () {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

$.fn.removeClassRegex = function (regex) {
    return $(this).removeClass(function (index, classes) {
        return classes.split(/\s+/).filter(function (c) {
            return regex.test(c);
        }).join(' ');
    });
};

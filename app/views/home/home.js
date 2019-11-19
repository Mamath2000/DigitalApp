$(document).ready(function () {

});

// Call by app.js when page is charge.
function onloadHomeScreen() {
    initGraphique();

    refreshChart("myChart", 1, localStorage.getItem('UserIdAssociates'), new Date().getFullYear());
};


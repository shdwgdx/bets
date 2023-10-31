let openMenu = false;
function burger_menu() {
    const btn_burger = document.getElementById('btn_burger');
    const menu_body = document.querySelector('.wrapper_for_mob_menu');

    btn_burger.addEventListener('click', () => {
        btn_burger.classList.toggle('active');
        menu_body.classList.toggle('active');
        openMenu = !openMenu;
        if (openMenu) {
            document.documentElement.style.overflow = "hidden";
        } else {
            document.documentElement.style.overflow = "auto";
        }
    })

}
burger_menu();
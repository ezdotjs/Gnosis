'use strict';

import { _, toggleClass, docReady } from './modules/utilities';

class Gnosis
{
    constructor()
    {
        this.menu        = {};
        this.menu.el     = _(document.body, '.nav')[0];
        this.menu.button = _(document.body, '.content__menu-button')[0];
        this.flash       = _(document.body, '.flash__close');
        this.addListeners();
    }

    addListeners()
    {
        !this.menu.el || this.menu.button.addEventListener('click', this.toggleMenu.bind(this));

        [...this.flash].forEach((el) => {
            console.log(el);
            el.addEventListener('click', this.removeFlash, true);
        });
    }

    toggleMenu(e)
    {
        e.preventDefault();
        toggleClass(this.menu.el, 'nav--open');
    }

    removeFlash(e)
    {
        e.preventDefault();
        let parent = this.parentNode;
        parent.parentNode.removeChild(parent);
    }
}

docReady(function() {
    const app = new Gnosis;
});

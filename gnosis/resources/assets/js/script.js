'use strict';

import { _, toggleClass, docReady } from './modules/utilities';

class Gnosis
{
    constructor()
    {
        this.menu        = {};
        this.menu.el     = _(document.body, '.nav')[0];
        this.menu.button = _(document.body, '.content__menu-button')[0];

        this.addListeners();
    }

    addListeners()
    {
        this.menu.button.addEventListener('click', this.toggleMenu.bind(this));
    }

    toggleMenu(e)
    {
        e.preventDefault();
        console.log('click');
        toggleClass(this.menu.el, 'nav--open');
    }
}

docReady(function() {
    console.log('ready');
    const app = new Gnosis;
});

'use strict';

import { _, toggleClass, docReady } from './modules/utilities';

class Gnosis
{
    constructor()
    {
        this.menu        = {};
        this.menu.el     = _('.nav')[0];
        this.menu.button = _('.content__menu-button')[0];
        this.flash       = _('.flash__close');
        this.confirms    = _('form.confirm');
        this.addListeners();
    }

    addListeners()
    {
        !this.menu.el || this.menu.button.addEventListener('click', this.toggleMenu.bind(this));

        [...this.flash].forEach((el) => {
            el.addEventListener('click', this.removeFlash, true);
        });

        [...this.confirms].forEach((el) => {
            el.addEventListener('click', this.confirmForm, true);
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

    confirmForm(e)
    {
        e.preventDefault();
        if (confirm('Do you wish to proceed?')) {
            this.submit();
        }
    }
}

docReady(function() {
    const app = new Gnosis;
});

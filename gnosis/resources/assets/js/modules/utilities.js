/**
 * @module utilities
 */


 /**
  * Runs a callback function after the DOM has finished loading
  * @param {genericCallback} [callback] - contains code to be executed after DOM has finished loading
  */
 export function docReady(callback)
 {
    if(document.readyState !== 'loading') {
        callback();
    } else {
        document.addEventListener('DOMContentLoaded', callback);
    }
 }

/**
 * A useful alias for querySelectorAll
 * @param {(string|Element)} target - The selector or target element
 * @param {string} [selector] - The selector to query against the target element
 */
export function _(target, selector)
{
    if (typeof target === 'object') {
        return target.querySelectorAll(selector);
    }
    return document.querySelectorAll(target);
}

/**
 * Toggles a class on an element
 * @param {string} [className] - The class to toggle
 */
export function toggleClass(el, className)
{
    if (el.classList) {
        el.classList.toggle(className);
    } else {
        let classes       = el.className.split(' ');
        let existingIndex = classes.indexOf(className);
        if (existingIndex >= 0) {
            classes.splice(existingIndex, 1);
        } else {
            classes.push(className);
        }
        el.className = classes.join(' ');
    }
}

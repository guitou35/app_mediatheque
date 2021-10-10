import {Flipper, spring} from "flip-toolkit";


/**
 * @property {HTMLElement} pagination
 * @property {HTMLElement} content
 * @property {HTMLFormElement} form
 * @property {number} page
 * @property {boolean} moreNav
 */
export default class Filter {

    /**
     *
     * @param {HTMLElement|null} element
     */
    constructor(element) {
        if (element === null) {
            return;
        }
        this.pagination = document.querySelector('.js-filter-pagination');
        this.content = document.querySelector('.js-filter-content');
        this.form = document.querySelector('.js-filter-form');
        this.page = parseInt(new URLSearchParams(window.location.search).get('page') || 1)
        this.moreNav = this.page === 1
        this.bindEvents()
    }

    /**
     * Ajouter les comportements au différents éléments
     */
    bindEvents() {
        const aClickListener = e => {
            if (e.target.tagName === 'A') {
                e.preventDefault();
                this.loadUrl(e.target.getAttribute('href'))
            }
        }
        if (this.moreNav) {
            this.pagination.innerHTML = '<button class="rounded-xl bg-theme-blue-clair text-white mb-2 mx-auto hover:text-white hover:bg-purple-900 p-2">Voir plus </button>'
            this.pagination.querySelector('button').addEventListener('click', this.loadMore.bind(this))
        } else {
            this.pagination.addEventListener('click', aClickListener)
        }
        this.form.querySelectorAll('input[type=checkbox]').forEach(input => {
            input.addEventListener('change', this.loadForm.bind(this))
        })

    }

    async loadMore() {
        const button = this.pagination.querySelector('button')
        button.setAttribute('disabled', 'disabled')
        this.page++
        const url = new URL(window.location.href)
        const params = new URLSearchParams(url.search)
        params.set('page', this.page)
        await this.loadUrl(url.pathname + '?' + params.toString())
        button.removeAttribute('disabled')
    }

    async loadForm() {
        const data = new FormData(this.form);
        const url = new URL(this.form.getAttribute('action') || window.location.href)
        const params = new URLSearchParams()

        data.forEach((value, key) => {
            params.append(key, value)
        })
      //  console.log(params.toString())
        return this.loadUrl(url.pathname + '?' + params.toString(), true)
    }

    async loadUrl(url, append = false) {
        const params = new URLSearchParams(url.split('?')[1] || '')
        params.set('ajax', 1)

        const response = await fetch(url.split('?')[0] + '?' + params.toString(), {
            headers: {
                "X-Requested-With": 'XMLHttpRequest'
            }
        })

        if (response.status >= 200 && response.status < 300) {
            const data = await response.json();
            this.flipContent(data.content, append)
            if (!this.moreNav ) {
                this.pagination.innerHTML = data.pagination
            }else if (this.page === data.pages){
                this.pagination.style.display = 'none';
            }else{
                this.pagination.style.display = null;
            }
            params.delete('ajax')

            history.replaceState({}, '', url.split('?')[0] + '?' + params.toString());
        } else {
            console.log(response)
        }
    }

    /**
     * Remplace les éléments de la grille avec effets alimentation
     * @param {string} content
     * @param {boolean} append
     */
    flipContent(content, append) {
        const exitSpring = function (element, index, onComplete) {
            spring({
                config: "wobbly",
                values: {
                    translateY: [-15, 0],
                    opacity: [1, 0]
                },
                onUpdate: ({translateY, opacity}) => {
                    element.style.opacity = opacity;
                    element.style.transform = `translateY(${translateY}px)`;
                },
                onComplete
            })
        }
        const flipper = new Flipper({
            element: this.content
        })


        flipper.recordBeforeUpdate()
        if (!append) {
            this.content.innerHTML += content;
        } else {
            this.content.innerHTML = content;
        }

        flipper.update()
    }
}


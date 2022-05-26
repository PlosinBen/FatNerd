require('./bootstrap');

import {createApp, h} from 'vue';
import {createInertiaApp, Link} from '@inertiajs/inertia-vue3';
import {InertiaProgress} from '@inertiajs/progress';
import {vfmPlugin} from 'vue-final-modal'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}`),
    setup({el, app, props, plugin}) {
        return createApp({render: () => h(app, props)})
            .use(plugin)
            .use(vfmPlugin)
            .component('InertiaLink', Link)
            // .mixin({ methods: { route } })
            .mount(el);
    },
});

InertiaProgress.init({color: '#4B5563'});

window.moneyFormatter = () => new Intl.NumberFormat('zh-TW', {
    trailingZeroDisplay: 'lessPrecision'
})

window.moneyFormat = (money) => {
    window.formatter = window.formatter || new Intl.NumberFormat('zh-TW', {
        trailingZeroDisplay: 'lessPrecision'
    })

    return window.formatter.format(money)
}

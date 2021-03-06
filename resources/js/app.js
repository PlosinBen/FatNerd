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

InertiaProgress.init({color: '#4B5563'})

window.profitClass = (amount, type) => {
    if (typeof type === "string" && type !== 'profit') {
        return ''
    }
    if (amount == 0) {
        return ''
    }

    return 'font-bold ' + (amount > 0 ? 'text-red-600' : 'text-green-600')
}

window.moneyFormatter = () => new Intl.NumberFormat('zh-TW', {
    trailingZeroDisplay: 'lessPrecision'
})

window.moneyFormat = (money) => {
    window.formatter = window.formatter || new Intl.NumberFormat('zh-TW', {
        trailingZeroDisplay: 'lessPrecision'
    })

    return window.formatter.format(money)
}

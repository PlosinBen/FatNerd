<template>
    <div class="flex flex-col h-screen">
        <section class="bg-white border-b shadow select-none">
            <Header :user="user">
                <InertiaLink href="/" class="text-4xl text-white font-bold">
                    <h1>
                        <img class="h-16" src="/lovelion.png" alt="LoveLion">
                    </h1>
                </InertiaLink>
                <div class="flex-grow flex justify-between items-center">
                    <nav class="ml-5 space-x-4 text-lg text-gray-400">
                        <InertiaLink v-if="false" href="/invest/history" class="hover:text-gray-800">歷史權益</InertiaLink>
                        <InertiaLink v-if="this.$page.props.isAdmin" href="/invest/futures" class="hover:text-gray-800">
                            對帳單
                        </InertiaLink>
                        <InertiaLink v-if="false" href="#" class="hover:text-gray-800">記帳</InertiaLink>

                        <a class="hover:text-gray-800" href="/about/privacy" target="_blank" rel="noopener noreferrer">隱私權政策</a>
                    </nav>

                    <div class="relative h-10">
                        <button class="w-10 overflow-hidden border border-gray-400 rounded-full">
                            <img :src="user.avatar" class="w-10 h-10" @click="showUserMenu = !showUserMenu" alt="Avatar">
                        </button>
                        <ul v-if="showUserMenu" class="sm:absolute sm:right-0 bg-white space-y border shadow rounded">
                            <li v-if="0">
                                <a class="inline-block w-full px-4 py-1.5 hover:bg-blue-50" href="#">
                                    Profile
                                </a>
                            </li>
                            <li v-if="0">
                                <a class="inline-block w-full px-4 py-1.5 hover:bg-blue-50" href="#">
                                    Setting
                                </a>
                            </li>
                            <li>
                                <a class="inline-block w-full px-4 py-1.5 hover:bg-blue-50" href="/logout">
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </Header>
        </section>
        <section class="flex-grow container mx-auto flex flex-col px-4 py-3">
            <Breadcrumbs class="hidden"></Breadcrumbs>
            <div class="py-3 flex items-center">
                <h2 class="font-bold text-3xl text-coolGray-600 px-4">
                    {{ title }}
                </h2>
                <div v-if="subTitle" class="text-2xl px-4 border-l-2">
                    {{ subTitle }}
                </div>
            </div>
            <div class="flex-grow bg-white px-4 py-4 border rounded">
                <slot></slot>
            </div>
        </section>
        <Footer/>
    </div>
</template>

<script>
import Breadcrumbs from "@/Components/LayoutPartial/Breadcrumbs"
import Header from "@/Components/LayoutPartial/Header";
import Footer from "@/Components/LayoutPartial/Footer"

export default {
    name: "Basic",
    components: {
        Breadcrumbs,
        Header,
        Footer
    },
    data() {
        return {
            title: undefined,
            subTitle: undefined,
            menu: [
                ['投資', '#'],
                ['記帳', '#']
            ],
            showUserMenu: false
        }
    },
    methods: {
        loadProps() {
            this.title = this.$page.props._title
        }
    },
    beforeMount() {
        this.user = this.$page.props.user
        this.isAdmin = this.$page.props.isAdmin

        this.loadProps()
    },
    beforeUpdate() {
        this.loadProps()
    }
}
</script>

<style scoped>

</style>

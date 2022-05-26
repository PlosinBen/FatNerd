<template>
    <div class="flex flex-col h-screen">
        <section class="bg-white border-b shadow select-none">
            <Header class="justify-between">
                <div class="flex-grow flex justify-between items-center">
                    <nav class="ml-5 space-x-4 text-lg text-gray-400">
                        <InertiaLink v-if="false" href="/invest/history" class="hover:text-gray-800">投資</InertiaLink>
                        <InertiaLink v-if="this.$page.props.isAdmin" href="/invest/futures" class="hover:text-gray-800">對帳單</InertiaLink>
                        <InertiaLink v-if="false" href="#" class="hover:text-gray-800">記帳</InertiaLink>
                    </nav>

                    <div v-if="false" class="relative h-10">
                        <button class="w-10 overflow-hidden border border-gray-400 rounded-full">
                            <img src="https://graph.facebook.com/1918911484817617/picture?width=150&height=150" alt="Avatar">
                        </button>
                        <ul v-if="false" class="absolute right-0 bg-white space-y border shadow rounded">
                            <li>
                                <a class="inline-block w-full px-3 py-1 hover:bg-blue-50" href="#">
                                    Profile
                                </a>
                            </li>
                            <li>
                                <a class="inline-block w-full px-3 py-1 hover:bg-blue-50" href="#">
                                    Setting
                                </a>
                            </li>
                            <li>
                                <a class="inline-block w-full px-3 py-1 hover:bg-blue-50" href="#">
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
        <Footer v-if="false"/>
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
        console.log(
            this.$page.props,
        )

        return {
            title: undefined,
            subTitle: undefined,
            menu: [
                ['投資', '#'],
                ['記帳', '#']
            ]
        }
    },
    methods: {
        loadProps() {
            this.title = this.$page.props._title
        }
    },
    beforeMount() {
        this.loadProps()
    }
}
</script>

<style scoped>

</style>

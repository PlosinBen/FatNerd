<template>
    <div class="flex flex-col h-screen">
        <section class="bg-white border-b shadow select-none">
            <header class="container mx-auto flex px-4 justify-between items-center">
                <InertiaLink href="/" class="text-4xl text-white font-bold">
                    <h1>
                        <img class="h-16" src="https://www.lovelion.me/logo.png" alt="LoveLion">
                    </h1>
                </InertiaLink>

                <div class="flex-grow flex justify-between items-center">
                    <nav class="ml-5 space-x-4 text-lg text-gray-400">
                        <InertiaLink href="/invest" class="hover:text-gray-800">投資</InertiaLink>
                        <InertiaLink href="/invest/futures" class="hover:text-gray-800">對帳單</InertiaLink>
                        <InertiaLink href="#" class="hover:text-gray-800">記帳</InertiaLink>
                    </nav>

                    <div class="relative h-10">
                        <button class="w-10 overflow-hidden border border-gray-400 rounded-full">
                            <img  src="https://graph.facebook.com/1918911484817617/picture?width=150&height=150" alt="Avatar">
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
            </header>
        </section>
        <section class="flex-grow container mx-auto flex flex-col px-4 py-3 overflow-y-auto">
            <Breadcrumbs></Breadcrumbs>
            <div class="py-3 flex items-center">
                <h2 class="font-bold text-3xl text-coolGray-600 px-4 border-r-2">
                    Page Title
                </h2>
                <div class="text-2xl px-4">
                    SubTitle
                </div>
            </div>
            <div class="flex-grow bg-white px-4 py-4 border rounded overflow-x-auto">
                <slot></slot>
            </div>
        </section>
        <footer class="bg-coolGray-300 border-t h-10">

        </footer>
    </div>
</template>

<script>
import Breadcrumbs from "@/Components/LayoutPartial/Breadcrumbs";

export default {
    name: "Basic",
    components: {
        Breadcrumbs
    },
    data() {
        return {
            menu: [
                ['投資', '#'],
                ['記帳', '#']
            ]
        }
    }
}
</script>

<style scoped>

</style>

<template>
    <header class="container mx-auto flex px-4 items-center">
        <slot></slot>
        <InertiaLink v-if="0" href="/" class="text-4xl text-white font-bold">
            <h1>
                <img class="h-16" src="/lovelion.png" alt="LoveLion">
            </h1>
        </InertiaLink>
        <div v-if="0" class="mr-2 flex-grow text-right sm:hidden">
            <button>
                <i class="fas fa-lg fa-bars"></i>
            </button>
        </div>
        <div v-if="0" class="fixed left-0 top-0 z-40 w-screen h-screen flex">
            <div class="flex-grow bg-gray-600 opacity-70"></div>
            <div class="max-w-sm w-full bg-white px-4 py-3">
                <button class="absolute px-1.5 right-3">
                    <i class="fas fa-times"></i>
                </button>
                <div v-if="user" class="flex">
                    <button class="w-10 overflow-hidden border border-gray-400 rounded-full">
                        <img :src="user.avatar" class="w-10 h-10" @click="showUserMenu = !showUserMenu" alt="Avatar">
                    </button>
                    <div class="px-2 flex flex-row items-center">
                        {{ user.name }}
                    </div>
                </div>
                <ul class="sm:absolute sm:right-0 bg-white space-y border rounded">
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
    </header>
</template>

<script>
export default {
    name: "Header",
    props: {
        user: Object
    },
    data() {
        return {
            showUserMenu: false
        }
    }
}
</script>

<style scoped>

</style>

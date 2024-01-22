<template>
    <div  class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 back">
        <div>
            <slot name="logo" />
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <slot />
        </div>
    </div>
</template>
<style>
.back{
    background-size: 100%;
    background-repeat: no-repeat;
    background-image: url('../../assets/img/register_bg_2.png')
}
</style>

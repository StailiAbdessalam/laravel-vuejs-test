<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
</script>

<template>
    <AppLayout title="categories">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Articles admin
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <div class="shadow-lg rounded-lg overflow-hidden  ">
                        <table class="w-full table-fixed">
                            <thead>
                            <tr class="bg-gray-100">
                                <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">id</th>
                                <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">name</th>
                                <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">description</th>
                                <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">categorie</th>
                                <th class="w-1/4 py-4 px-6 text-left text-gray-600 font-bold uppercase">Status</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white">
                            <tr>
                                <td class="py-4 px-6 border-b border-gray-200">1</td>
                                <td class="py-4 px-6 border-b border-gray-200">see this cat</td>
                                <td class="py-4 px-6 border-b border-gray-200 ">
                                    <textarea class="border-1 border-gray-100 text-xs" name="" id="" cols="25" rows="1">see this ca 4 px-6 border-b border-grt</textarea>
                                </td>
                                <td class="py-4 px-6 border-b border-gray-200 truncate">Devlopment</td>
                                <td class="py-4 px-6 border-b border-gray-200">
                                    <span class="bg-green-500 text-white py-1 px-2 rounded-full text-xs">Active</span>
                                </td>
                            </tr>

                            <!-- Add more rows here -->
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>

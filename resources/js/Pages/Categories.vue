<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Modal from "@/Components/Modal.vue";
import {ref, watch} from "vue";
import {usePage} from "@inertiajs/vue3";

const { props, inertia } = usePage();


const categories = ref(props.categories);
const pagination = ref(props.pagination);


const showAddCategorie = ref(false)
const showDeleteCategorie = ref(false)
const CategoryID = ref(0)
const newcategorie = ref([]);

const deleteRecord = async (recordId) => {

    try {
        const response = await axios.delete(`/categories/${recordId}`);
        categories.value = response.data.categories;
        pagination.value=response.data.pagination;
        showDeleteCategorie.value=!showDeleteCategorie.value
    } catch (error) {
        console.error('Error deleting record:', error);
    }
};
const addCategorie = async ()=>{

    if(newcategorie.value.id){
        const response = await editcategorie();
        return response;
    }
    try {
        const response = await axios.post('/create-category',{
            'name': newcategorie.value['name'],
            'status': newcategorie.value['status']
        });

        categories.value = response.data.categories;
        pagination.value=response.data.pagination;


    } catch (error) {
        console.error('Error deleting record:', error);
    }
}

const editcategorie=async ()=>{
    try {
        const response = await axios.post('/create-category',{
            'name': newcategorie.value['name'],
            'status': newcategorie.value['status'],
            'id': newcategorie.value['id']
        });



        // Update the local state (categories) with the new data
        categories.value = response.data.categories;
        pagination.value=response.data.pagination;

    } catch (error) {
        console.error('Error deleting record:', error);
    }
}

</script>
<style>
/* Customize scrollbar styles */
/* Note: These styles may not work in all browsers */

/* Width */
::-webkit-scrollbar {
    width: 12px;
    height: 4px;
}


/* Handle */
::-webkit-scrollbar-thumb {
    background: #68b7df;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
    background: #45aee3;
}
</style>
<template>
    <AppLayout title="categories">
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    categories
                </h2>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    <button @click="()=>{newcategorie= []; showAddCategorie=!showAddCategorie}"
                            class="bg-transparent hover:bg-blue-500 text-gray-400 font-sans hover:text-white py-2 px-4 border border-gray-400 hover:border-transparent rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>

                    </button>
                </h2>
            </div>
        </template>


        <Modal :closeable="!showDeleteCategorie" :show="showDeleteCategorie">
                <div class="w-full h-full flex items-center justify-center">
                    <div
                        class="w-full modal-container bg-gradient-to-r bg-white  mx-auto rounded shadow-lg z-50 overflow-y-auto">
                        <div class="modal-content py-4 text-left px-6">
                            <div class="flex justify-end items-center pb-3">
                                <div @click="showDeleteCategorie=!showDeleteCategorie" class="modal-close cursor-pointer z-50 p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 " fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                            </div>
                            <h2 class="text-center font-bold text-2xl mt-1 text-gray-600 ">Delete media?</h2>
                            <p class=" text-gray-500 font-medium text-center my-6 mx-6 ">
                                Are you sure you want to delete "rainiy_day.jpg"? You can't undo this action.
                            </p>
                            <div class="px-4 flex flex-row py-4 min-w-min border-l-4 border-red-400  bg-red-100  rounded mx-auto">
                                  <span class="w-6 h-6 mr-4 mt-1 text-red-500 ">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                      <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                  </span>
                                    <div>
                                        <h2 class="text-lg font-bold text-red-700">Warning</h2>
                                        <p class="text-sm my-2 text-red-500 font-medium">By deleting this media 8 connected
                                            hotspots will also be deleting.</p>
                                    </div>
                            </div>
                            <div class="flex-row md:flex items-center md:justify-between py-4 text-center mx-auto">
                                <div class="space-y-2 sm:space-x-2 my-4">
                                    <button @click="showDeleteCategorie=!showDeleteCategorie"
                                        class="modal-close px-5 py-2 bg-gray-500 rounded-full text-gray-200 font-semibold hover:bg-gray-800  hover:text-gray-100 focus:outline-none">No,
                                        Keep it.</button>
                                    <button
                                        @click="deleteRecord(CategoryID)"
                                        class="modal-close px-5 py-2 bg-red-500  rounded-full text-gray-200  font-semibold hover:bg-red-600  hover:text-gray-100  focus:outline-none">
                                        Yes, Delete media!
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </Modal>


        <Modal :closeable="!showAddCategorie" :show="showAddCategorie">
            <div class="flex justify-end p-5 hover:cursor-pointer">
                <svg @click="showAddCategorie=!showAddCategorie" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </div>
            <div class="flex justify-center items-center py-4 mt-3">
                <form class="w-full max-w-lg">
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full  px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                   for="grid-first-name">
                                Name Category
                            </label>
                            <input
                                class="appearance-none block w-full bg-gray-200 text-gray-700 border border-red-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                                id="grid-first-name" v-model="newcategorie['name']" type="text" placeholder="Development">
                            <p class="text-red-500 text-xs italic">Please fill out this field.</p>
                        </div>
                    </div>
                    <div class="flex flex-col flex-wrapw-full  mb-6">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
                                   for="grid-state">
                                State
                            </label>
                            <div class="relative">
                                <select
                                    v-model="newcategorie['status']"
                                    class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                    id="grid-state">
                                    <option disabled>State</option>
                                    <option>active</option>
                                    <option>inactive</option>
                                </select>
                            </div>
                    </div>
                    <button @click="()=>{showAddCategorie=!showAddCategorie; addCategorie()}"
                            class="mb-2 mb-24 mt-5 w-full bg-transparent hover:bg-blue-500 text-blue-500 font-sans hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                        {{ !newcategorie['id']  ? 'add categories' :'update categories'  }}
                    </button>
                </form>
            </div>
        </Modal>

        <!-- component -->
        <div class="overflow-x-scroll rounded-lg border border-gray-200 shadow-md m-5">
            <table class="w-full border-collapse bg-white text-left text-sm text-gray-500">
                <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-4 font-medium text-gray-900">id</th>
                    <th scope="col" class="px-6 py-4 font-medium text-gray-900">User Admin</th>
                    <th scope="col" class="px-6 py-4 font-medium text-gray-900">State</th>
                    <th scope="col" class="px-6 py-4 font-medium text-gray-900">Role</th>
                    <th scope="col" class="px-6 py-4 font-medium text-gray-900">count articles</th>
                    <th scope="col" class="px-6 py-4 font-medium text-gray-900">Category Name</th>
                    <th scope="col" class="px-6 py-4 font-medium text-gray-900">Action</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                <tr v-for="category in categories" :key="category.id" class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{category.id}}</td>
                    <th class="flex gap-3 px-6 py-4 font-normal text-gray-900">
                        <div class="relative h-10 w-10">
                            <img
                                class="h-full w-full rounded-full object-cover object-center"
                                :src="category.users.profile_photo_url"
                                alt="profile"
                            />
                            <span
                                class="absolute right-0 bottom-0 h-2 w-2 rounded-full bg-green-400 ring ring-white"></span>
                        </div>
                        <div class="text-sm">
                            <div class="font-medium text-gray-700">{{category.users.name}}</div>
                            <div class="text-gray-400">{{category.users.email}}</div>
                        </div>
                    </th>
                    <td class="px-6 py-4">
                      <span :class="{'text-green-600 bg-green-50':category.status=='active','text-red-600 bg-red-50':category.status=='inactive','inline-flex items-center gap-1 rounded-full  px-2 py-1 text-xs font-semibold ':true}">
                        <span   :class="{'bg-green-600':category.status=='active','bg-red-600':category.status=='inactive','h-1.5 w-1.5 rounded-full ':true}">
                        </span>
                        {{ category.status }}
                      </span>
                    </td>
                    <td class="px-6 py-4">{{category.users.role}}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            {{category.articles.length}}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
<!--                            <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-600">-->
<!--                              Design-->
<!--                            </span>-->
<!--                            <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-2 py-1 text-xs font-semibold text-indigo-600">-->
<!--                              Product-->
<!--                            </span>-->
                            <span class="inline-flex items-center gap-1 rounded-full bg-violet-50 px-2 py-1 text-xs font-semibold text-violet-600">
                             {{category.name}}
                            </span>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-4">
                            <div class="hover:cursor-pointer" @click="() => {showDeleteCategorie = !showDeleteCategorie; CategoryID = category.id}">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="h-6 w-6"
                                    x-tooltip="tooltip"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                                    />
                                </svg>
                            </div>
                            <div class="hover:cursor-pointer" @click="() => { CategoryID = category.id; newcategorie['name']=category.name;newcategorie['status']=category.status; showAddCategorie=!showAddCategorie ; newcategorie['id']=category.id }">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="h-6 w-6"
                                    x-tooltip="tooltip"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"
                                    />
                                </svg>
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
            <div v-if="pagination?.pages.length>2"
                 class="w-[100%] flex items-center justify-start pb-2 lg:px-0 sm:px-6 px-4 sm:pl-4">
                <div class="lg:w-3/5 w-full  flex items-center justify-between border-t border-gray-200">
                    <div v-if="pagination?.pages?.length > 1" class="flex">
                        <p v-for="page in pagination?.pages" :key="page.label"
                           :class="{ 'text-indigo-700 border-t border-transparent border-indigo-400': page.active, 'cursor-pointer text-gray-600 hover:text-indigo-700 border-t border-transparent hover:border-indigo-400 pt-3 mr-4 px-2': true }">
                            <InertiaLink :href="page.url" v-html="page.label" />
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Dropdown from "@/Components/Dropdown.vue";
import Modal from "@/Components/Modal.vue";
import {computed, ref} from "vue";
import {usePage} from "@inertiajs/vue3";
import { DateTime } from 'luxon';
import { Head, Link } from '@inertiajs/vue3';
const { props, inertia } = usePage();

const articles = ref(props.articles);
const pagination = ref(props.pagination);
const categories = ref(props.categories);


const showAddArticles = ref(false)
const showDeleteArticle = ref(false)
const articleid = ref(0);
const selectedCategory = ref('all');
const newArticle = ref({
    name: '',
    description: '',
    category_id:'',
    status:'',
    image: null,
    id:''
});


const addArticle = async () => {
    if (newArticle.value.name && newArticle.value.status && newArticle.value.image && newArticle.value.description && newArticle.value.category_id) {

        try {
            const response = await axios.post('/create-article', newArticle.value, {
                headers: {
                    'Content-Type': 'multipart/form-data', // Set the content type for file upload
                },
            });
            articles.value = response.data.articles;
            pagination.value=response.data.pagination;
            showAddArticles.value=!showAddArticles.value;

            newArticle.value = {
                name: '',
                description: '',
                category_id:'',
                status:'',
                image: null,
            };
        } catch (error) {
            console.error('Error adding article:', error);
        }
    } else {
        console.warn('Incomplete form. Please fill in all fields and select an image.');
    }
};

const updateArticle = async () => {
    if (newArticle.value.name && newArticle.value.status && newArticle.value.image) {

        try {
            const response = await axios.post('/update-article', newArticle.value, {
                headers: {
                    'Content-Type': 'multipart/form-data', // Set the content type for file upload
                },
            });
            articles.value = response.data.articles;
            pagination.value=response.data.pagination;
            showAddArticles.value=!showAddArticles.value;

            newArticle.value = {
                name: '',
                description: '',
                category_id:'',
                status:'',
                image: null,
                id:''
            };
        } catch (error) {
            console.error('Error adding article:', error);
        }
    } else {
        console.warn('Incomplete form. Please fill in all fields and select an image.');
    }
};

const openeditArticles= (article)=> {
    showAddArticles.value=!showAddArticles.value
    newArticle.value = {
        name:article.name ,
        description: article.description,
        category_id:article.category_id,
        status:article.status,
        image: article.image,
        id: article.id,
    };
}
const geturlImage = (valueUrl)=>{
    return valueUrl.startsWith('https://') ?  valueUrl : '/storage/' +valueUrl ;
}
const handleFileChange = (event) => {
    // Get the selected file from the input event
    newArticle.value.image = event.target.files[0];
};

const formatCreatedAt = (created_at)=>{
    return created_at ? DateTime.fromISO(created_at).toRelative() : '';
}

const filteredArticles = computed(()=> {
        if (selectedCategory.value!='all') {
                return articles.value.filter(article => article.category.name === selectedCategory.value);
        }
        return articles.value;
})

const deleteArticle = async (recordId)=>{
    try {
        const response = await axios.delete(`/articles/${recordId}`);


        articles.value = response.data.articles;
        pagination.value=response.data.pagination;
        showDeleteArticle.value=!showDeleteArticle.value
    } catch (error) {
        console.error('Error deleting record:', error);
    }
}



</script>


<template>
    <AppLayout title="Dashboard">




        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl flex justify-center items-center text-gray-800 leading-tight">
                    Articles
                </h2>

                <h2 class="font-semibold w-[30%] text-xl flex justify-center items-center  leading-tight">
                    <label for="underline_select" class="sr-only">Underline select</label>
                    <select  id="underline_select" v-model="selectedCategory" class="w-[300px] block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400  focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                        <option value="all"  disabled>Explore by Category</option>
                        <option value="all"  selected>all</option>
                        <option v-for="categorie in categories" :key="categorie.id" :value="categorie.name">{{categorie.name}}</option>
                    </select>
                </h2>
                <h2 v-if="$page.props.auth.user.role === 'write'" class="font-semibold text-xl text-gray-800 leading-tight">
                    <button  @click="showAddArticles=!showAddArticles"  class="bg-transparent hover:bg-blue-500 text-blue-500 font-sans hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </button>
                </h2>
            </div>
        </template>

        <div class="flex flex-col gap-0 ">
            <div class="pt-12" v-for="article in filteredArticles" :key="article.id">
                <div class="md:max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <!-- component -->
                        <section class="md:py-6 bg-white flex flex-col justify-center">

                            <div class="grid md:grid-cols-2 grid-cols-1 gap-5  md:gap-10 max-w-6xl mx-auto">

                                <div class="md:-mt-64 md:-mb-64 flex justify-start">
                                    <img class="rounded-lg object-cover overflow-hidden"
                                         :src="geturlImage(article.image)" />
                                </div>
                                <div class="md:p-16 p-4  md:max-h-full max-h-[500px] shadow-xl rounded-xl flex flex-col gap-4 overflow-hidden bg-white">
                                    <div class="flex justify-between items-center">
                                        <th class="flex gap-3  font-normal text-gray-900">
                                            <div class="relative h-10 w-10">
                                                <img
                                                    class=" rounded-full object-cover object-center"
                                                    :src="article.users.profile_photo_url"
                                                    alt="profile"
                                                />
                                                <span
                                                    class="absolute right-0 bottom-0 h-2 w-2 rounded-full bg-green-400 ring ring-white"></span>
                                            </div>
                                            <div class="pl-0 text-sm">
                                                <div class="font-medium text-gray-700 flex justify-start">{{article.users.name}}</div>
                                                <div class="text-gray-400 ">{{article.users.email}}</div>
                                                <div class="text-gray-400 flex justify-start">{{formatCreatedAt(article.created_at) }}</div>
                                            </div>
                                        </th>
                                        <Dropdown v-if="$page.props.auth.user.role === 'write'" class="cursor-pointer" align="right" width="60" >
                                            <template #trigger>
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                                </svg>

                                            </template>
                                            <template #content>
                                                <div class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                                    <div  @click="()=>{openeditArticles(article)}"  class="flex justify-around items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                                        </svg>
                                                        edit
                                                    </div>
                                                </div>
                                                <div class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                                    <div @click="()=>{showDeleteArticle=!showDeleteArticle;articleid=article.id}"  class="flex justify-around items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                        </svg>
                                                        delete
                                                    </div>
                                                </div>
                                            </template>

                                        </Dropdown>
                                    </div>
                                    <div  class="space-y-4">
                                        <p class="text-2xl font-bold text-black tracking-tight">
                                            {{article.name}}
                                        </p>
                                        <p  class="text-l text-black">
                                            {{article.description}}
                                        </p>
                                    </div>
                                    <div class="grid grid-cols-1 gap-6 border-t border-b border-gray-200 my-12 py-8">
                                        <div>
                                            <p class="flex justify-start items-center  font-bold text-black gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                                                </svg>
                                                category : {{article.category.name}}
                                            </p>
                                            <p class="text-sm text-black">
                                                people in the Apple supply chain covered by our health and safety standards in 2020
                                            </p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </section>




                    </div>


                </div>

            </div>
            <div  v-if="filteredArticles.length>0 && pagination?.pages.length>2"
                 class="w-[100%] ml-12 py-6 flex items-center justify-start pb-2 lg:px-0 sm:px-6 px-4 sm:pl-4">
                <div class="lg:w-3/5 w-full  flex items-center justify-between border-t border-gray-200">
                    <div v-if="pagination?.pages?.length > 1" class="flex">
                        <p v-for="page in pagination?.pages" :key="page.label"
                           :class="{ 'text-indigo-700 border-t border-transparent border-indigo-400': page.active, 'cursor-pointer text-gray-600 hover:text-indigo-700 border-t border-transparent hover:border-indigo-400 pt-3 mr-4 px-2': true }">
                            <Link :href="page.url" v-html="page.label" ></Link>
                        </p>

                    </div>
                </div>
            </div>

        </div>

        <Modal :closeable="!showDeleteArticle" :show="showDeleteArticle">
            <div class="w-full h-full flex items-center justify-center">
                <div
                    class="w-full modal-container bg-gradient-to-r bg-white  mx-auto rounded shadow-lg z-50 overflow-y-auto">
                    <div class="modal-content py-4 text-left px-6">
                        <div class="flex justify-end items-center pb-3">
                            <div @click="showDeleteArticle=!showDeleteArticle" class="modal-close cursor-pointer z-50 p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 " fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                        </div>
                        <div class="px-4 flex flex-row py-4 min-w-min rounded mx-auto justify-center text-center">
                            <div>
                                <h2 class="text-lg font-bold text-red-700">Delete media?</h2>
                                <p class="text-sm my-2 text-red-500 font-medium">Are you sure you want to delete? You can't undo this action.</p>
                            </div>
                        </div>
                        <div class="flex-row md:flex items-center md:justify-between py-4 text-center mx-auto">
                            <div class="space-y-2 sm:space-x-2 my-4">
                                <button @click="showDeleteArticle=!showDeleteArticle"
                                        class="modal-close px-5 py-2 bg-gray-500 rounded-full text-gray-200 font-semibold hover:bg-gray-800  hover:text-gray-100 focus:outline-none">No,
                                    Keep it.</button>
                                <button
                                    @click="deleteArticle(articleid)"
                                    class="modal-close px-5 py-2 bg-red-500  rounded-full text-gray-200  font-semibold hover:bg-red-600  hover:text-gray-100  focus:outline-none">
                                    Yes, Delete media!
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Modal>



        <Modal :closeable="!showAddArticles" :show="showAddArticles" >
            <div class="flex justify-end p-5 hover:cursor-pointer">
                <svg @click="showAddArticles=!showAddArticles" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </div>
            <div class="flex justify-center items-center py-4 mt-3">
                <div class="w-full max-w-lg">
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                                 title of articles
                            </label>
                            <input v-model="newArticle.name" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-red-500 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" id="grid-first-name" type="text" placeholder="Jane">
                            <p class="text-red-500 text-xs italic">Please fill out this field.</p>
                        </div>
                        <div class="w-full md:w-1/2 px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                                status
                            </label>
                            <select v-model="newArticle.status" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-state">
                                <option>active</option>
                                <option>inactive</option>
                            </select>

                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                                image
                            </label>
                            <div class="flex items-center justify-center w-full">
                                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-58 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50  hover:bg-gray-100">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                                    </div>
                                    <input @change="handleFileChange"  id="dropzone-file" type="file" class="hidden" />
                                </label>
                            </div>

                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                                Description
                            </label>
                            <div class="flex items-center justify-center w-full">
                                <textarea v-model="newArticle.description" id="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 " placeholder="Write your thoughts here..."></textarea>
                            </div>

                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-2">
                        <div class="w-full  px-3 mb-6 md:mb-0">
                            <div class="relative">
                                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                                    choose categorie
                                </label>
                                <select v-model="newArticle.category_id" id="addcategorie" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" >
                                    <option v-for="categorie in categories" :key="categorie.id" :value="categorie.id">{{categorie.name}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button @click="newArticle.id ? updateArticle() : addArticle()"  class="mb-2 mb-14 mt-5 w-full bg-transparent hover:bg-blue-500 text-blue-500 font-sans hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">
                        {{ !newArticle.id  ? 'add article' :'update article'  }}
                    </button>
                </div>
            </div>
        </Modal>

    </AppLayout>
</template>

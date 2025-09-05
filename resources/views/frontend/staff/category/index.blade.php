@extends('frontend.staff.layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb my-0">
        <li class="breadcrumb-item active"><span>{{ __('message.category') }}</span></li>
    </ol>
</nav>
@endsection

@section('content')
<div id="app">
    <div class="container-lg px-4">
        <div class="mb-4">
            <h2>{{ __('message.categories') }}</h2>
        </div>
        <div class="row mb-4">
            <div class="col-3" v-for="category in categories.data" :key="category.id">
                <div class="card mb-4 w-auto h-auto">
                    <img :src="category.image_url" class="card-img-top object-cover w-full h-36" alt="...">

                    <div class="card-body">
                        <h5 class="card-title" v-text="category.name"></h5>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item" :class="{ disabled: !categories.links.prev}">
                        <button class="page-link" @click="fetchCategories(categories.links.prev)" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </button>
                    </li>
                    <li class="page-item"
                        v-for="page in paginationButtons"
                        :key="page"
                    >
                        <button class="page-link"
                            @click="fetchCategoriesPage(page)"
                            :disabled="page === categories.meta.current_page"
                            :class="{ active: page === categories.meta.current_page }"
                            v-text="page"
                        >
                        </button>
                    </li>
                    <li class="page-item" :class="{ disabled: !categories.links.next}">
                        <button class="page-link" @click="fetchCategories(categories.links.next)" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </button>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('vue.global.js') }}"></script>
<script src="{{ asset('axios.min.js') }}"></script>

<script>
    const { createApp } = Vue;

    createApp({
        data() {
            return {
                categories: {
                    data: [],
                    links: {},
                    meta: {},
                }
            };
        },
        computed: {
            paginationButtons() {
                const buttons = [];
                const totalPages = this.categories.meta.last_page;
                const currentPage = this.categories.meta.current_page;

                for (let i = 1; i <= totalPages; i++) {
                    buttons.push(i);
                }

                return buttons;
            }
        },
        methods: {
            fetchCategories(url = '/get-category-list') {
                axios.get(url)
                .then(response => {
                    console.log(response);

                    this.categories = response.data.data;
                })
                .catch(error => {
                    console.error("There was an error fetching the categories!", error);
                });
            },
            fetchCategoriesPage(page) {
                const url = `/get-category-list?page=${page}`;
                this.fetchCategories(url);
            }
        },
        mounted() {
            this.fetchCategories();
        }
    }).mount('#app');
</script>
@endsection

@extends('frontend.staff.layouts.app')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb my-0">
        <li class="breadcrumb-item active"><span>{{ __('message.product') }}</span></li>
    </ol>
</nav>
@endsection

@section('content')
    <div id="app">
        <div class="container-lg px-4">
            <div class="row mb-4">
                <div class="col-6">
                    <h2>{{ __('message.products') }}</h2>
                </div>
                <div class="col-6">
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="inputGroupSelect01">{{ __('message.categories') }}</label>
                        <select class="form-select form-select-sm" v-model="category_id" @change="filterCategory(category_id)" id="inputGroupSelect01">
                            <option value="">{{ __('message.all_categories') }}...</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-md-8">
                    <div class="row mb-4">
                        <div class="col-sm-6 col-md-4 col-lg-4" v-for="product in products.data" :key="product.id">
                            <div class="card mb-4 w-auto h-auto">
                                <img :src="product.image_url" class="card-img-top object-cover w-full h-36" alt="...">

                                <div class="card-body">
                                    <div class="card-title">
                                        <h6 v-text="product.name"></h6>
                                        <small class="text-muted my-0">{{ __('message.stock_quantity') }}: <span v-text="product.stock_quantity"></span></small>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="m-0 p-0">
                                            <button class="btn btn-primary btn-sm me-1" @click="addToCart(product.id, 'add')">
                                                <i class="fa-solid fa-circle-plus"></i>
                                            </button>
                                            <button class="btn btn-primary btn-sm" @click="addToCart(product.id, 'remove')">
                                                <i class="fa-solid fa-circle-minus"></i>
                                            </button>
                                        </div>
                                        <span v-text="product.price"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                <li class="page-item" :class="{ disabled: !products.links.prev }">
                                    <button class="page-link" @click="fetchProducts(products.links.prev)"
                                        aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </button>
                                </li>
                                <li class="page-item" v-for="page in paginationButtons" :key="page">
                                    <button class="page-link" @click="fetchProductsPage(page)"
                                        :disabled="page === products.meta.current_page"
                                        :class="{ active: page === products.meta.current_page }" v-text="page">
                                    </button>
                                </li>
                                <li class="page-item" :class="{ disabled: !products.links.next }">
                                    <button class="page-link" @click="fetchProducts(products.links.next)" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </button>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4">
                    <div class="card mb-3" style="max-width: 18rem;">
                        <div class="card-header bg-transparent">{{ __('message.order_items') }}</div>
                        <div class="card-body">
                            <ol class="list-group">
                                <li class="list-group-item d-flex justify-content-between align-items-start"
                                    v-for="(add_to_cart_item, key) in add_to_cart_order.order_items" :key="add_to_cart_item.id"
                                >
                                    <span v-text="`${key + 1}.`"></span>
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold" v-text="add_to_cart_item.product_name"></div>
                                        <div class="btn-group btn-group-sm mt-2" role="group" aria-label="Small button group">
                                            <button type="button" class="btn btn-outline-secondary" @click="addToCart(add_to_cart_item.product_id, 'add')">
                                                <i class="fa-solid fa-circle-plus"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary" v-text="add_to_cart_item.quantity"></button>
                                            <button type="button" class="btn btn-outline-secondary" @click="addToCart(add_to_cart_item.product_id, 'remove')">
                                                <i class="fa-solid fa-circle-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <span class="badge text-bg-primary rounded-pill" v-text="add_to_cart_item.price"></span>
                                </li>
                            </ol>
                            <ol class="list-group mt-2 total-cart-item-lists-area">
                                <li class="list-group-item align-items-start">
                                    <div class="d-flex justify-content-between">
                                        <div class="fw-bold">{{ __('message.total_product') }}</div>
                                        <div v-text="add_to_cart_order.total_product ? add_to_cart_order.total_product : 0"></div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div class="fw-bold">{{ __('message.total_quantity') }}</div>
                                        <div v-text="add_to_cart_order.total_quantity ? add_to_cart_order.total_quantity : 0"></div>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <div class="fw-bold">{{ __('message.total_price') }}</div>
                                        <div v-text="add_to_cart_order.total_price ? add_to_cart_order.total_price : 0"></div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <div class="fw-bold">{{ __('message.tax') }}</div>
                                        <div v-text="add_to_cart_order.tax ? add_to_cart_order.tax : 0"></div>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <div class="fw-bold">{{ __('message.total_amount') }}</div>
                                        <div v-text="add_to_cart_order.total_amount ? add_to_cart_order.total_amount : 0"></div>
                                    </div>
                                </li>
                            </ol>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary btn-sm" type="button" @click="orderConfirmButton(add_to_cart_order.id)">{{ __('message.order_confirm') }}</button>
                                <button class="btn btn-secondary btn-sm" type="button" @click="orderCancelButton(add_to_cart_order.id)">{{ __('message.order_cancel') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('vue.global.js') }}"></script>
    <script src="{{ asset('axios.min.js') }}"></script>

    <script>
        const {
            createApp
        } = Vue;

        createApp({
            data() {
                return {
                    category_id: '',
                    products: {
                        data: [],
                        links: {},
                        meta: {},
                    },
                    order_id: null,
                    add_to_cart_order: []
                };
            },
            computed: {
                paginationButtons() {
                    const buttons = [];
                    const totalPages = this.products.meta.last_page;
                    const currentPage = this.products.meta.current_page;

                    for (let i = 1; i <= totalPages; i++) {
                        buttons.push(i);
                    }

                    return buttons;
                }
            },
            methods: {
                // pagination --- start
                fetchProducts(url = '/get-product-list') {
                    axios.get(url)
                        .then(response => {
                            this.products = response.data.data;
                        })
                        .catch(error => {
                            console.error("There was an error fetching the products!", error);
                        });
                },
                fetchProductsPage(page) {
                    const url = `/get-product-list?page=${page}&category_id=${this.category_id}`;
                    this.fetchProducts(url);
                },
                // pagination --- end

                // Category filter --- start
                filterCategory(category_id) {
                    this.category_id = category_id;
                    const url = `/get-product-list?category_id=${this.category_id}`;
                    this.fetchProducts(url);
                },
                // Category filter --- end

                // Add to cart --- start
                getAddToCartOrder() {
                    axios.get('get-add-to-cart-order')
                        .then(response => {
                            if (response.data.success) {
                                this.order_id = response.data.data.id;
                                this.add_to_cart_order = response.data.data;
                            } else {
                                toastr.warning(response.data.message);
                            }
                        })
                        .catch(error => {
                            toastr.warning(error.response.data.message);
                            console.error("There was an error fetching the add to cart order!", error);
                        });
                },
                addToCart(product_id, status) {
                    axios.post('add-to-cart-order-items', {
                        product_id,
                        status
                    })
                        .then(response => {
                            if (response.data.success) {
                                this.fetchProducts();
                                this.getAddToCartOrder();
                            } else {
                                toastr.warning(response.data.message);
                            }
                        })
                        .catch(error => {
                            toastr.warning(error.response.data.message);
                            console.error("There was an error from the add to cart order items!", error);
                        });

                },
                // Add to cart --- end

                // Order --- start
                orderConfirmButton(order_id) {
                    CustomAlert.fire({
                        text: "{{ translate('Are you sure to order?', 'အမှာစာတင်ရန် အတည်ပြုပါသလား?') }}",
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            ProcessingAlert.fire({
                                text: "{{ translate('Ordering..., Please wait!', 'အမှာစာမှာယူနေသည်..., ခဏစောင့်ပါ!') }}",
                            });

                            axios.post(`/order/${order_id}/confirm`)
                                .then(response => {
                                    let res = response.data;
                                    if (res.success) {
                                        toastr.success(res.message);

                                        setTimeout(() => {
                                            window.location.href = `/order/${order_id}`;
                                        }, 2000);
                                    } else {
                                        toastr.warning(res.message);
                                    }
                                })
                                .catch(error => {
                                    toastr.error(error.message);
                                    console.error("There was an error from the order confirm!", error);
                                });
                        }
                    })
                },
                orderCancelButton(order_id) {
                    CustomAlert.fire({
                        text: "{{ translate('Are you sure to order cancel?', 'အမှာစာပယ်ဖျက်ရန် သေချာပါသလား?') }}",
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            ProcessingAlert.fire({
                                text: "{{ translate('Canceling..., Please wait!', 'အမှာစာပယ်ဖျက်နေသည်..., ခဏစောင့်ပါ!') }}",
                            });

                            axios.post(`/order/${order_id}/cancel`)
                                .then(response => {
                                    let res = response.data;
                                    if (res.success) {
                                        toastr.success(res.message);

                                        setTimeout(() => {
                                            window.location.href = `/product`;
                                        }, 2000);
                                    } else {
                                        toastr.warning(res.message);
                                    }
                                })
                                .catch(error => {
                                    toastr.error(error.message);
                                    console.error("There was an error from the order cancel!", error);
                                });
                        }
                    })
                }
                // Order --- end
            },
            mounted() {
                this.fetchProducts();
                this.getAddToCartOrder();
            }
        }).mount('#app');
    </script>
@endsection

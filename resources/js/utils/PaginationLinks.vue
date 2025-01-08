<template>
    <div class="d-flex no-block justify-content-between align-items-center flex-wrap"
        v-show="pagination.last_page > 1">
        <div class="d-flex no-block justify-content-between align-items-center py-1">
            <div class="pr-2">
                Mostrando registros del
                <span class="font-weight-bold" v-text="pagination.from"></span> al
                <span class="font-weight-bold" v-text="pagination.to"></span> de un total de
                <span class="font-weight-bold" v-text="pagination.total"></span> registros
            </div>
            <select class="form-control form-control-sm w-auto" v-model="perpage" @change="doChangePerPage">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="20">20</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="250">250</option>
            </select>
        </div>
        <ul class="pagination mb-0 ml-auto" v-if="pagination.last_page > 1">
            <li :class="['page-item',{'disabled': isOnFirstPage}]">
                <a class="page-link" href="javascript:void(0)" @click.prevent="loadPage(1)">
                    <i class="fas fa-angle-double-left"></i>
                </a>
            </li>
            <li :class="['page-item d-none d-sm-inline-block',{'disabled': isOnFirstPage}]">
                <a class="page-link" href="#" @click.prevent="loadPage('prev')">
                    <i class="fas fa-angle-left"></i>
                </a>
            </li>
            <template v-if="isPosibleShowAll">
                <li v-for="n in pagination.last_page" :key="n" :class="['page-item', {'active': isCurrentPage(n)}]">
                    <a class="page-link" @click.prevent="loadPage(n)" v-html="n"></a>
                </li>
            </template>
            <template v-else-if="separatorAtEnd">
                <li v-for="n in paginationItemsLimit" :key="n" :class="['page-item', {'active': isCurrentPage(n)}]">
                    <a class="page-link" @click.prevent="loadPage(n)" v-html="n"></a>
                </li>
                <li class="page-item disabled">
                    <a class="page-link border-top-0 border-bottom-0">...</a>
                </li>
                <li :class="['page-item', {'active': isCurrentPage(pagination.last_page)}]">
                    <a class="page-link" @click.prevent="loadPage(pagination.last_page)"
                        v-html="pagination.last_page"></a>
                </li>
            </template>
            <template v-else-if="separatorAtAround">
                <li :class="['page-item', {'active': isCurrentPage(1)}]">
                    <a class="page-link" @click.prevent="loadPage(1)">1</a>
                </li>
                <li class="page-item disabled">
                    <a class="page-link border-top-0 border-bottom-0">...</a>
                </li>

                <li :class="['page-item', {'active': isCurrentPage(pagination.current_page -1)}]">
                    <a class="page-link" @click.prevent="loadPage(pagination.current_page -1)"
                        v-html="pagination.current_page -1"></a>
                </li>
                <li :class="['page-item', {'active': isCurrentPage(pagination.current_page)}]">
                    <a class="page-link" @click.prevent="loadPage(pagination.current_page)"
                        v-html="pagination.current_page"></a>
                </li>
                <li :class="['page-item', {'active': isCurrentPage(pagination.current_page +1)}]">
                    <a class="page-link" @click.prevent="loadPage(pagination.current_page +1)"
                        v-html="pagination.current_page +1"></a>
                </li>

                <li class="page-item disabled">
                    <a class="page-link border-top-0 border-bottom-0">...</a>
                </li>
                <li :class="['page-item', {'active': isCurrentPage(pagination.last_page)}]">
                    <a class="page-link" @click.prevent="loadPage(pagination.last_page)"
                        v-html="pagination.last_page"></a>
                </li>
            </template>
            <template v-else>
                <li :class="['page-item', {'active': isCurrentPage(1)}]">
                    <a class="page-link" @click.prevent="loadPage(1)">1</a>
                </li>
                <li class="page-item disabled">
                    <a class="page-link border-top-0 border-bottom-0">...</a>
                </li>
                <li v-for="n in paginationItemsLimit" :key="n"
                    :class="['page-item', {'active': isCurrentPage(pagination.last_page-(paginationItemsLimit- n ))}]">
                    <a class="page-link" @click.prevent="loadPage(pagination.last_page - (paginationItemsLimit- n ))"
                        v-text="pagination.last_page - (paginationItemsLimit- n )"></a>
                </li>
            </template>
            <li :class="['page-item d-none d-sm-inline-block', {'disabled': isOnLastPage}]">
                <a class="page-link" href="" @click.prevent="loadPage('next')">
                    <i class="fas fa-angle-right"></i>
                </a>
            </li>
            <li :class="['page-item', {'disabled': isOnLastPage}]">
                <a class="page-link" href="javascript:void(0)" @click.prevent="loadPage(pagination.last_page)">
                    <i class="fas fa-angle-double-right"></i>
                </a>
            </li>
        </ul>
    </div>
</template>
<script>
    export default {
        props: {
            pagination: {
                type: Object,
                // a factory function
                default: function () {
                    return {
                        current_page: 1,
                        first_page_url: '',
                        from: 0,
                        last_page: 0,
                        last_page_url: '',
                        next_page_url: '',
                        path: '',
                        per_page: 0,
                        prev_page_url: '',
                        to: 0,
                        total: 0
                    }
                }
            }
        },
        data() {
            return {
                perpage: 10,
                paginationItemsLimit: 5
            }
        },
        methods: {
            doChangePerPage() {
                let vm = this;
                let filters = {};

                filters.page = 1;
                filters.perpage = this.perpage;
                
                if (this.pagination.current_page){

                    let penultimatePage = (this.perpage * this.pagination.current_page) - this.perpage;

                    if(penultimatePage < this.pagination.total)
                        filters.page = this.pagination.current_page;                       
                }

                this.$emit('changePerPage', filters)
            },
            loadPage(page) {
                if (page === 'prev') {
                    this.gotoPreviousPage()
                } else if (page === 'next') {
                    this.gotoNextPage()
                } else {
                    this.gotoPage(page)
                }
            },
            isCurrentPage(page) {
                return page === this.pagination.current_page
            },
            gotoPreviousPage() {
                if (this.pagination.current_page > 1) {
                    this.pagination.current_page--
                    this.doChangePerPage();
                }
            },
            gotoNextPage() {
                if (this.pagination.current_page < this.pagination.last_page) {
                    this.pagination.current_page++
                    this.doChangePerPage();
                }
            },
            gotoPage(page) {
                if (page != this.pagination.current_page && (page > 0 && page <= this.pagination.last_page)) {
                    this.pagination.current_page = page
                    this.doChangePerPage();
                }
            }
        },
        computed: {
            isOnFirstPage() {
                return this.pagination.current_page === 1
            },
            isOnLastPage() {
                return this.pagination.current_page === this.pagination.last_page
            },
            isPosibleShowAll() {
                return this.pagination.last_page <= this.paginationItemsLimit;
            },
            separatorAtEnd() {
                return this.pagination.current_page < this.paginationItemsLimit;
            },
            separatorAtAround() {
                return (this.pagination.current_page >= this.paginationItemsLimit && this.pagination.last_page > this
                    .paginationItemsLimit && this.pagination.last_page > (this.pagination.current_page + 1));
            }
        }
    }

</script>
